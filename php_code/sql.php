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
// Returns true if user created
function createUser(string $username,string $hash,string $email): bool {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO users (username, hash, email) VALUES (:username, :hash, :email)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':hash', $hash);
    $stmt->bindParam(':email', $email);
    
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'error occured when signing up, try again'; // TEMP
        return false;
    }

    return true;
}

// Returns true if password is correct for the given username
function checkUser(string $username,string $password): bool {
    global $conn;

    $stmt = $conn->prepare("SELECT hash FROM users WHERE username=:username");
    $stmt->bindParam(':username', $username);
    
    try {
        $stmt->execute();
    
    } catch (PDOException $e) {
        $echo = 'error occured when logging in, try again'; // TEMP
        return false;
    }

    $arr = $stmt->fetch(PDO::FETCH_ASSOC);
    $hash = $arr["hash"];

    $password_match = password_verify($password,$hash);
    return $password_match;
}

// Adds the new note for given username
function createNote(string $username, Array $data, String $color): bool {
    global $conn;

    $content = json_encode($data);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'JSON Encode Error: ' . json_last_error_msg(); // TEMP
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO notes (username, content, color) VALUES (:username, :content, :color)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':color', $color);
    
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo 'error occured when creating note, try again'; // TEMP
        return false;
    }

    return true;
}

// Adds the new note for given username
function readNotes(string $username): Array {
    global $conn;

    // $stmt = $conn->prepare("SELECT * FROM notes WHERE username=:username ORDER BY updated_at DESC");
    $stmt = $conn->prepare("SELECT * FROM notes WHERE username=:username");
    $stmt->bindParam(':username', $username);
    
    try {
        $stmt->execute();
    
    } catch (PDOException $e) {
        $echo = 'error occured when logging in, try again'; // TEMP
        return [];
    }

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($rows as $row) {
        $out_row = json_decode($row['content']);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON Decode Error: ' . json_last_error_msg(); // TEMP
            return [];
        }
        $out_row->id = $row['note_id'];
        $out_row->color = $row['color'];
        $out_row->created_at = $row['created_at'];
        $out_row->updated_at = $row['updated_at'];
        array_push($out,$out_row);
    }
    return $out;
}

// Returns the Note specified by Id
function readNoteById(int $note_id): Array {
    global $conn;

    // $stmt = $conn->prepare("SELECT * FROM notes WHERE username=:username ORDER BY updated_at DESC");
    $stmt = $conn->prepare("SELECT * FROM notes WHERE note_id=:note_id");
    $stmt->bindParam(':note_id', $note_id);
    
    try {
        $stmt->execute();    
    } catch (PDOException $e) {
        $echo = 'error occured when logging in, try again'; // TEMP
        return [];
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

// Returns true if the note is deleted
function deleteNoteById(int $note_id): bool {
    global $conn;

    // $stmt = $conn->prepare("SELECT * FROM notes WHERE username=:username ORDER BY updated_at DESC");
    $stmt = $conn->prepare("DELETE FROM notes where note_id=:note_id");
    $stmt->bindParam(':note_id', $note_id);
    
    try {
        $stmt->execute();
    } catch (PDOException $e) {
        $echo = 'error occured when logging in, try again'; // TEMP
        return false;
    }

    return true;
}
?>