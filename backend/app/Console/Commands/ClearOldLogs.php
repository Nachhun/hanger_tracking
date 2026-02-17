<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ClearOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear {--days=7 : Number of days to keep logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log files older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $logPath = storage_path('logs');

        if (!File::isDirectory($logPath)) {
            $this->error('Logs directory not found!');
            return 1;
        }

        $this->info("🗑️  Clearing logs older than {$days} days...");

        $files = File::files($logPath);
        $deletedCount = 0;
        $cutoffDate = Carbon::now()->subDays($days);

        foreach ($files as $file) {
            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));

            if ($fileTime->lt($cutoffDate)) {
                File::delete($file);
                $deletedCount++;
                $this->line("✅ Deleted: " . $file->getFilename());
            }
        }

        if ($deletedCount > 0) {
            $this->info("✅ Successfully deleted {$deletedCount} log file(s)");
        } else {
            $this->info("ℹ️  No old log files found to delete");
        }

        return 0;
    }
}
