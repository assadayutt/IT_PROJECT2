<?php
require_once("../../Database/db.php");

try {
    $ratingText1 = isset($_POST['ratingText1']) ? $_POST['ratingText1'] : '';
    $area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';

    $updateSql = "UPDATE Area_Assign_work SET Score = :ratingText1 WHERE area_id = :area_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':ratingText1', $ratingText1);
    $updateStmt->bindParam(':area_id', $area_id);
    $updateStmt->execute(); 
 
    if ($updateStmt->rowCount() > 0) {
        // ทำการ UPDATE ค่าในตาราง Equipment_repair เมื่อบันทึกข้อมูลสำเร็จ
        $updateStatusSql = "UPDATE Area_repair SET status_id = 1 WHERE area_id = :area_id";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bindParam(':area_id', $area_id);
        
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
