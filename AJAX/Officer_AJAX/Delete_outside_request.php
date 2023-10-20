<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $approve_o_id = $_POST['approve_o_id'];

    try {
        // Execute a DELETE query to delete the equipment type
        $sql = "DELETE FROM Approve_Outside_repairman WHERE approve_o_id = :approve_o_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':approve_o_id', $approve_o_id);
        $stmt->execute();

        echo "success"; // ส่งคำตอบกลับว่าลบสำเร็จ
    } catch (PDOException $e) {
        echo "error";
    }
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>
