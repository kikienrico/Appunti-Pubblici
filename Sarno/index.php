<?php
session_start();
$host = "localhost";
$user = "admin";
$pass = "Password";
$dbname = "registro_elettronico";

// Connessione a MariaDB
$conn = new mysqli($host, $user, $pass, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Gestione login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Recupera i dati dell'utente
    $stmt = $conn->prepare("SELECT id, nome, password_hash, ruolo FROM utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome, $stored_password, $ruolo);
        $stmt->fetch();

        // Confronto diretto della password (senza hash)
        if ($password === $stored_password) {
            // Memorizza i dati dell'utente nella sessione
            $_SESSION["user_id"] = $id;
            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;
            $_SESSION["ruolo"] = $ruolo;

            // Reindirizza in base al ruolo
            if ($ruolo === 'studente') {
                header("Location: student_dashboard.php");
            } else if ($ruolo === 'docente') {
                header("Location: teacher_dashboard.php");
            } else if ($ruolo === 'admin') {
                header("Location: admin_dashboard.php");
            }
            exit();
        } else {
            $error = "Password errata.";
        }
    } else {
        $error = "Utente non trovato.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #212121;
            color: #fff;
            font-family: monospace, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .form-container h1 {
            font-size: 23px;
            margin-bottom: 20px;
        }
        .control {
            margin-bottom: 24px;
        }
        .control input {
            width: 100%;
            padding: 14px 16px;
            border: 0;
            background: transparent;
            color: #fff;
            font-size: 16px;
            border-bottom: 2px solid #fff;
            text-align: center;
        }
        .control input:focus {
            outline: none;
            border-bottom: 2px solid cyan;
        }
        .btn {
            width: 100%;
            padding: 14px 16px;
            background: cyan;
            border: none;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background: #00a2a2;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <img src="./teacher.png" alt="Teacher Logo" width=150px>
        <h1>Login</h1>
        <form method="POST" action="">
            <div class="control">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="control">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button class="btn" type="submit">Accedi</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
