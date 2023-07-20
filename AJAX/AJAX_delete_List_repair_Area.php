<?php
require_once("../Database/db.php");

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];


    $sql = "DELETE FROM Area_repair WHERE area_id  = $area_id";
    $result = $conn->query($sql);

    if ($result) {
        // ส่งข้อความกลับเป็น JSON ในกรณีที่การลบสำเร็จ
        echo json_encode(array("status" => "success", "message" => "ลบข้อมูลสำเร็จ"));
    } else {
        // ส่งข้อความกลับเป็น JSON ในกรณีที่เกิดข้อผิดพลาดในการลบข้อมูล
        echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
    }
} else {
    // ส่งข้อความกลับเป็น JSON ในกรณีที่ไม่มีการส่งค่า ID ของข้อมูลมา
    echo json_encode(array("status" => "error", "message" => "ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ"));
}
?>
