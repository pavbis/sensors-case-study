<?php declare(strict_types=1);

namespace App\Application\Read\Results;

use App\Application\Read\AbstractResult;
use App\Application\Types\RepresentsSensorStatus;

final class SensorStatusResult extends AbstractResult
{
    private RepresentsSensorStatus $sensorStatus;

    public function getSensorStatus(): RepresentsSensorStatus
    {
        return $this->sensorStatus;
    }

    public function setSensorStatus(RepresentsSensorStatus $sensorStatus): void
    {
        $this->sensorStatus = $sensorStatus;
    }
}