<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../HTML/signup.php");
    exit();
}

$fullname = trim($_POST["fullname"] ?? "");
$username = trim($_POST["username"] ?? "");
$email    = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

$errors = [];

// simpan input lama
$_SESSION["old"] = [
    "fullname" => $fullname,
    "username" => $username,
    "email"    => $email
];

// ========================
// VALIDASI
// ========================

if ($fullname === "") {
    $errors["fullname"] = "Full name wajib diisi";
}

if ($username === "") {
    $errors["username"] = "Username wajib diisi";
} elseif (strlen($username) < 8) {
    $errors["username"] = "Username minimal 8 karakter";
}

if ($email === "") {
    $errors["email"] = "Email wajib diisi";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Format email tidak valid";
}

if ($password === "") {
    $errors["password"] = "Password wajib diisi";
} elseif (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
) {
    $errors["password"] = "Password min 8 karakter + huruf besar + angka + simbol";
}

// ========================
// CEK DATABASE
// ========================
if (empty($errors)) {

    $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");

    if ($stmt) {
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($row["username"] === $username) {
                $errors["username"] = "Username sudah digunakan";
            }
            if ($row["email"] === $email) {
                $errors["email"] = "Email sudah terdaftar";
                $errors["email_exists"] = "You already have an account, Go SignIn!!";
            }
        }

        $stmt->close();
    } else {
        $errors["general"] = "Query error (cek database)";
    }
}

// ========================
// JIKA ERROR
// ========================
if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    header("Location: ../HTML/signup.php");
    exit();
}

// ========================
// INSERT
// ========================
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {

        $_SESSION["user"] = [
            "fullname" => $fullname,
            "username" => $username,
            "email"    => $email
        ];

        unset($_SESSION["old"]);

        header("Location: ../assets/home_signed.php");
        exit();

    } else {
        $_SESSION["errors"] = ["general" => "Gagal menyimpan data"];
    }

    $stmt->close();
} else {
    $_SESSION["errors"] = ["general" => "Prepare statement gagal"];
}

header("Location: ../HTML/signup.php");
exit();