<?php

if (!defined('APPURL')) {
    define("APPURL", "http://localhost/anime-main");
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





