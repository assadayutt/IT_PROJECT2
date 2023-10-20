<?php
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('../../Database/db.php'); 

if (isset($_GET['approve_o_id'])) {
    $approve_o_id = $_GET['approve_o_id'];
    try {
      
        $sql = "SELECT Approve_Outside_repairman.*, Repairman.Repairman_name
        FROM Approve_Outside_repairman
        JOIN Repairman ON Approve_Outside_repairman.Repairman_id = Repairman.repairman_id
        WHERE Approve_Outside_repairman.approve_o_id = $approve_o_id";

    
        $stmt = $conn->query($sql);

        // สร้างเอกสาร PDF
        $pdf = new TCPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetPrintHeader(false);

        $pdf->AddPage();
      

        $pdf->SetFont('thsarabun', '', 16);

        $image_url = '../../Images/pdf_logo.jpeg';

        $pdf->Image($image_url, 10, 10, 20, 20, 'JPG', '', 'T', false, 300);

        $pdf->SetFont('thsarabun', 'B', 25);

        $pdf->Cell(150, 30, "บันทึกข้อความ", 0, 1, 'C');

        $pdf->SetFont('thsarabun', '', 16);
        $pdf->Cell(0, 0, "ส่วนราชการ สำนักงานเลขนุการ กลุ่มงานบริหาร งานโสติทัศนูปกรณ์และอาคารสถานที่ ภายใน 5147", 0, 1);
        $pdf->Cell(0, 0, "ที่ .......................................", 0, 0);

        // เพิ่มวันที่จากฐานข้อมูล
        $firstRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $date = $firstRow['date'];
        $pdf->SetX(100); 
        $pdf->Cell(0, 0, "วันที่ $date", 0, 1);

        $pdf->MultiCell(0, 10, "เรื่อง ..................................", 0, 'L');
        $pdf->Ln();
        $pdf->MultiCell(0, 10, "เรียน คณบดีคณะวิทยาการสารสนเทศ", 0, 'L');

        // เพิ่มรายละเอียด details ด้านล่าง "เรียน คณบดีคณะวิทยาการสารสนเทศ"
        $pdf->SetFont('thsarabun', 16);
        $details = $firstRow['details'];
        $pdf->MultiCell(0, 20, "รายละเอียด: $details", 0, 'L');        
        $pdf->SetY(120);

        $pdf->SetFont('thsarabun', 16);

        $pdf->Cell(150, 20, "จึงเรียนมาเพื่อโปรดทราบและดำเนินการซ่อม", 0, 1, 'C');
        
        $pdf->Ln();

         // แสดงชื่อช่างและตำแหน่ง
         $pdf->SetFont('thsarabun', 16);
         $repairman_name = $firstRow['Repairman_name'];
         $pdf->Cell(170, 20, "$repairman_name", 0, 1, 'R');
         $pdf->Cell(175, 0, "ตำแหน่ง..................................", 0, 1, 'R');
 
        ob_clean();

        // Output PDF content ไปยังเบราวเซอร์หรือบันทึกลงไฟล์
        $pdf->Output('แบบคำขอเบิกอุปกรณ์การซ่อม.pdf', 'I');
    } catch (PDOException $e) {
        echo 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
    }
} else {
    echo 'กรุณาระบุ approve_o_id ใน POST';
    // หรือสามารถทำอะไรตามที่คุณต้องการเมื่อไม่มี approve_id ส่งมาได้ที่นี่
}
?>
