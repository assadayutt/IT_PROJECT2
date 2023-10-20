<?php
require_once("../../Database/db.php");


$repair_id  = isset($_POST['repair_id']) ? $_POST['repair_id'] : '';


try {
    $updateSql = "UPDATE Equipment_repair SET status_id = 7 WHERE repair_id = :repair_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':repair_id', $repair_id); // ตรวจสอบให้แน่ใจว่าถูกต้อง
    

    
    if ($updateStmt->execute()) {
        $response = array(
            "status" => "success",
            "message" => "Data saved successfully!"
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error updating Equipment_repair status."
        );
    }
} catch (PDOException $e) {
    $response = array(
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    );
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$conn = null;

// ส่งค่ากลับเป็น JSON
header('Content-Type: application/json');
echo json_encode($response);
?>