<?php declare(strict_types=1);

namespace App\Application\Read\QueryHandlers;

use App\Application\Ports\RepresentsReadSensorStorage;
use App\Application\Read\Constants\ResultType;
use App\Application\Read\Queries\SensorMetricsQuery;
use App\Application\Read\Results\SensorMetricsResult;
use Throwable;

final class ReadSensorMetricsQueryHandler
{
    private RepresentsReadSensorStorage $storage;

    public function __construct(RepresentsReadSensorStorage $storage)
    {
        $this->storage = $storage;
    }

    public function handle(SensorMetricsQuery $query): SensorMetricsResult
    {
        try {
            $sensorMetrics = $this->storage->readSensorMetrics($query->getSensorId());
            $result = new SensorMetricsResult;
            $result->setMetricsString($sensorMetrics);

            return $result;
        } catch (Throwable $e) {
            return new SensorMetricsResult(ResultType::FAILURE, $e->getMessage());
        }
    }
}