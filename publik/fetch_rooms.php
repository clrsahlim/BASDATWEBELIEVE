<?php
ob_start();  // Memulai buffering output

include 'database.php';

$tipe_kamar = isset($_GET['tipe_kamar']) ? $_GET['tipe_kamar'] : ''; 

$query = "SELECT nomor_kamar FROM kamar WHERE status_kamar = true AND tipe_kamar = :tipe_kamar";
$stmt = $conn->prepare($query);

$stmt->bindParam(':tipe_kamar', $tipe_kamar);

// Menjalankan query
$stmt->execute();

$kamar_tersedia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengecek apakah array kosong
if (!empty($kamar_tersedia)) {
    // Jika array tidak kosong, kirimkan data kamar
    echo json_encode($kamar_tersedia);
}

ob_end_flush();
?>
