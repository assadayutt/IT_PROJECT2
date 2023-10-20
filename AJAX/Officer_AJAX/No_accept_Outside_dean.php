<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_o_id'])) {
       
        $approve_o_id = $_POST['approve_o_id'];
 
        try {   // 3 =  อนุมัติการซ่อม
            $sql = "UPDATE  Approve_Outside_repairman SET date_approve = NOW(), status = 10 WHERE approve_o_id = :approve_o_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':approve_o_id', $approve_o_id);
           
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