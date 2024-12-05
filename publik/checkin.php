<?php
session_start();
include 'database.php';
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

$query = "
    SELECT 
        r.nama_tamu, 
        r.id_reservasi, 
        r.tipe_kamar, 
        r.tanggal_checkin, 
        r.tanggal_checkout, 
        p.id_prepayment,
        p.status_prepayment, 
        p.total_charge,
        c.id_reservasi AS checkin_reservation_id 
    FROM 
        reservation r 
    JOIN 
        prepayment p ON r.id_reservasi = p.id_reservasi
    LEFT JOIN 
        check_in c ON r.id_reservasi = c.id_reservasi
";

$stmt = $conn->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Manajemen Hotel</title>
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
</style>

<body class="flex flex-col h-screen">
    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">CHECK-IN</h1>
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
                <ul>
                    <li class="flex items-center mb-8 mr-2 gap-2 mt-5">
                        <img class="h-5" src="img/dashboard.png" alt="">
                        <a href="dasboard.php" class="font-audiowide text-xs md:text-xl">DASHBOARD</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/room.png" alt="">
                        <a href="room.php" class="font-audiowide text-xs md:text-xl">ROOM STATUS</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/guest.png" alt="">
                        <a href="guest.php" class="font-audiowide text-xs md:text-xl">GUEST DATABASE</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/reserv.png" alt="">
                        <a href="reservasi.php" class="font-audiowide text-xs md:text-xl">RESERVATION</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/in.png" alt="">
                        <a href="checkin.php" class="font-audiowide text-xs md:text-xl underline underline-offset-4">CHECK IN</a>
                    </li>

                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/out.png" alt="">
                        <a href="checkout.php" class="font-audiowide text-xs md:text-xl">CHECK OUT</a>
                    </li>
                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/payment.png" alt="">
                        <a href="prepayment.php" class="font-audiowide text-xs md:text-xl">PRE-PAYMENT</a>
                    </li>
                    <li class="flex items-center mb-8 mr-2 gap-2">
                        <img class="h-5" src="img/payment.png" alt="">
                        <a href="payment.php" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                    </li>
                </ul>
            </div>

            <div class="flex-1 p-10">

                <label class="relative block">
                    <span class="sr-only">Search</span>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                      <img class="h-5 w-5 " viewBox="0 0 20 20" src="img/IconSeacrh.png"></img>
                    </span>
                    <input class="placeholder:font-bold placeholder:text-coklat placeholder:text-opacity-50 block bg-white w-full border border-coklat rounded-2xl py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-coklat focus:ring-coklat focus:ring-1" placeholder="Search reservations..." type="text" name="search"/>
                </label>

                <div class="md:grid md:grid-cols-2 md:gap-6">

                <?php foreach ($reservations as $reservation): 
                    $checkinDate = new DateTime($reservation['tanggal_checkin']);
                    $checkoutDate = new DateTime($reservation['tanggal_checkout']);
                    $totalNights = $checkoutDate->diff($checkinDate)->days;

                    $isCheckedIn = !empty($reservation['checkin_reservation_id']);
                ?>


                    <div class="outline outline-coklat m-5 rounded-2xl p-3 pl-5">
                        <div class="flex items-center gap-5 pb-5">
                            <p class="font-bold underline underline-offset-3">Reservation Details</p>

                            <button class="outline <?= $reservation['status_prepayment'] ? 'outline-green-500 bg-green-500' : 'outline-merah bg-merah' ?> rounded-full text-boneWhite px-3 text-xs font-semibold">
                                <?= $reservation['status_prepayment'] ? 'Paid' : 'Down-Payment' ?>
                            </button>
                    </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex">
                                <span class="w-32 font-semibold">Name</span>
                                <span>: </span>

                                <span class="ml-4"><?= htmlspecialchars($reservation['nama_tamu']) ?></span>

                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Reservation ID</span>
                                <span>: </span>

                                <span class="ml-4"><?= htmlspecialchars($reservation['id_reservasi']) ?></span>

                            </div>

                            <div class="flex">
                                <span class="w-32 font-semibold">Room Type</span>
                                <span>: </span>

                                <span class="ml-4"><?= htmlspecialchars($reservation['tipe_kamar']) ?></span>
                            </div>

                            <div class="flex">
                                <span class="w-32 font-semibold">Total Nights:</span>
                                <span>: </span>
                                <span class="ml-4"><?= $totalNights ?></span>

                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Check-In</span>
                                <span>: </span>

                                <span class="ml-4"><?= htmlspecialchars($reservation['tanggal_checkin']) ?></span>

                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Check-Out</span>
                                <span>: </span>

                                <span class="ml-4"><?= htmlspecialchars($reservation['tanggal_checkout']) ?></span>

                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Total Charges</span>
                                <span>: </span>

                                <span class="ml-4">Rp<?= number_format($reservation['total_charge'], 0, ',', '.') ?></span>
                            </div>

                            <?php 
                                if ($reservation['status_prepayment'] && !$isCheckedIn):  ?>
                                <form action="datacheckin.php" method="POST">
                                <input type="hidden" name="id_reservasi" value="<?= htmlspecialchars($reservation['id_reservasi']) ?>">
                                <input type="hidden" name="id_prepayment" value="<?= htmlspecialchars($reservation['id_prepayment'] ?? '') ?>">
                                <button type="submit" class="outline outline-coklat bg-coklat text-boneWhite rounded-full w-full">Check-In</button>
                                </form>
                            <?php else: ?>
                                <button class="bg-gray text-boneWhite rounded-full cursor-not-allowed" disabled>Check-In</button>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>

                    
            </div>
        </div>

        <script src="js/klik.js"></script>
</body>
</html>