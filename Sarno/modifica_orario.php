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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM orario WHERE id = ? AND docente_id = ?");
        $stmt->bind_param("ii", $delete_id, $docente_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['classe_id'], $_POST['materia_id'], $_POST['giorno'], $_POST['ora_inizio'])) {
        $classe_id = $_POST['classe_id'];
        $materia_id = $_POST['materia_id'];
        $giorno = $_POST['giorno'];
        $ora_inizio = $_POST['ora_inizio'];
        $ora_fine = date("H:i", strtotime($ora_inizio . " +1 hour"));

        $stmt = $conn->prepare("INSERT INTO orario (classe_id, docente_id, materia_id, giorno, ora_inizio, ora_fine) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $classe_id, $docente_id, $materia_id, $giorno, $ora_inizio, $ora_fine);
        $stmt->execute();
        $stmt->close();
    }
}

$query = "SELECT orario.id, classi.nome AS classe, materie.nome AS materia, orario.giorno, orario.ora_inizio, orario.ora_fine FROM orario JOIN classi ON orario.classe_id = classi.id JOIN materie ON orario.materia_id = materie.id WHERE orario.docente_id = ? ORDER BY FIELD(orario.giorno, 'Luned\u00ec', 'Marted\u00ec', 'Mercoled\u00ec', 'Gioved\u00ec', 'Venerd\u00ec', 'Sabato'), orario.ora_inizio";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $docente_id);
$stmt->execute();
$result = $stmt->get_result();
$orario = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Orario</title>
    <style>
        body { background-color: #212121; color: white; font-family: monospace; text-align: center; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid white; }
        th { background: rgba(255, 255, 255, 0.2); }
        .btn { padding: 8px; background: cyan; border: none; cursor: pointer; }
        .btn:hover { background: #00a2a2; }
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
    <h1>Modifica il tuo orario</h1>
    <table>
        <tr>
            <th>Classe</th>
            <th>Materia</th>
            <th>Giorno</th>
            <th>Ora Inizio</th>
            <th>Ora Fine</th>
            <th>Azioni</th>
        </tr>
        <?php foreach ($orario as $lezione): ?>
            <tr>
                <td><?= htmlspecialchars($lezione['classe']) ?></td>
                <td><?= htmlspecialchars($lezione['materia']) ?></td>
                <td><?= htmlspecialchars($lezione['giorno']) ?></td>
                <td><?= htmlspecialchars($lezione['ora_inizio']) ?></td>
                <td><?= htmlspecialchars($lezione['ora_fine']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= $lezione['id'] ?>">
                        <button type="submit" class="btn">Elimina</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="orario.php" class="btn">Aggiungi lezione</a>
</body>
</html>
