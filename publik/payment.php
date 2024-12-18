<?php
session_start();
include 'database.php';
include 'save_payment.php';
if (!isset($_SESSION['user_id'])) {
    // Pengguna belum login
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    // Hanya admin yang bisa mengakses halaman ini
    header('Location: dasboard.php');
    exit;
}

try {
    $query = "SELECT 
    r.nama_tamu, 
    r.tipe_kamar, 
    c.id_reservasi, 
    c.damage_charge, 
    c.tanggal_checkout, 
    p.deposit, 
    p.id_prepayment, 
    c.id_checkout, 
    COALESCE(pay.status_payment, FALSE) AS status_payment
    FROM check_out c
    JOIN reservation r ON c.id_reservasi = r.id_reservasi
    JOIN prepayment p ON c.id_reservasi = p.id_reservasi
    LEFT JOIN payment pay ON c.id_reservasi = pay.id_reservasi -- Join tabel payment untuk memeriksa status
    WHERE c.status_checkout = true";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $dataTamu = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($dataTamu)) {
        echo "Data tidak ditemukan.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    body {
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

    /* Add styles for disabled button */
    .disabled {
        background-color: #D3D3D3;
        cursor: not-allowed;
    }

    .success {
        background-color: #28a745; /* Green color */
    }
</style>


<body class="flex flex-col h-screen">
    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">PAYMENT</h1>
        <div class="flex items-center space-x-4">
            <div id="timeDisplay" class="bg-gray-200 px-3 py-1" style="margin-right: 1rem;"></div>
            <svg id="userIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 32 32" fill="currentColor">
                    <!-- Lingkaran Luar -->
                    <circle cx="16" cy="16" r="15" fill="none" stroke="#6C4E31" stroke-width="2"></circle>
                    
                    <!-- Ikon Profil -->
                    <path fill-rule="evenodd" color="#6C4E31" d="M16 4a6 6 0 00-6 6c0 3.314 2.686 6 6 6s6-2.686 6-6a6 6 0 00-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zM8 26c0-4.418 3.582-8 8-8s8 3.582 8 8a1 1 0 11-2 0c0-3.309-2.691-6-6-6s-6 2.691-6 6a1 1 0 11-2 0z" clip-rule="evenodd" />
            </svg>
        </div>
    </nav>
    
    <div class="flex flex-1">
        <div id="sidebar" class="bg-coklat text-white md:w-72 min-h-full p-5 hidden">
            <!-- Sidebar items -->
            <ul>
            <!-- Dashboard -->
            <li class="flex items-center mb-8 mr-2 gap-2 mt-5 hover:bg-">
                <img class="h-5" src="img/dashboard.png" alt="">
                <a href="dasboard.php" class="font-audiowide text-xs md:text-xl">DASHBOARD</a>
            </li>

            <!-- Room Management (Accessible for both admin and user) -->
            <li class="flex items-center mb-8 mr-2 gap-2">
                <img class="h-5" src="img/room.png" alt="">
                <a href="room.php" class="font-audiowide text-xs md:text-xl">ROOM STATUS</a>
            </li>

            <!-- Guest Database (Only for admin) -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/guest.png" alt="">
                    <a href="guest.php" class="font-audiowide text-xs md:text-xl">GUEST DATABASE</a>
                </li>
            <?php } ?>

            <!-- Reservation (Accessible for both admin and user) -->
            <li class="flex items-center mb-8 mr-2 gap-2">
                <img class="h-5" src="img/reserv.png" alt="">
                <a href="reservasi.php" class="font-audiowide text-xs md:text-xl">RESERVATION</a>
            </li>

            <!-- Check-In (Only for admin) -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/in.png" alt="">
                    <a href="checkin.php" class="font-audiowide text-xs md:text-xl">CHECK IN</a>
                </li>
            <?php } ?>

            <!-- Check-Out (Only for admin) -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/out.png" alt="">
                    <a href="checkout.php" class="font-audiowide text-xs md:text-xl">CHECK OUT</a>
                </li>
            <?php } ?>

            <?php if (isset($_SESSION['role'])) { ?>
    <li class="flex items-center mb-8 mr-2 gap-2">
        <img class="h-5" src="img/payment.png" alt="">
        <a href="<?php echo ($_SESSION['role'] == 'admin') ? 'prepayment.php' : 'prepayment_user.php'; ?>" class="font-audiowide text-xs md:text-xl">PRE-PAYMENT</a>
    </li>
<?php } ?>

            <!-- Payment (Only for admin) -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/payment.png" alt="">
                    <a href="payment.php" class="font-audiowide text-xs md:text-xl underline underline-offset-4">PAYMENT</a>
                </li>
            <?php } ?>
        </ul>
        </div>
        
        <div class="flex-1 p-10">
            <div class="md:grid md:grid-cols-2 md:gap-6">
                <?php foreach ($dataTamu as $payment): ?>
                    <div class="outline outline-coklat m-5 rounded-2xl p-3 pl-5">
                        <form action="save_payment.php" method="POST" id="paymentForm<?= $payment['id_reservasi'] ?>" onsubmit="return disableButtonAndChangeColor(event, <?= $payment['id_reservasi'] ?>)">
                            <div class="flex items-center gap-5 pb-5">
                                <p class="font-bold underline underline-offset-3">Payment Details</p>
                                <button 
                                    type="button" 
                                    id="downPaymentBtn<?= $payment['id_reservasi'] ?>" 
                                    class="outline rounded-full text-boneWhite px-3 text-xs font-semibold 
                                        <?= $payment['status_payment'] ? 'outline-green-500 bg-green-500' : 'outline-merah bg-merah' ?>">
                                    <?= $payment['status_payment'] ? 'Paid' : 'Down-Payment' ?>
                                </button>

                            </div>

                            <div class="flex flex-col space-y-2">
                                <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($payment['id_reservasi']) ?>">
                                <input type="hidden" name="id_checkout" value="<?= htmlspecialchars($payment['id_checkout']) ?>">
                                <input type="hidden" name="id_prepayment" value="<?= htmlspecialchars($payment['id_prepayment']) ?>">
                                <input type="hidden" name="deposit" value="<?= $payment['deposit'] ?>">
                                <input type="hidden" name="damage_charge" value="<?= $payment['damage_charge'] ?>">

                                <div class="flex">
                                    <span class="w-36 font-semibold">Guest Name</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= htmlspecialchars($payment['nama_tamu']) ?></span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 font-semibold">Room Type</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= htmlspecialchars($payment['tipe_kamar']) ?></span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 font-semibold">Deposit</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= number_format($payment['deposit'], 0, ',', '.') ?></span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 font-semibold">Damage charges</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= number_format($payment['damage_charge'], 0, ',', '.') ?></span>
                                </div>
                                <div class="flex">
                                    <span class="w-36 font-semibold">Refund</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= number_format($payment['deposit'] - $payment['damage_charge'], 0, ',', '.') ?></span>
                                </div>

                                <?php if ($payment['status_payment']): ?>
                                    <button class="bg-gray text-boneWhite rounded-full" disabled>Payment Saved</button>
                                <?php else: ?>
                                    <button type="submit" class="bg-coklat text-boneWhite rounded-full">Save Payment</button>
                                <?php endif; ?>

                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="js/klik.js"></script>
    <script>
    // Function to enable the button and change color of Down-Payment button after clicking it
        window.onload = function() {
        <?php foreach ($dataTamu as $payment): ?>
            const saveButton = document.getElementById(`savePaymentBtn<?= $payment['id_reservasi'] ?>`);
            
            // Jika status_payment sudah true, ubah tombol menjadi disabled
            <?php if ($payment['status_payment']): ?>
                saveButton.classList.add('bg-gray rounded-full', 'cursor-not-allowed');
                saveButton.disabled = true;
            <?php endif; ?>
        <?php endforeach; ?>
    };

    </script>



</body>
</html>