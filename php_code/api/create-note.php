<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '<response><status>401 Unathourized</status><message>Invalid session</message></response>';
    return;
}

include '../sql.php';

// Set the content type for the response
header('Content-Type: application/xml');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawPostData = file_get_contents('php://input');
    $xml = simplexml_load_string($rawPostData);
    
    if ($xml === false) {
        echo '<response><status>400 Bad Request</status><message>Invalid XML</message></response>';
        return;
    }

    $title = (string) $xml->title;
    $type = (string) $xml->type;
    $username = $_SESSION['username'];
    if ($type === 'text') {
        $body = (string) $xml->body;
        $data = [
            "title" => $title,
            "type" => $type,
            "body" => $body
        ];
        $success = createNote($username, $data);
        if ($success) {

            $response = '<response><status>200 OK</status></response>';
            echo $response;
            return;
        }
    }
    
    $response = '<response><status>400 Failure</status></response>'; // Add a more detailed response
    echo $response;
}


?>