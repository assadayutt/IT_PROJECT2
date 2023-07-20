<?php
require_once("../Database/db.php");

if (isset($_POST['repair_id'])) {
    $repairId = $_POST['repair_id'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=IMS-Project", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve repair details based on the repair_id
        $sql = "SELECT * FROM Equipment_repair WHERE repair_id = :repair_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':repair_id', $repairId);
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'equipment_number' => $row['equipment_number'],
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
