<?php
include 'sql.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: notes.php");
}

$error = $result = '';
$success = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_repeat = trim($_POST['password-repeat']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email) || empty($password) || empty($password_repeat) ) {
        $error = "Please fill all fields.";
        $success = false;
    }
    
    if ($password != $password_repeat) {
        $error = "Passwords do not match";
        $success = false;
    }

    if ($success) {
        // Hash the password
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $created = createUser($username, $hash, $email);
        if ($created) {
            $result = "sign up success!";
        } else {
            $result = "sign up failed :(";
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

    <h1>Sign up page</h1>
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
        Re-enter password:
        <label for="password-repeat">
            <input type="password" name="password-repeat" id="password-repeat-input" value=<?php echo $password_repeat;?>>
        </label>
        <br>
        Enter Email:
        <label for="email">
            <input type="email" name="email" id="email-input" value=<?php echo $email;?>>
        </label>
        <br>
        <button type="submit">Sign up!</button>
        <div>Error: <?php echo $error ?></div>
        <div>Result: <?php echo $result ?></div>
    </form>


</body>
</html>
