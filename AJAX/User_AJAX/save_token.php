<?php
require_once("../../Database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $token = $_POST['token'];

    $stmt = $conn->prepare("UPDATE User SET user_linetoken = :token WHERE user_id = :user_id");
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "error" => $stmt->errorInfo()));
    }

    $conn = null;
}
?>
