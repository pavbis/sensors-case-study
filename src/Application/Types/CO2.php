<?php declare(strict_types=1);

namespace App\Application\Types;

use App\Application\Exceptions\InvalidArgumentException;

final class CO2
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0 || $value > PHP_INT_MAX) {
            throw InvalidArgumentException::invalidCO2();
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}