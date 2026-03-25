<?php

function isLoggedIn(): bool
{
    return isset($_SESSION['username']) && !empty($_SESSION['username']);
}

function requireGuest(string $redirectTo = null): void
{
    if (isLoggedIn()) {
        header("location: " . ($redirectTo ?? APPURL));
        exit();
    }
}

function requireAuth(string $redirectTo = null): void
{
    if (!isLoggedIn()) {
        header("location: " . ($redirectTo ?? (APPURL . "/auth/login.php")));
        exit();
    }
}

