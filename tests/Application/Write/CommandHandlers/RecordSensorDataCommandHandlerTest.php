<?php declare(strict_types=1);

namespace App\Tests\Application\Write\CommandHandlers;

use App\Application\Ports\RepresentsWriteSensorStorage;
use App\Application\Types\CO2;
use App\Application\Types\Measurement;
use App\Application\Types\Sensor;
use App\Application\Types\SensorId;
use App\Application\Types\SensorNotFoundStatus;
use App\Application\Write\CommandHandlers\RecordSensorDataCommandHandler;
use App\Application\Write\Commands\RecordSensorDataCommand;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

final class RecordSensorDataCommandHandlerTest extends TestCase
{
    public function testItReturnsFailureResult(): void
    {
        $errorMessage = 'failed to proceed sensor data';

        $fakeStorageWithError = new class($errorMessage) implements RepresentsWriteSensorStorage
        {
            private string $errorMessage;

            public function __construct(string $errorMessage)
            {
                $this->errorMessage = $errorMessage;
            }

            public function writeSensorMeasurements(Sensor $sensor): SensorId
            {
                throw new Exception($this->errorMessage);
            }
        };

        /** @var SensorId $sensorId */
        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $measurement = new Measurement(UuidV4::v4(), new CO2(100), new DateTimeImmutable);
        $sensor = new Sensor($sensorId, new SensorNotFoundStatus, $measurement);
        $command = new RecordSensorDataCommand($sensor);
        $result = (new RecordSensorDataCommandHandler($fakeStorageWithError))->handle($command);

        self::assertTrue($result->failed());
        self::assertEquals($errorMessage, $result->getMessage());
    }

    public function testItReturnsSuccessResultAndRecordsSensor(): void
    {
        /**
         * @var Sensor[]
         */
        $sensors = [];

        $fakeStorageWithoutError = new class($sensors) implements RepresentsWriteSensorStorage
        {
            /**
             * @var Sensor[]
             */
            public array $sensors = [];

            public function __construct(array $sensors)
            {
                $this->sensors = $sensors;
            }

            public function writeSensorMeasurements(Sensor $sensor): SensorId
            {
                $this->sensors[] = $sensor;

                return $sensor->getSensorId();
            }
        };

        /** @var SensorId $sensorId */
        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $measurement = new Measurement(UuidV4::v4(), new CO2(100), new DateTimeImmutable);
        $sensor = new Sensor($sensorId, new SensorNotFoundStatus, $measurement);
        $command = new RecordSensorDataCommand($sensor);
        $result = (new RecordSensorDataCommandHandler($fakeStorageWithoutError))->handle($command);

        self::assertTrue($result->succeeded());
        self::assertCount(1, $fakeStorageWithoutError->sensors);
        self::assertEquals($sensorId->toRfc4122(), $fakeStorageWithoutError->sensors[0]->getSensorId()->toRfc4122());
        self::assertEmpty($result->getMessage());
    }
}
