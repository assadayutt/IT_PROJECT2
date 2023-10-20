<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['user_id']) &&
        isset($_POST['user_stu']) &&
        isset($_POST['user_name']) &&
        isset($_POST['user_email']) &&
        isset($_POST['user_pass']) &&
        isset($_POST['user_linetoken'])
    ) {
        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];
        $user_email = $_POST['user_email'];
        $user_pass = $_POST['user_pass'];
        $user_linetoken = $_POST['user_linetoken'];
        $user_stu = $_POST['user_stu'];

        // ตรวจสอบว่ามีการอัพโหลดรูปภาพมาหรือไม่
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $profileImage = $_FILES['profileImage']['name'];
            $tempImage = $_FILES['profileImage']['tmp_name'];
            $uploadPath = "../../Images/User/" . $profileImage;

            try {
                if (move_uploaded_file($tempImage, $uploadPath)) {
                    $sql = "UPDATE User SET user_stu = :user_stu, user_name = :user_name, user_pass = :user_pass, user_pic = :pic, user_email = :user_email, user_linetoken = :user_linetoken WHERE user_id = :user_id";
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
            $sql = "UPDATE User SET user_stu = :user_stu, user_name = :user_name, user_pass = :user_pass, user_email = :user_email, user_linetoken = :user_linetoken WHERE user_id = :user_id";
        }
 
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_stu', $user_stu);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':user_email', $user_email);
            $stmt->bindParam(':user_pass', $user_pass);
            $stmt->bindParam(':user_linetoken', $user_linetoken);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

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
