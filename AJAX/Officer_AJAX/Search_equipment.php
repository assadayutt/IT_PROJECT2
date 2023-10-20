<?php
require_once("../../Database/db.php");

$searchText = $_GET['searchText'];

$sql = "SELECT * FROM Equipment WHERE equipment_number = '$searchText' OR equipment_name LIKE '$searchText'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$counter = 1; // Initialize the row counter

foreach ($results as $row) {
    echo "<div class='row'>";
    echo "<div class='card-body'>";
    echo "<table class='table table-hover table-product' style='width:100%'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th style='width: 0px;'></th>";
    echo "<th style='width: 80px;'>ลำดับ</th>";
    echo "<th style='width: 270px;'>ชื่อครุภัณฑ์</th>";
    echo "<th style='width: 170px;'>หมายเลขครุภัณฑ์</th>";
    echo "<th style='width: 100px;'>ยี่ห้อ</th>";
    echo "<th style='width: 200px;'>รุ่น</th>";
    echo "<th style='width: 150px;'>ราคาต่อหน่วย</th>";
    echo "<th style='width: 210px;'>ที่อยู่</th>";
    echo "<th style='width: 200px;'>รายละเอียด</th>";
    echo "<th></th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    echo "<tr>";
    echo "<td></td>";
    echo "<td>" . $counter . "</td>"; 
    echo "<td>" . $row["equipment_name"] . "</td>";
    echo "<td>" . $row["equipment_number"] . "</td>";
    echo "<td>" . $row["equipment_brand"] . "</td>";
    echo "<td>" . $row["equipment_model"] . "</td>";
    echo "<td>" . $row["equipment_price"] . "</td>";
    echo "<td>" . $row["equipment_address"] . "</td>";
    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px; width: 120px' onclick='equipment_detail(" . $row['equipment_id'] . ");'>เพิ่มเติม</a></td>";
    echo "<td><a class='button1' style='text-decoration: none; background-color: red; color: white; margin:7px; width: 70px' onclick='confirmDelete(" . $row['equipment_id'] . ");'>ลบ</a></td>";
    echo "<td></td>";
    echo "</tr>";

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";

    $counter++;
}

?>