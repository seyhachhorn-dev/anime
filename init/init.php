<?php
// anime-main bootstrap (npic-practice style)

// Base URL of the project (used in templates)
if (!defined('APPURL')) {
    define("APPURL", "http://localhost/anime-main");
}

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DB + functions
require_once __DIR__ . '/db_init.php';
require_once __DIR__ . '/func/auth.func.init.php';
require_once __DIR__ . '/func/helpers.func.init.php';
require_once __DIR__ . '/func/user.func.init.php';
require_once __DIR__ . '/func/shows.func.init.php';

