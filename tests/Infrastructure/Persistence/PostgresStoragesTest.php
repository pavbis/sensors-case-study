<?php declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence;

use App\Application\Types\SensorId;
use App\Infrastructure\Exceptions\SensorRateLimit;
use App\Infrastructure\Persistence\PostgresReadSensorStorage;
use App\Infrastructure\Persistence\PostgresWriteSensorStorage;
use App\Tests\Infrastructure\Traits\DoctrineORMTrait;
use App\Tests\Infrastructure\Traits\SensorProviding;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class PostgresStoragesTest extends TestCase
{
    use DoctrineORMTrait, SensorProviding;

    private EntityManagerInterface $em;
    private PostgresReadSensorStorage $readStorage;
    private PostgresWriteSensorStorage $writeStorage;

    public function setUp(): void
    {
        $this->em = $this->createTestEntityManager();
        $this->readStorage = new PostgresReadSensorStorage($this->em);
        $this->writeStorage = new PostgresWriteSensorStorage($this->em);
        foreach ($this->sensors() as $sensor) {
            // persist 3 sensor and 3 measurements
            $this->writeStorage->writeSensorMeasurements($sensor);
        }
    }

    public function testReadStorageReturnsStatusForSensors(): void
    {
       $firstSensorStatus = $this->readStorage->readSensorStatus(SensorId::fromString($this->firstSensorId));
       $secondSensorStatus = $this->readStorage->readSensorStatus(SensorId::fromString($this->secondSensorId));
       $thirdSensorStatus = $this->readStorage->readSensorStatus(SensorId::fromString($this->thirdSensorId));

       self::assertEquals('OK', $firstSensorStatus->toString());
       self::assertEquals('OK', $secondSensorStatus->toString());
       self::assertEquals('WARN', $thirdSensorStatus->toString());
    }

    public function testReadStorageReturnsMetricsForSensors(): void
    {
        $firstSensorMetric = $this->readStorage->readSensorMetrics(SensorId::fromString($this->firstSensorId));
        $secondSensorMetric = $this->readStorage->readSensorMetrics(SensorId::fromString($this->secondSensorId));
        $thirdSensorMetric = $this->readStorage->readSensorMetrics(SensorId::fromString($this->thirdSensorId));

        self::assertJsonStringEqualsJsonFile(__DIR__ . '/Assets/first_sensor_metric.json', $firstSensorMetric);
        self::assertJsonStringEqualsJsonFile(__DIR__ . '/Assets/second_server_metric.json', $secondSensorMetric);
        self::assertJsonStringEqualsJsonFile(__DIR__ . '/Assets/third_sensor_metric.json', $thirdSensorMetric);
    }

    public function testWriteStoreThrowsRateLimit(): void
    {
        $this->expectException(SensorRateLimit::class);
        $this->expectExceptionMessage(SensorRateLimit::reached(SensorId::fromString($this->firstSensorId))->getMessage());

        $this->writeStorage->writeSensorMeasurements($this->sensors()[0]);
    }

    public function tearDown(): void
    {
        $this->em->getConnection()->executeQuery('TRUNCATE sensors restart identity CASCADE');
    }
}
