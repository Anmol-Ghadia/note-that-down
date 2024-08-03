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

if ($_SERVER['REQUEST_METHOD'] === 'UPDATE') {
    $rawPostData = file_get_contents('php://input');
    $xml = simplexml_load_string($rawPostData);

    if ($xml === false) {
        http_response_code(400);
        sendResponse('Invalid XML');
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
        http_response_code(401);
        sendResponse('Unauthorized');
        return;
    }

    // Can Update note
    $success = updateNote($note_id, $data, $color);
    if (!$success) {
        http_response_code(500);
        sendResponse('Server Error (CODE:UPDATE-0)');
        return;
    }

    // Note updated
    $row = readNoteById($note_id);

    http_response_code(200);
    sendResponse(
        '<note>
            <color>' . $row->color . '</color>
            <title>' . $row->title . '</title>
            <type>' . $row->type . '</type>
            <body>' . $row->body . '</body>
            <timeUpdated>' . $row->updated_at . '</timeUpdated>
            <timeCreated>' . $row->created_at . '</timeCreated>
        </note>'
    );
    return;
}