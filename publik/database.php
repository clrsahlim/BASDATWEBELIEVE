<?php
// database.php
$host = "aws-0-ap-southeast-1.pooler.supabase.com";    
$dbname = "postgres";  
$user = "postgres.xncbbdqhmkyrkncilipi";    
$pass = "basisdatahotel3"; 

try {
    // Create a new PDO instance
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>