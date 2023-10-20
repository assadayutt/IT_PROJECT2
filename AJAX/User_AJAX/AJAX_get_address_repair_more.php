<?php
require_once("../../Database/db.php");



if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    try {
     
        $sql = "SELECT
        Area_Assign_work.assign_datecomp,
        Area_Assign_work.message_work,
        Area_repair.area_id,
        Area_repair.area_detail,
        Area_repair.area_problem,
        Area_repair.area_date,
        Area_repair.area_address,
        Area_repair.area_imagesbefor,
        Repairman.repairman_name,
        Repairman.repairman_id
    FROM
        Area_repair
    JOIN
        Repairman ON Area_repair.repairman_id = Repairman.repairman_id
    LEFT JOIN
        Area_Assign_work ON Area_repair.area_id = Area_Assign_work.area_id
    WHERE
        Area_repair.area_id = :area_id;
    ";


        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'area_detail' => $row['area_detail'],
                'area_problem' => $row['area_problem'],
                'area_date' => $row['area_date'],
                'area_address' => $row['area_address'],
                'area_imagesbefor' => $row['area_imagesbefor'],
                'repairman_name_area1' => $row['repairman_name'],
                'date_comp_area' => $row['assign_datecomp'],
                'message_area' => $row['message_work'],



              
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
