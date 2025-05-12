<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["ruolo"] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once "config.php";

// Gestione eliminazioni\
if (isset($_POST['delete_user'])) {
    $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ? AND ruolo = 'docente'");
    $stmt->bind_param("i", $_POST['delete_user']);
    $stmt->execute();
}
if (isset($_POST['delete_studente'])) {
    $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ? AND ruolo = 'studente'");
    $stmt->bind_param("i", $_POST['delete_studente']);
    $stmt->execute();
}
if (isset($_POST['delete_classe'])) {
    $stmt = $conn->prepare("DELETE FROM classi WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_classe']);
    $stmt->execute();
}
if (isset($_POST['delete_materia'])) {
    $stmt = $conn->prepare("DELETE FROM materie WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_materia']);
    $stmt->execute();
}

// Gestione aggiunte
if (isset($_POST['add_user'])) {
    $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password_hash, ruolo) VALUES (?, ?, ?, ?, 'docente')");
    $stmt->bind_param("ssss", $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password']);
    $stmt->execute();
}
if (isset($_POST['add_studente'])) {
    $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password_hash, ruolo) VALUES (?, ?, ?, ?, 'studente')");
    $stmt->bind_param("ssss", $_POST['nome_studente'], $_POST['cognome_studente'], $_POST['email_studente'], $_POST['password_studente']);
    $stmt->execute();
}
if (isset($_POST['add_classe'])) {
    $stmt = $conn->prepare("INSERT INTO classi (nome) VALUES (?)");
    $stmt->bind_param("s", $_POST['classe']);
    $stmt->execute();
}
if (isset($_POST['add_materia'])) {
    $stmt = $conn->prepare("INSERT INTO materie (nome) VALUES (?)");
    $stmt->bind_param("s", $_POST['materia']);
    $stmt->execute();
}

$docenti = $conn->query("SELECT * FROM utenti WHERE ruolo = 'docente'");
$studenti = $conn->query("SELECT * FROM utenti WHERE ruolo = 'studente'");
$classi = $conn->query("SELECT * FROM classi");
$materie = $conn->query("SELECT * FROM materie");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            background: #1e1e1e;
            color: #fff;
            font-family: monospace;
            padding: 20px;
        }
        h2 {
            margin-top: 40px;
            color: cyan;
        }
        form {
            margin-bottom: 20px;
        }
        input, button {
            padding: 5px 10px;
            font-family: monospace;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #333;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #555;
            padding: 10px;
        }
        th {
            background-color: #444;
        }
        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
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
    position: absolute; /* Posizionamento assoluto */
    top: 20px; /* Distanza dall'alto */
    right: 20px; /* Distanza da destra */
}

.logout-button:hover {
    background-color: darkred;
    color: yellow;
}

    </style>
</head>
<body>

<a href="logout.php">
                <button class="logout-button">Logout</button>
            </a>
    <h1>Benvenuto nella Dashboard Admin</h1>

    <h2>Aggiungi Docente</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cognome" placeholder="Cognome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="password" placeholder="Password" required>
        <button type="submit" name="add_user">Aggiungi</button>
    </form>

    <table>
        <tr><th>Nome</th><th>Cognome</th><th>Email</th><th>Elimina</th></tr>
        <?php while ($row = $docenti->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['cognome']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_user" value="<?= $row['id'] ?>">
                    <button class="delete-btn" type="submit">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Aggiungi Studente</h2>
    <form method="POST">
        <input type="text" name="nome_studente" placeholder="Nome" required>
        <input type="text" name="cognome_studente" placeholder="Cognome" required>
        <input type="email" name="email_studente" placeholder="Email" required>
        <input type="text" name="password_studente" placeholder="Password" required>
        <button type="submit" name="add_studente">Aggiungi</button>
    </form>

    <table>
        <tr><th>Nome</th><th>Cognome</th><th>Email</th><th>Elimina</th></tr>
        <?php while ($row = $studenti->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['cognome']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_studente" value="<?= $row['id'] ?>">
                    <button class="delete-btn" type="submit">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Aggiungi Classe</h2>
    <form method="POST">
        <input type="text" name="classe" placeholder="Nome classe" required>
        <button type="submit" name="add_classe">Aggiungi</button>
    </form>
    <table>
        <tr><th>Nome</th><th>Elimina</th></tr>
        <?php while ($row = $classi->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_classe" value="<?= $row['id'] ?>">
                    <button class="delete-btn" type="submit">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Aggiungi Materia</h2>
    <form method="POST">
        <input type="text" name="materia" placeholder="Nome materia" required>
        <button type="submit" name="add_materia">Aggiungi</button>
    </form>
    <table>
        <tr><th>Nome</th><th>Elimina</th></tr>
        <?php while ($row = $materie->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_materia" value="<?= $row['id'] ?>">
                    <button class="delete-btn" type="submit">Elimina</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>