<?php
require_once("../../Database/db.php");

// ตรวจสอบว่ามีข้อมูล JSON ถูกส่งมาหรือไม่
$data = json_decode(file_get_contents("php://input"));

if ($data) {
    $equipment_id = $data->equipment_id;
    $equipmentName = $data->equipmentName;
    $equipmentNumber = $data->equipmentNumber;
    $equipmentBrand = $data->equipmentBrand;
    $equipmentModel = $data->equipmentModel;
    $equipmentColor = $data->equipmentColor;
    $equipmentOwner = $data->equipmentOwner;
    $equipmentDetail = $data->equipmentDetail;
    $equipmentSerial = $data->equipmentSerial;
    $equipmentStatus = $data->equipmentStatus;
    $equipmentPrice = $data->equipmentPrice;
    $equipmentDateAdd = $data->equipmentDateAdd;
    $equipmentExp = $data->equipmentExp;
    $equipmentAddress = $data->equipmentAddress;
    $equipmentType = $data->equipmentType;

    try {
        $sql = "UPDATE Equipment SET 
        equipment_name = :equipmentName,
        equipment_number = :equipmentNumber,
        equipment_brand = :equipmentBrand,
        equipment_model = :equipmentModel,
        equipment_color = :equipmentColor,
        equipment_dateadd = :equipmentDateAdd,
        equipment_detail = :equipmentDetail,
        equipment_serial = :equipmentSerial,
        equipment_status = :equipmentStatus,
        equipment_price = :equipmentPrice,
        equipment_exp = :equipmentExp,
        equipment_owner = :equipmentOwner,
        equipment_address = :equipmentAddress,
        type_id = :equipmentType
        WHERE equipment_id = :equipment_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equipment_id', $equipment_id);
        $stmt->bindParam(':equipmentName', $equipmentName);
        $stmt->bindParam(':equipmentNumber', $equipmentNumber);
        $stmt->bindParam(':equipmentBrand', $equipmentBrand);
        $stmt->bindParam(':equipmentModel', $equipmentModel);
        $stmt->bindParam(':equipmentColor', $equipmentColor);
        $stmt->bindParam(':equipmentOwner', $equipmentOwner);
        $stmt->bindParam(':equipmentDetail', $equipmentDetail);
        $stmt->bindParam(':equipmentSerial', $equipmentSerial);
        $stmt->bindParam(':equipmentStatus', $equipmentStatus);
        $stmt->bindParam(':equipmentPrice', $equipmentPrice);
        $stmt->bindParam(':equipmentDateAdd', $equipmentDateAdd);
        $stmt->bindParam(':equipmentExp', $equipmentExp);
        $stmt->bindParam(':equipmentAddress', $equipmentAddress);
        $stmt->bindParam(':equipmentType', $equipmentType);

        $stmt->execute();

        echo "success"; // ส่งคำตอบกลับไปยัง Ajax ว่าอัพเดทสำเร็จ
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage(); // ส่งคำตอบกลับไปยัง Ajax ว่าเกิดข้อผิดพลาด
    }
} else {
    echo "ไม่มีข้อมูล JSON ที่ส่งมา";
}
?>
