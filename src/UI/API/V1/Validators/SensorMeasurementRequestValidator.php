<?php declare(strict_types=1);

namespace App\UI\API\V1\Validators;

use hollodotme\FluidValidator\FluidValidator;
use Symfony\Component\HttpFoundation\Request;

final class SensorMeasurementRequestValidator
{
    private FluidValidator $validator;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request   = $request;
        $this->validator = new FluidValidator;
    }

    public function failed() : bool
    {
        $this->validate();

        return $this->validator->failed();
    }

    private function validate() : void
    {
        $this->validator->isSame(
            'json',
            $this->request->getContentType(),
            'Only accepted content type should be application/json; charset=utf-8.'
        )->isJson(
            $this->request->getContent(),
            'An payload must be provided as a JSON string.'
        );
    }

    public function getMessages() : array
    {
        return $this->validator->getMessages();
    }
}