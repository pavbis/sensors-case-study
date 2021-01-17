<?php declare(strict_types=1);

namespace App\Tests\Application\Types;

use App\Application\Exceptions\InvalidArgumentException;
use App\Application\Types\CO2;
use Generator;
use PHPUnit\Framework\TestCase;

final class CO2Test extends TestCase
{
    public function testItCanBeCreatedWithValidInteger(): void
    {
        $someInt = 10;
        $co2 = new CO2($someInt);

        self::assertEquals($someInt, $co2->toInt());
    }

    /**
     * @dataProvider invalidIntegerDataProvider
     * @param int $invalidInteger
     */
    public function testItThrowsInvalidArgumentException(int $invalidInteger): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(InvalidArgumentException::invalidCO2()->getMessage());

        new CO2($invalidInteger);
    }

    public function invalidIntegerDataProvider(): Generator
    {
        yield[-1];
        yield[-38548723547823];
    }
}
