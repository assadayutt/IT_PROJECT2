<?php
require_once("../../Database/db.php");

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];

    try {
        

        $sql = "SELECT Area_repair.area_detail ,Area_repair.area_problem, Area_repair.area_address, Area_repair.area_date, Area_repair.area_imagesbefor , User.user_name
        FROM Area_repair 
        JOIN User ON Area_repair.user_id = User.user_id
        WHERE area_id = :area_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'area_detail' => $row['area_detail'],
                'area_problem' => $row['area_problem'],
                'area_address' => $row['area_address'],
                'area_date' => $row['area_date'],
                'area_imagesbefor' => $row['area_imagesbefor'],
                'user_name1' => $row['user_name'],
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

    $pdo = null;


    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
