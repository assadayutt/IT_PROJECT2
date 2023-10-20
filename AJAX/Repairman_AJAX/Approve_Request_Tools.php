<?php
require_once("../../Database/db.php");

if (isset($_POST['detail'])) {
    try {
        // รับค่าจาก AJAX
        $details = $_POST['detail'];
        $repairman_id = $_POST['repairman_id'];

        // สร้างวันที่ปัจจุบัน
        $current_date = date("Y-m-d");

        $sql = "INSERT INTO `Approve_Request_Tools`( `details`, `repairman_id`, `officer_id`, `date`, `date_approve`, `1st_approver`, `1st_position`, `2nd_approver`, `2nd_position`, `status`, `dean_id`)
         VALUES (:details,:repairman_id,1,:current_date,' ',' ',' ',' ',' ',8,3);";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':repairman_id', $repairman_id);
        $stmt->bindParam(':current_date', $current_date);

        // ประมวลผลคำสั่ง SQL
        $stmt->execute();

        // คืนค่าสำเร็จถ้าไม่มีข้อผิดพลาด
        echo json_encode(array("success" => true, "message" => "บันทึกข้อมูลเรียบร้อยแล้ว"));
    } catch (PDOException $e) {
        echo json_encode(array("success" => false, "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $e->getMessage()));
    }
}
?>