<?php
require_once("../../Database/db.php");

if (isset($_POST['equipment_id'])) {
    $equipment_id  = $_POST['equipment_id'];


    try { 
        $sql = "SELECT * FROM Equipment WHERE equipment_id = :equipment_id"; // Remove extra spaces
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':equipment_id', $equipment_id); // Remove extra space after ':equipment_id'
        $stmt->execute();

 
        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'equipment_number1' => $row['equipment_number'],
                'name_equipment' => $row['equipment_name'],
                'brand_equipment' => $row['equipment_brand'],
                'model_equipment' => $row['equipment_model'],
                'color_equipment' => $row['equipment_color'],
                'date_add_equipment' => $row['equipment_dateadd'],
                'Serial_number' => $row['equipment_serial'],
                'status_equipment' => $row['equipment_status'],
                'price_equipment' => $row['equipment_price'],
                'date_exp_equipment' => $row['equipment_exp'],
                'owner_equipment' => $row['equipment_owner'],
                'count_repair_equipment' => $row['equipment_count'],
                'address_equipment' => $row['equipment_address'],
                'details_equipment' => $row['equipment_detail'],
               
            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลการแจ้งซ่อม'
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