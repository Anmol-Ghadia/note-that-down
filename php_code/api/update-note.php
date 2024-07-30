<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo '<response><status>401 Unathourized</status><message>Invalid session</message></response>';
    return;
}

include '../sql.php';

// Set the content type for the response
header('Content-Type: application/xml');

if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {
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
    $title = (string) $xml->title;
    $type = (string) $xml->type;
    $body = (string) $xml->body;
    $color = (string) $xml->color;
    $username = $_SESSION['username'];
    $data = [
        "title" => $title,
        "type" => $type,
        "body" => $body
    ];

    // Check user is owner
    $old_note = readNoteById($note_id);
    if ($old_note->username != $username) {
        $response = '<response><status>401 Unauthorized</status></response>';
        echo $response;
        return;
    }

    // Can Update note
    $success = updateNote($note_id, $data, $color);
    if (!$success) {
        $response = '<response><status>500 Server Error</status></response>';
        echo $response;
        return;
    }

    // Note updated
    $row = readNoteById($note_id);
    
    $response = 
        '<response>
            <status>200 OK</status>
            <note>
                <color>' . $row->color . '</color>
                <title>' . $row->title . '</title>
                <type>' . $row->type . '</type>
                <body>' . $row->body . '</body>
                <timeUpdated>' . $row->updated_at . '</timeUpdated>
                <timeCreated>' . $row->created_at . '</timeCreated>
            </note>    
        </response>';
    echo $response;
    return;
}


?>