<?php
if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
    echo "GD with WebP support is available";
} else {
    echo "GD or WebP support is NOT available";
}
