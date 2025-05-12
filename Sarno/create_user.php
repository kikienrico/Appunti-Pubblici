<?php
session_start();
$host = "localhost";
$user = "admin";
$pass = "Password";
$dbname = "registro_elettronico";

// Connect to MariaDB
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$add_message = "";
$remove_message = "";

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ADD USER FORM
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'add') {
        $nome     = $_POST["nome"];
        $cognome  = $_POST["cognome"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $tipologia= $_POST["tipologia"];

        // Insert personal details into `dettagli_personali`
        $stmt = $conn->prepare("INSERT INTO dettagli_personali (nome, cognome) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $cognome);
        if ($stmt->execute()) {
            $dettagli_id = $stmt->insert_id;
            $stmt->close();

            // Insert credentials into `credenziali` (password stored in plaintext)
            $stmt = $conn->prepare("INSERT INTO credenziali (password_hash, tipologia, username, dettagli_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $password, $tipologia, $username, $dettagli_id);
            if ($stmt->execute()) {
                $add_message = "User added successfully!";
            } else {
                $add_message = "Error adding user to credentials table.";
            }
            $stmt->close();
        } else {
            $add_message = "Error adding personal details.";
        }
    }

    // REMOVE USER FORM
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'remove') {
        $dettagli_id = $_POST['dettagli_id'];

        // Delete from dettagli_personali so that the foreign key in credenziali cascades deletion
        $stmt = $conn->prepare("DELETE FROM dettagli_personali WHERE id = ?");
        $stmt->bind_param("s", $dettagli_id);
        if ($stmt->execute()) {
            $remove_message = "User removed successfully!";
        } else {
            $remove_message = "Error removing user.";
        }
        $stmt->close();
    }
}

// Fetch all existing users for the removal form
$sql = "SELECT dp.id AS dettagli_id, c.username, dp.nome, dp.cognome FROM credenziali c JOIN dettagli_personali dp ON c.dettagli_id = dp.id ORDER BY dp.nome, dp.cognome";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Users</title>
  <style>
      *, ::after, ::before {
          box-sizing: border-box;
      }
      body {
          background-color: #212121;
          color: #fff;
          font-family: monospace, serif;
          letter-spacing: 0.05em;
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
          margin: 0;
          padding: 20px;
      }
      .container {
          width: 100%;
          max-width: 600px;
      }
      .form-container {
          text-align: center;
          background: rgba(255, 255, 255, 0.1);
          padding: 40px;
          border-radius: 10px;
          box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
          margin-bottom: 30px;
      }
      .form-container img {
          width: 120px;
          margin-bottom: 20px;
      }
      h1, h2 {
          font-size: 23px;
          margin-bottom: 20px;
      }
      .form {
          width: 100%;
          margin: 0 auto;
      }
      .control {
          margin-bottom: 24px;
      }
      .control input, .control select {
          width: 100%;
          padding: 14px 16px;
          border: 0;
          background: transparent;
          color: #fff;
          font-family: monospace, serif;
          font-size: 16px;
          border-bottom: 2px solid #fff;
          text-align: center;
      }
      .control input:focus, .control select:focus {
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
      .success {
          color: green;
          margin-top: 10px;
      }
  </style>
</head>
<body>
  <div class="container">
      <!-- Add User Section -->
      <div class="form-container">
          <img src="./teacher.png" alt="Teacher Logo">
          <h1>Add User</h1>
          <form class="form" method="POST" action="">
              <input type="hidden" name="form_type" value="add">
              <div class="control">
                  <input type="text" name="nome" placeholder="First Name" required>
              </div>
              <div class="control">
                  <input type="text" name="cognome" placeholder="Last Name" required>
              </div>
              <div class="control">
                  <input type="text" name="username" placeholder="Username" required>
              </div>
              <div class="control">
                  <input type="password" name="password" placeholder="Password" required>
              </div>
              <div class="control">
                  <select name="tipologia" required>
                      <option value="S">Student</option>
                      <option value="D">Teacher</option>
                  </select>
              </div>
              <button class="btn" type="submit">Add User</button>
          </form>
          <?php if (!empty($add_message)) {
              echo "<p class='".(strpos($add_message, "Error") !== false ? "error" : "success")."'>$add_message</p>";
          } ?>
      </div>

      <!-- Remove User Section -->
      <div class="form-container">
          <img src="./teacher.png" alt="Teacher Logo">
          <h2>Remove User</h2>
          <form class="form" method="POST" action="">
              <input type="hidden" name="form_type" value="remove">
              <div class="control">
                  <select name="dettagli_id" required>
                      <option value="">-- Select User --</option>
                      <?php
                      foreach ($users as $user) {
                          $fullName = $user['nome'] . " " . $user['cognome'];
                          $display  = $fullName . " (" . $user['username'] . ")";
                          echo "<option value='".$user['dettagli_id']."'>".$display."</option>";
                      }
                      ?>
                  </select>
              </div>
              <button class="btn" type="submit">Remove User</button>
          </form>
          <?php if (!empty($remove_message)) {
              echo "<p class='".(strpos($remove_message, "Error") !== false ? "error" : "success")."'>$remove_message</p>";
          } ?>
      </div>
  </div>
</body>
</html>
