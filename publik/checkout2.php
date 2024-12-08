<?php
session_start();
include 'database.php';

if (!isset($_GET['id_checkout'])) {
    echo "ID Checkout tidak ditemukan!";
    exit;
}

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

$id_checkout = $_GET['id_checkout'];


if (isset($_POST['checkout'])) {
  // Ambil total damage charge yang dikirim dari form
  $total_damage_charge = isset($_POST['total_damage_charge']) ? $_POST['total_damage_charge'] : 0;

  // Pastikan total damage charge adalah angka yang valid
  $total_damage_charge = (int) $total_damage_charge;

  // Ambil tanggal dan waktu saat ini
  $tanggal_checkout = date('Y-m-d H:i:s');  // Format: YYYY-MM-DD HH:MM:SS

  // Update database dan tampilkan hasilnya
  $query = "UPDATE check_out SET damage_charge = :damage_charge, tanggal_checkout = :tanggal_checkout, status_checkout = TRUE WHERE id_checkout = :id_checkout";
  $stmt = $conn->prepare($query);

  // Bind parameter menggunakan bindValue() untuk PDO
  $stmt->bindValue(':damage_charge', $total_damage_charge, PDO::PARAM_INT);
  $stmt->bindValue(':tanggal_checkout', $tanggal_checkout, PDO::PARAM_STR);
  $stmt->bindValue(':id_checkout', $id_checkout, PDO::PARAM_INT);

  if ($stmt->execute()) {
      echo "<script>alert('Damage charge, tanggal checkout, dan status berhasil disimpan!'); window.location.href = 'checkout.php';</script>";
  } else {
      echo "<script>alert('Gagal menyimpan data checkout!');</script>";
  }
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
    *{
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

    button:hover {
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
      transform: translateY(-3px); /* Menarik tombol ke atas */
      transition: all 0.5s ease; /* Efek transisi halus */
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
    <!-- Main Section -->
  <section id="beranda" class="w-screen h-auto relative p-8">
    <h1 class="text-sm font-medium text-gray-500 mb-4">Muhammad Messi / 231401074</h1>

    <div class="grid grid-cols-2  gap-5">
      <!-- Left Content -->
      <div class=" border-r-2 border-cream grid grid-cols-2 gap-10 p-8">
        <!-- Room Damage -->
        <div class="p-4">
          <h1 class="text-4xl mb-4 font-semibold">Room<br>Damage</h1>
          <form id="damageForm" class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" name="room_damage[]" value="1000000" class="mr-2" onclick="updateDamageCharge()">Broken Wall (Rp 1,000,000)<br>
            </div>
            <!-- Repeat other checkboxes -->
            <div class="flex items-center">
              <input type="checkbox" name="room_damage[]" value="800000" class="mr-2" onclick="updateDamageCharge()">Broken Window (Rp 800,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="room_damage[]" value="1500000" class="mr-2" onclick="updateDamageCharge()">Sofa Damage (Rp 1,500,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="room_damage[]" value="1000000" class="mr-2" onclick="updateDamageCharge()">Carpet Damage (Rp 1,000,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="room_damage[]" value="0" class="mr-2" onclick="updateDamageCharge()">None (Rp 0)<br>
            </div>
          </form>
        </div>

        <div class="p-4">
          <h1 class="text-4xl mb-4 font-semibold">Bathroom Damage</h1>
          <form id="damageForm" class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" name="bathroom_damage[]" value="500000" class="mr-2" onclick="updateDamageCharge()">Toilet Damage (Rp 500,000)<br>
            </div>
            <!-- Repeat other checkboxes -->
            <div class="flex items-center">
              <input type="checkbox" name="bathroom_damage[]" value="600000" class="mr-2" onclick="updateDamageCharge()">Shower/Tub Damage (Rp 600,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bathroom_damage[]" value="500000" class="mr-2" onclick="updateDamageCharge()">Sink Damage (Rp 500,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bathroom_damage[]" value="400000" class="mr-2" onclick="updateDamageCharge()">Broken Mirror (Rp 400,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bathroom_damage[]" value="0" class="mr-2" onclick="updateDamageCharge()">None (Rp 0)<br>
            </div>
          </form>
        </div>

        <div class="p-4">
          <h1 class="text-4xl mb-4 font-semibold">Bedroom Damage</h1>
          <form id="damageForm" class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" name="bedroom_damage[]" value="800000" class="mr-2" onclick="updateDamageCharge()">Stained Carpet (Rp 800,000)<br>
            </div>
            <!-- Repeat other checkboxes -->
            <div class="flex items-center">
              <input type="checkbox" name="bedroom_damage[]" value="1000000" class="mr-2" onclick="updateDamageCharge()">Broken Bed Frame (Rp 1,000,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bedroom_damage[]" value="400000" class="mr-2" onclick="updateDamageCharge()">Broken Lamp (Rp 400,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bedroom_damage[]" value="1500000" class="mr-2" onclick="updateDamageCharge()">Mattress Damage(Rp 1,500,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="bedroom_damage[]" value="0" class="mr-2" onclick="updateDamageCharge()">None (Rp 0)<br>
            </div>
          </form>
        </div>

         <div class="p-4">
          <h1 class="text-4xl mb-4 font-semibold">Snack <br> Charges</h1>
          <form id="damageForm" class="space-y-2">
            <div class="flex items-center">
              <input type="checkbox" name="snack_charges[]" value="50000" class="mr-2" onclick="updateDamageCharge()">Chips (Rp 50,000)<br>
            </div>
            <!-- Repeat other checkboxes -->
            <div class="flex items-center">
              <input type="checkbox" name="snack_charges[]" value="100000" class="mr-2" onclick="updateDamageCharge()">Chocolate (Rp 100,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="snack_charges[]" value="100000" class="mr-2" onclick="updateDamageCharge()">Juice Box (Rp 100,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="snack_charges[]" value="50000" class="mr-2" onclick="updateDamageCharge()">Biscuit (Rp 50,000)<br>
            </div>

            <div class="flex items-center">
              <input type="checkbox" name="snack_charges[]" value="0" class="mr-2" onclick="updateDamageCharge()">None (Rp 0)<br>
            </div>
          </form>
        </div>
      </div>

      <!-- Right Content (Total Charges) -->
      <div class=" p-8">
        <h1 class="text-2xl font-semibold mb-4">Total Charges</h1>
        <div class="space-y-2">
          <pre>
            
          <p id="roomDamageTotal">Room Damage: Rp 0</p>
          <p id="bedroomDamageTotal">Bedroom Damage: Rp 0</p>
          <p id="bathroomDamageTotal">Bathroom Damage: Rp 0</p>
          <p id="snackChargesTotal">Snack Charges: Rp 0</p>
          </pre>
        </div>

        <form method="POST">
          <div class="border-t-2 pt-4 mt-4">
            <p class="text-2xl font-bold" id="finalTotal">Total Charges: Rp 0</p>
          </div>
          <input type="hidden" name="total_damage_charge" id="total_damage_charge">
          <button class="bg-[#6C4E31] text-white px-3 py-2 rounded-md mt-4 hover:shadow-lg" type="submit" name="checkout">
            Check-Out
          </button>
        </form>

      </div>
    </div>
  </section>
</div>


  <script src="js/klik.js"></script>
  <script>
  // Update the total charge dynamically
    // Update the total charge dynamically
function updateDamageCharge() {
   let roomDamage = 0;
   let bedroomDamage = 0;
   let bathroomDamage = 0;
   let snackCharges = 0;

   // Get room damage
   document.querySelectorAll('input[name="room_damage[]"]:checked').forEach((checkbox) => {
     roomDamage += parseInt(checkbox.value);
   });

   // Get bedroom damage
   document.querySelectorAll('input[name="bedroom_damage[]"]:checked').forEach((checkbox) => {
     bedroomDamage += parseInt(checkbox.value);
   });

   // Get bathroom damage
   document.querySelectorAll('input[name="bathroom_damage[]"]:checked').forEach((checkbox) => {
     bathroomDamage += parseInt(checkbox.value);
   });

   document.querySelectorAll('input[name="snack_charges[]"]:checked').forEach((checkbox) => {
     snackCharges += parseInt(checkbox.value);
   });


   // Update the displayed values
   document.getElementById('roomDamageTotal').textContent = `Room Damage: Rp ${roomDamage.toLocaleString()}`;
   document.getElementById('bedroomDamageTotal').textContent = `Bedroom Damage: Rp ${bedroomDamage.toLocaleString()}`;
   document.getElementById('bathroomDamageTotal').textContent = `Bathroom Damage: Rp ${bathroomDamage.toLocaleString()}`;
   document.getElementById('snackChargesTotal').textContent = `Snack Charges: Rp ${snackCharges.toLocaleString()}`;

   // Calculate and update the final total
   const total = roomDamage + bedroomDamage + bathroomDamage + snackCharges;
   document.getElementById('finalTotal').textContent = `Total Charges: Rp ${total.toLocaleString()}`;

   // Set the hidden input value
   document.getElementById('total_damage_charge').value = total;  // Update hidden input value
}
</script>
</body>
</html>