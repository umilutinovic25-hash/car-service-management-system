<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_servis"; // Proveri da li si je ovako nazvao u phpMyAdminu

$conn = new mysqli($servername, $username, $password, $dbname);

// Postavljanje UTF8 da bi radila naša slova (č, ć, ž...)
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Konekcija nije uspela: " . $conn->connect_error);
}
?>