<?php declare(strict_types=1);

namespace App\UI\API\V1\Controller;

use App\Application\Read\Queries\SensorMetricsQuery;
use App\Application\Read\QueryHandlers\ReadSensorMetricsQueryHandler;
use App\Application\Types\SensorId;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SensorMetricsController
{
    private ReadSensorMetricsQueryHandler $queryHandler;

    public function __construct(ReadSensorMetricsQueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    public function sensorMetrics(string $sensorId): JsonResponse
    {
        $sensorId =  SensorId::fromString($sensorId);
        $query = new SensorMetricsQuery($sensorId);
        $result = $this->queryHandler->handle($query);

        return new JsonResponse($result->getMetricsString(), JsonResponse::HTTP_OK, [], true);
    }
}
