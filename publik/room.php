<?php
session_start();
// Memasukkan file database.php untuk koneksi
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

// Query untuk mengambil data dari tabel kamar
try {
    $stmt = $conn->query("SELECT * FROM kamar ORDER BY status_kamar DESC");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Room Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .bg-cream {
            background-color: #FFDBB5;
        }

        .bg-coklat {
            background-color: #6C4E31;
        }

        .btn-book, .btn-unavailable {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.25rem 1.4rem; /* Lebih panjang di sisi kiri dan kanan */
            width: auto; /* Membiarkan lebar menyesuaikan konten */
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
        }

        .btn-book {
            box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.3);
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }  

        .btn-unavailable {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            cursor: not-allowed;
        }

        .text-sm-gray {
            color: #7F7F7F;
        }

        .font-semibold-text {
            font-weight: 600;
        }

        .sidebar-active {
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .room-card {
            display: flex;
            flex-direction: column;
            position: relative;
            border: 4px solid #6C4E31;
            border-radius: 30px;
            height: auto;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .card-header {
            background-color: #fff;
            padding: 8px 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
        }

        .card-body {
            background-color: #fff;
            padding: 15px 30px;
            border-top: 4px solid #6C4E31;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 12px;
            margin-bottom: 16px;
            padding-right: 20px;
        }

        .card-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .card-status, .card-price, .card-number {
            font-weight: 600;
        }

        .card-status {
            color: black;
        }

        .card-header span {
            font-size: 1rem;
            font-style: italic;
        }

        .card-body p {
            font-weight: 600;
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
</head>
<body class="flex flex-col h-screen bg-white">

    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">ROOM STATUS</h1>
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
                <a href="room.php" class="font-audiowide text-xs md:text-xl underline underline-offset-4">ROOM STATUS</a>
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
                    <a href="payment.php" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                </li>
            <?php } ?>
        </ul>
    </div>

        <main class="flex-1 p-6 lg:p-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <div class="room-card">
                            <div class="card-header">
                                <h2 class="text-xl font-bold card-text"><?= htmlspecialchars($room['tipe_kamar']); ?></h2>
                            </div>
                            <div class="card-body text-lg font-semibold">
                                <div class="flex">
                                    <span class="w-24">Room</span>
                                    <span>: </span>
                                    <span class="ml-4"><?= htmlspecialchars($room['nomor_kamar']); ?></span>
                                </div>
                                <div class="flex">
                                    <span class="w-24">Price</span>
                                    <span>: </span>
                                    <span class="ml-4">IDR <?= htmlspecialchars(number_format($room['harga_kamar']));?>/night</span>
                                </div>
                            </div>
                            <div class="btn-container">
                                <?php if ($room['status_kamar']): // Cek jika status_kamar bernilai true ?>
                                    <button class="btn-book bg-green-500 text-boneWhite" >Available</button>
                                <?php else: // Jika status_kamar bernilai false ?>
                                    <button class="btn-unavailable bg-merah text-boneWhite"  disabled>Unavailable</button>
                                <?php endif; ?>
                            </div>


                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No rooms available.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="js/klik.js"></script>
</body>
</html>