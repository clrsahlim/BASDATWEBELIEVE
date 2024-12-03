<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_reservasi = $_POST['id_reservasi'];
        $id_checkout = $_POST['id_checkout'];
        $id_prepayment = $_POST['id_prepayment'];
        $deposit = $_POST['deposit'];
        $damage_charge = $_POST['damage_charge'];
        $refund = $deposit - $damage_charge;
        $status_payment = '1'; // Atur sesuai logika Anda
        $tanggal_payment = date('Y-m-d H:i:s');

        // Cek apakah data sudah ada di tabel payment
        $checkQuery = "SELECT COUNT(*) FROM payment WHERE id_reservasi = :id_reservasi";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':id_reservasi', $id_reservasi);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            // Jika data sudah ada, tampilkan pesan atau kembalikan ke halaman
            header('Location: payment.php?error=already_exists');
            exit;
        }

        // Jika belum ada, masukkan data baru
        $query = "INSERT INTO payment (tanggal_payment, id_prepayment, id_reservasi, id_checkout, total_kembalian, status_payment) 
                  VALUES (:tanggal_payment, :id_prepayment, :id_reservasi, :id_checkout, :total_kembalian, :status_payment)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':tanggal_payment', $tanggal_payment);
        $stmt->bindParam(':id_prepayment', $id_prepayment);
        $stmt->bindParam(':id_reservasi', $id_reservasi);
        $stmt->bindParam(':id_checkout', $id_checkout);
        $stmt->bindParam(':total_kembalian', $refund);
        $stmt->bindParam(':status_payment', $status_payment);
        $stmt->execute();

        header('Location: payment.php?success=1');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
