<?php declare(strict_types=1);

namespace App\Application\Types;

interface RepresentsSensorStatus
{
    public function isValid(): bool;
    public function toString(): string;
}