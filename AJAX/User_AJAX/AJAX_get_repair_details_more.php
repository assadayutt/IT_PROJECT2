<?php
require_once("../../Database/db.php");


if (isset($_POST['repair_id'])) {
    $repairId = $_POST['repair_id'];

    try {
    
        
        $sql = "SELECT
        Equipment_Assign_work.assign_datecomp,
        Equipment_Assign_work.message_work,
        Equipment_repair.repair_id,
        Equipment_repair.equipment_number,
        Equipment_repair.repair_detail,
        Equipment_repair.repair_date,
        Equipment_repair.repair_imagesbefor,
        Equipment_repair.repairman_id,
        Repairman.repairman_name
    FROM
        Equipment_repair
    JOIN
        Repairman ON Equipment_repair.repairman_id = Repairman.repairman_id
    LEFT JOIN
        Equipment_Assign_work ON Equipment_repair.repair_id = Equipment_Assign_work.repair_id
    WHERE
        Equipment_repair.repair_id = :repair_id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':repair_id', $repairId);
        $stmt->execute();



        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'equipment_number' => $row['equipment_number'],
                'repair_detail' => $row['repair_detail'],
                'repair_date' => $row['repair_date'],
                'repair_imagesbefor' => $row['repair_imagesbefor'],
                'repairman_name' => $row['repairman_name'], 
                'date_comp_repair' => $row['assign_datecomp'], 
                'message_repair' => $row['message_work'], 
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