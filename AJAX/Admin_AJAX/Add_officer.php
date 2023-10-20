<?php
require_once("../../Database/db.php");
require_once 'PHPExcel/Classes/PHPExcel.php'; // ต้องแก้เป็นพาธที่ติดตั้งไลบรารี

try {
    // รับข้อมูลจาก AJAX
    $excelFilePath = $_FILES['excel_file']['tmp_name'];

    $inputFileType = PHPExcel_IOFactory::identify($excelFilePath);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($excelFilePath);

    $worksheet = $objPHPExcel->getActiveSheet();

    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในฐานข้อมูล
    $sql = "INSERT INTO Officer (officer_name, offer_pass, officer_Email, offer_pic, offer_admin, Line_Token)
            VALUES (:officer_name, :officer_pass, :officer_Email,'',1 ,'')";
    
    $stmt = $conn->prepare($sql);

    for ($row = 2; $row <= $highestRow; $row++) { // เริ่มที่แถวที่ 2 เพื่อข้ามหัวตาราง
        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

        // ตรวจสอบว่ามีข้อมูลในแถว
        if (!empty($rowData[0])) {
            $officer_name = $rowData[0][0];
            $officer_pass = $rowData[0][1];
            $officer_Email = $rowData[0][2];

            // Hash รหัสผ่านก่อนบันทึก
            $hashed_password = password_hash($officer_pass, PASSWORD_DEFAULT);

            // Bind พารามิเตอร์แต่ละตัว
            $stmt->bindParam(':officer_name', $officer_name);
            $stmt->bindParam(':officer_pass', $hashed_password);
            $stmt->bindParam(':officer_Email', $officer_Email);

            // ทำการ execute คำสั่ง SQL
            if ($stmt->execute()) {
                // ดำเนินการต่อในกรณีที่บันทึกสำเร็จ
            } else {
                // ดำเนินการต่อในกรณีที่เกิดข้อผิดพลาดในการบันทึก
            }

            $stmt->closeCursor();
        }
    }

    echo "บันทึกข้อมูลเรียบร้อยแล้ว";

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
} finally {
    $conn = null; // ปิดการเชื่อมต่อกับฐานข้อมูล
}
?>
