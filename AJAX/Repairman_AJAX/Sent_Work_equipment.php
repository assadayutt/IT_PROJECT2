<?php
require_once("../../Database/db.php");

$repair_id = isset($_POST['repair_id']) ? $_POST['repair_id'] : '';
$equipment_id = isset($_POST['equipment_id']) ? $_POST['equipment_id'] : '';

// Check if a file is uploaded
if (!empty($_FILES['image_after']['tmp_name'])) {
    $image_after = $_FILES['image_after'];

    $uploadDirectory = '../../Images/Send_Work_Equipment/';
    $uploadedFile = $uploadDirectory . basename($image_after['name']);

    if (move_uploaded_file($image_after['tmp_name'], $uploadedFile)) {
        try {
            // Update image_after field in Equipment_Assign_work table
            $sql = "UPDATE Equipment_Assign_work
            SET image_after = :uploadedFile
            WHERE repair_id = :repair_id";
             $stmt = $conn->prepare($sql);
             $stmt->bindParam(':repair_id', $repair_id);
             $stmt->bindParam(':uploadedFile', $uploadedFile); // Store file path in database
             $stmt->execute();

            // Update status_id in Equipment_repair table
            $updateSql = "UPDATE Equipment_repair SET status_id = 5 WHERE repair_id = :repair_id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':repair_id', $repair_id);
            $updateStmt->execute();

            $updatedate = "UPDATE Equipment_Assign_work SET date_complete = NOW() WHERE repair_id = :repair_id";
            $updatedateStmt = $conn->prepare($updatedate);
            $updatedateStmt->bindParam(':repair_id', $repair_id);
            $updatedateStmt->execute();

            $increaseCountSql = "UPDATE equipment SET equipment_count = equipment_count + 1 WHERE equipment_id = :equipment_id";
            $increaseCountStmt = $conn->prepare($increaseCountSql);
            $increaseCountStmt->bindParam(':equipment_id', $equipment_id);
            $increaseCountStmt->execute();
    

            $response = array(
                "status" => "success",
                "message" => "Data saved successfully!"
            );
        } catch (PDOException $e) {
            $response = array(
                "status" => "error",
                "message" => "Error: " . $e->getMessage()
            );
        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error: Unable to upload image."
        );
    }
} else {
    $response = array(
        "status" => "error",
        "message" => "Error: No image uploaded."
    );
}

// Close database connection
$conn = null;

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>