<?php
include 'sql.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: notes.php");
}

$error = $result = '';
$success = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    session_start();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please fill all fields.";
        $success = false;
    }
    
    if ($success) {
        $match = checkUser($username, $password);
        if ($match) {
            $result = "Logged in!";
            // session_start();
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            header("Location: notes.php");
        } else {
            $result = "Log in failed :(";
        }
    }
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

    <h1>Log in page</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Choose Username:
        <label for="username">
            <input type="text" name="username" id="username-input" value=<?php echo $username;?>>
        </label>
        <br>
        Enter password:
        <label for="password">
            <input type="password" name="password" id="password-input" value=<?php echo $password;?>>
        </label>
        <br>
        <button type="submit">Log in!</button>
        <div>Error: <?php echo $error ?></div>
        <div>Result: <?php echo $result ?></div>
    </form>


</body>
</html>
