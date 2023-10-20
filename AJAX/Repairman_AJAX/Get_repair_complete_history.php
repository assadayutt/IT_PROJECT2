<?php
require_once("../../Database/db.php");

if (isset($_POST['repair_id'])) {
    $repairId = $_POST['repair_id'];

    try {
     
        
        $sql = "SELECT Equipment_repair.*, Equipment_Assign_work.*,User.user_name 
        FROM Equipment_repair
        JOIN Equipment_Assign_work ON Equipment_repair.repair_id = Equipment_Assign_work.repair_id
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
                'repair_detail' => $row['repair_detail'],
                'repair_date' => $row['repair_date'],
                'repair_imagesbefor' => $row['repair_imagesbefor'],
                'image_after' => $row['image_after'],
                'user_name_repair' => $row['user_name'],
                'date_complete_area1' => $row['date_complete'],
                'date_comp_repair1' => $row['assign_datecomp'], 
                'message_repair1' => $row['message_work'],
                'Score' => $row['Score'], 


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