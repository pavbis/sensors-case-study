<?php declare(strict_types=1);

namespace App\Tests\UI\API\V1\Validators;

use App\UI\API\V1\Validators\SensorMeasurementRequestValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SensorMeasurementRequestValidatorTest extends TestCase
{
    public function testItFailWithInvalidContentType(): void
    {
        $request = new Request;
        $request->headers->set('Content-Type', 'application/xml');
        $validator = new SensorMeasurementRequestValidator($request);

        self::assertTrue($validator->failed());
        self::assertCount(2, $validator->getMessages());
    }

    public function testItFailsWithEmptyBody(): void
    {
        $request = new Request;
        $request->headers->set('Content-Type', 'application/json');
        $validator = new SensorMeasurementRequestValidator($request);

        self::assertTrue($validator->failed());
        self::assertCount(1, $validator->getMessages());
    }

    public function testItSucceedsWithValidRequestParameters(): void
    {
        $request = new Request([],[],[],[],[],[],'{"co2":1300,"time":"2020-12-23T18:55:47+00:00"}');
        $request->headers->set('Content-Type', 'application/json');
        $validator = new SensorMeasurementRequestValidator($request);

        self::assertFalse($validator->failed());
        self::assertEmpty($validator->getMessages());
    }
}
