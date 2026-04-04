<?php
require_once __DIR__ . "/init/init.php";

// Check if user is logged in
if (getCurrentUserId() === null) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to comment']);
    exit;
}

// Check if data is sent
if (!isset($_POST['comment']) || !isset($_POST['show_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing comment or show_id']);
    exit;
}

$comment = trim($_POST['comment']);
$show_id = (int) $_POST['show_id'];
$user_id = getCurrentUserId();
$user_name = $_SESSION['username'] ?? 'Anonymous';

// Validate comment
if (empty($comment)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
    exit;
}

// Insert comment
if (insertComment($comment, $show_id, $user_id, $user_name)) {
    // Get current timestamp (same format as database)
    $current_time = date('Y-m-d H:i:s');
    
    echo json_encode([
        'success' => true,
        'message' => 'Comment added successfully',
        'comment' => [
            'user_name' => htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'),
            'comment' => htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'),
            'created_at' => $current_time
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
}
