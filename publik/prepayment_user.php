<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    // Pengguna belum login
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'user') {
    // Hanya user yang bisa mengakses halaman ini
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['prepayment'])) {
    echo "No prepayment data found.";
    exit();
}

$prepayment = $_SESSION['prepayment'];

// Ambil data prepayment berdasarkan reservation_id dari session
$reservation_id = $_SESSION['prepayment']['reservation_id']; // Pastikan session ini sudah diatur

// Query untuk mendapatkan status_prepayment
$query = "SELECT status_prepayment FROM prepayment WHERE id_reservasi = :reservation_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':reservation_id', $reservation_id);

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah data ditemukan
$status_prepayment = isset($result['status_prepayment']) ? $result['status_prepayment'] : 0;

if ($status_prepayment === true) { // Status benar-benar "true" (boolean)
    $buttonClass = "outline outline-green-500 bg-green-500";
    $buttonText = "Paid";
} elseif ($status_prepayment === false) { // Status benar-benar "false" (boolean)
    $buttonClass = "outline outline-merah bg-merah";
    $buttonText = "Down-Payment";
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

            <!-- Pre-Payment (Only for admin) -->
            <?php if (isset($_SESSION['role'])) { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/payment.png" alt="">
                    <a href="<?php echo ($_SESSION['role'] == 'admin') ? 'prepayment.php' : 'prepayment_user.php'; ?>" class="font-audiowide text-xs md:text-xl underline underline-offset-4">PRE-PAYMENT</a>
                </li>
            <?php } ?>

            <!-- Payment (Only for admin) -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/payment.png" alt="">
                    <a href="payment.php" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="flex-1 p-10">
                    <div class="outline outline-coklat m-5 rounded-2xl p-3 pl-5">
                        <div class="flex items-center gap-5 pb-5">
                            <p class="font-bold underline underline-offset-3">Payment Details</p>
                            <button class="<?php echo $buttonClass; ?> rounded-full text-white px-3 text-xs font-semibold">
                                <?php echo $buttonText; ?>
                            </button>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex">
                                <span class="w-32 font-semibold">Guest Name</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($prepayment['nama_tamu']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Reservation ID</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($prepayment['reservation_id']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Room Type</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($prepayment['tipe_kamar']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Room Number</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($prepayment['nomor_kamar']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Room Charges</span>
                                <span>: </span>
                                <span class="ml-4">Rp <?= number_format($prepayment['total_harga'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Deposit</span>
                                <span>: </span>
                                <span class="ml-4">Rp <?= number_format($prepayment['deposit'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Total Charges</span>
                                <span>: </span>
                                <span class="ml-4">Rp <?= number_format($prepayment['total_charges'], 0, ',', '.') ?></span>
                    </div>
</div>

            

        <script src="js/klik.js"></script>
</body>
</html>
