<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        // json_decode(), file_get_contents()
        $composerJson = json_decode(
            file_get_contents(base_path('composer.json')),
            true
        );

        // memory_get_usage(), memory_get_peak_usage()
        $memoryUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        $memoryPeak = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        // ini_get()
        $memoryLimit = ini_get('memory_limit');
        $maxExecutionTime = ini_get('max_execution_time');

        // microtime()
        $startTime = microtime(true);

        // Raw SQL query
        $dbSize = DB::select("
            SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables
            WHERE table_schema = ?
        ", [config('database.connections.mysql.database')]);

        $queryTime = round((microtime(true) - $startTime) * 1000, 2);

        // RecursiveDirectoryIterator
        $storageSize = $this->getDirectorySize(storage_path('app/public'));

        // file() untuk baca log
        $logFile = storage_path('logs/laravel.log');
        $recentLogs = [];
        if (file_exists($logFile)) {
            $allLines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $recentLogs = array_slice(array_reverse($allLines), 0, 20);
        }

        return view('about', [
            'appName'          => config('app.name'),
            'laravelVersion'   => app()->version(),
            'phpVersion'       => PHP_VERSION,
            'serverSoftware'   => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI',
            'dependencies'     => $composerJson['require'] ?? [],
            'devDependencies'  => $composerJson['require-dev'] ?? [],
            'memoryUsage'      => $memoryUsage,
            'memoryPeak'       => $memoryPeak,
            'memoryLimit'      => $memoryLimit,
            'maxExecutionTime' => $maxExecutionTime,
            'dbSize'           => $dbSize[0]->size_mb ?? 0,
            'queryTime'        => $queryTime,
            'storageSize'      => $storageSize,
            'recentLogs'       => $recentLogs,
        ]);
    }

    private function getDirectorySize(string $path): string
    {
        if (!file_exists($path)) {
            return '0 KB';
        }

        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        if ($size >= 1048576) {
            return round($size / 1048576, 2) . ' MB';
        }

        return round($size / 1024, 2) . ' KB';
    }
}
