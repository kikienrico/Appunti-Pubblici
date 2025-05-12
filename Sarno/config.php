<?php
$host = "localhost";
$user = "admin";  // Change if needed
$password = "Password";  // Change if needed
$dbname = "registro_elettronico"; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
