<?php

if (!function_exists('auto_asset')) {
    /**
     * Genera URLs con o sin HTTPS automáticamente según el entorno.
     */
    function auto_asset($path)
    {
        if (app()->environment('production')) {
            return secure_asset($path);
        }

        return asset($path);
    }
}
