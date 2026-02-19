<?php
session_start();
include '..\..\database\db_connect.php';


class Users {

    public function login(string $email, string $password): ?int {
        global $conn;

        // Get customer by email
        $st = $conn->prepare("SELECT customerId, passwordHash FROM Customer WHERE email = ?");
        $st->bind_param("s", $email);
        $st->execute();
        $rs = $st->get_result();

        if ($rs->num_rows === 0) {
            return null; // no such email
        }

        $row = $rs->fetch_assoc();

        // Verify the password
        if (!password_verify($password, $row['passwordHash'])) {
            return null;
        }

        // Return customerId on success
        return (int)$row['customerId'];
    }

    public function getCustomerIdByEmail(string $email): ?int {
        global $conn;

        $st = $con->prepare("SELECT customerId FROM Customer WHERE email = ?");
        $st->bind_param("s", $email);
        $st->execute();
        $rs = $st->get_result();

        if ($row = $rs->fetch_assoc()) {
            return (int)$row["customerId"];
        }

        return null;
    }

    public function savePasswordResetToken(string $email, string $token, string $expires) {
    global $conn;

    $st = $conn->prepare("UPDATE Customer SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $st->bind_param("sss", $token, $expires, $email);
    $st->execute();
}

public function getUserByResetToken(string $token): ?array {
    global $conn;

    $st = $conn->prepare("SELECT * FROM Customer WHERE reset_token = ? AND reset_expires > NOW()");
    $st->bind_param("s", $token);
    $st->execute();
    $rs = $st->get_result();

    return $rs->fetch_assoc() ?: null;
}

public function updatePassword(int $customerId, string $password) {
    global $conn;

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $st = $conn->prepare(
        "UPDATE Customer 
         SET passwordHash = ?, reset_token = NULL, reset_expires = NULL 
         WHERE customerId = ?"
    );
    $st->bind_param("si", $hashed, $customerId);
    $st->execute();
}
}





