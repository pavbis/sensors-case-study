<?php declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\Types\SensorId;
use RuntimeException;

final class SensorRateLimit extends RuntimeException
{
    public static function reached(SensorId $sensorId): self
    {
        return new self(sprintf('Rate limit reached for sensor %s', $sensorId->toRfc4122()));
    }
}