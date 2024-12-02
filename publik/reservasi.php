<?php
session_start(); // Mulai session di awal file
include 'database.php';
include 'fetch_rooms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_tamu = $_POST['nama_tamu'];
    $nik = isset($_POST['nik']) ? $_POST['nik'] : null;
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];
    $tanggal_checkin = $_POST['tanggal_checkin'];
    $tanggal_checkout = $_POST['tanggal_checkout'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $nomor_kamar = $_POST['nomor_kamar'];

    try {
        // Insert ke tabel reservation
        $sql = "INSERT INTO reservation (nama_tamu, nik, alamat, no_telp, email, tanggal_checkin, tanggal_checkout, tipe_kamar, nomor_kamar)
                VALUES (:nama_tamu, :nik, :alamat, :no_telp, :email, :tanggal_checkin, :tanggal_checkout, :tipe_kamar, :nomor_kamar)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':nama_tamu', $nama_tamu);
        $stmt->bindParam(':nik', $nik);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':no_telp', $no_telp);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tanggal_checkin', $tanggal_checkin);
        $stmt->bindParam(':tanggal_checkout', $tanggal_checkout);
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':nomor_kamar', $nomor_kamar);

        if ($stmt->execute()) {
            // 2. Update the room status to false after successful reservation
            $query = "UPDATE kamar SET status_kamar = false WHERE tipe_kamar = :tipe_kamar AND nomor_kamar = :nomor_kamar AND status_kamar = true";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':tipe_kamar', $tipe_kamar);
            $stmt->bindParam(':nomor_kamar', $nomor_kamar);

            if ($stmt->execute()) {
                $reservation_id = $conn->lastInsertId(); // Ambil ID reservasi terakhir
            
                // Ambil harga kamar berdasarkan tipe_kamar atau nomor_kamar
                $query_kamar = "SELECT harga_kamar FROM kamar WHERE tipe_kamar = :tipe_kamar AND nomor_kamar = :nomor_kamar";
                $stmt_kamar = $conn->prepare($query_kamar);
                $stmt_kamar->bindParam(':tipe_kamar', $tipe_kamar);
                $stmt_kamar->bindParam(':nomor_kamar', $nomor_kamar);
                $stmt_kamar->execute();
            
                $kamar = $stmt_kamar->fetch(PDO::FETCH_ASSOC);
            
                if (!$kamar) {
                    echo "Room not found!";
                    exit();
                }
            
                $harga_kamar = $kamar['harga_kamar']; // Harga kamar per malam
            
                // Hitung jumlah hari menginap
                $checkin_date = new DateTime($tanggal_checkin);
                $checkout_date = new DateTime($tanggal_checkout);
                $jumlah_hari = $checkin_date->diff($checkout_date)->days;
            
                if ($jumlah_hari < 1) {
                    echo "Invalid check-in/check-out dates!";
                    exit();
                }
            
                // Hitung total harga
                $total_harga = $harga_kamar * $jumlah_hari;
                $deposit = $total_harga* (50/100); 
                $total_charges = $total_harga + $deposit;

                $prepayment_sql = "INSERT INTO prepayment (id_reservasi, total_harga, total_charge, deposit, status_prepayment)
                                   VALUES (:id_reservasi, :total_harga, :total_charge, :deposit, :status_prepayment)";
                $prepayment_stmt = $conn->prepare($prepayment_sql);
            
                $prepayment_stmt->bindParam(':id_reservasi', $reservation_id);
                $prepayment_stmt->bindParam(':total_harga', $total_harga);
                $prepayment_stmt->bindParam(':total_charge', $total_charges);
                $prepayment_stmt->bindParam(':deposit', $deposit);
                $prepayment_stmt->bindValue(':status_prepayment', 'false'); // Status awal pembayaran
            
                if ($prepayment_stmt->execute()) {
                    // Simpan data ke session untuk ditampilkan di halaman berikutnya
                    $_SESSION['prepayment'] = [
                        'reservation_id' => $reservation_id,
                        'nama_tamu' => $nama_tamu,
                        'tipe_kamar' => $tipe_kamar,
                        'nomor_kamar' => $nomor_kamar,
                        'total_charges' => $total_charges,
                        'deposit' => $deposit,
                        'total_harga' => $total_harga
                    ];
            
                    // Redirect ke halaman prepayment_user
                    header("Location: prepayment_user.php");
                    exit();
                } else {
                    echo "Failed to add prepayment data.";
                }
            } else {
                echo "Failed to add reservation.";
            }
    }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


