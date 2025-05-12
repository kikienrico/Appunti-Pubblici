<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config.php";

// Fetch all docenti
$docenti_result = $conn->query("SELECT id, email FROM utenti WHERE ruolo = 'docente'");

// Fetch all classi
$classi_result = $conn->query("SELECT id, nome FROM classi");

$orario = [];
$email_selezionata = '';
$classe_selezionata = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["docente_email"]) && !empty($_POST["classe_id"])) {
    $email_selezionata = $_POST["docente_email"];
    $classe_selezionata = $_POST["classe_id"];

    $stmt = $conn->prepare("SELECT o.giorno, o.ora_inizio, o.ora_fine, m.nome AS materia
                             FROM orario o
                             JOIN utenti u ON o.docente_id = u.id
                             JOIN materie m ON o.materia_id = m.id
                             WHERE u.email = ? AND o.classe_id = ?");
    $stmt->bind_param("si", $email_selezionata, $classe_selezionata);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $ora = date("H:i", strtotime($row['ora_inizio']));
        $orario[$ora][$row['giorno']] = $row['materia'];
    }
}

$fasce_orarie = [
    "07:50" => "7.50 - 8.50",
    "08:50" => "8.50 - 9.50",
    "09:50" => "9.50 - 10.50",
    "10:50" => "10.50 - 11.50",
    "11:50" => "11.50 - 12.50",
    "12:50" => "12.50 - 13.50"
];

$giorni = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orario Docente</title>
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
        .materia-cell {
            font-weight: bold;
            color: #00e5ff;
            font-size: 17px;
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
    </style>
</head>
<body>
    <a href="student_dashboard.php" class="back-button">
        <img src="/uploads/back_white.png" alt="Back">
    </a>
    <div class="centro-contenuto">
    <h2>Visualizza Orario del Docente</h2>
    <form method="POST">
        <label for="docente_email">Seleziona docente:</label>
        <select name="docente_email" id="docente_email" required>
            <option value="">Scegli un docente</option>
            <?php while ($row = $docenti_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['email']); ?>" <?php if ($email_selezionata == $row['email']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['email']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="classe_id">Seleziona classe:</label>
        <select name="classe_id" id="classe_id" required>
            <option value="">Scegli una classe</option>
            <?php while ($row = $classi_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php if ($classe_selezionata == $row['id']) echo 'selected'; ?> >
                    <?php echo htmlspecialchars($row['nome']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Visualizza</button>
    </form>

    <?php if (!empty($orario)): ?>
        <table>
            <thead>
                <tr>
                    <th>Ora</th>
                    <?php foreach ($giorni as $giorno): ?>
                        <th><?php echo $giorno; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fasce_orarie as $ora_inizio => $fascia): ?>
                    <tr>
                        <td><?php echo $fascia; ?></td>
                        <?php foreach ($giorni as $giorno): ?>
                            <td>
                                <?php echo isset($orario[$ora_inizio][$giorno]) ? '<span class="materia-cell">' . htmlspecialchars($orario[$ora_inizio][$giorno]) . '</span>' : "-"; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>Nessun orario disponibile per il docente e classe selezionati.</p>
    <?php endif; ?>
    </div>
</body>
</html>
