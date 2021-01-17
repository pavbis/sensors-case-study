<?php declare(strict_types=1);

namespace App\Application\Write\CommandHandlers;

use App\Application\Ports\RepresentsWriteSensorStorage;
use App\Application\Write\Commands\RecordSensorDataCommand;
use App\Application\Write\Constants\ResultType;
use App\Application\Write\Results\RecordSensorDataResult;
use Throwable;

final class RecordSensorDataCommandHandler
{
    private RepresentsWriteSensorStorage $storage;

    public function __construct(RepresentsWriteSensorStorage $storage)
    {
        $this->storage = $storage;
    }

    public function handle(RecordSensorDataCommand $command): RecordSensorDataResult
    {
        try {
            $sensorId = $this->storage->writeSensorMeasurements($command->getSensor());
            $result = new RecordSensorDataResult;
            $result->setSensorId($sensorId);

            return $result;
        } catch (Throwable $e) {
            return new RecordSensorDataResult(ResultType::FAILURE, $e->getMessage());
        }
    }
}