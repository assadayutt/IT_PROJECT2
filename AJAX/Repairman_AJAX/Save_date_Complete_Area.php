<?php
require_once("../../Database/db.php");

$completionDate2 = isset($_POST['completionDate2']) ? $_POST['completionDate2'] : '';
$area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';
$session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';


try {
    // ทำการบันทึกค่าลงฐานข้อมูล
    $sql = "INSERT INTO Area_Assign_work(area_id, assign_datecomp,date_complete, Score, message_work, image_after, repairman_id) 
    VALUES (:area_id, :completionDate2,:completionDate2,'', '', '', :repairman_id)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':area_id', $area_id);
    $stmt->bindParam(':completionDate2', $completionDate2);
    $stmt->bindParam(':repairman_id', $session_id);

    // ทำการ execute คำสั่ง SQL
    if ($stmt->execute()) {
        // ทำการ UPDATE ค่าในตาราง Equipment_repair เมื่อบันทึกข้อมูลสำเร็จ
        $updateSql = "UPDATE Area_repair SET status_id = 2, repairman_id = :session_id WHERE area_id = :area_id";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bindParam(':area_id', $area_id);
        $updateStmt->bindParam(':session_id', $session_id);
        
        $updateStmt->execute();

        $response = array(
            "status" => "success",
            "message" => "Data saved successfully!"
        );
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
