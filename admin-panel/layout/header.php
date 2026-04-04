<?php
require_once __DIR__ . "/../../init/init.php";

$adminLoggedIn = isset($_SESSION['admin_username']);
$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
$adminInitials = '';

if ($adminLoggedIn) {
    $name = (string) $_SESSION['admin_username'];
    $parts = preg_split('/\\s+/', trim($name));
    if (count($parts) >= 2) {
        $adminInitials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    } else {
        $adminInitials = strtoupper(substr($name, 0, 2));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Panel — Anime</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars(ADMINURL . '/styles/style.css', ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="<?php echo $adminLoggedIn ? 'admin-body admin-body--app' : 'admin-body admin-body--auth'; ?>">

    <?php if ($adminLoggedIn): ?>
        <div class="admin-app">
            <aside class="admin-sidebar" aria-label="Main navigation">
                <div class="admin-sidebar__brand">
                    <span class="admin-sidebar__logo" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="admin-sidebar__title">Anime <span class="admin-sidebar__title-muted">Admin</span></span>
                </div>

                <nav class="admin-sidebar__nav">
                    <p class="admin-nav-label">Overview</p>
                    <ul class="admin-nav-list">
                        <li>
                            <a class="admin-nav-link <?php echo $currentScript === 'index.php' ? 'is-active' : ''; ?>" href="<?php echo ADMINURL; ?>/index.php">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </span>
                                Dashboard
                            </a>
                        </li>
                    </ul>

                    <p class="admin-nav-label">Content</p>
                    <ul class="admin-nav-list">
                        <li>
                            <a class="admin-nav-link <?php echo in_array($currentScript, ['show-shows.php', 'create-shows.php'], true) ? 'is-active' : ''; ?>" href="<?php echo ADMINURL; ?>/shows-admins/show-shows.php">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="15" rx="2" />
                                        <polyline points="17 2 12 7 7 2" />
                                    </svg>
                                </span>
                                Shows
                            </a>
                        </li>
                        <li>
                            <a class="admin-nav-link <?php echo in_array($currentScript, ['show-genres.php', 'create-genres.php'], true) ? 'is-active' : ''; ?>" href="<?php echo ADMINURL; ?>/genres-admins/show-genres.php">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                    </svg>
                                </span>
                                Genres
                            </a>
                        </li>
                        <li>
                            <a class="admin-nav-link <?php echo in_array($currentScript, ['show-episodes.php', 'create-episodes.php'], true) ? 'is-active' : ''; ?>" href="<?php echo ADMINURL; ?>/episodes-admins/show-episodes.php">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="5 3 19 12 5 21 5 3" />
                                    </svg>
                                </span>
                                Episodes
                            </a>
                        </li>
                    </ul>

                    <p class="admin-nav-label">Administration</p>
                    <ul class="admin-nav-list">
                        <li>
                            <a class="admin-nav-link <?php echo in_array($currentScript, ['admins.php', 'create-admins.php'], true) ? 'is-active' : ''; ?>" href="<?php echo ADMINURL; ?>/admins/admins.php">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </span>
                                Admins
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="admin-sidebar__user">
                    <div class="admin-sidebar__avatar" aria-hidden="true"><?php echo htmlspecialchars($adminInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="admin-sidebar__user-text">
                        <span class="admin-sidebar__user-name"><?php echo htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="admin-sidebar__user-role">Administrator</span>
                    </div>
                    <a class="admin-sidebar__logout" href="<?php echo ADMINURL; ?>/admins/logout-admins.php" title="Log out" aria-label="Log out">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                    </a>
                </div>
            </aside>

            <div class="admin-shell">
                <header class="admin-topbar">
                    <button type="button" class="admin-menu-toggle d-lg-none" id="admin-menu-toggle" aria-label="Open navigation menu" aria-expanded="false">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <div class="admin-topbar__search-wrap">
                        <label class="admin-search" for="admin-search-input">
                            <span class="admin-search__icon" aria-hidden="true">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </span>
                            <input id="admin-search-input" class="admin-search__input" type="search" placeholder="Search shows, genres, episodes…" autocomplete="off" size="1">
                        </label>
                    </div>
                    <div class="admin-topbar__actions">
                        <a class="btn btn-admin-primary btn-admin-primary--sm" href="<?php echo ADMINURL; ?>/shows-admins/create-shows.php">+ New show</a>
                        <span class="admin-topbar__avatar" aria-hidden="true"><?php echo htmlspecialchars($adminInitials, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </header>

                <main class="admin-content">
                    <div class="admin-content__inner container-fluid">
                    <?php else: ?>
                        <div class="admin-auth">
                            <div class="admin-auth__panel">
                                <div class="admin-auth__brand">
                                    <span class="admin-sidebar__logo admin-sidebar__logo--auth" aria-hidden="true">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <span class="admin-auth__title">Anime Admin</span>
                                </div>
                            <?php endif; ?>