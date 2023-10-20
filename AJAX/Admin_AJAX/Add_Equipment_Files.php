<?php
require_once("../../Database/db.php");
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $excelFilePath = $_FILES['file']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($excelFilePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                $equipment_name = $rowData[0][0];
                $equipment_number = $rowData[0][1];
                $equipment_brand = $rowData[0][2];
                $equipment_model = $rowData[0][3];
                $equipment_color = $rowData[0][4];
                
                // แปลงวันที่จาก Excel เป็นรูปแบบที่ใช้งานใน PHP
                $excel_dateadd = $rowData[0][5];
                $unix_dateadd = ($excel_dateadd - 25569) * 86400;
                $equipment_dateadd = gmdate("Y-m-d", $unix_dateadd);

                $equipment_detail = $rowData[0][6];
                $equipment_serial = $rowData[0][7];
                $equipment_status = $rowData[0][8];
                $equipment_price = $rowData[0][9];

                // แปลงวันที่จาก Excel เป็นรูปแบบที่ใช้งานใน PHP
                $excel_exp = $rowData[0][10];
                $unix_exp = ($excel_exp - 25569) * 86400;
                $equipment_exp = gmdate("Y-m-d", $unix_exp);

                $equipment_owner = $rowData[0][11];
                $equipment_count = $rowData[0][12];
                $equipment_address = $rowData[0][13];
                $equipment_sale = $rowData[0][14];
                $type_id = $rowData[0][15];

                $sql = "INSERT INTO Equipment(equipment_name, equipment_number, equipment_brand, equipment_model, equipment_color, equipment_dateadd, equipment_detail, equipment_serial, equipment_status, equipment_price, equipment_exp, equipment_owner, equipment_count, equipment_address, equipment_sale, type_id) 
                    VALUES (:equipment_name, :equipment_number, :equipment_brand, :equipment_model, :equipment_color, :equipment_dateadd, :equipment_detail, :equipment_serial, :equipment_status, :equipment_price, :equipment_exp, :equipment_owner, :equipment_count, :equipment_address, :equipment_sale, :type_id)";

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':equipment_name', $equipment_name);
                $stmt->bindParam(':equipment_number', $equipment_number);
                $stmt->bindParam(':equipment_brand', $equipment_brand);
                $stmt->bindParam(':equipment_model', $equipment_model);
                $stmt->bindParam(':equipment_color', $equipment_color);
                $stmt->bindParam(':equipment_dateadd', $equipment_dateadd);
                $stmt->bindParam(':equipment_detail', $equipment_detail);
                $stmt->bindParam(':equipment_serial', $equipment_serial);
                $stmt->bindParam(':equipment_status', $equipment_status);
                $stmt->bindParam(':equipment_price', $equipment_price);
                $stmt->bindParam(':equipment_exp', $equipment_exp);
                $stmt->bindParam(':equipment_owner', $equipment_owner);
                $stmt->bindParam(':equipment_count', $equipment_count);
                $stmt->bindParam(':equipment_address', $equipment_address);
                $stmt->bindParam(':equipment_sale', $equipment_sale);
                $stmt->bindParam(':type_id', $type_id);

                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "SQL Error: " . $stmt->errorInfo()[2];
                }

                $stmt->closeCursor();
            }

        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    } else {
        echo "upload_error";
    }
} else {
    echo "invalid_request";
}

?>