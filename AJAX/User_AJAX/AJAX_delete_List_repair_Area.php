<?php
require_once("../../Database/db.php");

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    try {
        // ดึงข้อมูลรูปภาพจากฐานข้อมูลก่อนที่จะลบ
        $imageQuery = "SELECT area_imagesbefor FROM Area_repair WHERE area_id = :area_id";
        $imageStatement = $conn->prepare($imageQuery);
        $imageStatement->bindParam(":area_id", $area_id, PDO::PARAM_INT);
        $imageStatement->execute();
        $imageRow = $imageStatement->fetch(PDO::FETCH_ASSOC);

        // ลบข้อมูลที่เกี่ยวข้องในฐานข้อมูล
        $deleteQuery = "DELETE FROM Area_repair WHERE area_id = :area_id";
        $deleteStatement = $conn->prepare($deleteQuery);
        $deleteStatement->bindParam(":area_id", $area_id, PDO::PARAM_INT);
        $result = $deleteStatement->execute();

        if ($result) {
            // ส่งข้อความกลับเป็น JSON ในกรณีที่การลบสำเร็จ
            echo json_encode(array("status" => "success", "message" => "ลบข้อมูลสำเร็จ"));

            // ลบรูปภาพที่เกี่ยวข้องในโฟลเดอร์
            if ($imageRow && isset($imageRow['area_imagesbefor'])) {
                $imagePath = "../Images/Repair_Address/" . $imageRow['area_imagesbefor'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"));
        }
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error", "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "ไม่ได้ระบุ ID ของข้อมูลที่ต้องการลบ"));
}
?>
