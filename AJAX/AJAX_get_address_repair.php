<?php
require_once("../Database/db.php");

if (isset($_POST['area_id'])) {
    $areaId = $_POST['area_id'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=IMS-Project", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve repair details based on the repair_id
        $sql = "SELECT * FROM area_repair WHERE area_id = :area_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':area_id', $areaId);
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
