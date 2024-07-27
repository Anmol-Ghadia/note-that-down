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
function createUser(string $username,string $hash,string $email): bool {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO users (username, hash, email) VALUES (:username, :hash, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':hash', $hash);
    $stmt->bindParam(':email', $email);
    
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'error occured when signing up, try again'; 
        return false;
    }

    return true;
}

function checkUser(string $username,string $password): bool {
    global $conn;

    $stmt = $conn->prepare("SELECT hash FROM users WHERE username=:username");
    $stmt->bindParam(':username', $username);
    
    try {
        $stmt->execute();
    
    } catch (PDOException $e) {
        $echo = 'error occured when logging in, try again'; 
        return false;
    }

    $arr = $stmt->fetch(PDO::FETCH_ASSOC);
    $hash = $arr["hash"];

    $password_match = password_verify($password,$hash);
    return $password_match;
}
?>