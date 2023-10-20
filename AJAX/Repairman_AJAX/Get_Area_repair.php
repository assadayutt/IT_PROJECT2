<?php
require_once("../../Database/db.php");

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];


    try {
        

        // Retrieve repair details based on the repair_id
        $sql = "SELECT Area_repair.area_id, Area_repair.area_problem, Area_repair.area_detail, Area_repair.area_date, Area_repair.area_address,  Area_repair.area_imagesbefor, User.user_name
        FROM Area_repair 
        JOIN User ON Area_repair.user_id = User.user_id
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
                'user_name' => $row['user_name'],
                'area_date' => $row['area_date'],
                'area_address' => $row['area_address'],
                'area_imagesbefor' => $row['area_imagesbefor']
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
