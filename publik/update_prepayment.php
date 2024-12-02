<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reservasi = $_POST['id_reservasi'];

    try {
        $sql = "UPDATE prepayment SET status_prepayment = 'true' WHERE id_reservasi = :id_reservasi";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_reservasi', $id_reservasi);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
