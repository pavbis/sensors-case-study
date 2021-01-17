<?php declare(strict_types=1);

namespace App\Tests\Application\Types;

use App\Application\Types\CO2;
use App\Application\Types\Measurement;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

final class MeasurementTest extends TestCase
{
    public function testItDoesNotReachWarningLevel(): void
    {
        $measurement = new Measurement(UuidV4::v4(), new CO2(2000), new DateTimeImmutable);

        self::assertFalse($measurement->exceedsWarningLevel());
    }

    public function testItReachesWarningLevel(): void
    {
        $measurement = new Measurement(UuidV4::v4(), new CO2(2001), new DateTimeImmutable);

        self::assertTrue($measurement->exceedsWarningLevel());
    }
}
