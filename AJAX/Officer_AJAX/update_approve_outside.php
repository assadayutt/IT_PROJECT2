<?php
require_once("../../Database/db.php");

$person1out = isset($_POST['person1out']) ? $_POST['person1out'] : '';
$person1_positionout = isset($_POST['person1_positionout']) ? $_POST['person1_positionout'] : '';
$person2out = isset($_POST['person2out']) ? $_POST['person2out'] : '';
$person2_positionout = isset($_POST['person2_positionout']) ? $_POST['person2_positionout'] : '';
$approve_o_id = isset($_POST['approve_o_id']) ? $_POST['approve_o_id'] : '';
$Officer_id = isset($_POST['Officer_id']) ? $_POST['Officer_id'] : '';

try {
    $updateSql = "UPDATE Approve_Outside_repairman SET officer_id = :Officer_id, 1st_approver = :person1, 1st_position = :person1_position, 2nd_approver = :person2, 2nd_position = :person2_position, status = 11 WHERE approve_o_id = :approve_o_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':person1', $person1out);
    $updateStmt->bindParam(':person1_position', $person1_positionout);
    $updateStmt->bindParam(':person2', $person2out);
    $updateStmt->bindParam(':person2_position', $person2_positionout);
    $updateStmt->bindParam(':approve_o_id', $approve_o_id);
    $updateStmt->bindParam(':Officer_id', $Officer_id);


    if ($updateStmt->execute()) {
        $response = array(
            "status" => "success",
            "message" => "Data saved successfully!"
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error: Unable to save data."
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