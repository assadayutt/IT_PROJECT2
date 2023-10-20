<?php
require_once("../../Database/db.php");

// ตรวจสอบการรับค่า POST จาก Ajax
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $approve_o_id  = $_POST['approve_o_id'];

    // สร้างคำสั่ง SQL เพื่อค้นหา user_linetoken จากฐานข้อมูล
    $stmt = $conn->prepare("SELECT Officer.Line_Token 
    FROM Approve_Outside_repairman
    JOIN  Officer ON Approve_Outside_repairman.dean_id = officer.officer_id
    WHERE approve_o_id  = :approve_o_id ");
    $stmt->bindParam(':approve_o_id', $approve_o_id , PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $lineToken = $row['Line_Token'];
        // ส่งผลลัพธ์กลับเป็น JSON ที่มี Line Token เดียว
        echo json_encode(array("lineTokens" => array($lineToken)));
    } else {
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
