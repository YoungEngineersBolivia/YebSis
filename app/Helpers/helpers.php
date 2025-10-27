<?php

if (!function_exists('auto_asset')) {
    function auto_asset($path)
    {
        if (app()->environment('production')) {
            return 'https://' . request()->getHost() . '/' . ltrim($path, '/');
        }

        return asset($path);
    }
}
