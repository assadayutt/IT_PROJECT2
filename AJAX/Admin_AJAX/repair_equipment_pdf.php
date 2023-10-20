<?php
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('../../Database/db.php'); // เชื่อมต่อกับไฟล์ฐานข้อมูล

try {
    // คำสั่ง SQL สำหรับดึงข้อมูลจากตาราง User
    $sql = "SELECT * FROM User";

    // ประมวลผลคำสั่ง SQL
    $stmt = $conn->query($sql);

    // สร้างเอกสาร PDF
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // ตั้งค่าฟอนต์สำหรับภาษาไทย
    $pdf->SetFont('freeserif', '', 12);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // ตัวอย่างการใช้ข้อมูลจากฐานข้อมูล
        $stu = $row['user_stu'];
        $name = $row['user_name'];
        $email = $row['user_email'];

        // เพิ่มข้อมูลลงใน PDF
        $pdf->Cell(0, 10, "รหัสนักศึกษา: $stu", 0, 1);
        $pdf->Cell(0, 10, "ชื่อ: $name", 0, 1);
        $pdf->Cell(0, 10, "อีเมล: $email", 0, 1);
    }

    // ล้างข้อมูลใน output buffer
    ob_clean();

    // Output PDF content ไปยังเบราว์เซอร์หรือบันทึกลงไฟล์
    $pdf->Output('test_tcpdf.pdf', 'I');

} catch (PDOException $e) {
    echo 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
}
?>
