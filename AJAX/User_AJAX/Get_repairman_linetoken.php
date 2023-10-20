<?php
require_once("../../Database/db.php");

$sql = "SELECT Line_Token FROM Repairman";

$stmt = $conn->prepare($sql);
$stmt->execute();

$lineTokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($lineTokens) {
    $response = ['lineTokens' => $lineTokens];

    header('Content-Type: application/json');

    echo json_encode($response);
} else {
    http_response_code(404); 
    echo json_encode(['error' => 'ไม่พบ Line Token หรือเกิดข้อผิดพลาด']);
}
?>
