<?php
require_once('../../vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('../../Database/db.php'); 


if (isset($_GET['approve_id'])) {
    $approve_id = $_GET['approve_id'];
    try {
       
        $sql = "SELECT Approve_Request_Tools.*, Repairman.Repairman_name
        FROM Approve_Request_Tools
        JOIN Repairman ON Approve_Request_Tools.Repairman_id = Repairman.repairman_id
        WHERE Approve_Request_Tools.approve_id = $approve_id";

        $stmt = $conn->query($sql);

        if ($stmt->rowCount() > 0) {
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

            $pdf->SetFont('thsarabun', 16);
            $details = $firstRow['details'];
            $pdf->MultiCell(0, 20, "รายละเอียด: $details", 0, 'L');        
            $pdf->SetY(120);

            $pdf->SetFont('thsarabun', 16);

            $pdf->Cell(150, 20, "จึงเรียนมาเพื่อโปรดทราบและดำเนินการซ่อม", 0, 1, 'C');

            $pdf->Ln();

             $pdf->SetFont('thsarabun', 16);
             $repairman_name = $firstRow['Repairman_name'];
             $pdf->Cell(170, 20, "$repairman_name", 0, 1, 'R');
             $pdf->Cell(175, 0, "ตำแหน่ง..................................", 0, 1, 'R');

            ob_clean();

            $pdf->Output('แบบคำขอเบิกอุปกรณ์การซ่อม.pdf', 'I');
        } else {
            echo 'ไม่พบข้อมูลสำหรับ approve_id ที่ระบุ';
        }
    } catch (PDOException $e) {
        echo 'เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: ' . $e->getMessage();
    }
} else {
    echo 'กรุณาระบุ approve_id ใน POST';
}
?>