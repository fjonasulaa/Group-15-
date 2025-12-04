<?php

session_start();
require_once('../../database/db_connect.php');

if (isset($_POST['login'])) {
    $name = $_POST['first-Name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $pnumber = $_POST['pnumber'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->query("SELECT email FROM customer WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
    } else {
        $conn->query("INSERT INTO customer (firstName, surname, dateOfBirth, email, phoneNumber, passwordHash) VALUES ('$name', '$surname', '$dob', '$email', '$pnumber', '$passwordHash')");
    }

    header("Location: index.php");
    exit();
}


?>