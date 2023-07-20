<!DOCTYPE html>
<html>

<head>
    <title>หน้าแรก</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'IBM Plex Sans Thai', sans-serif;
        background: #FFEBCD;
    }
    </style>
</head>

<body>
    <?php include '../Navbar/navbar.php'; ?>

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="../Images/Index/1.png" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="../Images/Index/2.png" alt="Second slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <br>
    <br>
    <center>
        <h3>รายการแจ้งซ่อมครุภัณฑ์</h3>
    </center>

    <?php
    require_once("../Database/db.php");

    // สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมครุภัณฑ์
    $equipmentRepairSql = "SELECT User.user_name, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
        FROM Equipment_repair
        JOIN User ON Equipment_repair.user_id = User.user_id
        JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id";
        
    $equipmentRepairResult = $conn->query($equipmentRepairSql);

    $equipmentRepairLimit = 10;
    $equipmentRepairTotalRows = $equipmentRepairResult->num_rows;
    $equipmentRepairTotalPages = ceil($equipmentRepairTotalRows / $equipmentRepairLimit);

    if (isset($_GET['equipment_page'])) {
        $equipmentRepairPage = $_GET['equipment_page'];
    } else {
        $equipmentRepairPage = 1;
    }

    $equipmentRepairStart = ($equipmentRepairPage - 1) * $equipmentRepairLimit;
    $equipmentRepairSql .= " LIMIT $equipmentRepairStart, $equipmentRepairLimit";

    $equipmentRepairResult = $conn->query($equipmentRepairSql);

    if ($equipmentRepairResult->num_rows > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>รายการแจ้งซ่อม</th>";
        echo "<th>รายละเอียด</th>";
        echo "<th>วันที่แจ้งซ่อม</th>";
        echo "<th>ผู้แจ้งซ่อม</th>";
        echo "<th>สถานะแจ้งซ่อม</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $equipmentRepairNumRows = $equipmentRepairStart + 1;
        while ($equipmentRepairRow = $equipmentRepairResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $equipmentRepairNumRows . "</td>";
            echo "<td>ครุภัณฑ์ : " . $equipmentRepairRow["equipment_number"] . "</td>";
            echo "<td>" . $equipmentRepairRow["repair_detail"] . "</td>";
            echo "<td>" . $equipmentRepairRow["repair_date"] . "</td>";
            echo "<td>" . $equipmentRepairRow["user_name"] . "</td>";
        
            $statusId = $equipmentRepairRow["status_id"];
            $statusColorClass = "";
            $statusText = "";
        
            switch ($statusId) {
                case 1:
                    $statusColorClass = "status-completed";
                    $statusText = "เสร็จสิ้น";
                    break;
                case 2:
                    $statusColorClass = "status-in-progress";
                    $statusText = "กำลังดำเนินการ";
                    break;
                case 3:
                    $statusColorClass = "status-awaiting-equipment";
                    $statusText = "รออะไหล่";
                    break;
                case 4:
                    $statusColorClass = "status-pending";
                    $statusText = "รอรับงาน";
                    break;
                default:
                    $statusColorClass = "";
                    $statusText = "";
                    break;
            }
        
            echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";
            echo "</tr>";
            $equipmentRepairNumRows++;
        }
        
        

        echo "</tbody>";
        echo "</table>";

        if ($equipmentRepairTotalPages > 1) {
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $equipmentRepairTotalPages; $i++) {
                if ($i == $equipmentRepairPage) {
                    echo "<span>$i</span>";
                } else {
                    echo "<a href='?equipment_page=$i'>$i</a>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "<p>ไม่พบข้อมูล</p>";
    }
    ?>

    <br>
    <br>

    <center>
        <h3>รายการแจ้งซ่อมพื้นที่</h3>
    </center>

    <?php
    // สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมพื้นที่
    $areaRepairSql = "SELECT User.user_name, Area_repair.status_id, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
        FROM Area_repair
        JOIN User ON Area_repair.user_id = User.user_id
        JOIN Statuss ON Area_repair.status_id = Statuss.status_id";
        

    $areaRepairResult = $conn->query($areaRepairSql);

    $areaRepairLimit = 10;
    $areaRepairTotalRows = $areaRepairResult->num_rows;
    $areaRepairTotalPages = ceil($areaRepairTotalRows / $areaRepairLimit);

    if (isset($_GET['area_page'])) {
        $areaRepairPage = $_GET['area_page'];
    } else {
        $areaRepairPage = 1;
    }

    $areaRepairStart = ($areaRepairPage - 1) * $areaRepairLimit;
    $areaRepairSql .= " LIMIT $areaRepairStart, $areaRepairLimit";

    $areaRepairResult = $conn->query($areaRepairSql);

    if ($areaRepairResult->num_rows > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>รายการแจ้งซ่อม</th>";
        echo "<th>ปัญหาที่พบ</th>";
        echo "<th>วันที่แจ้งซ่อม</th>";
        echo "<th>ผู้แจ้งซ่อม</th>";
        echo "<th>สถานะแจ้งซ่อม</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $areaRepairNumRows = $areaRepairStart + 1;
        while ($areaRepairRow = $areaRepairResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $areaRepairNumRows . "</td>";
            echo "<td>" . $areaRepairRow["area_detail"] . "</td>";
            echo "<td>" . $areaRepairRow["area_problem"] . "</td>";
            echo "<td>" . $areaRepairRow["area_date"] . "</td>";
            echo "<td>" . $areaRepairRow["user_name"] . "</td>";

            $statusId = $areaRepairRow["status_id"];
            $statusColorClass = "";
            $statusText = "";

            switch ($statusId) {
                case 1:
                    $statusColorClass = "status-completed";
                    $statusText = "เสร็จสิ้น";
                    break;
                case 2:
                    $statusColorClass = "status-in-progress";
                    $statusText = "กำลังดำเนินการ";
                    break;
                case 3:
                    $statusColorClass = "status-awaiting-equipment";
                    $statusText = "รอการซ่อม";
                    break;
                case 4:
                    $statusColorClass = "status-pending";
                    $statusText = "รอรับงาน";
                    break;
                default:
                    $statusColorClass = "";
                    $statusText = "";
                    break;
            }
        
            echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

            echo "</tr>";
            $areaRepairNumRows++;
        }

        echo "</tbody>";
        echo "</table>";

        if ($areaRepairTotalPages > 1) {
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $areaRepairTotalPages; $i++) {
                if ($i == $areaRepairPage) {
                    echo "<span>$i</span>";
                } else {
                    echo "<a href='?area_page=$i'>$i</a>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "<p>ไม่พบข้อมูล</p>";
    }

    $conn->close();
    ?>

    <br>
    <br>

    <?php include '../Footer/footer.php'; ?>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js">
    < /scrip> < /
    body >

        <
        /html>