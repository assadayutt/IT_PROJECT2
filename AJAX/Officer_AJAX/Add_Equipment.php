<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipment_name = $_POST['equipment_name'];
    $equipment_number = $_POST['equipment_number'];
    $equipment_brand = $_POST['equipment_brand'];
    $equipment_model = $_POST['equipment_model'];
    $equipment_color = $_POST['equipment_color'];
    $equipment_detail = $_POST['equipment_detail'];
    $equipment_serial = $_POST['equipment_serial'];
    $equipment_status = $_POST['equipment_status'];
    $equipment_price = $_POST['equipment_price'];
    $equipment_dateadd = $_POST['equipment_dateadd'];
    $equipment_exp = $_POST['equipment_exp'];
    $equipment_owner = $_POST['equipment_owner'];
    $equipment_address = $_POST['equipment_address'];
    $equipment_sale = $_POST['equipment_sale'];
    $type_id = $_POST['type_id'];

    try {
        $sql = "INSERT INTO Equipment(equipment_name, equipment_number, equipment_brand, equipment_model, equipment_color,equipment_dateadd ,equipment_detail, equipment_serial, equipment_status, equipment_price, equipment_exp, equipment_owner, equipment_count, equipment_address, equipment_sale, type_id) 
                VALUES (:equipment_name, :equipment_number, :equipment_brand, :equipment_model, :equipment_color,:equipment_dateadd, :equipment_detail, :equipment_serial, :equipment_status, :equipment_price, :equipment_exp, :equipment_owner, 0, :equipment_address, :equipment_sale, :type_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equipment_name', $equipment_name);
        $stmt->bindParam(':equipment_number', $equipment_number);
        $stmt->bindParam(':equipment_brand', $equipment_brand);
        $stmt->bindParam(':equipment_model', $equipment_model);
        $stmt->bindParam(':equipment_color', $equipment_color);
        $stmt->bindParam(':equipment_detail', $equipment_detail);
        $stmt->bindParam(':equipment_serial', $equipment_serial);
        $stmt->bindParam(':equipment_status', $equipment_status);
        $stmt->bindParam(':equipment_price', $equipment_price);
        $stmt->bindParam(':equipment_dateadd', $equipment_dateadd);
        $stmt->bindParam(':equipment_exp', $equipment_exp);
        $stmt->bindParam(':equipment_owner', $equipment_owner);
        $stmt->bindParam(':equipment_address', $equipment_address);
        $stmt->bindParam(':equipment_sale', $equipment_sale);
        $stmt->bindParam(':type_id', $type_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->closeCursor();
    } catch (PDOException $e) {
        echo "PDOException: " . $e->getMessage();
    } finally {
        $pdo = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
    }
} else {
    echo "invalid_request";
}
?>