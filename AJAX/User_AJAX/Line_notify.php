<?php

require_once("../../Database/db.php");



$sql = "SELECT User.user_linetoken, Equipment_repair.status_id, Statuss.status_name
        FROM user
        JOIN Equipment_repair On Equipment_repair.status_id  = Statuss.status_id
        JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
        WHERE user.user_id  = $user_id";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("การเตรียมคำสั่งล้มเหลว: " . $mysqli->error);
}

// รับ session_id จากของคำขอ (สมมติว่าใช้ POST)
$session_id = $_POST['id'];

// ผูกค่า session_id กับคำสั่ง
$stmt->bind_param("s", $session_id);

// ส่งคำสั่ง
$stmt->execute();

// ผูกผลลัพธ์ในตัวแปร
$stmt->bind_result($token, $status, $image_url);

// ฟังก์ชันสำหรับส่งข้อมูลไปที่ Line Notify
function sendToLineNotify($message, $image_url, $line_notify_token, $line_api_url) {
    $image_path = "URL_TO_IMAGE_FOLDER/$image_url"; // เปลี่ยนเป็น URL ของรูปภาพ

    $data = array(
        'message' => $message,
        'imageThumbnail' => $image_path,
        'imageFullsize' => $image_path
    );

    $options = array(
        'http' => array(
            'header' => "Authorization: Bearer $line_notify_token\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'ignore_errors' => true
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($line_api_url, false, $context);

    // เช็คผลลัพธ์
    if ($result === false) {
        die("การส่งข้อมูล Line Notify ล้มเหลว");
    }
}

// สร้างข้อมูลสำหรับ Line Notify
$line_notify_token = "user_linetoken";
$line_api_url = "https://notify-api.line.me/api/notify";

while ($stmt->fetch()) {
    // ตรวจสอบสถานะและเรียกฟังก์ชันส่งข้อมูลไปที่ Line Notify ตามสถานะ
    if ($status === "รออ่ะไหล่") {
        $message = "สถานะ: $status (รออ่ะไหล่)";
        sendToLineNotify($message, $image_url, $line_notify_token, $line_api_url);
    } elseif ($status === "รอช่างภายนอก") {
        $message = "สถานะ: $status (รอช่างภายนอก)";
        sendToLineNotify($message, $image_url, $line_notify_token, $line_api_url);
    } elseif ($status === "ขยายวัน") {
        $message = "สถานะ: $status (ขยายวัน)";
        sendToLineNotify($message, $image_url, $line_notify_token, $line_api_url);
    }
}

// ปิดคำสั่งและเชื่อมต่อฐานข้อมูล
$stmt->close();
$mysqli->close();
?>