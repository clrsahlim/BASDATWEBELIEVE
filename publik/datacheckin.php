<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reservation = isset($_POST['id_reservasi']) ? $_POST['id_reservasi'] : null;
    $id_prepayment = isset($_POST['id_prepayment']) ? $_POST['id_prepayment'] : null;

    if (!is_numeric($id_reservation) || !is_numeric($id_prepayment)) {
        die("ID reservasi dan ID prepayment harus berupa angka.");
    }

    $checkQuery = "
        SELECT COUNT(*) as count FROM check_in WHERE id_reservasi = :id_reservasi
    ";

    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->execute([':id_reservasi' => (int)$id_reservation]);
    $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        die("Reservasi ini sudah melakukan check-in sebelumnya.");
    }

    $tanggal_checkin = date('Y-m-d');

    $insertQuery = "
        INSERT INTO check_in (tanggal_checkin, id_prepayment, id_reservasi, status_checkin)
        VALUES (:tanggal_checkin, :id_prepayment, :id_reservasi, :status_checkin)
    ";

    try{
    $stmt = $conn->prepare($insertQuery);
    $stmt->execute([
        ':tanggal_checkin' => $tanggal_checkin,
        ':id_prepayment' => (int)$id_prepayment,
        ':id_reservasi' => (int)$id_reservation,
        ':status_checkin' => true
    ]);

    header("Location: checkin.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}}
?>