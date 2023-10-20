<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['repairman_id']) && isset($_POST['Name']) && isset($_POST['email']) && isset($_POST['linetoken'])) {
        $repairman_id = $_POST['repairman_id'];
        $name = $_POST['Name'];
        $email = $_POST['email'];
        $linetoken = $_POST['linetoken'];

        // Hash รหัสผ่านก่อนบันทึก
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
        try {
            if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
                $profileImage = $_FILES['profileImage']['name'];
                $tempImage = $_FILES['profileImage']['tmp_name'];
                $uploadPath = "../../Images/repairman/" . $profileImage;

                if (move_uploaded_file($tempImage, $uploadPath)) {
                    $sql = "UPDATE Repairman SET repairman_name= :name, repairman_Email= :email, repairman_pic= :pic, Line_Token= :linetoken WHERE repairman_id = :repairman_id";
                } else {
                    echo "upload_error";
                    exit;  // ออกจากการทำงานหากเกิดข้อผิดพลาดในการอัพโหลด
                }
            } else {
                $sql = "UPDATE Repairman SET repairman_name= :name, repairman_Email= :email, Line_Token= :linetoken WHERE repairman_id = :repairman_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':linetoken', $linetoken);
            $stmt->bindParam(':repairman_id',  $repairman_id, PDO::PARAM_INT);

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
