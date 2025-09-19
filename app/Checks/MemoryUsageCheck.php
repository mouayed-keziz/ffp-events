<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class MemoryUsageCheck extends Check
{
    public function run(): Result
    {
        $memoryUsagePercentage = $this->getMemoryUsagePercentage();
        $usedGb = $this->getUsedMemory();
        $totalGb = $this->getTotalMemory();

        $summary = sprintf(
            '%.2f GB / %.2f GB (%d%%)',
            $usedGb,
            $totalGb,
            $memoryUsagePercentage
        );

        $result = Result::make()
            ->shortSummary($summary)
            ->meta([
                'memory_usage_percentage' => $memoryUsagePercentage,
                'memory_total_gb' => $totalGb,
                'memory_used_gb' => $usedGb,
            ]);

        if ($memoryUsagePercentage > 90) {
            return $result->failed("The server is almost out of memory : {$summary}");
        }

        if ($memoryUsagePercentage > 70) {
            return $result->warning("The server memory is getting high : {$summary}");
        }

        return $result->ok("Memory usage is healthy : {$summary}");
    }

    protected function getMemoryUsagePercentage(): int
    {
        $total = $this->getTotalMemory();
        $used  = $this->getUsedMemory();

        if ($total === 0) {
            return 0;
        }

        return (int) round(($used / $total) * 100);
    }

    protected function getTotalMemory(): float
    {
        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memInfo, $matches);

        return isset($matches[1]) ? round($matches[1] / 1024 / 1024, 2) : 0.0; // in GB
    }

    protected function getUsedMemory(): float
    {
        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $matches);

        $available = isset($matches[1]) ? round($matches[1] / 1024 / 1024, 2) : 0.0; // in GB
        $total = $this->getTotalMemory();

        return round($total - $available, 2);
    }
}
