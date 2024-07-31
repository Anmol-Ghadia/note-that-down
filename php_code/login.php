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
        @font-face {
            font-family: 'Handlee';
            src: url('public/Handlee-Regular.ttf') format('truetype');
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
            background: linear-gradient(to bottom right, #021526, #03346E, #6EACDA);
        }

        #login-container {
            position: relative;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 70vw;
            aspect-ratio: 1.3;
            text-align: center;
        }
        
        @media (orientation: landscape) {
            #login-container {
                width: 40vh;
                aspect-ratio: 1.1;
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

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
            text-align: center;
        }

        input[type="text"]:focus
        input[type="password"]:focus {
            border-color: #6a11cb;
            outline: none;
        }

        .notification-container {
            height: 5vh;
        }

        .sign-up-container {
            margin-top: 2.5%; 
            display: flex;
            justify-content: center;
        }

        .sign-up-container i {
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
            background-image: url('public/arrow-left-solid.svg');
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

    <div id="login-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <h2>Login</h2>
            <div>
                <label for="username">
                    <div class='input-label'>Username</div>
                    <input type="text" name="username" id="username-input" placeholder='Username' value=<?php echo $username;?>>
                </label>
                <br>
                <label for="password">
                    <div class='input-label'>Password</div>
                    <input type="password" name="password" id="password-input" placeholder='****' value=<?php echo $password;?>>
                </label>
            </div>
            <div class='notification-container'>
                <div><?php echo $error ?></div>
                <div><?php echo $result ?></div>
            </div>
            <button type="submit">Log in</button>
        </form>
        <div class='sign-up-container'><i>Don't have account?</i> <a href="signup.php"> sign up</a></div>
        <a href="index.php" class='home-page-button'></a>
        
    </div>
</body>
</html>
