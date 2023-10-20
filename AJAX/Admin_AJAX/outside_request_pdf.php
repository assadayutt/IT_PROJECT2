<?php
require_once("../../Database/db.php");
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');

try {
    $sql = "SELECT * FROM User";
 
    // ประมวลผลคำสั่ง SQL
    $stmt = $conn->query($sql);

    // สร้างเอกสาร PDF
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // เริ่มเขียนเนื้อหา PDF
    $pdf->SetFont('helvetica', '', 12);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // ตัวอย่างการใช้ข้อมูลจากฐานข้อมูล
        $id = $row['user_stu'];
        $name = $row['user_name'];

        // เพิ่มข้อมูลลงใน PDF
        $pdf->Cell(0, 10, "ชื่อ: $id", 0, 1);
        $pdf->Cell(0, 10, "อีเมล: $name", 0, 1);
    }

    // ส่ง header และเนื้อหา PDF ไปยังเบราว์เซอร์
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="equipment_repair.pdf"');
    

    // Output PDF content ไปยังเบราว์เซอร์
    $pdf->Output('example_007.pdf', 'I');
    exit;

} catch (PDOException $e) {
    echo 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
}
?>