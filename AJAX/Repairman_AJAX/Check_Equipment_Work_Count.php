<?php
require_once("../../Database/db.php");

$repairman_id = $_POST['repairman_id'];

try {

    $sql = "SELECT COUNT(*) AS work_count
            FROM Equipment_Assign_work AS raw
            JOIN Equipment_repair AS er ON raw.repair_id = er.repair_id
            WHERE raw.repairman_id = :repairman_id
            AND er.status_id IN (2, 3, 6, 7)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':repairman_id', $repairman_id);
    $stmt->execute();
    
    $work_count = $stmt->fetch(PDO::FETCH_ASSOC)['work_count'];
    
    if ($work_count > 0) {
        echo $work_count; // ส่งจำนวนงานที่ตรงเงื่อนไขกลับไปยัง AJAX
    } else {
        echo "0"; // ถ้าไม่มีงานที่ตรงเงื่อนไขให้ส่ง 0 กลับไปยัง AJAX
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

