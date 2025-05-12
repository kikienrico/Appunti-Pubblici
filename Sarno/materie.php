<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

// Recupera tutte le materie
$materie_result = $conn->query("SELECT id, nome FROM materie");

$docenti_materia = [];
$materia_selezionata = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["materia_id"])) {
    $materia_selezionata = $_POST["materia_id"];

    $stmt = $conn->prepare("SELECT DISTINCT u.nome, u.cognome, u.email 
                            FROM orario o
                            JOIN utenti u ON o.docente_id = u.id
                            WHERE o.materia_id = ?");
    $stmt->bind_param("i", $materia_selezionata);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $docenti_materia[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Docenti per Materia</title>
    <style>
        body {
            background-color: #212121;
            color: #fff;
            font-family: monospace, serif;
            padding: 20px;
        }
        h2, label, select, button {
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #333;
        }
        th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #444;
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
        .centro-contenuto {
            text-align: center;
            padding: 20px;
        }
        .highlight {
            font-weight: bold;
            color: #00e5ff;
        }
    </style>
</head>
<body>
    <a href="student_dashboard.php" class="back-button">
        <img src="/uploads/back_white.png" alt="Back">
    </a>
    <div class="centro-contenuto">
        <h2>Visualizza Docenti per Materia</h2>
        <form method="POST">
            <label for="materia_id">Seleziona una materia:</label>
            <select name="materia_id" id="materia_id" required>
                <option value="">Scegli una materia</option>
                <?php while ($row = $materie_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if ($materia_selezionata == $row['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Visualizza</button>
        </form>

        <?php if (!empty($docenti_materia)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($docenti_materia as $docente): ?>
                        <tr>
                            <td class="highlight"><?php echo htmlspecialchars($docente['nome']); ?></td>
                            <td class="highlight"><?php echo htmlspecialchars($docente['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($docente['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p>Nessun docente attualmente insegna questa materia.</p>
        <?php endif; ?>
    </div>
</body>
</html>