$tipe_kamar = $_GET['tipe_kamar'] ?? '';  // Get room type if available

// Query untuk mengambil data kamar yang tersedia
$query = "SELECT id_kamar, nomor_kamar, tipe_kamar FROM kamar WHERE status_kamar = true";
if ($tipe_kamar) {
    $query .= " AND tipe_kamar = :tipe_kamar"; // Jika tipe kamar dipilih, tambahkan kondisi filter
}

$stmt = $conn->prepare($query);
if ($tipe_kamar) {
    $stmt->bindParam(':tipe_kamar', $tipe_kamar); // Bind tipe kamar ke query
}
$stmt->execute();
$kamar_tersedia = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil tipe kamar yang unik dari hasil query
$tipe_kamar_unik = [];
foreach ($kamar_tersedia as $kamar) {
    $tipe_kamar_unik[$kamar['tipe_kamar']] = $kamar['tipe_kamar'];
}
$tipe_kamar_unik = array_values($tipe_kamar_unik); // Mengubah array menjadi numerik (untuk dropdown)
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Manajemen Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .bg-coklat {
            background-color: #6C4E31;
        }

        .bg-cream {
            background-color: #FFDBB5;
        }

        .text-white {
            color: #ffffff;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .font-bold {
            font-weight: bold;
        }

        .room-card {
            display: flex;
            flex-direction: column;
            position: relative;
            border: 4px solid #6C4E31;
            border-radius: 12px;
            height: auto;
            overflow: hidden;
            margin-top: 24px;
            margin-bottom: 24px;
        }

        .isi {
            margin: 10px;
        }

        .btn-submit {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.5rem 1.5rem; /* Lebih panjang di sisi kiri dan kanan */
            width: auto; /* Biarkan lebar menyesuaikan konten */
            border: 1px solid transparent; /* Menambahkan border, jika diperlukan */
            border-radius: 50px; /* Membuat tombol oval */
            transition: all 0.3s ease-in-out;
            color: whitesmoke;
            background-color: #8b5e3c; /* Menyesuaikan dengan warna coklat */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }


        ul {
            list-style-type: none;
            padding: 0;
        }

        li a {
            text-decoration: none;
            color: #ffffff;
        }

        input, select {
            font-size: 1rem;
            padding: 0.5rem;
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

    <nav class="bg-cream p-5 flex items-center justify-between relative">
        <button id="tombolSidebar">
            <img class="h-8 w-8" id="humb" src="img/1.png" alt="">
        </button>
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center" style="pointer-events: none;">RESERVATION</h1>
        <div class="flex items-center space-x-4">
            <div id="timeDisplay" class="bg-gray-200 px-3 py-1" style="margin-right: 1rem;"></div>
            <img id="userIcon" class="h-8 w-8 rounded-full" src="img/aaa.png" alt="User Icon">
        </div>
    </nav>

    <div class="flex flex-1">
        <div id="sidebar" class="bg-coklat text-white md:w-72 min-h-full p-5 hidden">
            <ul>
                <li class="flex items-center mb-8 mr-2 gap-2 mt-5">
                    <img class="h-5" src="img/dashboard.png" alt="Dashboard">
                    <a href="dasboard.html" class="font-audiowide text-xs md:text-xl">DASHBOARD</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/room.png" alt="Room">
                    <a href="room.html" class="font-audiowide text-xs md:text-xl">ROOM MANAGEMENT</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/guest.png" alt="Guest">
                    <a href="guest.html" class="font-audiowide text-xs md:text-xl">GUEST DATABASE</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/reserv.png" alt="Reservation">
                    <a href="reservasi.html" class="font-audiowide text-xs md:text-xl underline underline-offset-4">RESERVATION</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/in.png" alt="Check In">
                    <a href="checkin.html" class="font-audiowide text-xs md:text-xl">CHECK IN</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/out.png" alt="Check Out">
                    <a href="checkout.html" class="font-audiowide text-xs md:text-xl">CHECK OUT</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/payment.png" alt="">
                    <a href="prepayment.html" class="font-audiowide text-xs md:text-xl">PRE-PAYMENT</a>
                </li>
                <li class="flex items-center mb-8 mr-2 gap-2">
                    <img class="h-5" src="img/payment.png" alt="Payment">
                    <a href="payment.html" class="font-audiowide text-xs md:text-xl">PAYMENT</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-10">
            <p style="font-weight: bold;">Welcome to the reservation page of (hotel name)</p>
            <p>Please fill in the form below to order your room!</p>

            <form class="reservation-form" method="POST" action="">
            <div class="room-card">
                <label class="isi" for="nama_tamu">Guest Name *</label>
                <input class="isi" type="text" id="nama_tamu" name="nama_tamu" placeholder="Enter your name" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="NIK">NIK *</label>
                <input class="isi" type="text" id="NIK" name="nik" placeholder="Enter your NIK" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="alamat">Address *</label>
                <input class="isi" type="text" id="alamat" name="alamat" placeholder="Enter your address" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="no_telp">Phone Number *</label>
                <input class="isi" type="text" id="no_telp" name="no_telp" placeholder="Enter your phone number" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="email">Email *</label>
                <input class="isi" type="email" id="email" name="email" placeholder="Enter your email" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="tanggal_checkin">Check-in Date *</label>
                <input class="isi" type="date" id="tanggal_checkin" name="tanggal_checkin" required style="border-bottom: 2px solid #000;">
            </div>
            <div class="room-card">
                <label class="isi" for="tanggal_checkout">Check-out Date *</label>
                <input class="isi" type="date" id="tanggal_checkout" name="tanggal_checkout" required style="border-bottom: 2px solid #000;">
            </div>
            <!-- Tipe Kamar -->
            <div class="room-card">
                <label class="isi" for="tipe_kamar">Room Type *</label>
                <select class="isi" id="tipe_kamar" name="tipe_kamar" required style="border-bottom: 2px solid #000;">
                    <option value="">Select Room Type</option> <!-- Pilihan default -->
                    <?php foreach ($tipe_kamar_unik as $tipe) { ?>
                        <option value="<?php echo $tipe; ?>" <?php echo ($tipe == $tipe_kamar) ? 'selected' : ''; ?>>
                            <?php echo ucfirst(str_replace('_', ' ', $tipe)); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Nomor Kamar -->
            <div class="room-card">
                <label class="isi" for="nomor_kamar">Room Number *</label>
                <select class="isi" id="nomor_kamar" name="nomor_kamar" required style="border-bottom: 2px solid #000;">
                    <?php
                    if (!empty($kamar_tersedia)) {
                        foreach ($kamar_tersedia as $kamar) {
                            echo "<option value='" . $kamar['nomor_kamar'] . "'>" . $kamar['nomor_kamar'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button class="btn-submit" type="submit">Place Reservation</button>
        </form>
        </div>
    </div>

    <script src="js/klik.js"></script>
    <script>
       document.getElementById('tipe_kamar').addEventListener('change', function() {
            let tipeKamar = this.value;

            fetch(`fetch_rooms.php?tipe_kamar=${tipeKamar}`)
                .then(response => response.json())
                .then(data => {
                    let nomorKamarSelect = document.getElementById('nomor_kamar');
                    nomorKamarSelect.innerHTML = ''; // Clear previous options

                    if (data.length === 0) {
                        let option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No rooms available for this type';
                        nomorKamarSelect.appendChild(option);
                    } else {
                        data.forEach(kamar => {
                            let option = document.createElement('option');
                            option.value = kamar.nomor_kamar;
                            option.textContent = kamar.nomor_kamar;
                            nomorKamarSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

</body>
</html>