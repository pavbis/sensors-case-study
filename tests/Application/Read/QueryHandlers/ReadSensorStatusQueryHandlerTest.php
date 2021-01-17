<?php declare(strict_types=1);

namespace App\Tests\Application\Read\QueryHandlers;

use App\Application\Ports\RepresentsReadSensorStorage;
use App\Application\Read\Queries\SensorStatusQuery;
use App\Application\Read\QueryHandlers\ReadSensorStatusQueryHandler;
use App\Application\Types\RepresentsSensorStatus;
use App\Application\Types\SensorId;
use App\Application\Types\SensorStatus;
use Exception;
use PHPUnit\Framework\TestCase;

final class ReadSensorStatusQueryHandlerTest extends TestCase
{
    public function testItReturnsFailureResultIfStorageErrorHappens(): void
    {
        $errorMessage = 'read sensor data error';

        $fakeStorageWithError = new class($errorMessage) implements RepresentsReadSensorStorage
        {
            private string $errorMessage;

            public function __construct(string $errorMessage)
            {
                $this->errorMessage = $errorMessage;
            }

            public function readSensorStatus(SensorId $sensorId): RepresentsSensorStatus
            {
                throw new Exception($this->errorMessage);
            }

            public function readSensorMetrics(SensorId $sensorId): string
            {
                return '';
            }

            public function readSensorAlerts(SensorId $sensorId)
            {
                // TODO: Implement readSensorAlerts() method.
            }
        };

        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $query = new SensorStatusQuery($sensorId);
        $result = (new ReadSensorStatusQueryHandler($fakeStorageWithError))->handle($query);

        self::assertTrue($result->failed());
        self::assertEquals($errorMessage, $result->getMessage());
    }

    public function testItReturnsSuccessResult(): void
    {
        $sensorStatus = new SensorStatus('OK');

        $fakeStorageWithError = new class($sensorStatus) implements RepresentsReadSensorStorage
        {
            private SensorStatus $sensorStatus;

            public function __construct(SensorStatus $sensorStatus)
            {
                $this->sensorStatus = $sensorStatus;
            }

            public function readSensorStatus(SensorId $sensorId): RepresentsSensorStatus
            {
                return $this->sensorStatus;
            }

            public function readSensorMetrics(SensorId $sensorId): string
            {
                return '';
            }

            public function readSensorAlerts(SensorId $sensorId)
            {
                // TODO: Implement readSensorAlerts() method.
            }
        };

        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $query = new SensorStatusQuery($sensorId);
        $result = (new ReadSensorStatusQueryHandler($fakeStorageWithError))->handle($query);

        self::assertTrue($result->succeeded());
        self::assertEquals($sensorStatus->toString(), $result->getSensorStatus()->toString());
        self::assertEmpty($result->getMessage());
    }
}
