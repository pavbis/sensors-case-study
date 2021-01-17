<?php declare(strict_types=1);

namespace App\UI\API\V1\Controller;

use App\Application\Read\Queries\SensorStatusQuery;
use App\Application\Read\QueryHandlers\ReadSensorStatusQueryHandler;
use App\Application\Types\SensorId;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SensorStatusController
{
    private ReadSensorStatusQueryHandler $queryHandler;

    public function __construct(ReadSensorStatusQueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    public function sensorStatus(string $sensorId): JsonResponse
    {
        $sensorId =  SensorId::fromString($sensorId);
        $query = new SensorStatusQuery($sensorId);
        $result = $this->queryHandler->handle($query);

        if ($result->failed()) {
            return new JsonResponse(['error' => $result->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $sensorStatus = $result->getSensorStatus();
        $statusCode = $sensorStatus->isValid() ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST;

        return new JsonResponse(['status' => $sensorStatus->toString()], $statusCode);
    }
}