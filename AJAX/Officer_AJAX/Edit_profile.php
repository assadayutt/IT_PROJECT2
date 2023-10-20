<?php
include("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $officer_id = $_POST["officer_id"];
    $officer_name = $_POST["nameText"];

    // เช็คว่าไฟล์รูปถูกอัปโหลดหรือไม่
    if (isset($_FILES["profileImage"]) && $_FILES["profileImage"]["error"] === UPLOAD_ERR_OK) {
        // ดึงข้อมูลเกี่ยวกับไฟล์รูปภาพ
        $fileTmpName = $_FILES["profileImage"]["tmp_name"];
        $fileName = $_FILES["profileImage"]["name"];
        
        // กำหนดโฟลเดอร์ปลายทางสำหรับบันทึกไฟล์
        $uploadDir = "../../Images/Officer/";
        $targetFile = $uploadDir . basename($fileName);
        
        // ย้ายไฟล์ไปยังโฟลเดอร์ปลายทาง
        if (move_uploaded_file($fileTmpName, $targetFile)) {
            // ไฟล์ถูกอัปโหลดสำเร็จ
            $offer_pic = $fileName;
        } else {
            // มีปัญหาในการอัปโหลดไฟล์
            $offer_pic = ""; // หรือสามารถกำหนดค่าเป็นรูปภาพเริ่มต้นอื่น ๆ ตามต้องการ
        }
    } else {
        // ไม่มีการอัปโหลดไฟล์รูปภาพใหม่ ใช้ค่าเดิม
        $offer_pic = ""; // หรือสามารถกำหนดค่าเป็นรูปภาพเริ่มต้นอื่น ๆ ตามต้องการ
    }

    try {
        $sql = "UPDATE Officer SET officer_name = :officer_name, offer_pic = :offer_pic  WHERE officer_id = :officer_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":officer_name", $officer_name, PDO::PARAM_STR);
        $stmt->bindParam(":offer_pic", $offer_pic, PDO::PARAM_STR);
        $stmt->bindParam(":officer_id", $officer_id, PDO::PARAM_INT);

        // ประมวลผลคำสั่ง SQL
        if ($stmt->execute()) {
            $response = ["message" => "แก้ไขข้อมูลสำเร็จ"];
        } else {
            $response = ["errorMessage" => "เกิดข้อผิดพลาดในการแก้ไขข้อมูล"];
        }
    } catch (PDOException $e) {
        $response = ["errorMessage" => "เกิดข้อผิดพลาด: " . $e->getMessage()];
    }
    
    // ส่งข้อมูล JSON ใน PHP
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>