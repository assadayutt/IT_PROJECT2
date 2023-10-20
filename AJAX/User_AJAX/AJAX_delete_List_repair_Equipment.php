<?php
require_once("../../Database/db.php");


if (isset($_POST['repair_id'])) {
    $repair_id = $_POST['repair_id'];

    // ดึงข้อมูลรูปภาพก่อนลบ
    $sql_select = "SELECT repair_imagesbefor FROM Equipment_repair WHERE repair_id = $repair_id";
    $result_select = $conn->query($sql_select);
    $row_select = $result_select->fetch(PDO::FETCH_ASSOC);
    $imageToDelete = $row_select['repair_imagesbefor'];

    // ลบข้อมูลจากฐานข้อมูล
    $sql = "DELETE FROM Equipment_repair WHERE repair_id = $repair_id";
    $result = $conn->query($sql);

    if ($result) {
        // ลบรูปภาพจากโฟลเดอร์ (ถ้ามี)
        if (!empty($imageToDelete)) {
            $imagePath = "/Images/Repair_equipment/" . $imageToDelete;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // ส่งข้อความกลับเป็น JSON ในกรณีที่การลบสำเร็จ
        echo json_encode(array("status" => "success", "message" => "ลบข้อมูลและรูปภาพสำเร็จ"));
    } else {
        // ส่งข้อความกลับเป็น JSON ในกรณีที่เกิดข้อผิดพลาดในการลบข้อมูล
        echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
    }
} else {
    // ส่งข้อความกลับเป็น JSON ในกรณีที่ไม่มีการส่งค่า ID ของข้อมูลมา
    echo json_encode(array("status" => "error", "message" => "ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ"));
}
?>
