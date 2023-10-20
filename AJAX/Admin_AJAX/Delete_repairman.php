<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $repairman_id = $_POST['repairman_id'];

    try {
        $sql = "DELETE FROM Repairman WHERE repairman_id = :repairman_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':repairman_id', $repairman_id);
        $stmt->execute();

        echo "success"; 
    } catch (PDOException $e) {
        echo "delete_error";
    }
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>