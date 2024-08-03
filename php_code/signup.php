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
        /* body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; } */

        @font-face {
            font-family: 'Handlee';
            src: url('public/fonts/Handlee-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-size: 2vh;
            font-family: Georgia, serif;
        }

        body, h1, h2, h3, p {
            margin: 0;
            padding: 0;
            font-family:  'Handlee', 'Arial', sans-serif;
            font-size: 4vh;
        }
        
        @media (orientation: landscape) {
            body, h1, h2, h3, p {
                font-size: 3vw;
            }
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #03346E;
            background-image: 
                            radial-gradient(ellipse at top, #021526 35%, transparent),
                            radial-gradient(ellipse at center, #03346E, transparent),
                            linear-gradient(to top, #6EACDA, transparent);
        }

        #signup-container {
            position: relative;
            background: #eee;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 70vw;
            aspect-ratio: 1.15;
            text-align: center;
        }
        
        @media (orientation: landscape) {
            #signup-container {
                width: 45vh;
                aspect-ratio: 0.85;
            }
        }

        form {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
        }

        form label,
        form div {
            width: 100%;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .input-label {
            text-align: left;
        }

        input {
            background: transparent;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #888;
            border-radius: 5px;
            transition: border-color 0.3s;
            text-align: center;
        }

        input:focus {
            border-color: #6a11cb;
            outline: none;
        }

        .notification-container {
            height: 5vh;
        }

        .login-container {
            margin-top: 2.5%; 
            display: flex;
            justify-content: center;
        }

        .login-container i {
            color: #888;
            user-select: none;
        }

        a,
        a:visited {
            color: #03346E;
            margin-left: min(1vh,1vw);
            transition: color 0.3s;
        }

        a:hover {
            color: black;
            text-decoration: none;
        }


        button {
            width: 100%;
            padding: 10px;
            background: #03346E;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color,color 250ms;
        }

        button:hover {
            background: #6EACDA;
            color: black;
        }

        .home-page-button {
            position: absolute;
            top: 0%;
            left: 0%;
            margin: 1vh;
            background-color: transparent;
            background-image: url('public/images/arrow-left-solid.svg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            width: 3vh;
            height: 3vh;
        }
        
        .home-page-button:hover {
            background-size: 90% 90%;
        }
    </style>
</head>
<body>

    <div id="signup-container">
        <h2>Sign up</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="username">
                <div class="input-label">Username</div>
                <input type="text" name="username" id="username-input" placeholder='Username' value=<?php echo $username;?>>
            </label>
            <label for="password">
                <div class="input-label">Password</div>
                <input type="password" name="password" id="password-input" placeholder='****' value=<?php echo $password;?>>
            </label>
            <label for="password-repeat">
                <div class="input-label">Confirm Password</div>    
                <input type="password" name="password-repeat" id="password-repeat-input" placeholder='****' value=<?php echo $password_repeat;?>>
            </label>
            <label for="email">
                <div class="input-label">Email</div>    
                <input type="email" name="email" id="email-input" placeholder='email@example.com' value=<?php echo $email;?>>
            </label>
            <div class='notification-container'>
                <div><?php echo $error ?></div>
                <div><?php echo $result ?></div>
            </div>
            <button type="submit">Sign up!</button>
        </form>
        <div class="login-container"><i>Already have an account?</i><a href="login.php">Login</a></div>
        <a href="index.php" class='home-page-button'></a>
    </div>

</body>
</html>
