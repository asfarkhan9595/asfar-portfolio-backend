<?php

if (!function_exists('format_image_url')) {
    /**
     * Helper to reliably format image asset URLs.
     * Handles nulls, absolute HTTP URLs, 'storage/' prefixes, and standard relative paths.
     */
    function format_image_url(?string $path, string $default = ''): string {
        if (!$path || trim($path) === '') {
            return $default;
        }

        $trimmed = trim($path);

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $cleanPath = ltrim($trimmed, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }
}
