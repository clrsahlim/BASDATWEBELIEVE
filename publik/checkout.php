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
    header('Location: dashboard.php');
    exit;
}

try {
    // Ambil data dari tabel check_in dan reservation
    $query = "SELECT 
                c.id_checkin, 
                r.id_reservasi, 
                r.nama_tamu, 
                r.tipe_kamar, 
                c.tanggal_checkin,
                co.tanggal_checkout,
                co.id_checkout AS id_checkout
              FROM check_in c
              JOIN reservation r ON c.id_reservasi = r.id_reservasi
              LEFT JOIN check_out co ON c.id_checkin = co.id_checkin AND c.id_reservasi = co.id_reservasi
              WHERE c.status_checkin = TRUE";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $dataTamu = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($dataTamu)) {
        // Periksa dan masukkan data ke tabel checkout jika belum ada
        $checkQuery = "SELECT 1 FROM check_out WHERE id_checkin = :id_checkin AND id_reservasi = :id_reservasi";
        $insertQuery = "INSERT INTO check_out (id_checkin, id_reservasi, status_checkout, tanggal_checkout) 
                        VALUES (:id_checkin, :id_reservasi, FALSE, NULL)";
        
        $checkStmt = $conn->prepare($checkQuery);
        $insertStmt = $conn->prepare($insertQuery);

        foreach ($dataTamu as $row) {
            // Periksa apakah data sudah ada
            $checkStmt->execute([
                ':id_checkin' => $row['id_checkin'],
                ':id_reservasi' => $row['id_reservasi']
            ]);

            if ($checkStmt->rowCount() === 0) {
                // Jika tidak ada, masukkan data baru
                $insertStmt->execute([
                    ':id_checkin' => $row['id_checkin'],
                    ':id_reservasi' => $row['id_reservasi']
                ]);
            }
        }
    } else {
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
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">CHECK-OUT</h1>
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
                    <a href="checkout.php" class="font-audiowide text-xs md:text-xl underline underline-offset-4">CHECK OUT</a>
                </li>
            <?php } ?>

            <!-- Pre-Payment (Only for admin) -->
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
                    <a href="payment.php" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="flex-1 p-10">
                <div class="md:grid md:grid-cols-2 md:gap-6">
                <?php foreach ($dataTamu as $checkout): ?>
                    <div class="outline outline-coklat m-5 rounded-2xl p-3 pl-5">
                        <div class="flex items-center gap-5 pb-5">
                            <p class="font-bold underline underline-offset-3">Reservation Details</p>
                            <?php if (isset($checkout['id_checkout'])): ?>
                                <?php
                                // Ambil status_checkout dari tabel check_out
                                $statusQuery = "SELECT status_checkout FROM check_out WHERE id_checkout = :id_checkout";
                                $statusStmt = $conn->prepare($statusQuery);
                                $statusStmt->execute([':id_checkout' => $checkout['id_checkout']]);
                                $status = $statusStmt->fetch(PDO::FETCH_ASSOC);

                                if ($status && $status['status_checkout'] == true): ?>
                                    <!-- Tombol Checked-Out -->
                                    <button class="outline outline-green-500 bg-green-500 rounded-full text-boneWhite px-3 text-xs font-semibold">
                                        Checked-Out
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <div class="flex">
                                <span class="w-32 font-semibold">Name</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($checkout['nama_tamu']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Reservation ID</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($checkout['id_reservasi']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Room Type</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars($checkout['tipe_kamar']) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Check-In</span>
                                <span>: </span>
                                <span class="ml-4"><?= htmlspecialchars((new DateTime($checkout['tanggal_checkin']))->format('d F Y')) ?></span>
                            </div>
                            <div class="flex">
                                <span class="w-32 font-semibold">Check-Out</span>
                                <span>: </span>
                                <span class="ml-4"><?= isset($checkout['tanggal_checkout']) && !empty($checkout['tanggal_checkout']) 
                                    ? htmlspecialchars((new DateTime($checkout['tanggal_checkout']))->format('d F Y')) 
                                    : 'Belum Check Out' ?>
                                </span>
                            </div>
                            <?php if (isset($checkout['id_checkout'])): ?>
                                <?php
                                // Ambil status_checkout dari tabel check_out
                                $statusQuery = "SELECT status_checkout FROM check_out WHERE id_checkout = :id_checkout";
                                $statusStmt = $conn->prepare($statusQuery);
                                $statusStmt->execute([':id_checkout' => $checkout['id_checkout']]);
                                $status = $statusStmt->fetch(PDO::FETCH_ASSOC);

                                if ($status && $status['status_checkout'] == true): ?>
                                    <!-- Tombol tidak aktif -->
                                    <button class="bg-gray text-boneWhite rounded-full" disabled>
                                        Details
                                    </button>
                                <?php else: ?>
                                    <!-- Tombol aktif -->
                                    <button class="outline outline-coklat bg-coklat text-boneWhite rounded-full">
                                        <a href="checkout2.php?id_checkout=<?= htmlspecialchars($checkout['id_checkout']) ?>" class="details-button">Details</a>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
</div>


        <script src="js/klik.js"></script>
</body>
</html>