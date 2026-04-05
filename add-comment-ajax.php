<?php
require_once __DIR__ . "/init/init.php";

header('Content-Type: application/json');

// Only require login here when user submits comment
if (getCurrentUserId() === null) {
    $currentPage = $_SERVER['HTTP_REFERER'] ?? (APPURL . '/anime-watching.php');

    // Extra safety: only allow internal redirect
    if (strpos($currentPage, APPURL) !== 0) {
        $currentPage = APPURL;
    }

    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Please login to comment',
        'redirect' => APPURL . '/auth/login.php?redirect=' . urlencode($currentPage)
    ]);
    exit;
}

// Check if required fields exist
if (!isset($_POST['comment']) || !isset($_POST['show_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing comment or show_id'
    ]);
    exit;
}

$comment = trim($_POST['comment']);
$show_id = (int) $_POST['show_id'];
$user_id = getCurrentUserId();
$user_name = $_SESSION['username'] ?? 'Anonymous';

// Validate comment
if ($comment === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Comment cannot be empty'
    ]);
    exit;
}

// Validate show id
if ($show_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid show ID'
    ]);
    exit;
}

// Insert comment
if (insertComment($comment, $show_id, $user_id, $user_name)) {
    echo json_encode([
        'success' => true,
        'message' => 'Comment added successfully',
        'comment' => [
            'user_name' => htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'),
            'comment' => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
            'created_at' => date('Y-m-d H:i:s')
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to add comment'
    ]);
}