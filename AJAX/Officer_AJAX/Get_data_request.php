<?php
require_once("../../Database/db.php");

if (isset($_POST['approve_id'])) {
    $approve_id  = $_POST['approve_id'];


    try { 
        $sql = "SELECT Approve_Request_Tools.status,Approve_Request_Tools.1st_approver,Approve_Request_Tools.1st_position,Approve_Request_Tools.2nd_approver,Approve_Request_Tools.2nd_position , Approve_Request_Tools.details, Approve_Request_Tools.date, Repairman.repairman_name 
        FROM Approve_Request_Tools
        JOIN Repairman ON Approve_Request_Tools.repairman_id =  Repairman.repairman_id
        WHERE approve_id = :approve_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':approve_id', $approve_id); 
        $stmt->execute();

        // Fetch the repair details
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'repairman_name' => $row['repairman_name'],
                'details' => $row['details'],
                'date' => $row['date'],
                'onest_approver' => $row['1st_approver'],
                'onest_position' => $row['1st_position'],
                'twond_approver' => $row['2nd_approver'],
                'twond_position' => $row['2nd_position'],    
                'status' => $row['status'],   
            );
            $response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'ไม่พบข้อมูล'
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