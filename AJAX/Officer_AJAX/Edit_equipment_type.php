<?php
include("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typeId = $_POST["type_id"];
    $editedTypeName = $_POST["edited_type_name"];

    try {
        // สร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $sql = "UPDATE Equipment_type SET type_name = :edited_type_name WHERE type_id = :type_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":edited_type_name", $editedTypeName, PDO::PARAM_STR);
        $stmt->bindParam(":type_id", $typeId, PDO::PARAM_INT);

        // ประมวลผลคำสั่ง SQL
        if ($stmt->execute()) {
            $response = ["message" => "แก้ไขข้อมูลสำเร็จ"];
        } else {
            $response = ["errorMessage" => "เกิดข้อผิดพลาดในการแก้ไขข้อมูล"];
        }
    } catch (PDOException $e) {
        $response = ["errorMessage" => "เกิดข้อผิดพลาด: " . $e->getMessage()];
    }
    
    // ส่งข้อมูล JSON ใน PHP
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // ส่วนการดึงข้อมูลและการรูปแบบข้อมูลให้อยู่ในรูปแบบ JSON
    $data = array(); // สร้างอาร์เรย์เปล่า

    // อ่านข้อมูลจากฐานข้อมูลและเพิ่มลงในอาร์เรย์ $data ตามความเหมาะสม

    // รูปแบบข้อมูลให้อยู่ในรูปแบบ JSON
    $response = json_encode($data);

    // ส่งข้อมูล JSON ใน PHP
    header("Content-Type: application/json");
    echo $response;
}
?>
