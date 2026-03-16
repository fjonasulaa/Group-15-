<?php
session_start();
require_once('../../database/db_connect.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['credential'])) {
    echo json_encode(["success" => false, "message" => "No credential received"]);
    exit;
}

$idToken = $data['credential'];
$CLIENT_ID = "966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com";

// Verify token with Google's API directly - no library needed
$response = file_get_contents("https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken));

if (!$response) {
    echo json_encode(["success" => false, "message" => "Could not reach Google to verify token"]);
    exit;
}

$payload = json_decode($response, true);

// Check it's valid and meant for your app
if (!isset($payload['email']) || $payload['aud'] !== $CLIENT_ID) {
    echo json_encode(["success" => false, "message" => "Invalid token: " . json_encode($payload)]);
    exit;
}

$email     = $conn->real_escape_string($payload['email']);
$firstName = $conn->real_escape_string($payload['given_name'] ?? 'Google');
$surname   = $conn->real_escape_string($payload['family_name'] ?? 'User');

// Check if user already exists
$result = $conn->query("SELECT customerID FROM customer WHERE email = '$email'");

if ($result && $result->num_rows > 0) {
    // Existing user — log them in
    $row = $result->fetch_assoc();
    $_SESSION['customerID'] = $row['customerID'];
    echo json_encode(["success" => true, "redirect" => "account.php"]);
} else {
    // New Google user — store details in session temporarily, redirect to complete profile
    $_SESSION['google_pending'] = [
        'firstName' => $firstName,
        'surname'   => $surname,
        'email'     => $email
    ];
    echo json_encode(["success" => true, "redirect" => "complete-profile.php"]);
}
exit;
?>