<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Project</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
    </style>
</head>
<body>

    <h1>Notes will be displayed here</h1>
    <form action="logout.php" method="post">
        <button type="submit">Log out</button>
    </form>
    
    <br>
    <a href="index.php">index page</a>
    <br>
    <a href="sql.php">example SQL page</a>
    <br>
    <a href="signup.php">sign up page</a>
    <br>
    <a href="login.php">log in page</a>
    <br>
    <a href="notes.php">notes page</a>

</body>
</html>
