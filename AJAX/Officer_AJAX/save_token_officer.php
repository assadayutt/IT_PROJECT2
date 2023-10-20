<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $officer_id = $_POST['officer_id'];
    $token = $_POST['token'];

    $stmt = $conn->prepare("UPDATE Officer SET Line_Token = :token WHERE officer_id = :officer_id");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':officer_id', $officer_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "error" => $stmt->errorInfo()));
    }

    $conn = null;
}
?>
