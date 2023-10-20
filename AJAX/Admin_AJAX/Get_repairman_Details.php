<?php
require_once("../../Database/db.php");

if (isset($_POST['repairman_id'])) {
    $repairman_id  = $_POST['repairman_id'];


    try { 
        $sql = "SELECT * FROM Repairman WHERE repairman_id = :repairman_id"; // Remove extra spaces
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':repairman_id', $repairman_id); // Remove extra space after ':equipment_id'
        $stmt->execute();

 
        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'repairman_name' => $row['repairman_name'],
                'repairman_Email' => $row['repairman_Email'],
                'repairman_pic' => $row['repairman_pic'],
                'Line_Token' => $row['Line_Token']
            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลช่าง'
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