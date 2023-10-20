<?php
require_once("../../Database/db.php");

$person1 = isset($_POST['person1']) ? $_POST['person1'] : '';
$person1_position = isset($_POST['person1_position']) ? $_POST['person1_position'] : '';
$person2 = isset($_POST['person2']) ? $_POST['person2'] : '';
$person2_position = isset($_POST['person2_position']) ? $_POST['person2_position'] : '';
$approve_id = isset($_POST['approve_id']) ? $_POST['approve_id'] : '';
$Officer_id= isset($_POST['Officer_id']) ? $_POST['Officer_id'] : '';

try {
    $updateSql = "UPDATE Approve_Request_Tools SET officer_id = :Officer_id, 1st_approver = :person1, 1st_position = :person1_position, 2nd_approver = :person2, 2nd_position = :person2_position, 
                  status = 11 WHERE approve_id = :approve_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':person1', $person1);
    $updateStmt->bindParam(':person1_position', $person1_position);
    $updateStmt->bindParam(':person2', $person2);
    $updateStmt->bindParam(':person2_position', $person2_position);
    $updateStmt->bindParam(':approve_id', $approve_id);
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