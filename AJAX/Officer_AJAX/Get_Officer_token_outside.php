<?php
require_once("../../Database/db.php");

// ตรวจสอบการรับค่า POST จาก Ajax
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $approve_o_id  = $_POST['approve_o_id'];

    // สร้างคำสั่ง SQL เพื่อค้นหา user_linetoken จากฐานข้อมูล
    $stmt = $conn->prepare("SELECT Officer.Line_Token , Approve_Outside_repairman.date_approve
    FROM Approve_Outside_repairman
    JOIN Officer ON Approve_Outside_repairman.officer_id = Officer.officer_id
    WHERE Approve_Outside_repairman.approve_o_id = :approve_o_id");
    $stmt->bindParam(':approve_o_id', $approve_o_id , PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
     // ตรวจสอบว่าพบ Line Token หรือไม่
     if ($result && isset($result['Line_Token'])) {
        $lineTokens = $result['Line_Token'];
        $date_approve = $result['date_approve'];
     
        // สร้างข้อมูล JSON เพื่อส่งกลับไปให้ AJAX
        $responseData = [
            'lineTokens' => $lineTokens,
            'date_approve' => $date_approve
        ];
        
        header('Content-Type: application/json');
        echo json_encode($responseData);
    }  else {
        // ถ้าไม่พบข้อมูลในฐานข้อมูล
        echo json_encode(array("lineTokens" => array()));
    }
    
    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn = null;
} else {
    // ถ้าไม่ได้รับค่า POST จาก Ajax ให้ส่ง JSON กลับแจ้งเตือนข้อผิดพลาด
    echo json_encode(array("error" => "ไม่พบค่า repairman_id ในคำร้องขอ"));
}
?>