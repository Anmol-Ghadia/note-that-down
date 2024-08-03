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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $rawPostData = file_get_contents('php://input');
    $xml = simplexml_load_string($rawPostData);

    if ($xml === false) {
        http_response_code(400);
        sendResponse('Invalid XML');
        return;
    }

    $note_id = (int) $xml->id;
    $username = $_SESSION['username'];

    // Check user is owner
    $old_note = readNoteById($note_id);
    if ($old_note->username != $username) {
        http_response_code(401);
        sendResponse('Unauthorized');
        return;
    }

    // Can Delete note
    $success = deleteNoteById($note_id);
    if (!$success) {
        http_response_code(500);
        sendResponse('Server Error (CODE:DELETE-0)');
        return;
    }


    // Note deleted
    http_response_code(202);
    sendResponse('');
    return;

}