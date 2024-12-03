<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header('Location: dasboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/checkout2.css">
  <title>Check-Out</title>
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

<body class="">
    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">CHECK-OUT DETAILS</h1>
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
    <!-- Main Section -->
  <section id="beranda" class="w-screen h-auto relative p-8">
    <h1 class="text-sm font-medium text-gray-500 mb-4">Muhammad Messi / 231401074</h1>

    <div class="grid grid-cols-2  gap-5">
      <!-- Left Content -->
      <div class=" border-r-2 border-cream grid grid-cols-2 gap-10 p-8">
        <!-- Room Damage -->
        <div class=" p-4">
          <h1 class="text-4xl mb-4 font-semibold">Room Damage</h1>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken Wall (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>

          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken Window (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>carpet damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>sofa damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken Wall (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>none </label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          
        </div>

        <!-- Bathroom Damage -->
        <div class=" p-4">
          <h1 class="text-4xl mb-4 font-semibold">Bathroom Damage</h1>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>shower damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>

          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>jetshower damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>closet (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>water heater (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken Wall (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>none</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
        </div>

        <!-- Bedroom Damage -->
        <div class="p-4">
          <h1 class="text-4xl mb-4 font-semibold">Bedroom Damage</h1>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken headshet (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>

          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>pillow damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Blanket damage (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Badstead damge (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>Broken Wall (Rp 1,500,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>none</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
        </div>

        <!-- Snack Charges -->
        <div class=" p-4">
          <h1 class="text-4xl mb-4 font-semibold">Snack Charges</h1>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>m & ms(Rp 1,500)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>

          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>kitkat(Rp 20,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>lays (Rp 17,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>oreo (Rp 20,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>toblerone (Rp 50,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
          <form class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" class="mr-2">
              <label>aqua reflection (Rp 15,000)</label>
            </div>
            <!-- Repeat other checkboxes -->
          </form>
        </div>
      </div>

      <!-- Right Content (Total Charges) -->
      <div class=" p-8">
        <h1 class="text-2xl font-semibold mb-4">Total Charges</h1>
        <div class="space-y-2">
          <pre>
            
            <p>Room Damage      : Rp 0</p>
            <p>Bedroom Damage   : Rp 0</p>
            <p>Bathroom Damage  : Rp 0</p>
            <p>Snack Charges    : Rp 25,000</p>
            <p>Room Charges     : Rp 4,500,000</p>
          </pre>
        </div>
        <div class="border-t-2 pt-4 mt-4">
          <p class="text-2xl font-bold">Total Charges: Rp 4,525,000</p>
        </div>
        <button class="bg-[#6C4E31] text-white px-6 py-2 rounded-md mt-4 hover:bg-yellow-600">
          Check-Out
        </button>
      </div>
    </div>
  </section>
</div>


  <script src="js/klik.js"></script>
</body>
</html>