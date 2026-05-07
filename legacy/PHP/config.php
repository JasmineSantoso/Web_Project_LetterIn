<?php
$conn = new mysqli("localhost", "root", "", "db_letterin");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// optional tapi bagus
$conn->set_charset("utf8mb4");
?>