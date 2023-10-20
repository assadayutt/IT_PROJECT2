<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $typeId = $_POST['type_id'];

    try {
        // Execute a DELETE query to delete the equipment type
        $sql = "DELETE FROM Equipment_type WHERE type_id = :type_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':type_id', $typeId);
        $stmt->execute();

        echo "success"; // ส่งคำตอบกลับว่าลบสำเร็จ
    } catch (PDOException $e) {
        echo "delete_error";
    }
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>