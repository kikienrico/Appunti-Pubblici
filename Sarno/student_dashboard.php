<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        *, ::after, ::before {
            box-sizing: border-box;
        }

        body {
            background-color: #212121;
            color: #fff;
            font-family: monospace, serif;
            letter-spacing: 0.05em;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px 30px;
            width: 100%;
            position: absolute;
            top: 0;
        }

        .navbar .links {
            display: flex;
            gap: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
        }

        .navbar a:hover {
            color: cyan;
        }

        .user-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .username {
            font-size: 18px;
            color: cyan;
        }

        .logout-button {
            background-color: red;
            color: white;
            font-family: monospace;
            font-size: 16px;
            padding: 5px 10px;
            border: 2px solid white;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .logout-button:hover {
            background-color: darkred;
            color: yellow;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-size: 30px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="links">
            <a href="http://localhost:8000/classi.php">Classi</a>
            <a href="http://localhost:8000/orario_studente.php">Orario</a>
            <a href="http://localhost:8000/materie.php">Materie</a>
        </div>
        <div class="user-container">
            <span class="username">
                <?php echo "Benvenuto/a, " . htmlspecialchars($_SESSION["nome"]) . "!"; ?>
            </span>
            <a href="logout.php">
                <button class="logout-button">Logout</button>
            </a>
        </div>
    </div>
    <div class="container">
        <h1>Benvenuto, sei uno Studente!</h1>
    </div>
</body>
</html>
