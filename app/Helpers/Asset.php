<?php

if (! function_exists('asset_ver')) {
    /**
     * Generate an asset path with version for the application.
     *
     * @param string $path
     * @param bool $root
     * @param null $secure
     * @return string
     */
    function asset_ver($path, $root = false, $secure = null)
    {
        if ($root) {
            $url = asset($path, $secure);
        } else {
            $url = '/' . ltrim($path, '/');
        }

        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            $url .= '?v=' . filemtime($fullPath);
        }

        return $url;
    }
}
