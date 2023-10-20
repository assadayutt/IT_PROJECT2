<?php
require_once("../../Database/db.php");

if (isset($_POST['equipment_id'])) {
    $equipment_id  = $_POST['equipment_id'];


    try { 
        $sql = "SELECT Equipment.*, Equipment_type.type_name
        FROM Equipment
        JOIN Equipment_type ON Equipment.type_id =  Equipment_type.type_id
        WHERE Equipment.equipment_id = :equipment_id"; 
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equipment_id', $equipment_id); 
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'equipment_name' => $row['equipment_name'],
                'equipment_number' => $row['equipment_number'],
                'equipment_brand' => $row['equipment_brand'],
                'equipment_model' => $row['equipment_model'],
                'equipment_color' => $row['equipment_color'],
                'equipment_dateadd' => $row['equipment_dateadd'],
                'equipment_detail' => $row['equipment_detail'],
                'equipment_serial' => $row['equipment_serial'],
                'equipment_status' => $row['equipment_status'],
                'equipment_price' => $row['equipment_price'],
                'equipment_exp' => $row['equipment_exp'],
                'equipment_owner' => $row['equipment_owner'],
                'equipment_count' => $row['equipment_count'],
                'equipment_address' => $row['equipment_address'],
                'equipment_sale' => $row['equipment_sale'],
                'equipment_type' => $row['type_name'],
               
            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบครุภัณฑ์'
            );
        }
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'การเชื่อมต่อฐานข้อมูลผิดพลาด: ' . $e->getMessage()
        );
    }

    // Close the database connection
    $pdo = null;

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>