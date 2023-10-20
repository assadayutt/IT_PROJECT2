<?php
require_once("../../Database/db.php");

if (isset($_POST['approve_o_id'])) {
    $approve_o_id  = $_POST['approve_o_id'];


    try { 
        $sql = "SELECT  Approve_Outside_repairman.status,Approve_Outside_repairman.1st_approver,Approve_Outside_repairman.1st_position,Approve_Outside_repairman.2nd_approver,Approve_Outside_repairman.2nd_position, Approve_Outside_repairman.details, Approve_Outside_repairman.date, Approve_Outside_repairman.file, Repairman.repairman_name 
        FROM Approve_Outside_repairman
        JOIN Repairman ON Approve_Outside_repairman.repairman_id =  Repairman.repairman_id
        WHERE approve_o_id  = :approve_o_id ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':approve_o_id', $approve_o_id); 
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $data = array(
                'repairman_name1' => $row['repairman_name'],
                'details1' => $row['details'],
                'date1' => $row['date'],
                'file1' => $row['file'],
                'onest_approver1' => $row['1st_approver'],
                'onest_position2' => $row['1st_position'],
                'twond_approver3' => $row['2nd_approver'],
                'twond_position4' => $row['2nd_position'], 
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