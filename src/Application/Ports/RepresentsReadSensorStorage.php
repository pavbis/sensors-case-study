<?php declare(strict_types=1);

namespace App\Application\Ports;

use App\Application\Types\RepresentsSensorStatus;
use App\Application\Types\SensorId;

interface RepresentsReadSensorStorage
{
    public function readSensorStatus(SensorId $sensorId): RepresentsSensorStatus;

    public function readSensorMetrics(SensorId $sensorId): string;

    public function readSensorAlerts(SensorId $sensorId);
}