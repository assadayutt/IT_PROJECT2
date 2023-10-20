<?php
require_once("../../Database/db.php");

try {
    $sql = "SELECT * FROM Equipment_type";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    header("Content-Type: application/json");
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle database error
    echo json_encode(array("error" => "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage()));
}
?>
