<?php
require_once("../../Database/db.php");

if (isset($_POST['repair_id'])) {
    $repairId = $_POST['repair_id'];


    try {

        // Retrieve repair details based on the repair_id
        $sql = "SELECT Equipment_repair.repair_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date, Equipment_repair.repair_imagesbefor, User.user_name
        FROM Equipment_repair 
        JOIN User ON Equipment_repair.user_id = User.user_id
        WHERE Equipment_repair.repair_id = :repair_id"; 
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':repair_id', $repairId);
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'equipment_number' => $row['equipment_number'],
                'user_name' => $row['user_name'],
                'repair_detail' => $row['repair_detail'],
                'repair_date' => $row['repair_date'],
                'repair_imagesbefor' => $row['repair_imagesbefor']
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
