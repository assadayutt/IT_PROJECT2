<?php
require_once("../../Database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];
    
    try {
        // ดึงข้อมูล lineTokens, session_id, และ completionDate จากฐานข้อมูล โดยใช้ค่า repair_id
        $stmt = $conn->prepare("SELECT User.user_linetoken, Repairman.repairman_name, Area_Assign_work.assign_datecomp 
        FROM Area_repair 
        JOIN Area_Assign_work ON Area_repair.area_id = Area_Assign_work.area_id 
        JOIN User ON Area_repair.user_id = User.user_id 
        JOIN Repairman ON Area_repair.repairman_id = Repairman.repairman_id 
        WHERE Area_repair.area_id = :area_id");
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบว่าพบ Line Token หรือไม่
        if ($result && isset($result['user_linetoken'])) {
            $lineTokens = $result['user_linetoken']; // ให้ข้อมูลเป็นอาร์เรย์ JSON 1 รายการ
            $session_id = $result['repairman_name'];
            $completionDate = $result['assign_datecomp'];
            
            // สร้างข้อมูล JSON เพื่อส่งกลับไปให้ AJAX
            $responseData = [
                'lineTokens' => $lineTokens,
                'session_id' => $session_id,
                'completionDate' => $completionDate
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
