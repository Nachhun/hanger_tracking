<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanOldLogsMiddleware
{
    /**
     * Handle an incoming request.
     * Cleans logs older than 7 days once per day.
     */
    public function handle(Request $request, Closure $next)
    {
        $this->cleanLogsIfNeeded();
        return $next($request);
    }

    private function cleanLogsIfNeeded()
    {
        $markerFile = storage_path('logs/.last_cleanup');

        // Check if cleanup was done today
        if (File::exists($markerFile)) {
            $lastCleanup = Carbon::createFromTimestamp(File::lastModified($markerFile));
            if ($lastCleanup->isToday()) {
                return; // Already cleaned today
            }
        }

        // Clean logs older than 7 days
        $logPath = storage_path('logs');
        $cutoffDate = Carbon::now()->subDays(7);

        foreach (File::files($logPath) as $file) {
            if ($file->getFilename() === '.last_cleanup') {
                continue; // Skip marker file
            }

            $fileTime = Carbon::createFromTimestamp(File::lastModified($file));
            if ($fileTime->lt($cutoffDate)) {
                File::delete($file);
            }
        }

        // Update marker file
        File::put($markerFile, Carbon::now()->toDateTimeString());
    }
}
