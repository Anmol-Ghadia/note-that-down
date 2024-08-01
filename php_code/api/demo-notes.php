<?php

header('Content-Type: application/xml');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $xmlFilePath = '../public/demo-notes.xml';
    if (!file_exists($xmlFilePath)) {
        $response = '<response><status>500 Server Error</status></response>';
        echo $response;
        return;
    }

    $xmlData = file_get_contents($xmlFilePath);
    if ($xmlData === false) {
        $response = '<response><status>500 Server Error</status></response>';
        echo $response;
        return;
    }

    echo $xmlData;
}
?>