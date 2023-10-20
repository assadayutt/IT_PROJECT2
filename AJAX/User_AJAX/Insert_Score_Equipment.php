<?php
require_once("../../Database/db.php");

try {
    $ratingText = isset($_POST['ratingText']) ? $_POST['ratingText'] : '';
    $repair_id = isset($_POST['repair_id']) ? $_POST['repair_id'] : '';

    $updateSql = "UPDATE Equipment_Assign_work SET Score = :ratingText WHERE repair_id = :repair_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':ratingText', $ratingText);
    $updateStmt->bindParam(':repair_id', $repair_id);
    $updateStmt->execute();

    if ($updateStmt->rowCount() > 0) {
        // ทำการ UPDATE ค่าในตาราง Equipment_repair เมื่อบันทึกข้อมูลสำเร็จ
        $updateStatusSql = "UPDATE Equipment_repair SET status_id = 1 WHERE repair_id = :repair_id";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bindParam(':repair_id', $repair_id);
        
        $updateStatusStmt->execute();

        $response = array(
            "status" => "success",
            "message" => "Data saved successfully!"
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error: No rows were affected."
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
