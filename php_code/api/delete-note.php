<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '<response><status>401 Unathourized</status><message>Invalid session</message></response>';
    return;
}

include '../sql.php';

// Set the content type for the response
header('Content-Type: application/xml');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $rawPostData = file_get_contents('php://input');
    $xml = simplexml_load_string($rawPostData);
    
    if ($xml === false) {
        echo '
        <response>
            <status>400 Bad Request</status>
            <message>Invalid XML</message>
        </response>';
        return;
    }

    $note_id = (int) $xml->id;
    $username = $_SESSION['username'];
    
    // Check user is owner
    $old_note = readNoteById($note_id);
    if ($old_note['username'] != $username) {
        $response = '<response><status>401 Unauthorized</status></response>';
        echo $response;
        return;
    }

    // Can Delete note
    $success = deleteNoteById($note_id);
    if (!$success) {
        $response = '<response><status>500 Server Error</status></response>';
        echo $response;
        return;
    }

    // Note deleted
    $response = '<response><status>200 OK</status></response>';
    echo $response;
    return;
    
}

?>