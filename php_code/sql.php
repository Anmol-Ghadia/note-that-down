<?php
$servername = "db";
$server_username = "root";
$server_password = "mariadb";
$server_database = "notes";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$server_database", $server_username, $server_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Internal Server error " . $e->getMessage();
}

// REQUIRES: user is unique && email is unique && parameters meet requirement
function createUser(string $username,string $hash,string $email) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO users (username, hash, email) VALUES (:username, :hash, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':hash', $hash);
    $stmt->bindParam(':email', $email);
    
    $output = '';
    try {
        $output = $stmt->execute();
    } catch (PDOException $e) {
        $output = 'error occured when signing up, try again'; 
    }

    return $output;
}
?>