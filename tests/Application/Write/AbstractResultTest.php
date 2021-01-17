<?php declare(strict_types=1);

namespace App\Tests\Application\Write;

use App\Application\Write\AbstractResult;
use App\Application\Write\Constants\ResultType;
use PHPUnit\Framework\TestCase;

final class AbstractResultTest extends TestCase
{
    public function testResultHasInitiallyStatusSuccess(): void
    {
        $result = new class extends AbstractResult{
        };

        self::assertTrue($result->succeeded());
        self::assertEmpty($result->getMessage());
    }

    public function testResultIsFailed(): void
    {
        $message = 'error';

        $result = new class(ResultType::FAILURE, $message) extends AbstractResult
        {
        };

        self::assertTrue($result->failed());
        self::assertEquals($message, $result->getMessage());
    }
}
