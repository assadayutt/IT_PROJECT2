<?php
require_once("../../Database/db.php");

try {
    $user_stu = $_POST['user_stu'];
    $user_name = $_POST['user_name'];
    $user_pass = $_POST['user_pass'];

    // Hash รหัสผ่านก่อนบันทึก

    // เตรียมคำสั่ง SQL สำหรับการตรวจสอบว่า user_stu ไม่ซ้ำกัน
    $checkSql = "SELECT COUNT(*) FROM User WHERE user_stu = :user_stu";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':user_stu', $user_stu);
    $checkStmt->execute();
    $rowCount = $checkStmt->fetchColumn();

    if ($rowCount == 0) {
        // หาก user_stu ไม่ซ้ำกันในฐานข้อมูล
        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในฐานข้อมูล
        $sql = "INSERT INTO User (user_stu, user_name, user_pass, user_pic, user_email, user_linetoken)
                VALUES (:user_stu, :user_name, :user_pass, '', '', '')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_stu', $user_stu);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_pass', $user_pass);

        $stmt->execute();

        echo "success";
    } else {
        // หาก user_stu ซ้ำกันในฐานข้อมูล
        echo "notsuccess";
    }
} catch(PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

$conn = null;
?>
