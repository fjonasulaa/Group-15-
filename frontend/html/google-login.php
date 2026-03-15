<?php
session_start();

require_once("users.php");
require_once __DIR__ . "/../../vendor/autoload.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['credential'])) {
    echo json_encode(["success" => false, "message" => "No credential received"]);
    exit;
}

$idToken = $data['credential'];

$CLIENT_ID = "966067449001-4ajt4ll22p3p2kefig7e2rj4ih7oipml.apps.googleusercontent.com";

$client = new Google_Client(['client_id' => $CLIENT_ID]);
$payload = $client->verifyIdToken($idToken);

if ($payload) {

    $email = $payload['email'];
    $firstName = $payload['given_name'] ?? 'Google';
    $surname = $payload['family_name'] ?? 'User';

    $u = new Users();

    $customerId = $u->getCustomerIdByEmail($email);

    if ($customerId === null) {
        $customerId = $u->createGoogleUser($firstName, $surname, $email);
    }

    $_SESSION['customerID'] = $customerId;

    echo json_encode(["success" => true]);

} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Google token"
    ]);
}
