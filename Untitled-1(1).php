<?php
// Konfigurasi database
$host = 'localhost';
$db = 'new folder';
$user = 'kaysi';
$pass = '221177';

try {
    // Membuat koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fungsi untuk membersihkan input
    function cleanInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Misalnya, menerima input dari form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = cleanInput($_POST['username']);
        $password = cleanInput($_POST['password']);

        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Mengecek apakah user ada
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifikasi password (asumsi password sudah di-hash)
            if (password_verify($password, $user['password'])) {
                echo "Login berhasil!";
            } else {
                echo "Password salah.";
            }
        } else {
            echo "User tidak ditemukan.";
        }
    }
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
