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
}





