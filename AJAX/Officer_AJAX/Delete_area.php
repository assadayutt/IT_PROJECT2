<?php
require_once("../../Database/db.php");

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    try {
        $conn->beginTransaction();

        // ตรวจสอบสถานะใน Area_repair
        $sqlSelectStatus = "SELECT status_id FROM Area_repair WHERE area_id = :area_id";
        $stmtSelectStatus = $conn->prepare($sqlSelectStatus);
        $stmtSelectStatus->bindParam(":area_id", $area_id, PDO::PARAM_INT);
        $stmtSelectStatus->execute();
        $rowSelectStatus = $stmtSelectStatus->fetch(PDO::FETCH_ASSOC);
        $status_id = $rowSelectStatus['status_id'];

        if ($status_id == 4) {
            // ลบข้อมูลใน Area_Assign_work ที่เกี่ยวข้องกับ area_id ที่ต้องการลบ
            $sqlDeleteAssignWork = "DELETE FROM Area_Assign_work WHERE area_id = :area_id";
            $stmtDeleteAssignWork = $conn->prepare($sqlDeleteAssignWork);
            $stmtDeleteAssignWork->bindParam(":area_id", $area_id, PDO::PARAM_INT);
            $resultDeleteAssignWork = $stmtDeleteAssignWork->execute();

            if ($resultDeleteAssignWork !== false) {
                // ดึงข้อมูลรูปภาพจากฐานข้อมูลก่อนที่จะลบ
                $imageQuery = "SELECT area_imagesbefor FROM Area_repair WHERE area_id = :area_id";
                $imageStatement = $conn->prepare($imageQuery);
                $imageStatement->bindParam(":area_id", $area_id, PDO::PARAM_INT);
                $imageStatement->execute();
                $imageRow = $imageStatement->fetch(PDO::FETCH_ASSOC);

                // ลบข้อมูลในฐานข้อมูลใน Area_repair
                $deleteQuery = "DELETE FROM Area_repair WHERE area_id = :area_id";
                $deleteStatement = $conn->prepare($deleteQuery);
                $deleteStatement->bindParam(":area_id", $area_id, PDO::PARAM_INT);
                $resultDeleteAreaRepair = $deleteStatement->execute();

                if ($resultDeleteAreaRepair !== false) {
                    // ส่งข้อความกลับเป็น JSON ในกรณีที่การลบสำเร็จ
                    echo json_encode(array("status" => "success", "message" => "ลบข้อมูลสำเร็จ"));

                    // ลบรูปภาพที่เกี่ยวข้องในโฟลเดอร์
                    if ($imageRow && isset($imageRow['area_imagesbefor'])) {
                        $imagePath = "../Images/Repair_Address/" . $imageRow['area_imagesbefor'];
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }

                    $conn->commit();
                } else {
                    $conn->rollBack();
                    echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูลใน Area_repair"));
                }
            } else {
                $conn->rollBack();
                echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูลใน Area_Assign_work"));
            }
        } else {
            $conn->rollBack();
            echo json_encode(array("status" => "error", "message" => "ไม่สามารถลบได้เนื่องจากช่างรับงานแล้ว"));
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ"));
}
?>
