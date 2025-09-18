<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class CpuUsageCheck extends Check
{
    public function run(): Result
    {
        $cpuUsage = $this->getCpuUsage();

        $result = Result::make()
            ->shortSummary("{$cpuUsage}%")
            ->meta(['cpu_usage' => $cpuUsage]);

        if ($cpuUsage > 90) {
            return $result->failed("CPU usage too high ({$cpuUsage}%)");
        }

        if ($cpuUsage > 70) {
            return $result->warning("CPU usage getting high ({$cpuUsage}%)");
        }

        return $result->ok("CPU usage is healthy ({$cpuUsage}%)");
    }

    protected function getCpuUsage(): float
    {
        $load = sys_getloadavg()[0]; // 1-minute load average
        $cores = (int) shell_exec('nproc');

        return round(($load / $cores) * 100, 2); // Convert to % usage approximation
    }
}
