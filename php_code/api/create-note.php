<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    sendResponse('Unauthorized');
    return;
}

include '../sql.php';
include '../helpers/api_helpers.php';

// Set the content type for the response
header('Content-Type: application/xml');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawPostData = file_get_contents('php://input');
    $xml = simplexml_load_string($rawPostData);

    if ($xml === false) {
        http_response_code(400);
        sendResponse('Invalid XML');
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
        if ($new_note_id == -1) {
            http_response_code(500);
            sendResponse('Server Error (CODE:CREATE-0)');

        }

        // Return the note
        $new_note = readNoteById($new_note_id);

        http_response_code(201);
        sendResponse(
            '<note>
                <id>' . $new_note_id . '</id>
                <color>' . $new_note->color . '</color>
                <title>' . $new_note->title . '</title>
                <type>' . $new_note->type . '</type>
                <body>' . $new_note->body . '</body>
                <timeUpdated>' . $new_note->updated_at . '</timeUpdated>
                <timeCreated>' . $new_note->created_at . '</timeCreated>
            </note>'
        );
        return;
    }

    http_response_code(400);
    sendResponse('Note type invalid');
}