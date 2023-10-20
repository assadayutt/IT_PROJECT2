<?php
require_once("../../Database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repair_id'])) {
    $repair_id = $_POST['repair_id'];
    
    try {
        // ดึงข้อมูล lineTokens, session_id, และ completionDate จากฐานข้อมูล โดยใช้ค่า repair_id
        $stmt = $conn->prepare("SELECT Equipment_Assign_work.message_work,  Equipment_repair.equipment_number , User.user_linetoken, Repairman.repairman_name, Equipment_Assign_work.assign_datecomp
        FROM Equipment_repair 
        JOIN Equipment_Assign_work ON Equipment_repair.repair_id = Equipment_Assign_work.repair_id 
        JOIN User ON Equipment_repair.user_id = User.user_id 
        JOIN Repairman ON Equipment_repair.repairman_id = Repairman.repairman_id 
        WHERE Equipment_repair.repair_id = :repair_id");
        $stmt->bindParam(':repair_id', $repair_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบว่าพบ Line Token หรือไม่
        if ($result && isset($result['user_linetoken'])) {
            $lineTokens = $result['user_linetoken']; // ให้ข้อมูลเป็นอาร์เรย์ JSON 1 รายการ
            $repairman_name = $result['repairman_name'];
            $equipment_number = $result['equipment_number'];
            $message_work = $result['message_work'];
            $assign_datecomp = $result['assign_datecomp'];

            
            // สร้างข้อมูล JSON เพื่อส่งกลับไปให้ AJAX
            $responseData = [
                'lineTokens' => $lineTokens,
                'repairman_name' => $repairman_name,
                'message_work' => $message_work,
                'assign_datecomp' => $assign_datecomp,
                'equipment_number' => $equipment_number,
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
