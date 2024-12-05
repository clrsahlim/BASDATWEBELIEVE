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

try {
    // Query untuk menghitung total kamar
    $queryTotal = $conn->query("SELECT COUNT(*) as total_rooms FROM kamar");
    $totalRooms = $queryTotal->fetch(PDO::FETCH_ASSOC)['total_rooms'];

    // Query untuk menghitung kamar tersedia (status_kamar = true)
    $queryAvailable = $conn->query("SELECT COUNT(*) as available_rooms FROM kamar WHERE status_kamar = true");
    $availableRooms = $queryAvailable->fetch(PDO::FETCH_ASSOC)['available_rooms'];

    // Query untuk menghitung kamar terisi (status_kamar = false)
    $queryOccupied = $conn->query("SELECT COUNT(*) as occupied_rooms FROM kamar WHERE status_kamar = false");
    $occupiedRooms = $queryOccupied->fetch(PDO::FETCH_ASSOC)['occupied_rooms'];

    $queryCheckIn = $conn->query("SELECT COUNT(*) as checked_in FROM check_in WHERE status_checkin = true");
    $checkedInGuests = $queryCheckIn->fetch(PDO::FETCH_ASSOC)['checked_in'];

    // Query untuk Current Guests (Check-Out)
    $queryCheckOut = $conn->query("SELECT COUNT(*) as checked_out FROM check_out WHERE status_checkout = true");
    $checkedOutGuests = $queryCheckOut->fetch(PDO::FETCH_ASSOC)['checked_out'];

    // Hitung Total Current Guests
    $currentGuests = $checkedInGuests + $checkedOutGuests;

    $queryPending = $conn->query("SELECT COUNT(*) as pending FROM prepayment WHERE status_prepayment = false");
    $pending = $queryPending->fetch(PDO::FETCH_ASSOC)['pending'];

    $queryPaid = $conn->query("SELECT COUNT(*) as paid FROM prepayment WHERE status_prepayment = true");
    $paid= $queryPaid->fetch(PDO::FETCH_ASSOC)['paid'];

    $queryReservations = $conn->query("SELECT COUNT(*) as reservations FROM reservation");
    $reservations= $queryReservations->fetch(PDO::FETCH_ASSOC)['reservations'];


} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <title>Manajemen Hotel</title>
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

        .btn-green {
            background-color: #0FB30F;
            color: black;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2px 10px;
            border-radius: 20%;
            color: white;
            margin: 5px 0px;
        }

        .btn-red {
            background-color: #BE1717;
            color: black;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color:white;

        }

        .status-card {
            display: flex;
            flex-direction: column;
            position: relative;
            border: 4px solid #6C4E31;
            border-radius: 30px;
            height: auto;
            overflow: hidden;
            margin: 25px 100px; 
        }

        .card-header {
            background-color: #ffffff;
            padding: 3px 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            border-bottom: 4px solid #6C4E31;
        }

        .card-header .card-text {
            font-size: 25px;
            color: #000000;
        }

        .card-body .body-card-text {
            font-size: 70px;
        }

        .card-body {
            display: flex;
            flex-direction: row;
            height: 100%; /* Pastikan kontainer memiliki tinggi penuh */
        }

        .card-body .left {
            width: 50%; 
            height: 100%; /* Sesuaikan tinggi elemen dengan kontainer induk */
            border-right: 4px solid #6C4E31; 
            display: flex;
            justify-content: center;
            align-items: center; 
            text-align: center;
            padding: 8px;
        }


        .card-body .right {
            width: 50%; /* Membagi area kanan menjadi 50% */
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center;
            gap: 8px;
            padding: 8px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr; /* 1 card per baris */
            gap: 16px;
        }

        .btn-green, .btn-red {
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            border-radius: 20px;
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

<body class="flex flex-col h-screen">
    <body class="flex flex-col h-screen">
        <nav class="bg-cream p-5 flex items-center justify-between relative">
            <button id="tombolSidebar">
                <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
            </button>
            <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">GUEST DATABASE</h1>
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
                <a href="dasboard.php" class="font-audiowide text-xs md:text-xl underline underline-offset-4">DASHBOARD</a>
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
                    <a href="payment.php" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <main class="flex-1 lg:p-4">
                <div class="grid gap-1 grid-cols-1">
                    <div class="status-card font-semibold">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Total Rooms</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text"><?php echo htmlspecialchars($totalRooms); ?></p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40"><?php echo htmlspecialchars($availableRooms); ?> Available</button>
                                <button class="btn-red text-lg w-full md:w-40"><?php echo htmlspecialchars($occupiedRooms); ?> Occupied</button>
                            </div>
                        </div>
                    </div>

                    <div class="status-card">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Current Guests</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text"><?php echo htmlspecialchars($currentGuests); ?></p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40"><?php echo htmlspecialchars($checkedInGuests); ?> Checks-In</button>
                                <button class="btn-red text-lg w-full md:w-40"><?php echo htmlspecialchars($checkedOutGuests); ?> Checks-Out</button>
                            </div>
                        </div>
                    </div>

                    <div class="status-card">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Reservations</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text"><?php echo htmlspecialchars($reservations); ?></p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40"><?php echo htmlspecialchars($paid); ?> Paid</button>
                                <button class="btn-red text-lg w-full md:w-40"><?php echo htmlspecialchars($pending); ?> Pending</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
</div>


        <script src="js/klik.js"></script>
</body>
</html>