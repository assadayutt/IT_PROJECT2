<?php
require_once("../../Database/db.php");

$detail = $_POST['detail'];
$repairman_id = $_POST['repairman_id'];

$current_date = date("Y-m-d");

$target_dir = "../../Files/quotation/";
$random_filename = uniqid() . "_" . basename($_FILES["Image"]["name"]);
$target_file = $target_dir . $random_filename;

if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
    $file_name_only = $random_filename; 

    $sql = "INSERT INTO Approve_Outside_repairman (details, repairman_id, officer_id, File, date, date_approve, 1st_approver, 1st_position, 2nd_approver, 2nd_position, status, dean_id) 
    VALUES (:details, :repairman_id, 1, :file_name_only, :current_date,' ',' ',' ',' ',' ', 8,3)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':details', $detail, PDO::PARAM_STR);
    $stmt->bindParam(':repairman_id', $repairman_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->bindParam(':file_name_only', $file_name_only, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $response = array("success" => true);
    } else {
        $response = array("success" => false);
    }
} else {
    $response = array("success" => false);
}

header("Content-Type: application/json");
echo json_encode($response);
?>
