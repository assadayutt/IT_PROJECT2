<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // hostname ของ MySQL
$username = "root"; // username ของ MySQL
$password = "root"; // password ของ MySQL
$dbname = "IMS-Project"; // ชื่อฐานข้อมูลที่ต้องการเชื่อมต่อ

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = mysqli_connect($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
  //  echo "Connected successfully";
?>
