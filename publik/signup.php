<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($email) || empty($password)) {
        die('Please fill in all fields.');
    }

    try {
        // Hash password menggunakan bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Query untuk insert ke database
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        // Redirect ke halaman login setelah berhasil
        header('Location: login.php');
        exit(); // Pastikan tidak ada eksekusi kode lebih lanjut

    } catch (PDOException $e) {
        // Menangani error, cek untuk kode error duplikat entri (PostgreSQL error 23505)
        if ($e->getCode() == '23505') { 
            echo "Email already exists. Please use a different email.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up Form</h2>
    <form action="signup.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>