<?php
include 'sql.php';

session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: notes.php");
}

$error = '';
$result = '0';
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
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            $result = '1';
        } else {
            $error = "Log in failed :(";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/public/styles/pages/login.css">
</head>

<body>
    <div id="login-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Login</h2>
            <div>
                <label for="username">
                    <div class='input-label'>Username</div>
                    <input type="text" name="username" id="username-input" placeholder='Username' value=<?php echo $username; ?>>
                </label>
                <label for="password">
                    <div class='input-label'>Password</div>
                    <input type="password" name="password" id="password-input" placeholder='****' value=<?php echo $password; ?>>
                </label>
            </div>
            <div class='notification-container'>
                <div class='error'><?php echo $error ?></div>
            </div>
            <button type="submit">Log in</button>
        </form>
        <div class='sign-up-container'><i>Don't have account?</i> <a href="signup.php"> sign up</a></div>
        <a href="index.php" class='home-page-button'></a>
    </div>
    <div id="banner-background">
        <div id="banner">
            <h3>Log in succesfully!</h3>
            <div class="sign-up-container"><i>Redirecting. If not redirected in few seconds, click here:</i><a href="notes.php">Noteboard</a></div>
        </div>
    </div>
</body>
<script>
    const result = <?php echo $result ?>;
    if (result == '1') {
        document.querySelector('#banner-background').dataset.show = result;
        setTimeout(()=>{
            window.location.href = "/notes.php";
        },2500)
    }
</script>
</html>