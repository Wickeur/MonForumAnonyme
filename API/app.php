<?php

// Connexion à la base de données
$servername = "db";
$username = "db_user";
$password = "db_password";
$dbname = "forum_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/Thread':
        $data = getMessage($conn);
        echo json_encode($data);
        break;

    case '/Sender':
        postMessage($conn);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route non reconnue']);
        exit;
}

/**
 * Fonction pour récupérer les données des messages depuis la base de données
 */
function getMessage($conn) {
    $stmt = $conn->query("SELECT * FROM messages");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fonction pour gérer les requêtes pour la route /Sender
 */
function postMessage($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = json_decode(file_get_contents('php://input'), true);

        if (validateMessageData($postData)) {
            addMessage($conn, $postData);
            exit;
        } 
        else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Données invalides pour l\'ajout du message']);
            exit;
        }
    }
    else {
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Méthode non autorisée']);
        exit;
    }
}

/**
 * Fonction pour valider les données pour l'ajout d'un message
 */
function validateMessageData($data) {
    return isset($data['user']) && isset($data['description']);
}

/**
 * Fonction pour ajouter un message dans la base de données
 */
function addMessage($conn, $data) {
    $user = $data['user'];
    $description = $data['description'];
    $stmt = $conn->prepare("INSERT INTO messages (user, description) VALUES (:user, :description)");
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201); // Created
    echo json_encode(['message' => 'Message ajouté avec succès']);
}
?>
