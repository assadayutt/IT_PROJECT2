<?php
$dir = '../../Images/Index/'; // ระบุโฟลเดอร์ที่เก็บรูปภาพ
$imageList = glob($dir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE); // ดึงรายชื่อรูปภาพ

echo json_encode($imageList); // ส่งรายการรูปภาพเป็น JSON
?>
