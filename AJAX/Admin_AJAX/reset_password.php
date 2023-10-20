<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password']) && isset($_POST['repairman_id'])) {
       
        $password = $_POST['password'];
        $repairman_id = $_POST['repairman_id'];

        // Hash รหัสผ่านก่อนบันทึก
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
        try {
            $sql = "UPDATE repairman SET repairman_pass = :password WHERE repairman_id = :repairman_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':repairman_id', $repairman_id);
            $stmt->bindParam(':password', $hashed_password);
           
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
