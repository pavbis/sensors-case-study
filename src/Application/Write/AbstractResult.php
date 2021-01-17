<?php declare(strict_types=1);

namespace App\Application\Write;

use App\Application\Write\Constants\ResultType;

abstract class AbstractResult
{
    private int $type;
    private string $message;

    public function __construct(int $type = ResultType::SUCCESS, string $message = '')
    {
        $this->type    = $type;
        $this->message = $message;
    }

    public function succeeded(): bool
    {
        return (ResultType::SUCCESS === $this->type);
    }

    public function failed(): bool
    {
        return (ResultType::FAILURE === $this->type);
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}