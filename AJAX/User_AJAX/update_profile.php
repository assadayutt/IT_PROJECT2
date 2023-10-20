<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['Name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['linetoken'])) {
        $user_id = $_POST['user_id'];
        $name = $_POST['Name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $linetoken = $_POST['linetoken'];

        // ตรวจสอบว่ามีการอัพโหลดรูปภาพมาหรือไม่
        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $profileImage = $_FILES['profileImage']['name'];
            $tempImage = $_FILES['profileImage']['tmp_name'];
            $uploadPath = "../../Images/User/" . $profileImage;

            try {
                if (move_uploaded_file($tempImage, $uploadPath)) {
                    $sql = "UPDATE User SET user_name= :name, user_pass= :password, user_pic= :pic, user_email= :email, user_linetoken= :linetoken WHERE user_id = :user_id";
                } else {
                    echo "upload_error";
                    exit;  // ออกจากการทำงานหากเกิดข้อผิดพลาดในการอัพโหลด
                }
            } catch (PDOException $e) {
                echo "PDOException: " . $e->getMessage();
                exit;  // ออกจากการทำงานหากเกิดข้อผิดพลาดในการจัดการกับฐานข้อมูล
            }
        } else {
            $sql = "UPDATE User SET user_name= :name, user_pass= :password, user_email= :email, user_linetoken= :linetoken WHERE user_id = :user_id";
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':linetoken', $linetoken);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if (isset($profileImage)) {
                $stmt->bindParam(':pic', $profileImage);
            }

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error";
            }

            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
        } finally {
            $pdo = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
        }
    } else {
        echo "missing_data";
    }
} else {
    echo "invalid_request";
}
?>
