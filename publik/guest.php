<?php
session_start();

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
                <img id="userIcon" class="h-8 w-8 rounded-full" src="img/aaa.png" alt="User Icon">
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
                    <div class="status-card">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Total Rooms</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text">50</p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40">12 Available</button>
                                <button class="btn-red text-lg w-full md:w-40">38 Occupied</button>
                            </div>
                        </div>
                    </div>

                    <div class="status-card">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Current Guests</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text">10</p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40">5 Checks-In</button>
                                <button class="btn-red text-lg w-full md:w-40">5 Checks-Out</button>
                            </div>
                        </div>
                    </div>

                    <div class="status-card">
                        <div class="card-header">
                            <h2 class="font-bold card-text">Reservations</h2>
                        </div>
                        <div class="card-body">
                            <div class="left">
                                <p class="body-card-text">7</p>
                            </div>
                            <div class="right">
                                <button class="btn-green text-lg w-full md:w-40">2 Paid</button>
                                <button class="btn-red text-lg w-full md:w-40">5 Pending</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
</div>


        <script src="js/klik.js"></script>
</body>
</html>