<?php
require_once("../../Database/db.php");

$equipment_id = isset($_POST['equipment_id']) ? $_POST['equipment_id'] : '';
$sale_equipment_detail = isset($_POST['sale_equipment_detail']) ? $_POST['sale_equipment_detail'] : '';
$equipment_number = isset($_POST['equipment_number']) ? $_POST['equipment_number'] : '';


try {
    $Sql = "INSERT INTO Sale_Equipment (equipment_id, equipment_number, detail, date_sale) 
    VALUES (:equipment_id, :equipment_number, :sale_equipment_detail, NOW())";
    $Sql = $conn->prepare($Sql);
    $Sql->bindParam(':equipment_id', $equipment_id);
    $Sql->bindParam(':equipment_number', $equipment_number);
    $Sql->bindParam(':sale_equipment_detail', $sale_equipment_detail);
    
    if ($Sql->execute()) {
        // อัปเดตสถานะ การแทงจำหน่ายให้เป็น 1
        $updateStatusSql = "UPDATE Equipment SET equipment_sale = 1 WHERE equipment_id = :equipment_id";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bindParam(':equipment_id', $equipment_id);
         
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