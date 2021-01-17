<?php declare(strict_types=1);

namespace App\UI\API\V1\Controller;

use App\Application\Types\CO2;
use App\Application\Types\Measurement;
use App\Application\Types\Sensor;
use App\Application\Types\SensorId;
use App\Application\Write\CommandHandlers\RecordSensorDataCommandHandler;
use App\Application\Write\Commands\RecordSensorDataCommand;
use App\UI\API\V1\Validators\SensorMeasurementRequestValidator;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

final class RecordSensorDataController
{
    private RecordSensorDataCommandHandler $commandHandler;

    public function __construct(RecordSensorDataCommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    public function sensorMeasurements(Request $request, string $sensorId): JsonResponse
    {
        $validator = new SensorMeasurementRequestValidator($request);

        if ($validator->failed()) {
            return new JsonResponse($validator->getMessages(), JsonResponse::HTTP_BAD_REQUEST);
        }

        $body = json_decode($request->getContent(), true);
        $co2 = new CO2($body['co2']);
        $time = new DateTimeImmutable($body['time']);
        $measurementId = Uuid::v4();
        $measurement = new Measurement($measurementId, $co2, $time);
        $sensorId = SensorId::fromString($sensorId);

        if ($measurement->exceedsWarningLevel()) {
            $sensor = Sensor::newWithWarning($sensorId, $measurement);
        } else {
            $sensor = Sensor::newWithOK($sensorId, $measurement);
        }

        $command = new RecordSensorDataCommand($sensor);
        $result = $this->commandHandler->handle($command);

        if ($result->succeeded()) {
            return new JsonResponse(['co2' => $body['co2'], 'time' => $body['time']]);
        }

        return new JsonResponse(['error' => $result->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}