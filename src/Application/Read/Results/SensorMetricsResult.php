<?php declare(strict_types=1);

namespace App\Application\Read\Results;

use App\Application\Read\AbstractResult;

final class SensorMetricsResult extends AbstractResult
{
    private string $metricsString;

    public function getMetricsString(): string
    {
        return $this->metricsString;
    }

    public function setMetricsString(string $metricsString): void
    {
        $this->metricsString = $metricsString;
    }
}