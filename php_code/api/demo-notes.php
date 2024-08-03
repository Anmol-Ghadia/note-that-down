<?php

header('Content-Type: application/xml');

include '../helpers/api_helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $xmlFilePath = '../public/demo-notes.xml';
    if (!file_exists($xmlFilePath)) {
        http_response_code(500);
        sendResponse('Server Error (CODE:DEMO-0)');
        return;
    }

    $xmlData = file_get_contents($xmlFilePath);
    if ($xmlData === false) {
        http_response_code(500);
        sendResponse('Server Error (CODE:DEMO-1)');
        return;
    }

    http_response_code(200);
    echo $xmlData;
}
?>