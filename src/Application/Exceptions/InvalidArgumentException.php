<?php declare(strict_types=1);

namespace App\Application\Exceptions;

use RuntimeException;

final class InvalidArgumentException extends RuntimeException
{
    public static function invalidCO2(): self
    {
        return new self('invalid CO2 provided');
    }

    public static function invalidSensorStatus(string $providedStatus): self
    {
        return new self(sprintf("status %s is not allowed", $providedStatus));
    }
}