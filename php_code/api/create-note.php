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
        echo '<response><status>400</status><message>Invalid XML</message></response>';
        return;
    }

    $title = (string) $xml->title;
    $type = (string) $xml->type;
    $username = $_SESSION['username'];
    if ($type === 'text') {
        $body = (string) $xml->body;
        $color = (string) $xml->color;
        $data = [
            "title" => $title,
            "type" => $type,
            "body" => $body
        ];
        $new_note_id = createNote($username, $data, $color);
        if ($new_note_id != -1) {

            // Return the note
            $new_note = readNoteById($new_note_id);
    
            $response = 
                '<response>
                    <status>200 OK</status>
                    <note>
                        <id>' . $new_note_id . '</id>
                        <color>' . $new_note->color . '</color>
                        <title>' . $new_note->title . '</title>
                        <type>' . $new_note->type . '</type>
                        <body>' . $new_note->body . '</body>
                        <timeUpdated>' . $new_note->updated_at . '</timeUpdated>
                        <timeCreated>' . $new_note->created_at . '</timeCreated>
                    </note>    
                </response>';
            echo $response;
            return;
        }
    }
    
    $response = '<response><status>400 Failure</status></response>'; // Add a more detailed response
    echo $response;
}


?>