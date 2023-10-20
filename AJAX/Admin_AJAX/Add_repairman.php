<?php
require_once("../../Database/db.php");

try {
    
    // รับข้อมูลจาก AJAX
    $repairman_name = $_POST['repairman_name'];
    $repairman_pass = $_POST['repairman_pass'];
    $repairman_Email = $_POST['repairman_Email'];

    // Hash รหัสผ่านก่อนบันทึก
    $hashed_password = password_hash($repairman_pass, PASSWORD_DEFAULT);

    // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในฐานข้อมูล
    $sql = "INSERT INTO Repairman (repairman_name, repairman_pass, repairman_Email, repairman_pic, Line_Token)
            VALUES (:repairman_name, :repairman_pass, :repairman_Email, '', '')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':repairman_name', $repairman_name);
    $stmt->bindParam(':repairman_pass', $hashed_password);
    $stmt->bindParam(':repairman_Email', $repairman_Email);

    $stmt->execute();

    echo "success";
} catch(PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$conn = null;
?>
