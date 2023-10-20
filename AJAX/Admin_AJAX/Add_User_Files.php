<?php
require_once("../../Database/db.php");
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    // ตรวจสอบว่าได้รับไฟล์จาก AJAX หรือไม่
    if (isset($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
        $excelFilePath = $_FILES['file']['tmp_name'];

        // ใช้ IOFactory ในการอ่านไฟล์ Excel
        $spreadsheet = IOFactory::load($excelFilePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestRow(); 
        $highestColumn = $worksheet->getHighestColumn();

        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในฐานข้อมูล
        $sql = "INSERT INTO User (user_stu, user_name, user_pass, user_pic, user_email, user_linetoken)
                VALUES (:user_stu, :user_name, :user_pass, '', '', '')";
        
        $stmt = $conn->prepare($sql);

        for ($row = 2; $row <= $highestRow; $row++) { // เริ่มที่แถวที่ 2 เพื่อข้ามหัวตาราง
            $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

            // ตรวจสอบว่ามีข้อมูลในแถว
            if (!empty($rowData[0])) {
                $user_stu = $rowData[0][0];
                $user_name = $rowData[0][1];
                $user_pass = $rowData[0][2];

                // Bind พารามิเตอร์แต่ละตัว
                $stmt->bindParam(':user_stu', $user_stu);
                $stmt->bindParam(':user_name', $user_name);
                $stmt->bindParam(':user_pass', $user_pass);

                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "notsuccess";
                }
                $stmt->closeCursor();
            }
        }
    } else {
        echo "ไม่ได้รับไฟล์ Excel จาก AJAX";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
} finally {
    $conn = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
}
?>
