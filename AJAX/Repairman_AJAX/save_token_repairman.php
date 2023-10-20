<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $repairman_id = $_POST['repairman_id'];
    $token = $_POST['token'];

    $stmt = $conn->prepare("UPDATE Repairman SET Line_Token = :token WHERE repairman_id = :repairman_id");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':repairman_id', $repairman_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "error" => $stmt->errorInfo()));
    }

    $conn = null;
}
?>
