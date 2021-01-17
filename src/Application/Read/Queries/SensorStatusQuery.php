<?php declare(strict_types=1);

namespace App\Application\Read\Queries;

use App\Application\Types\SensorId;

final class SensorStatusQuery
{
    private SensorId $sensorId;

    public function __construct(SensorId $sensorId)
    {
        $this->sensorId = $sensorId;
    }

    public function getSensorId(): SensorId
    {
        return $this->sensorId;
    }
}