<?php declare(strict_types=1);

namespace App\Application\Types;

use App\Application\Exceptions\InvalidArgumentException;

final class SensorStatus implements RepresentsSensorStatus
{
    private const STATUS_OK = 'OK';
    private const STATUS_WARN = 'WARN';
    private const STATUS_ALERT = 'ALERT';
    private const ALLOWED_STATUSES = [self::STATUS_OK, self::STATUS_WARN, self::STATUS_ALERT];
    private string $status;

    public function __construct(string $status)
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw InvalidArgumentException::invalidSensorStatus($status);
        }
        $this->status = $status;
    }

    public static function newWithWarningStatus(): self
    {
        return new self(self::STATUS_WARN);
    }

    public static function newWithOKStatus(): self
    {
        return new self(self::STATUS_OK);
    }

    public function toString(): string
    {
        return $this->status;
    }

    public function isValid(): bool
    {
        return true;
    }
}