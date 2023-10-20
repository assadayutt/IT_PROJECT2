<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['officer_name']) &&
        isset($_POST['officer_email']) &&
        isset($_POST['officer_linetoken']) &&
        isset($_POST['officer_id']) 
    ) {
        $officer_name = $_POST['officer_name'];
        $officer_email = $_POST['officer_email'];
        $officer_linetoken = $_POST['officer_linetoken'];
        $officer_id = $_POST['officer_id'];

        // ตรวจสอบว่ามีการอัพโหลดรูปภาพมาหรือไม่
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $profileImage = $_FILES['profileImage']['name'];
            $tempImage = $_FILES['profileImage']['tmp_name'];
            $uploadPath = "../../Images/Officer/" . $profileImage;

            try {
                if (move_uploaded_file($tempImage, $uploadPath)) {
                    $sql = "UPDATE Officer SET 	officer_name = :officer_name, officer_email = :officer_email, offer_pic = :pic, Line_Token = :officer_linetoken WHERE officer_id = :officer_id";
                } else { 
                    echo json_encode(array('status' => 'upload_error'));
                    exit;  // ออกจากการทำงานหากเกิดข้อผิดพลาดในการอัพโหลด
                }
            } catch (PDOException $e) {
                echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
                exit;  // ออกจากการทำงานหากเกิดข้อผิดพลาดในการจัดการกับฐานข้อมูล
            }
        } else {
            // หากไม่มีการอัพโหลดรูปภาพใหม่ ให้ใช้ SQL ที่ไม่รวมพารามิเตอร์ :pic
            $sql = "UPDATE Officer SET officer_name = :officer_name, officer_email = :officer_email, Line_Token = :officer_linetoken WHERE officer_id = :officer_id";
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':officer_name', $officer_name);
            $stmt->bindParam(':officer_email', $officer_email);
            $stmt->bindParam(':officer_linetoken', $officer_linetoken);
            $stmt->bindParam(':officer_id', $officer_id, PDO::PARAM_INT);

            // ตรวจสอบว่ามีการอัพโหลดรูปภาพหรือไม่และให้เพิ่มพารามิเตอร์ :pic ตามต้องการ
            if (isset($profileImage)) {
                $stmt->bindParam(':pic', $profileImage);
            }

            if ($stmt->execute()) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'ไม่สามารถอัปเดตข้อมูลได้'));
            }

            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        } finally {
            $conn = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
        }
    } else {
        echo json_encode(array('status' => 'missing_data'));
    }
} else {
    echo json_encode(array('status' => 'invalid_request'));
}
?>
