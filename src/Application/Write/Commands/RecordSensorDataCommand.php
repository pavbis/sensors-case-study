<?php declare(strict_types=1);

namespace App\Application\Write\Commands;

use App\Application\Types\Sensor;

final class RecordSensorDataCommand
{
    private Sensor $sensor;

    public function __construct(Sensor $sensor)
    {
        $this->sensor = $sensor;
    }

    public function getSensor(): Sensor
    {
        return $this->sensor;
    }
}