<?php
$servername = ""; 
$username = "u530196580_Project_Ims"; 
$password = "0988980954@Mew"; 
$dbname = "u530196580_IMS_Project"; 

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
