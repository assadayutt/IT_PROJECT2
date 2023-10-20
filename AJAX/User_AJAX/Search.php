<?php
require_once("../../Database/db.php");

try {
    // รับค่า searchText จาก AJAX request
    $searchText = $_POST['searchText'];

    // สร้าง SQL query สำหรับค้นหาข้อมูลในตารางที่ต้องการ
    $sql = "SELECT Area_repair.area_id, User.user_name, Area_repair.status_id, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
    FROM Area_repair
    JOIN User ON Area_repair.user_id = User.user_id
    JOIN Statuss ON Area_repair.status_id = Statuss.status_id
    WHERE area_detail LIKE :searchText OR area_problem LIKE :searchText OR user_name LIKE :searchText

    UNION
    
    SELECT  Equipment_repair.repair_id, User.user_name, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
    FROM Equipment_repair
    JOIN User ON Equipment_repair.user_id = User.user_id
    JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
    WHERE equipment_number  LIKE :searchText OR user_name LIKE :searchText";

 

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':searchText', '%' . $searchText . '%', PDO::PARAM_STR);
    $stmt->execute();
    $searchResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn = null;

    // แปลงผลลัพธ์เป็น JSON และส่งกลับไปยัง AJAX request
    echo json_encode($searchResult);

} catch (PDOException $e) {
    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn = null;
    
    echo "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage();
}
?>