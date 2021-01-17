<?php declare(strict_types=1);

namespace App\Tests\Application\Types;

use App\Application\Types\SensorNotFoundStatus;
use PHPUnit\Framework\TestCase;

final class SensorNotFoundTest extends TestCase
{
    public function testItIsInvalid(): void
    {
        self::assertFalse((new SensorNotFoundStatus)->isValid());
    }
    public function testToString(): void
    {
        self::assertEquals('sensor does not exist', (new SensorNotFoundStatus)->toString());
    }
}
