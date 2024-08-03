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

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_repeat = trim($_POST['password-repeat']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email) || empty($password) || empty($password_repeat)) {
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
            $result = "1";
        } else {
            $error = "sign up failed :(";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="/public/styles/pages/signup.css">
</head>

<body>
    <div id="signup-container">
        <h2>Sign up</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">
                <div class="input-label">Username</div>
                <input type="text" name="username" id="username-input" placeholder='Username' value=<?php echo $username; ?>>
            </label>
            <label for="password">
                <div class="input-label">Password</div>
                <input type="password" name="password" id="password-input" placeholder='****' value=<?php echo $password; ?>>
            </label>
            <label for="password-repeat">
                <div class="input-label">Confirm Password</div>
                <input type="password" name="password-repeat" id="password-repeat-input" placeholder='****' value=<?php echo $password_repeat; ?>>
            </label>
            <label for="email">
                <div class="input-label">Email</div>
                <input type="email" name="email" id="email-input" placeholder='email@example.com' value=<?php echo $email; ?>>
            </label>
            <div class='notification-container'>
                <div class="error"><?php echo $error ?></div>
            </div>
            <button type="submit">Sign up!</button>
        </form>
        <div class="login-container"><i>Already have an account?</i><a href="login.php">Login</a></div>
        <a href="index.php" class='home-page-button'></a>
    </div>
    <div id="banner-background">
        <div id="banner">
            <h3>Signed up succesfully!</h3>
            <div class="login-container"><i>Continue here:</i><a href="login.php">Login</a></div>
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