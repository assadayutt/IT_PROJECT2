<?php
require_once("../../Database/db.php");


$query = "SELECT * FROM equipment_type";
$stmt = $conn->prepare($query);
$stmt->execute();
$equipmentTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ส่งข้อมูลเป็น JSON กลับไป
echo json_encode($equipmentTypes);
?>
