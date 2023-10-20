<?php
require_once("../../Database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];
    
    try {
        $stmt = $conn->prepare("SELECT User.user_linetoken, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_address
        FROM Area_repair 
        JOIN User ON Area_repair.user_id = User.user_id 
        WHERE Area_repair.area_id = :area_id");
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบว่าพบ Line Token หรือไม่
        if ($result && isset($result['user_linetoken'])) {
            $lineTokens = $result['user_linetoken'];
            $area_detail = $result['area_detail'];
            $area_problem = $result['area_problem'];
            $area_address = $result['area_address'];
            
            // สร้างข้อมูล JSON เพื่อส่งกลับไปให้ AJAX
            $responseData = [
                'lineTokens' => $lineTokens,
                'area_detail' => $area_detail,
                'area_problem' => $area_problem,
                'area_address' => $area_address
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
