<?php declare(strict_types=1);

namespace App\Tests\Application\Types;

use App\Application\Types\CO2;
use App\Application\Types\Measurement;
use App\Application\Types\Sensor;
use App\Application\Types\SensorId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

final class SensorTest extends TestCase
{
    public function testWithWarningStatus(): void
    {
        $sensor = Sensor::newWithWarning(
            SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343'),
            new Measurement(UuidV4::v4(), new CO2(100), new DateTimeImmutable)
        );

        self::assertEquals('WARN', $sensor->getSensorStatus()->toString());
    }

    public function testWithOKStatus(): void
    {
        $sensor = Sensor::newWithOK(
            SensorId::fromString('20426d89-f806-4c8a-a417-0e38ad96e343'),
            new Measurement(UuidV4::v4(), new CO2(100), new DateTimeImmutable)
        );

        self::assertEquals('OK', $sensor->getSensorStatus()->toString());
    }
}
