<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class MemoryUsageCheck extends Check
{
    public function run(): Result
    {
        $memoryUsagePercentage = $this->getMemoryUsagePercentage();

        $result = Result::make()
            ->shortSummary("{$memoryUsagePercentage}%")
            ->meta([
                'memory_usage_percentage' => $memoryUsagePercentage,
                'memory_total_mb' => $this->getTotalMemory(),
                'memory_used_mb' => $this->getUsedMemory(),
            ]);

        if ($memoryUsagePercentage > 90) {
            return $result->failed("The server is almost out of memory ({$memoryUsagePercentage}% used)");
        }

        if ($memoryUsagePercentage > 70) {
            return $result->warning("The server memory is getting high ({$memoryUsagePercentage}% used)");
        }

        return $result->ok("Memory usage is healthy ({$memoryUsagePercentage}% used)");
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

    protected function getTotalMemory(): int
    {
        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memInfo, $matches);

        return isset($matches[1]) ? (int) round($matches[1] / 1024) : 0; // in MB
    }

    protected function getUsedMemory(): int
    {
        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $matches);

        $available = isset($matches[1]) ? (int) round($matches[1] / 1024) : 0; // in MB
        $total = $this->getTotalMemory();

        return $total - $available;
    }
}
