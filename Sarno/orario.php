<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["ruolo"] !== 'docente') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "admin";
$pass = "Password";
$dbname = "registro_elettronico";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$docente_id = $_SESSION["user_id"];
$orari = [];
$query = "SELECT orario.id, classi.nome AS classe, materie.nome AS materia, orario.giorno, orario.ora_inizio, orario.ora_fine 
          FROM orario 
          JOIN classi ON orario.classe_id = classi.id 
          JOIN materie ON orario.materia_id = materie.id 
          WHERE orario.docente_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $docente_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orari[] = $row;
}
$stmt->close();

// Recupero classi e materie disponibili
$classi = $conn->query("SELECT * FROM classi")->fetch_all(MYSQLI_ASSOC);
$materie = $conn->query("SELECT * FROM materie")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classe_id = $_POST["classe"];
    $materia_id = $_POST["materia"];
    $giorno = $_POST["giorno"];
    $ora_inizio = $_POST["ora_inizio"];
    $ora_fine = date("H:i", strtotime($ora_inizio) + 3600);
    
    $stmt = $conn->prepare("INSERT INTO orario (docente_id, classe_id, materia_id, giorno, ora_inizio, ora_fine) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $docente_id, $classe_id, $materia_id, $giorno, $ora_inizio, $ora_fine);
    $stmt->execute();
    $stmt->close();
    header("Location: orario.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Orario Docente</title>
    <style>
        body { 
            background-color: #212121; 
            color: #fff; 
            font-family: monospace, serif; 
            text-align: center; 
        }

        table { 
            width: 80%; 
            margin: 20px auto; 
            border-collapse: collapse;
        }

        th, td { 
            padding: 10px; 
            border: 1px solid cyan; 
        }

        .btn { 
            padding: 10px; 
            background: cyan; 
            color: #000; 
            text-decoration: none; 
        }

        h1 {
            font-size: 32px;
        }

        h2 {
            font-size: 28px;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-button img {
            width: 32px;
            height: 32px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <a href="teacher_dashboard.php" class="back-button">
        <img src="/uploads/back_white.png" alt="Back">
    </a>
    <h1>Il tuo Orario</h1>
    <table>
        <tr><th>Classe</th><th>Materia</th><th>Giorno</th><th>Ora Inizio</th><th>Ora Fine</th></tr>
        <?php foreach ($orari as $lezione): ?>
            <tr>
                <td><?= htmlspecialchars($lezione["classe"]) ?></td>
                <td><?= htmlspecialchars($lezione["materia"]) ?></td>
                <td><?= htmlspecialchars($lezione["giorno"]) ?></td>
                <td><?= htmlspecialchars($lezione["ora_inizio"]) ?></td>
                <td><?= htmlspecialchars($lezione["ora_fine"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Aggiungi una Lezione</h2>
    <form method="post">
        <select name="classe">
            <?php foreach ($classi as $classe) { echo "<option value='{$classe['id']}'>{$classe['nome']}</option>"; } ?>
        </select>
        <select name="materia">
            <?php foreach ($materie as $materia) { echo "<option value='{$materia['id']}'>{$materia['nome']}</option>"; } ?>
        </select>
        <select name="giorno">
            <option value="Lunedì">Lunedì</option>
            <option value="Martedì">Martedì</option>
            <option value="Mercoledì">Mercoledì</option>
            <option value="Giovedì">Giovedì</option>
            <option value="Venerdì">Venerdì</option>
            <option value="Sabato">Sabato</option>
        </select>
        <input type="time" name="ora_inizio" min="07:50" max="13:50" required>
        <button type="submit" class="btn">Aggiungi</button>
    </form>
    <br>
    <a href="modifica_orario.php" class="btn">Modifica Orario</a>
</body>
</html>
