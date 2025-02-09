<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default is empty
$dbname = "flutter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
