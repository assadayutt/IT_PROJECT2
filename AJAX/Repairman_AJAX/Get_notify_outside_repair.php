<?php
require_once("../../Database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repair_id'])) {
    $repair_id = $_POST['repair_id'];
    
    try {
        $stmt = $conn->prepare("SELECT User.user_linetoken, Equipment_repair.equipment_number, equipment.equipment_name
        FROM Equipment_repair 
        JOIN User ON Equipment_repair.user_id = User.user_id 
        JOIN Equipment ON Equipment_repair.equipment_id = Equipment.equipment_id 
        WHERE Equipment_repair.repair_id = :repair_id");
        $stmt->bindParam(':repair_id', $repair_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบว่าพบ Line Token หรือไม่
        if ($result && isset($result['user_linetoken'])) {
            $lineTokens = $result['user_linetoken'];
            $equipment_number = $result['equipment_number'];
            $equipment_name = $result['equipment_name'];
            
            // สร้างข้อมูล JSON เพื่อส่งกลับไปให้ AJAX
            $responseData = [
                'lineTokens' => $lineTokens,
                'equipment_number' => $equipment_number,
                'equipment_name' => $equipment_name
            ];
            
            header('Content-Type: application/json');
            echo json_encode($responseData);
        } else {
            // หากไม่พบ Line Token
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => "ไม่พบ Line Token หรือเกิดข้อผิดพลาดในการรับ Line Token"]);
        }
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => "เกิดข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล: " . $e->getMessage()]);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => "คำขอไม่ถูกต้องหรือไม่มีค่า repair_id ที่ส่งมา"]);
}
?>
