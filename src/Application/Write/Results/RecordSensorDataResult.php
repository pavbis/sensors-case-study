<?php declare(strict_types=1);

namespace App\Application\Write\Results;

use App\Application\Types\SensorId;
use App\Application\Write\AbstractResult;

final class RecordSensorDataResult extends AbstractResult
{
    private SensorId $sensorId;

    public function getSensorId(): SensorId
    {
        return $this->sensorId;
    }

    public function setSensorId(SensorId $sensorId): void
    {
        $this->sensorId = $sensorId;
    }
}