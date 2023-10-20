<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST['type']; 

    try {
        // ตรวจสอบว่าชนิดครุภัณฑ์มีอยู่ในฐานข้อมูลหรือไม่
        $checkSql = "SELECT COUNT(*) FROM Equipment_type WHERE type_name = :type";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bindParam(':type', $type);
        $stmtCheck->execute();

        $count = $stmtCheck->fetchColumn();

        if ($count > 0) {
            echo "error_type_exists"; // ส่งคำตอบว่าชนิดครุภัณฑ์นี้มีอยู่แล้ว
        } else {
            // เพิ่มชนิดครุภัณฑ์ใหม่
            $insertSql = "INSERT INTO Equipment_type (type_name) VALUES (:type)";
            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bindParam(':type', $type);
            $stmtInsert->execute();
            echo "success"; // ส่งคำตอบกลับไปยัง Ajax ว่าเพิ่มสำเร็จ
        }
    } catch (PDOException $e) {
        echo "error_database"; // ส่งคำตอบว่ามีข้อผิดพลาดในการเข้าถึงฐานข้อมูล
    }
} else {
    echo "error_no_data"; // ส่งคำตอบว่าไม่มีข้อมูลที่ส่งมา
}
?>
