<?php declare(strict_types=1);

namespace App\Application\Types;

use DateTimeInterface;
use Symfony\Component\Uid\UuidV4;

final class Measurement
{
    private const WARNING_LEVEL = 2000;
    private UuidV4 $measurementId;
    private CO2 $co2;
    private DateTimeInterface $time;

    public function __construct(UuidV4 $measurementId, CO2 $co2, DateTimeInterface $time)
    {
        $this->measurementId = $measurementId;
        $this->co2 = $co2;
        $this->time = $time;
    }

    public function getMeasurementId(): UuidV4
    {
        return $this->measurementId;
    }

    public function getCo2(): CO2
    {
        return $this->co2;
    }

    public function getTime(): DateTimeInterface
    {
        return $this->time;
    }

    public function exceedsWarningLevel(): bool
    {
        return $this->co2->toInt() > self::WARNING_LEVEL;
    }
}