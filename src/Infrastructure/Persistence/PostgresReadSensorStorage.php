<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Application\Ports\RepresentsReadSensorStorage;
use App\Application\Types\RepresentsSensorStatus;
use App\Application\Types\SensorId;
use App\Application\Types\SensorNotFoundStatus;
use App\Application\Types\SensorStatus;
use Doctrine\ORM\EntityManagerInterface;

final class PostgresReadSensorStorage implements RepresentsReadSensorStorage
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function readSensorStatus(SensorId $sensorId): RepresentsSensorStatus
    {
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare('SELECT status FROM sensors WHERE sensor_id = :sensorId LIMIT 1');

        $stmt->execute([':sensorId' => $sensorId->toRfc4122()]);

        $result = $stmt->fetchOne();

        if (false === $result) {
            return new SensorNotFoundStatus;
        }

        return new SensorStatus($result);
    }

    public function readSensorMetrics(SensorId $sensorId): string
    {
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare("
                WITH stats AS (
                    SELECT m.co2
                    FROM measurements m
                    WHERE sensor_id = :sensorId
                    AND m.created_at >= NOW() - interval '1 month'
                )
                SELECT json_strip_nulls(json_build_object(
                    'maxLast30Days', COALESCE(MAX(s.co2), 0),
                    'avgLast30Days', COALESCE(AVG(s.co2), 0)
                ))
                FROM stats s;
            ");

        $stmt->execute([':sensorId' => $sensorId->toRfc4122()]);

        return $stmt->fetchOne();
    }

    public function readSensorAlerts(SensorId $sensorId)
    {
        // TODO: Implement readSensorAlerts() method.
    }
}