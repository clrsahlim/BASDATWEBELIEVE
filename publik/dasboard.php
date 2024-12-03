<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900" rel="stylesheet">
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

        .room-card1{
            display: flex;
            flex-direction: column;
            position: relative;
            border-radius: 30px;
            height: auto;
            overflow: hidden;
            margin-left: 80px;
            margin-right: 45px;
        }

        .room-card2{
            display: flex;
            flex-direction: column;
            position: relative;
            border-radius: 30px;
            height: auto;
            overflow: hidden;
            margin-left: 45px;
            margin-right: 80px;
        }

        .room-card1, .room-card2{
            margin-bottom: 40px;
            margin-top: 30px;
        }

        .card-header {
            background-color: #e30000;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .card-header .card-text {
            font-size: 25px;
            color: #000000;
        }

        .card-header img {
            width: 100%;
            height: 19rem; 
            object-fit: cover; 
            object-position: center; 
        }

        .card-body .room-type {
            color: #ffffff;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }

        .card-body .room-desc{
            color: #ffffff;
            margin-top: 8px;
            margin-bottom: 3px;
            width: 65%;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            height: 100%; 
            background-color: #6C4E31;
            padding: 15px 20px;
        }

        .card-body .bottom{
            display: flex;
            flex-direction: row;
            justify-content: space-between;

        }

        .card-body .view-more{
            color: #ffffff;
            width: 20%;
            margin-top: 25px;
            text-align: right;
            align-items: center;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .card-body .view-more:hover{
            color: #FFDBB5;
        }

        #overlay {
            overflow-y: auto;
            backdrop-filter: blur(3px);
        }

        body.no-scroll {
            overflow: hidden;
        }

        .overlay-card-header {
            background-color: #6C4E31; 
            color: #ffffff; 
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .overlay-description img{
            width: 100%;
            height: 28rem; 
            object-fit: cover; 
            object-position: center; 
        }

        .overlay-card-header h2{
            font-weight: 500;
        }

        #closeOverlay {
            z-index: 60; 
        }

        .overlay-card-description{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 50px;
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
        <h1 class="text-3xl font-audiowide absolute inset-0 flex justify-center items-center pointer-events-none">DASHBOARD</h1>
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
    <main class="flex-1 lg:p-10">
                <div class="grid gap-1 grid-cols-2">
                    <div class="room-card1">
                        <div class="card-header">
                            <img src="img/executiveking.webp">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Executive King</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Guest room, 1 King, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Executive King">View More</p>
                            </div>
                        </div>
                    </div>

                    <div class="room-card2">
                        <div class="card-header">
                            <img src="img/executivetwin.webp">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Executive Twin</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Guest room, 2 Twin, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Executive Twin">View More</p>
                            </div>
                        </div>
                    </div>

                    <div class="room-card1">
                        <div class="card-header">
                            <img src="img/exedeluxeking.webp">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Executive Deluxe King</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Larger Guest room, <br>1 King, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Executive Deluxe King">View More</p>
                            </div>
                        </div>
                    </div>

                    <div class="room-card2">
                        <div class="card-header">
                            <img src="img/juniorsuite.avif">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Junior Suite</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Junior Suite, 1 King, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Junior Suite">View More</p>
                            </div>
                        </div>
                    </div>

                    <div class="room-card1">
                        <div class="card-header">
                            <img src="img/ambassadorsuite.avif">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Ambassador Suite</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Larger Suite, 1 King, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Ambassador Suite">View More</p>
                            </div>
                        </div>
                    </div>

                    <div class="room-card2">
                        <div class="card-header">
                            <img src="img/presidentialsuite.webp">
                        </div>
                        <div class="card-body">
                            <div class="top">
                                <p class="room-type text-xl font-semibold">Presidential Suite</p>
                            </div>
                            <div class="bottom">
                                <p class="room-desc text-sm">Executive lounge access, Presidential Suite, <br>1 King, Panoramic City view</p>
                                <p class="view-more text-gray-500" data-room="Presidential Suite">View More</p> 
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
</div>


            

        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-70 hidden flex justify-center items-center z-50">
            <div class="bg-white rounded-3xl w-3/4 lg:w-1/2 shadow-lg relative">
                <button id="closeOverlay" class="absolute top-5 right-5 text-white text-lg font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div id="overlayContent" class="text-gray-800">
                </div>
            </div>
        </div>

        <script src="js/klik.js"></script>
        <script>
            document.querySelectorAll('.view-more').forEach(button => {
                button.addEventListener('click', function () {
                    const room = this.getAttribute('data-room');
                    const overlayContent = document.getElementById('overlayContent');
                    
                    // Set content based on room type
                    switch (room) {
                        case 'Executive King':
                        overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Executive King</h2> <!-- Judul kamar -->      
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/executiveking.webp" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Guest room, 1 King, Panoramic City view</p>
                                        <p class="text-sm">Executive Room King, 1 King, 45sqm/484sqft, Living/sitting area, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">45sqm/484sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are available (for some rooms)<br>Living/sitting area<br>Windows, floor-to-ceiling</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>1 King<br>Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Sofa<br> Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Desk, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                        case 'Executive Twin':
                            overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Executive Twin</h2>
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/executivetwin.webp" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Guest room, 2 Twin, Panoramic City view</p>
                                        <p class="text-sm">Executive Room Twin, 2 Twin, 45sqm/484sqft, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">45sqm/484sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are available (for some rooms)<br>Windows, floor-to-ceiling</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>2 Twin <br> Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Sofa<br> Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Desk, writing/work, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                            
                        case 'Executive Deluxe King':
                            overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Executive Deluxe King</h2> <!-- Judul kamar -->      
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/exedeluxeking.webp" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Larger Guest room, 1 King, Panoramic City view</p>
                                        <p class="text-sm">Executive Deluxe Room King, 1 King, 65sqm/699sqft, Living/sitting area, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food: <br> Hors d'oeuvres<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">65sqm/699sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are available (for some rooms)<br>Living/sitting area<br>Windows, floor-to-ceiling</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>1 King<br>Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Chair<br> Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Desk, writing/work, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                        case 'Junior Suite':
                            overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Junior Suite</h2> <!-- Judul kamar -->      
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/juniorsuite.avif" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Junior Suite, 1 King, Panoramic City view</p>
                                        <p class="text-sm">Junior Suite King, 1 King, 90sqm/968sqft, Living/sitting area, Dining area, Separate living room, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food: <br> Hors d'oeuvres<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee <br> Complimentary pressing, 2 garments per stay</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">90sqm/968sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are not available<br>Living/sitting area<br>Dining area <br>Separate living room<br>Windows, floor-to-ceiling</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>1 King<br>Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Sofa<br>Chair<br> Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Table, seats 4 <br>Desk, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                        case 'Ambassador Suite':
                            overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Ambassador Suite</h2> <!-- Judul kamar -->      
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/ambassadorsuite.avif" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Larger Suite, 1 King, Panoramic City view</p>
                                        <p class="text-sm">Ambassador Suite King, 1 King, Bathrooms: 2, 125sqm/1345sqft, Living/sitting area, Dining area, Separate dining room, Separate living room, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food: <br> Hors d'oeuvres<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee <br> Complimentary pressing, 2 garments per stay</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">125sqm/1345sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are available (for some rooms)<br>Living/sitting area<br>Dining area <br>Separate dining room<br>Separate living room<br>Study<br>Windows, floor-to-ceiling<br> Separate kitchen: <br>Pantry area, refrigerator</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Bathrooms: 2<br>Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>1 King<br>Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Sofa<br>Chair<br>Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Table, seats 6<br>Desk, writing/work, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                        case 'Presidential Suite':
                            overlayContent.innerHTML = `
                                <div class="overlay-card-header sticky top-10">
                                    <h2 class="text-xl p-4 ml-3 text-left">Presidential Suite</h2> <!-- Judul kamar -->      
                                </div>
                                
                                <div class="overlay-description overflow-y-auto max-h-[80vh]">
                                    <img src="img/presidentialsuite.webp" class="w-full object-cover mb-4" alt="Executive King Room"> <!-- Gambar fit -->

                                    <div class="overlay-headdescription p-3 ml-6 mb-3">
                                        <p class="text-lg font-semibold mb-3">Executive lounge access, Presidential Suite, 1 King, Panoramic City view</p>
                                        <p class="text-sm">Presidential Suite King, 1 King, Bathrooms: 2, 200sqm/2152sqft, Living/sitting area, Dining area, Separate dining room, Separate living room, Wireless internet, complimentary, Coffee/tea maker</p>
                                    </div>
                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Special Benefits</h2>
                                            <p class="text-sm" style="line-height:2.5"> This room features Executive lounge access <br> Private access floor<br>Complimentary food: <br> Hors d'oeuvres<br>High-speed Wi-Fi<br>Complimentary cocktails<br>Complimentary non-alcoholic beverages<br>Business services, for a fee <br> Complimentary pressing, 2 garments per stay</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Room Features</h2>
                                            <p class="text-sm" style="line-height:2.5">200sqm/2152sqft<br>Air-conditioned<br>This room is non-smoking<br>Connecting rooms are available (for some rooms)<br>Living/sitting area<br>Dining area <br>Separate dining room<br>Separate living room<br>Study<br>Windows, floor-to-ceiling<br> Separate kitchen: <br>Pantry area, refrigerator</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Bath and Bathroom Features</h2>
                                            <p class="text-sm" style="line-height:2.5"> Bathrooms: 2<br>Separate bathtub and shower<br>Hair dryer<br>Robe<br>Slippers</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Beds and Bedding</h2>
                                            <p class="text-sm" style="line-height:2.5"> Maximum occupancy: 3<br>1 King<br>Rollaway beds permitted: 1<br>Cribs permitted: 1<br>Maximum cribs/rollaway beds permitted: 1<br>Duvet</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Furniture and Furnishings</h2>
                                            <p class="text-sm" style="line-height:2.5">Sofa<br>Chair<br>Alarm clock <br>Safe, in room<br>Dual voltage outlets<br>Table, seats 6<br>Desk, writing/work, electrical outlet<br>Iron and ironing board</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Food and Beverages</h2>
                                            <p class="text-sm" style="line-height:2.5"> Room service, 24-hour <br>Bottled water, complimentary <br> Coffee/tea maker<br> Minibar, for a fee</p>
                                        </div>
                                    </div>

                                    <hr style="height: 0.5px; width: 93%; background-color: #000; border: none; margin: 0 auto;">

                                    <div class="overlay-card-description p-3 ml-8 mt-3">
                                        <div class = "overlay-card-description-left mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Hospitality Services</h2>
                                            <p class="text-sm" style="line-height:2.5">Evening turndown service<br>Newspaper delivered to room, on request</p>
                                        </div>
                                        <div class = "overlay-card-description-right mb-3" style="width:50%">
                                            <h2 class="font-semibold mb-3">Entertainment</h2>
                                            <p class="text-sm" style="line-height:2.5"> Plug-in, high-tech room <br>Premium movie channels <br>Cable/satellite <br>International cable/satellite <br>CNN, ESPN, and HBO <br>Movies, pay-per-view</p>
                                        </div>
                                    </div>

                                    
                                </div>
                            `;
                            break;
                        default:
                            overlayContent.innerHTML = `<p>No details available for this room.</p>`;
                    }

                    document.getElementById('overlay').classList.remove('hidden');
                    document.body.classList.add('no-scroll'); // Tambahkan kelas untuk mencegah scroll pada body
                });
            });

            document.getElementById('closeOverlay').addEventListener('click', function () {
                document.getElementById('overlay').classList.add('hidden');
                document.body.classList.remove('no-scroll'); // Hapus kelas untuk mengembalikan scroll pada body
            });

        </script>
</body>
</html>