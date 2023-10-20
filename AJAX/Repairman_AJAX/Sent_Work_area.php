<?php
require_once("../../Database/db.php");

$area_id = isset($_POST['area_id']) ? $_POST['area_id'] : '';

// Check if a file is uploaded
if (!empty($_FILES['image_after']['tmp_name'])) {
    $image_after = $_FILES['image_after'];

    $uploadDirectory = '../../Images/Send_Work_Area/';
    $uploadedFile = $uploadDirectory . basename($image_after['name']);
    $uploadedFileName = $image_after['name']; // เก็บชื่อไฟล์

    if (move_uploaded_file($image_after['tmp_name'], $uploadedFile)) {
        try {
            // Update image_after field in Equipment_Assign_work table with file name
            $sql = "UPDATE Area_Assign_work
            SET image_after = :uploadedFileName
            WHERE area_id = :area_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':uploadedFileName', $uploadedFileName); // Store file name in database
            $stmt->execute();

            // Update status_id in Equipment_repair table
            $updateSql = "UPDATE Area_repair SET status_id = 5 WHERE area_id = :area_id";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':area_id', $area_id);
            $updateStmt->execute();

            $updatedate = "UPDATE Area_Assign_work SET date_complete = NOW() WHERE area_id = :area_id";
            $updatedateStmt = $conn->prepare($updatedate);
            $updatedateStmt->bindParam(':area_id', $area_id);
            $updatedateStmt->execute();


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
