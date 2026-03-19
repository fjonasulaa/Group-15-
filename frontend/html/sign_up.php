<?php

session_start();
require_once('../../database/db_connect.php');

if (isset($_POST['signup'])) {
    $name = $_POST['firstName'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $pnumber = $_POST['pnumber'];
    $dob = $_POST['dob'];
    $addressline = $_POST['addressline'];
    $postcode = $_POST['postcode'];

    $birth = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birth)->y;

    if ($age < 18) {

        $_SESSION['register_error'] = 'Must be 18 or older to create account';
        header("Location: signup.php");
        exit();
    }

    $password = $_POST['password'] ?? '';

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/';

    if (!preg_match($pattern, $password)) {
        $_SESSION['register_error'] = "Password must contain at least one uppercase letter, one lowercase letter, and one special character.";
        header("Location: signup.php");
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->prepare("SELECT email FROM customer WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        header("Location: signup.php");
        exit();
    } else {
        
        $stmt = $conn->prepare("INSERT INTO customer 
            (firstName, surname, dateOfBirth, addressLine, postcode, email, phoneNumber, passwordHash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssss",
            $name,
            $surname,
            $dob,
            $addressline,
            $postcode,
            $email,
            $pnumber,
            $passwordHash
        );
        $stmt->execute();

        $customerID = $conn->insert_id;
        $_SESSION["customerID"] = $customerID;
        $_SESSION["firstname"] = $name;
        $_SESSION["surname"] = $surname;
        $_SESSION["email"] = $email;
        $_SESSION["dob"] = $dob;
        $_SESSION["address"] = $addressline;
        $_SESSION["postcode"] = $postcode;
    }
    if (isset($_GET['admin']) && $_GET['admin'] === 'true') {
        $conn->query("UPDATE customer SET role = 'adminPending' WHERE customerID = $customerID");
    }

    header("Location: account.php");
    exit();
}


?>