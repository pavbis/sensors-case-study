<?php declare(strict_types=1);

namespace App\Tests\Application\Types;

use App\Application\Exceptions\InvalidArgumentException;
use App\Application\Types\SensorStatus;
use Generator;
use PHPUnit\Framework\TestCase;

final class SensorStatusTest extends TestCase
{
    /**
     * @dataProvider validSensorStatusDataProvider
     * @param string $validStatusPrimitive
     */
    public function testItCanBeCreatedWithValidPrimitives(string $validStatusPrimitive): void
    {
        $sensorStatus = new SensorStatus($validStatusPrimitive);

        self::assertTrue($sensorStatus->isValid());
        self::assertEquals($validStatusPrimitive, $sensorStatus->toString());
    }

    public function validSensorStatusDataProvider(): Generator
    {
        yield['OK'];
        yield['WARN'];
        yield['ALERT'];
    }

    public function testItThrowsExceptionWithInvalidPrimitiveProvided(): void
    {
        $invalidStatus = 'INVALID';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(InvalidArgumentException::invalidSensorStatus($invalidStatus)->getMessage());

        new SensorStatus($invalidStatus);
    }

    public function testCreateWithWarningStatus(): void
    {
        $status = SensorStatus::newWithWarningStatus();

        self::assertEquals('WARN', $status->toString());
    }

    public function testCreateWithOKStatus(): void
    {
        $status = SensorStatus::newWithOKStatus();

        self::assertEquals('OK', $status->toString());
    }
}
