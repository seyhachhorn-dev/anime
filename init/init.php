<?php

/**
 * Build APPURL from the current request so CSS, redirects, and links work when
 * you use 127.0.0.1, another port, HTTPS, or a different folder name — not only http://localhost/anime-main.
 */
if (!defined('APPURL')) {
    $defaultAppUrl = 'http://localhost/anime-main';

    if (PHP_SAPI_NAME() === 'cli' || empty($_SERVER['HTTP_HOST'])) {
        define('APPURL', $defaultAppUrl);
    } else {
        $pathPrefix = '';
        $detected = false;

        $projectRoot = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');
        $docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

        if ($projectRoot !== false && $docRoot !== false) {
            $pr = str_replace('\\', '/', $projectRoot);
            $dr = str_replace('\\', '/', $docRoot);
            if (strlen($pr) >= strlen($dr) && strncasecmp($pr, $dr, strlen($dr)) === 0) {
                $relative = trim(substr($pr, strlen($dr)), '/');
                $pathPrefix = $relative === '' ? '' : '/' . $relative;
                $detected = true;
            }
        }

        if (!$detected && !empty($_SERVER['SCRIPT_NAME'])) {
            $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
            if (preg_match('#^((?:/[^/]+)*)/admin-panel/#', $script, $m)) {
                $pathPrefix = $m[1];
                $detected = true;
            }
        }

        if (!$detected) {
            define('APPURL', $defaultAppUrl);
        } else {
            $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
                || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])
                    && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
            $scheme = $https ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            define('APPURL', rtrim($scheme . '://' . $host . $pathPrefix, '/'));
        }
    }
}

if (!defined('ADMINURL')) {
    define('ADMINURL', APPURL . '/admin-panel');
}

session_start();


// DB + functions
require_once __DIR__ . '/db_init.php';
require_once __DIR__ . '/func/auth.func.init.php';
require_once __DIR__ . '/func/helpers.func.init.php';
require_once __DIR__ . '/func/user.func.init.php';
require_once __DIR__ . '/func/shows.func.init.php';
require_once __DIR__ . '/func/category.func.init.php';
require_once __DIR__ . '/func/comment.func.init.php';
require_once __DIR__ . '/func/follow.func.init.php';
require_once __DIR__ . '/func/episode.func.init.php';
require_once __DIR__ . '/func/genre.func.init.php';






