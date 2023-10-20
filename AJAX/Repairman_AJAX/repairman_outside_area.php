<?php
require_once("../../Database/db.php");


$area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';


try {
    $updateSql = "UPDATE Area_repair SET status_id  = 6  WHERE area_id = :area_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':area_id', $area_id);

    
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
