<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['repairman_name']) &&
        isset($_POST['repairman_email']) &&
        isset($_POST['repairman_linetoken']) &&
        isset($_POST['repairman_id']) 
      
    ) {
        $repairman_name = $_POST['repairman_name'];
        $repairman_email = $_POST['repairman_email'];
        $repairman_linetoken = $_POST['repairman_linetoken'];
        $repairman_id = $_POST['repairman_id'];
      

        // ตรวจสอบว่ามีการอัพโหลดรูปภาพมาหรือไม่
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $profileImage = $_FILES['profileImage']['name'];
            $tempImage = $_FILES['profileImage']['tmp_name'];
            $uploadPath = "../../Images/repairman/" . $profileImage;

            try {
                if (move_uploaded_file($tempImage, $uploadPath)) {
                    $sql = "UPDATE Repairman SET repairman_name = :repairman_name, repairman_email = :repairman_email, Line_Token = :repairman_linetoken, repairman_pic = :pic WHERE repairman_id = :repairman_id";
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
            $sql = "UPDATE Repairman SET repairman_name = :repairman_name, repairman_email = :repairman_email, Line_Token = :repairman_linetoken WHERE repairman_id = :repairman_id";
        }
 
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repairman_name', $repairman_name);
            $stmt->bindParam(':repairman_email', $repairman_email);
            $stmt->bindParam(':repairman_linetoken', $repairman_linetoken);
            $stmt->bindParam(':repairman_id', $repairman_id, PDO::PARAM_INT);

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
            $pdo = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
        }
    } else {
        echo json_encode(array('status' => 'missing_data'));
    }
} else {
    echo json_encode(array('status' => 'invalid_request'));
}
?>
