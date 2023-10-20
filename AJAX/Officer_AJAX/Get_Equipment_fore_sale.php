<?php
require_once("../../Database/db.php");

try {
    // ตรวจสอบว่าครุภัณฑ์มีการซ่อมอยู่หรือไม่
    $sqlCheckRepair = "SELECT equipment_id FROM Equipment_repair";
    $stmtCheckRepair = $conn->prepare($sqlCheckRepair);
    $stmtCheckRepair->execute();
    $equipmentIdsUnderRepair = $stmtCheckRepair->fetchAll(PDO::FETCH_COLUMN);

    // ดึงข้อมูลครุภัณฑ์ที่ไม่มีการซ่อมอยู่
    $sql = "SELECT * FROM Equipment WHERE equipment_sale = 0 AND equipment_id NOT IN (" . implode(',', $equipmentIdsUnderRepair) . ")";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    header("Content-Type: application/json");
    echo json_encode($data);
} catch (PDOException $e) {
    // Handle database error
    echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage()));
}
?>
 