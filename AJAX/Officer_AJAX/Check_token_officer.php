<?php
require_once("../../Database/db.php");

if (isset($_POST['officer_id'])) {
    $officer_id = $_POST['officer_id'];

    // เตรียมและสร้างคำสั่ง SQL
    $query = "SELECT Line_Token FROM Officer WHERE officer_id = :officer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':officer_id', $officer_id, PDO::PARAM_INT);
    $stmt->execute();

    // ดึงข้อมูลออกมา
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($stmt->rowCount() > 0 && !empty($result['Line_Token'])) {
        // มีข้อมูลและ user_linetoken ไม่ว่างเปล่าหรือไม่ใช่ null
        echo json_encode(['status' => 'exists', 'stmt_result' => $result]);
    } else {
        // ไม่มีข้อมูลหรือ user_linetoken เป็นค่าว่างหรือ null
        echo json_encode(['status' => 'empty', 'stmt_result' => $result]);
    }
    

    // ปิดการเชื่อมต่อ PDO
    $conn = null;
} else {
    echo json_encode(['status' => 'error']);
}
?>
