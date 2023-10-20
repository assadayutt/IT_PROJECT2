<?php
require_once("../../Database/db.php");

$datenew = isset($_POST['date']) ? $_POST['date'] : '';
$area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';
$message_new = isset($_POST['message']) ? $_POST['message'] : '';

try {
    $updateSql = "UPDATE Area_Assign_work SET message_work = :message_new , assign_datecomp = :datenew WHERE area_id = :area_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':area_id', $area_id);
    $updateStmt->bindParam(':datenew', $datenew);
    $updateStmt->bindParam(':message_new', $message_new);
    
    if ($updateStmt->execute()) {
        // อัปเดตสถานะ Area_repair เป็น 2
        $updateStatusSql = "UPDATE Area_repair SET status_id = 2 WHERE area_id = :area_id";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bindParam(':area_id', $area_id);
        
        if ($updateStatusStmt->execute()) {
            $response = array(
                "status" => "success",
                "message" => "Data saved successfully!"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Error updating Area_repair status."
            );
        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error updating Area_Assign_work."
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
