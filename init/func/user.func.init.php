<?php


function findUserByEmail(string $email): ?array
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row : null;
}

function findAdminByEmail(string $email): ?array
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND level = 'admin' LIMIT 1");
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row : null;
}

/**
 * Returns the user row (assoc array) if password matches, otherwise null.
 */
function loginUser(string $email, string $password): ?array
{
    $user = findUserByEmail($email);
    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['password'])) {
        return null;
    }

    return $user;
}


function loginAdmin(string $email, string $password): ?array
{
    $user = findAdminByEmail($email);
    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['password'])) {
        return null;
    }

    return $user;
}

/**
 * Creates a user and returns true/false.
 * (Password must already be hashed before calling.)
 */
function createUser(string $email, string $username, string $passwordHash): bool
{
    global $conn;

    $insert = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
    return $insert->execute([
        ":email" => $email,
        ":username" => $username,
        ":password" => $passwordHash,
    ]);
}

function getCurrentUserId(): ?int
{
    return $_SESSION['id'] ?? null;
}
function getCurrentUser(): ?array
{
    global $conn;

    if(!isset($_SESSION['id'])){
        return null;
    }

    $userId = $_SESSION['id'];

    if (!$userId) {
        return null;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}
