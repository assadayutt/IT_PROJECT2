<?php
require_once("../../Database/db.php");



if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    try {
     

        // Retrieve repair details based on the repair_id
        $sql = "SELECT Area_repair.*, Area_Assign_work.*, Repairman.repairman_name 
        FROM Area_repair
        JOIN Area_Assign_work ON Area_repair.area_id = Area_Assign_work.area_id
        JOIN Repairman ON Area_repair.repairman_id = Repairman.repairman_id
        WHERE Area_repair.area_id = :area_id";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'area_detail' => $row['area_detail'],
                'area_problem' => $row['area_problem'],
                'area_address' => $row['area_address'],
                'area_date' => $row['area_date'],
                'area_imagesbefor' => $row['area_imagesbefor'],
                'image_after' => $row['image_after'],
                'repairman_name_area' => $row['repairman_name'],
                'date_complete_area' => $row['date_complete'],
                'date_comp_area1' => $row['assign_datecomp'],
                'message_area1' => $row['message_work'],

            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลการแจ้งซ่อมพื้นที่'
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
