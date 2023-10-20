<?php
require_once("../../Database/db.php");

if (isset($_POST['officer_id'])) {
    $officer_id = $_POST['officer_id'];

    try {
        $sql = "SELECT * FROM Officer WHERE officer_id = :officer_id ";
 
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':officer_id', $officer_id);
        $stmt->execute();
        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = array(
                'name' => $row['officer_name'],
                'picture' => $row['offer_pic'],
        
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