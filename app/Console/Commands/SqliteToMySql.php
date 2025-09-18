<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class SqliteToMySql extends Command
{
    protected $signature = 'db:move
        {--tables=* : Optional list of tables to move; defaults to predefined list}
        {--chunk=250 : Chunk size for batching inserts}
        {--truncate : Truncate target tables before inserting}
        {--source=sqlite : Source DB connection name}
        {--target=mysql : Target DB connection name}
        {--sqlite-db= : Override path to the SQLite database file}
        {--verify : Verify row counts after copy}';

    protected $description = 'Copy data from SQLite to MySQL using chunks';

    protected array $defaultTables = [
        'activity_log',
        'article_category',
        'articles',
        'badge_check_logs',
        'badges',
        'banners',
        'cache',
        'cache_locks',
        'categories',
        'current_attendees',
        'event_announcement_user',
        'event_announcements',
        'exhibitor_forms',
        'exhibitor_payment_slices',
        'exhibitor_post_payment_forms',
        'exhibitor_submissions',
        'exhibitors',
        'exports',
        'failed_import_rows',
        'failed_jobs',
        'imports',
        'job_batches',
        'jobs',
        'laravisits',
        'media',
        'migrations',
        'model_has_permissions',
        'model_has_roles',
        'notifications',
        'password_reset_tokens',
        'permissions',
        'plan_tiers',
        'plans',
        'products',
        'role_has_permissions',
        'roles',
        'sessions',
        'settings',
        'shares',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        'users',
        'visitor_forms',
        'visitor_submissions',
        'visitors',
    ];

    public function handle()
    {
        $source = (string) $this->option('source');
        $target = (string) $this->option('target');
        $chunk = (int) $this->option('chunk');
        $truncate = (bool) $this->option('truncate');
        $verify = (bool) $this->option('verify');

        $tables = (array) $this->option('tables');
        if (empty($tables)) {
            $tables = $this->defaultTables;
        }

        if ($chunk < 1) {
            $this->error('Chunk size must be >= 1');
            return self::FAILURE;
        }

        // Ensure the sqlite connection points to a real file even if DB_DATABASE is set for MySQL
        if ($source === 'sqlite') {
            $overridePath = $this->option('sqlite-db');
            if ($overridePath) {
                $sqlitePath = (string) $overridePath;
            } else {
                $default2 = database_path('database2.sqlite');
                $default1 = database_path('database.sqlite');
                $sqlitePath = File::exists($default2) ? $default2 : $default1;
            }

            if (! File::exists($sqlitePath)) {
                $this->error("SQLite file not found at: {$sqlitePath}");
                return self::FAILURE;
            }
            config(['database.connections.sqlite.database' => $sqlitePath]);
        }

        // Test connections
        try {
            DB::connection($source)->getPdo();
        } catch (Throwable $e) {
            $this->error("Failed connecting to source '{$source}': " . $e->getMessage());
            return self::FAILURE;
        }

        try {
            DB::connection($target)->getPdo();
        } catch (Throwable $e) {
            $this->error("Failed connecting to target '{$target}': " . $e->getMessage());
            return self::FAILURE;
        }

        $this->line("Source: {$source}");
        $this->line("Target: {$target}");
        if ($source === 'sqlite') {
            $this->line('Using SQLite file: ' . config('database.connections.sqlite.database'));
        }
        $this->line('Tables: ' . implode(', ', $tables));
        $this->line("Chunk size: {$chunk}");
        if ($truncate) {
            $this->warn('Truncating target tables before copy (FK checks temporarily disabled).');
        }

        // Disable FK checks on target for the duration of the copy
        $this->disableForeignKeys($target);

        // Optionally truncate all target tables first
        if ($truncate) {
            foreach ($tables as $table) {
                try {
                    DB::connection($target)->table($table)->truncate();
                    $this->info("Truncated {$table}");
                } catch (Throwable $e) {
                    $this->warn("Could not truncate {$table}: " . $e->getMessage());
                }
            }
        }

        $overallStart = microtime(true);
        $failures = [];
        $summary = [];

        foreach ($tables as $table) {
            $this->newLine();
            $this->info("Copying table: {$table}");

            try {
                $total = DB::connection($source)->table($table)->count();
            } catch (Throwable $e) {
                $this->error("Failed counting source rows for {$table}: " . $e->getMessage());
                $failures[] = $table;
                continue;
            }

            $this->line("Rows to copy: {$total}");
            if ($total === 0) {
                $this->info('Nothing to copy.');
                $summary[] = ['table' => $table, 'source' => $total, 'moved' => 0, 'status' => 'ok'];
                continue;
            }

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $copied = 0;

            try {
                $pages = (int) ceil($total / $chunk);
                for ($page = 0; $page < $pages; $page++) {
                    $rows = DB::connection($source)
                        ->table($table)
                        ->offset($page * $chunk)
                        ->limit($chunk)
                        ->get();

                    if ($rows->isEmpty()) {
                        break;
                    }

                    $batch = [];
                    foreach ($rows as $row) {
                        $arr = (array) $row;
                        foreach ($arr as $k => $v) {
                            if ($v === '0000-00-00 00:00:00' || $v === '0000-00-00') {
                                $arr[$k] = null;
                            }
                        }
                        $batch[] = $arr;
                    }

                    $inserted = 0;
                    if (! empty($batch)) {
                        if ($truncate) {
                            DB::connection($target)->table($table)->insert($batch);
                            $inserted = count($batch);
                        } else {
                            // Avoid duplicate key errors when target contains data
                            $inserted = (int) DB::connection($target)->table($table)->insertOrIgnore($batch);
                        }
                    }

                    $copied += $inserted;
                    $bar->advance($inserted);
                }
            } catch (Throwable $e) {
                $bar->clear();
                $this->error("Error while copying {$table}: " . $e->getMessage());
                $failures[] = $table;
                $summary[] = ['table' => $table, 'source' => $total, 'moved' => $copied, 'status' => 'failed'];
                continue;
            }

            $bar->finish();
            $this->newLine();
            $this->info("Copied {$copied} rows into {$table}");
            $summary[] = ['table' => $table, 'source' => $total, 'moved' => $copied, 'status' => 'ok'];

            if ($verify) {
                try {
                    $targetCount = DB::connection($target)->table($table)->count();
                    if ($targetCount !== $copied && ! $truncate) {
                        $this->warn("Verification note for {$table}: target now has {$targetCount} rows (copied {$copied}). This may include pre-existing rows.");
                    } elseif ($truncate && $targetCount !== $copied) {
                        $this->warn("Verification mismatch for {$table}: expected {$copied}, got {$targetCount}");
                    }
                } catch (Throwable $e) {
                    $this->warn("Verification failed for {$table}: " . $e->getMessage());
                }
            }
        }

        $this->enableForeignKeys($target);

        $elapsed = number_format(microtime(true) - $overallStart, 2);
        $this->newLine();
        $this->info('Summary (moved/total):');
        foreach ($summary as $row) {
            $line = sprintf('- %s: %d/%d', $row['table'], $row['moved'], $row['source']);
            if (($row['status'] ?? 'ok') !== 'ok') {
                $line .= ' [FAILED]';
            }
            $this->line($line);
        }
        $this->newLine();
        $this->info("Done in {$elapsed}s");

        if (! empty($failures)) {
            $this->warn('Some tables failed: ' . implode(', ', $failures));
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function disableForeignKeys(string $connection): void
    {
        try {
            DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=0');
        } catch (Throwable $e) {
            // Ignore if not supported
        }
    }

    protected function enableForeignKeys(string $connection): void
    {
        try {
            DB::connection($connection)->statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Throwable $e) {
            // Ignore if not supported
        }
    }
}
