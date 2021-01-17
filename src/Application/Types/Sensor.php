<?php declare(strict_types=1);

namespace App\Application\Types;

final class Sensor
{
    private SensorId $sensorId;
    private RepresentsSensorStatus $sensorStatus;
    private Measurement $measurement;

    public function __construct(SensorId $sensorId, RepresentsSensorStatus $sensorStatus, Measurement $measurement)
    {
        $this->sensorId = $sensorId;
        $this->sensorStatus = $sensorStatus;
        $this->measurement = $measurement;
    }

    public static function newWithWarning(SensorId $sensorId, Measurement $measurement): self
    {
        return new self($sensorId, SensorStatus::newWithWarningStatus(), $measurement);
    }

    public static function newWithOK(SensorId $sensorId, Measurement $measurement): self
    {
        return new self($sensorId, SensorStatus::newWithOKStatus(), $measurement);
    }

    public function getSensorId(): SensorId
    {
        return $this->sensorId;
    }

    public function getSensorStatus(): RepresentsSensorStatus
    {
        return $this->sensorStatus;
    }

    public function getMeasurement(): Measurement
    {
        return $this->measurement;
    }
}