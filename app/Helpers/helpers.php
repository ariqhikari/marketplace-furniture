<?php

/**
 * Helper Functions
 */

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('format_rupiah')) {
    function format_rupiah($amount): string
    {
        return 'Rp ' . number_format($amount ?? 0, 0, ',', '.');
    }
}

if (!function_exists('generate_order_number')) {
    function generate_order_number(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

if (!function_exists('product_image_url')) {
    function product_image_url(?string $path): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return 'https://placehold.co/400x400/e9ecef/495057?text=No+Image';
    }
}

if (!function_exists('get_memory_usage')) {
    function get_memory_usage(): array
    {
        return [
            'current' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak'    => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];
    }
}

if (!function_exists('measure_execution_time')) {
    function measure_execution_time(callable $callback): array
    {
        $start   = microtime(true);
        $result  = $callback();
        $elapsed = round((microtime(true) - $start) * 1000, 2);

        return [
            'result'  => $result,
            'time_ms' => $elapsed,
        ];
    }
}

if (!function_exists('get_directory_size')) {
    function get_directory_size(string $path): float
    {
        if (!file_exists($path)) return 0;

        $size = 0;
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        } catch (Exception $e) {
            return 0;
        }

        return round($size / 1024, 1); // KB
    }
}
