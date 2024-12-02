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

try {
    // Query untuk mengambil data dari kedua tabel
    $sql = "SELECT 
                reservation.id_reservasi,
                reservation.nama_tamu,
                reservation.tipe_kamar,
                reservation.nomor_kamar,
                prepayment.total_harga,
                prepayment.total_charge,
                prepayment.deposit,
                prepayment.status_prepayment
            FROM 
                prepayment
            INNER JOIN 
                reservation
            ON 
                prepayment.id_reservasi = reservation.id_reservasi";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch data
    $prepaymentData = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Payment</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900" rel="stylesheet">
</head>

<style>
    body{
        font-family: 'Poppins', sans-serif;
    }

    #timeDisplay {
        min-width: 150px; 
        text-align: center;
        background-color: transparent;
        color: rgb(108, 78, 49); 
        border: 2px solid rgb(108, 78, 49); 
        border-radius: 1.5rem; 
        padding: 0.5rem 1rem; 
        font-weight: 600; 
        font-size: 0.875rem; 
        white-space: nowrap; 
    }
</style>


<body class="flex flex-col h-screen">
    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">PRE-PAYMENT</h1>
        <div class="flex items-center space-x-4">
            <div id="timeDisplay" class="bg-gray-200 px-3 py-1" style="margin-right: 1rem;"></div>
            <img id="userIcon" class="h-8 w-8 rounded-full" src="img/aaa.png" alt="User Icon">
        </div>
    </nav>
    
        <div class="flex flex-1">
            <div id="sidebar" class="bg-coklat text-white md:w-72 min-h-full p-5 hidden">
                <ul>
                    <li class="flex items-center mb-8 mr-2 gap-2 mt-5">
                        <img class="h-5" src="img/dashboard.png" alt="">
                        <a href="dasboard.html" class="font-audiowide text-xs md:text-xl">DASHBOARD</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/room.png" alt="">
                        <a href="room.html" class="font-audiowide text-xs md:text-xl">ROOM MANAGEMENT</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/guest.png" alt="">
                        <a href="guest.html" class="font-audiowide text-xs md:text-xl">GUEST DATABASE</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/reserv.png" alt="">
                        <a href="reservasi.php" class="font-audiowide text-xs md:text-xl">RESERVATION</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/in.png" alt="">
                        <a href="checkin.html" class="font-audiowide text-xs md:text-xl">CHECK IN</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/out.png" alt="">
                        <a href="checkout.html" class="font-audiowide text-xs md:text-xl">CHECK OUT</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/payment.png" alt="">
                        <a href="prepayment.html" class="font-audiowide text-xs md:text-xl underline underline-offset-4">PRE-PAYMENT</a>
                    </li>
                    
                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/payment.png" alt="">
                        <a href="payment.html" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                    </li>

                </ul>
            </div>

            <div class="flex-1 p-10">
                <div class="md:grid md:grid-cols-2 md:gap-6">

                <?php foreach ($prepaymentData as $data): ?>
                            <div class="outline outline-coklat m-5 rounded-2xl p-3 pl-5">
                                <div class="flex items-center gap-5 pb-5">
                                    <p class="font-bold underline underline-offset-3">Payment Details</p>
                                    <button class="outline <?= $data['status_prepayment'] == 'false' ? 'outline-green-500 bg-green-500' : 'outline-merah bg-merah' ?> rounded-full text-white px-3 text-xs font-semibold">
                                        <?= $data['status_prepayment'] == 'false' ? 'Paid' : 'Down-Payment'?>
                                    </button>
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Guest Name</span>
                                        <span>: </span>
                                        <span class="ml-4"><?= htmlspecialchars($data['nama_tamu']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Reservation ID</span>
                                        <span>: </span>
                                        <span class="ml-4"><?= htmlspecialchars($data['id_reservasi']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Room Type</span>
                                        <span>: </span>
                                        <span class="ml-4"><?= htmlspecialchars($data['tipe_kamar']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Room Number</span>
                                        <span>: </span>
                                        <span class="ml-4"><?= htmlspecialchars($data['nomor_kamar']) ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Room Charges</span>
                                        <span>: </span>
                                        <span class="ml-4">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Deposit</span>
                                        <span>: </span>
                                        <span class="ml-4">Rp <?= number_format($data['deposit'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 font-semibold">Total Charges</span>
                                        <span>: </span>
                                        <span class="ml-4">Rp <?= number_format($data['total_charge'], 0, ',', '.') ?></span>
                                    </div>
                                    <button 
                                        class="outline outline-coklat bg-coklat text-boneWhite rounded-full pay-button <?= $data['status_prepayment'] == 'true' ? 'bg-gray-400 cursor-not-allowed' : '' ?>" 
                                        data-id="<?= $data['id_reservasi'] ?>" 
                                        <?= $data['status_prepayment'] == 'true' ? 'disabled' : '' ?>
                                    >
                                        <a href="#">Pay</a>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                    </div>

        <script src="js/klik.js"></script>
        <script>
            document.querySelectorAll('.pay-button').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const idReservasi = this.getAttribute('data-id');
                    const buttonElement = this;

                    fetch('update_prepayment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_reservasi=${idReservasi}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button appearance
                            buttonElement.classList.remove('bg-coklat');
                            buttonElement.classList.add('bg-gray-400', 'cursor-not-allowed');
                            buttonElement.disabled = true;
                            buttonElement.innerHTML = 'Paid';
                        } else {
                            console.error('Error updating status:', data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        </script>

</body>
</html>