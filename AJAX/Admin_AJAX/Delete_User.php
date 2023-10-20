<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];

    try {
        $sql = "DELETE FROM User WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        echo "success"; 
    } catch (PDOException $e) {
        echo "delete_error";
    }
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>