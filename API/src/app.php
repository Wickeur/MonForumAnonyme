<?php

define('CSV_FILE_PATH', __DIR__ . '/CSV/message.csv');

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$uri = $_SERVER['REQUEST_URI'];

switch ($uri) {
    case '/Thread':
        $data = readCSV(CSV_FILE_PATH);
        echo json_encode(getMessage());
        break;

    case '/Sender':
        postMessage();
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route non reconnue']);
        exit;
}

/**
 * Fonction pour lire les données à partir d'un fichier CSV (en retirant la première ligne)
 * @param string $filename Chemin vers le fichier CSV
 **/ 
function readCSV($filename) {
    $csvData = [];
    $handle = fopen($filename, "r");

    if ($handle === FALSE) {
        // Gérer l'erreur d'ouverture de fichier
        http_response_code(500); // Erreur interne du serveur
        echo json_encode(['error' => 'Erreur lors de l\'ouverture du fichier CSV']);
        exit;
    }

    fgetcsv($handle, 1000, ",");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $csvData[] = $data;
    }

    fclose($handle);
    return $csvData;
}

/**
 * Fonction pour récupérer les données
 */
function getMessage(){
    $data = [];

    if (($handle = fopen(CSV_FILE_PATH, 'r')) !== false) {
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            // On saute la 1er ligne
            if ($row[0] === 'id') {
                continue;
            }
            // Vérification du nombre de colonnes
            if (count($row) === 3) {
                $id = $row[0];
                $data[$id] = [
                    'user' => $row[1],
                    'description' => $row[2],
                ];
            } 
        }
        fclose($handle);
    } else {
        // Gestion de l'erreur lors de l'ouverture du fichier
        die("Erreur lors de l'ouverture du fichier CSV.");
    }

    return $data;
}

/**
 * Fonction pour gérer les requêtes pour la route /sender
 */
function postMessage() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = json_decode(file_get_contents('php://input'), true);

        if (validateMessageData($postData)) {
            $newMessage = addMessage($postData);
            echo json_encode($newMessage);
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
 * Fonction pour ajouter un message
 */
function addMessage($data) {
    $lastId = getLastMessageId(CSV_FILE_PATH);
    $newMessage = [$lastId + 1, $data['user'], $data['description']];
    saveFile(CSV_FILE_PATH, $newMessage);
    return $newMessage;
}

/**
 * Fonction pour récupérer la dernière ID existante dans un fichier CSV
 * @param string $fichier_csv Chemin vers le fichier CSV
 */
function getLastMessageId($fichier_csv) {
    $messages = readCSV($fichier_csv);
    if (!empty($messages)) {
        // Récupérer la dernière ID existante
        $lastMessage = end($messages);
        return $lastMessage[0];
    }
    return 0; // Retourne 0 si le fichier est vide
}

/**
 * Fonction pour sauvegarder les données dans un fichier CSV
 * @param string $fichier_csv Chemin vers le fichier CSV
 * @param array $data Données à sauvegarder
 */
function saveFile($fichier_csv, $data) {
    $csvFile = fopen($fichier_csv, "a"); 
    fputcsv($csvFile, $data);
    fclose($csvFile);
}
