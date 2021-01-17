<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Application\Ports\RepresentsWriteSensorStorage;
use App\Application\Types\Sensor;
use App\Application\Types\SensorId;
use App\Infrastructure\Exceptions\SensorRateLimit;
use Doctrine\ORM\EntityManagerInterface;

final class PostgresWriteSensorStorage implements RepresentsWriteSensorStorage
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function writeSensorMeasurements(Sensor $sensor): SensorId
    {
        if ($this->recordNotPossible($sensor->getSensorId())) {
            throw SensorRateLimit::reached($sensor->getSensorId());
        }

        $stmt = $this->entityManager
            ->getConnection()
            ->prepare('
                    INSERT INTO sensors ("sensor_id", "status", "created_at", "updated_at") 
                    VALUES (:sensorId, :status, NOW(), NOW()) ON CONFLICT ("sensor_id") 
                    DO UPDATE SET "updated_at" = NOW(), status = :status
		            RETURNING "sensor_id";
            ');

        $stmt->execute(
            [
                ':sensorId' => $sensor->getSensorId()->toRfc4122(),
                ':status'   => $sensor->getSensorStatus()->toString()
            ]
        );

        $this->recordMeasurementForSensor($sensor);

        return $sensor->getSensorId();
    }

    private function recordNotPossible(SensorId $sensorId): bool
    {
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare("
                SELECT EXISTS(
                    SELECT 1
                    FROM sensors s
                    WHERE sensor_id = :sensorId
                    AND s.updated_at >= NOW() - interval '1 minute'
                );
            ");
        $stmt->execute([':sensorId' => $sensorId]);

        return $stmt->fetchOne();
    }

    private function recordMeasurementForSensor(Sensor $sensor): void
    {
        $measurement = $sensor->getMeasurement();

        $stmt = $this->entityManager
            ->getConnection()
            ->prepare('
                    INSERT INTO measurements (measurement_id, sensor_id, co2, created_at) 
                    VALUES (:measurementId, :sensorId, :co2, :createdAt);
            ');

        $stmt->execute(
            [
                ':measurementId' => $measurement->getMeasurementId()->toRfc4122(),
                ':sensorId'      => $sensor->getSensorId()->toRfc4122(),
                ':co2'           => $measurement->getCo2()->toInt(),
                ':createdAt'     => $measurement->getTime()->format('Y-m-d H:i:s'),
            ]
        );
    }
}