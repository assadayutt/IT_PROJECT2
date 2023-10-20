<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $approve_id = $_POST['approve_id'];

    try {
        $sql = "DELETE FROM Approve_Request_Tools WHERE approve_id = :approve_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':approve_id', $approve_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
    
} else {
    echo "ไม่มีข้อมูลที่ส่งมา";
}
?>