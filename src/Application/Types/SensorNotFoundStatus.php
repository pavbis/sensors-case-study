<?php declare(strict_types=1);

namespace App\Application\Types;

final class SensorNotFoundStatus implements RepresentsSensorStatus
{
    public function toString(): string
    {
        return 'sensor does not exist';
    }

    public function isValid(): bool
    {
        return false;
    }
}