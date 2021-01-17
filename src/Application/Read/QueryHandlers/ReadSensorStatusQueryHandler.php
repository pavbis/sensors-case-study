<?php declare(strict_types=1);

namespace App\Application\Read\QueryHandlers;

use App\Application\Ports\RepresentsReadSensorStorage;
use App\Application\Read\Constants\ResultType;
use App\Application\Read\Queries\SensorStatusQuery;
use App\Application\Read\Results\SensorStatusResult;
use Throwable;

final class ReadSensorStatusQueryHandler
{
    private RepresentsReadSensorStorage $storage;

    public function __construct(RepresentsReadSensorStorage $storage)
    {
        $this->storage = $storage;
    }

    public function handle(SensorStatusQuery $query): SensorStatusResult
    {
        try {
            $sensorStatus = $this->storage->readSensorStatus($query->getSensorId());

            $result = new SensorStatusResult;
            $result->setSensorStatus($sensorStatus);

            return $result;
        } catch (Throwable $e) {
            return new SensorStatusResult(ResultType::FAILURE, $e->getMessage());
        }
    }
}