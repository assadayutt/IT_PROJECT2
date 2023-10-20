<?php
require_once("../../Database/db.php");

$completionDate = isset($_POST['completionDate']) ? $_POST['completionDate'] : '';
$repair_id = isset($_POST['repair_id']) ? $_POST['repair_id'] : '';
$session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';

try {
    $sql = "INSERT INTO Equipment_Assign_work(repair_id, assign_datecomp, date_complete, Score, message_work, image_after, repairman_id) 
    VALUES (:repair_id, :completionDate,:completionDate, '', '', '', :repairman_id)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':repair_id', $repair_id);
    $stmt->bindParam(':completionDate', $completionDate);
    $stmt->bindParam(':repairman_id', $session_id);

    if ($stmt->execute()) {
        $updateSql = "UPDATE Equipment_repair SET status_id = 2, repairman_id = :session_id WHERE repair_id = :repair_id";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':repair_id', $repair_id);
        $updateStmt->bindParam(':session_id', $session_id);
        
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
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error: Something went wrong while saving data."
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
