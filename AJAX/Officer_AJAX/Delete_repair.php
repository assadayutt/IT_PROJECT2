<?php
require_once("../../Database/db.php");

if (isset($_POST['repair_id'])) {
    $repair_id = $_POST['repair_id'];

    try {
        $conn->beginTransaction();

        // ตรวจสอบสถานะใน Equipment_repair
        $sqlSelectStatus = "SELECT status_id FROM Equipment_repair WHERE repair_id = $repair_id";
        $stmtSelectStatus = $conn->prepare($sqlSelectStatus);
        $stmtSelectStatus->execute();
        $rowSelectStatus = $stmtSelectStatus->fetch(PDO::FETCH_ASSOC);
        $status_id = $rowSelectStatus['status_id'];

        if ($status_id == 4) {
            // ลบข้อมูลใน Equipment_Assign_work ที่เกี่ยวข้องกับ repair_id ที่ต้องการลบ
            $sqlDeleteAssignWork = "DELETE FROM Equipment_Assign_work WHERE repair_id = $repair_id";
            $resultDeleteAssignWork = $conn->exec($sqlDeleteAssignWork);

            if ($resultDeleteAssignWork !== false) {
                // ดึงข้อมูลรูปภาพก่อนลบ
                $sqlSelect = "SELECT repair_imagesbefor FROM Equipment_repair WHERE repair_id = $repair_id";
                $stmtSelect = $conn->prepare($sqlSelect);
                $stmtSelect->execute();
                $rowSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);
                $imageToDelete = $rowSelect['repair_imagesbefor'];

                // ลบข้อมูลจากฐานข้อมูลใน Equipment_repair
                $sqlDeleteRepair = "DELETE FROM Equipment_repair WHERE repair_id = $repair_id";
                $resultDeleteRepair = $conn->exec($sqlDeleteRepair);

                if ($resultDeleteRepair !== false) {
                    // ลบรูปภาพจากโฟลเดอร์ (ถ้ามี)
                    if (!empty($imageToDelete)) {
                        $imagePath = "/Images/Repair_equipment/" . $imageToDelete;
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }

                    $conn->commit();
                    echo json_encode(array("status" => "success", "message" => "ลบข้อมูลและรูปภาพสำเร็จ"));
                } else {
                    $conn->rollBack();
                    echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูลใน Equipment_repair"));
                }
            } else {
                $conn->rollBack();
                echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูลใน Equipment_Assign_work"));
            }
        } else {
            $conn->rollBack();
            echo json_encode(array("status" => "error", "message" => "ไม่สามารถลบได้เนื่องจากช่างรับงานแล้ว"));
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการดำเนินการ: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ"));
}
?> 
