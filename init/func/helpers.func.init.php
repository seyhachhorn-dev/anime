<?php

/**
 * Small safe helper to build paths like APPURL . "/auth/login.php"
 */
function app_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(APPURL, '/') . ($path !== '' ? '/' . $path : '');
}

