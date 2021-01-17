<?php declare(strict_types=1);

namespace App\Tests\Application\Read\QueryHandlers;

use App\Application\Ports\RepresentsReadSensorStorage;
use App\Application\Read\Queries\SensorMetricsQuery;
use App\Application\Read\QueryHandlers\ReadSensorMetricsQueryHandler;
use App\Application\Types\RepresentsSensorStatus;
use App\Application\Types\SensorId;
use App\Application\Types\SensorStatus;
use Exception;
use PHPUnit\Framework\TestCase;

final class ReadSensorMetricsQueryHandlerTest extends TestCase
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
                throw new Exception($this->errorMessage);
            }

            public function readSensorAlerts(SensorId $sensorId)
            {
                // TODO: Implement readSensorAlerts() method.
            }
        };

        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $query = new SensorMetricsQuery($sensorId);
        $result = (new ReadSensorMetricsQueryHandler($fakeStorageWithError))->handle($query);

        self::assertTrue($result->failed());
        self::assertEquals($errorMessage, $result->getMessage());
    }

    public function testItReturnsSuccessResult(): void
    {
        $sensorMetrics = '{
    "maxLast30Days": 1300,
    "avgLast30Days": 1122.8571428571428571
}';

        $fakeStorageWithError = new class($sensorMetrics) implements RepresentsReadSensorStorage
        {
            private string $sensorMetrics;

            public function __construct(string $sensorMetrics)
            {
                $this->sensorMetrics = $sensorMetrics;
            }

            public function readSensorStatus(SensorId $sensorId): RepresentsSensorStatus
            {
                return new SensorStatus('OK');
            }

            public function readSensorMetrics(SensorId $sensorId): string
            {
                return $this->sensorMetrics;
            }

            public function readSensorAlerts(SensorId $sensorId)
            {
                // TODO: Implement readSensorAlerts() method.
            }
        };

        $sensorId = SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343');
        $query = new SensorMetricsQuery($sensorId);
        $result = (new ReadSensorMetricsQueryHandler($fakeStorageWithError))->handle($query);

        self::assertTrue($result->succeeded());
        self::assertEquals($sensorMetrics, $result->getMetricsString());
        self::assertEmpty($result->getMessage());
    }
}
