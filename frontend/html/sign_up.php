<?php

session_start();
require_once('../../database/db_connect.php');

if (isset($_POST['signup'])) {
    $name = $_POST['firstName'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $pnumber = $_POST['pnumber'];
    $dob = $_POST['dob'];

    $birth = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birth)->y;

    if ($age < 18) {

        $_SESSION['register_error'] = 'Must be 18 or older to create account';
        header("Location: signup.php");
        exit();
    }

    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->query("SELECT email FROM customer WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        header("Location: signup.php");
        exit();
    } else {
        $conn->query("INSERT INTO customer (firstName, surname, dateOfBirth, email, phoneNumber, passwordHash) VALUES ('$name', '$surname', '$dob', '$email', '$pnumber', '$passwordHash')");
        $customerID = $conn->insert_id;
        $_SESSION["customerID"] = $customerID;
        $_SESSION["firstname"] = $name;
        $_SESSION["surname"] = $surname;
        $_SESSION["email"] = $email;
        $_SESSION["dob"] = $dob;
    }

    header("Location: account.php");
    exit();
}


?>