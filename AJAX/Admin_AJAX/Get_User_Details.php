<?php
require_once("../../Database/db.php");

if (isset($_POST['user_id'])) {
    $user_id  = $_POST['user_id'];


    try { 
        $sql = "SELECT * FROM User WHERE user_id = :user_id"; // Remove extra spaces
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id); // Remove extra space after ':equipment_id'
        $stmt->execute();

 
        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'user_stu' => $row['user_stu'],
                'user_name' => $row['user_name'],
                'user_pass' => $row['user_pass'],
                'user_pic' => $row['user_pic'],
                'user_email' => $row['user_email'],
                'user_linetoken' => $row['user_linetoken'],
               
            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลผู้ใช้งาน'
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