<?php declare(strict_types=1);

namespace App\Application\Ports;

use App\Application\Types\Sensor;
use App\Application\Types\SensorId;

interface RepresentsWriteSensorStorage
{
    public function writeSensorMeasurements(Sensor $sensor): SensorId;
}