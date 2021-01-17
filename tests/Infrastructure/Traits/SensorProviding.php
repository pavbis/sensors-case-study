<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Traits;

use App\Application\Types\CO2;
use App\Application\Types\Measurement;
use App\Application\Types\Sensor;
use App\Application\Types\SensorId;
use App\Application\Types\SensorStatus;
use DateTimeImmutable;
use Symfony\Component\Uid\UuidV4;

trait SensorProviding
{
    private string $firstSensorId = '20426d89-f806-4c8a-a417-0e38ad96e343';
    private string $secondSensorId = 'e1554664-8159-4b0c-ad72-d443530f7546';
    private string $thirdSensorId = '9c0349c6-262b-459f-bbf7-9c6d1d3f6e7a';

    /**
     * @return Sensor[]
     */
    private function sensors(): array
    {
        return [
            new Sensor(
                SensorId::fromString($this->firstSensorId),
                new SensorStatus('OK'),
                new Measurement(
                    UuidV4::fromString('38573a4f-2497-4745-847f-ac35b3e234fe'),
                    new CO2(100),
                    new DateTimeImmutable('2021-01-01')
                )
            ),
            new Sensor(
                SensorId::fromString($this->secondSensorId),
                new SensorStatus('OK'),
                new Measurement(
                    UuidV4::fromString('38406443-c778-418a-ba3c-d2c28a1f1874'),
                    new CO2(1300),
                    new DateTimeImmutable('2021-01-02')
                )
            ),
            new Sensor(
                SensorId::fromString($this->thirdSensorId),
                new SensorStatus('WARN'),
                new Measurement(
                    UuidV4::fromString('4514e28d-be04-4220-b5d1-165c93d645d5'),
                    new CO2(600),
                    new DateTimeImmutable('2021-01-03')
                )
            ),
        ];
    }
}