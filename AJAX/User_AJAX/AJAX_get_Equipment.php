<?php
require_once("../../Database/db.php");


// รับค่ารหัสครุภัณฑ์ที่ส่งมาจาก AJAX
$equipmentCode = $_GET['equipmentCode'];

try {
    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลของครุภัณฑ์
    $sql = "SELECT * FROM Equipment WHERE equipment_number = :equipmentCode AND equipment_sale = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':equipmentCode', $equipmentCode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) { 
        // ดึงข้อมูลจากแถวแรก
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // สร้างอาร์เรย์ข้อมูลเพื่อส่งกลับเป็น JSON
        $response = array(
            'success' => true,
            'equipmentID' => $row['equipment_id'],
            'equipmentName' => $row['equipment_name'],
            'equipmentType' => $row['equipment_brand'],
            'equipmentColor' => $row['equipment_color'],
            'equipmentAddress' => $row['equipment_address']
        );
    } else {
        $response = array('success' => false);
    }

    // ส่งกลับเป็น JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    $response = array('success' => false, 'error' => $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode($response);
}

$conn = null;
?>
