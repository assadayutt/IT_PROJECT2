<!DOCTYPE html>
<html>

<head>
    <title>รายการซ่อม</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;

}

.pagination a,
.pagination span {
    padding: 5px 10px;
    margin: 0 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
}

.pagination a:hover {
    background-color: #f2f2f2;
}

.pagination span {
    background-color: #007bff;
    color: #fff;
}
</style>



<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php'; ?>

    <div class="container mt-4">
        <h2>รายการแจ้งซ่อมครุภัณฑ์</h2>
        <div class="table-responsive">
            <?php
            require_once("../../Database/db.php");

            $equipmentRepairSql = "SELECT User.user_name, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
                FROM Equipment_repair
                JOIN User ON Equipment_repair.user_id = User.user_id";
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
                echo "<table class='table table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>ลำดับ</th>";
                echo "<th>หมายเลขครุภัณฑ์</th>";
                echo "<th>รายละเอียด</th>";
                echo "<th>วันที่แจ้งซ่อม</th>";
                echo "<th>ผู้แจ้งซ่อม</th>";
                echo "<th>เพิ่มเติม</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $equipmentRepairNumRows = $equipmentRepairStart + 1;
                while ($equipmentRepairRow = $equipmentRepairResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $equipmentRepairNumRows . "</td>";
                    echo "<td>" . wordwrap($equipmentRepairRow["equipment_number"], 10, "<br>", true) . "</td>";

                    echo "<td>" . $equipmentRepairRow["repair_detail"] . "</td>";
                    echo "<td>" . $equipmentRepairRow["repair_date"] . "</td>";
                    echo "<td>" . $equipmentRepairRow["user_name"] . "</td>";
                    echo "<td><a class='btn btn-primary' href='javascript:void(0);'>รายละเอียด</a></td>";
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
        </div>
    </div>


    <br>
    <br>


    <div class="container mt-4">
        <h2>รายการแจ้งซ่อมพื้นที่</h2>
        <div class="table-responsive">
            <?php
    // สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมพื้นที่
    $areaRepairSql = "SELECT User.user_name, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
    FROM Area_repair
    JOIN User ON Area_repair.user_id = User.user_id";
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
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ลำดับ</th>";
        echo "<th>รายการแจ้งซ่อม</th>";
        echo "<th>ปัญหาที่พบ</th>";
        echo "<th>วันที่แจ้งซ่อม</th>";
        echo "<th>ผู้แจ้งซ่อม</th>";
        echo "<th>เพิ่มเติม</th>";
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
            echo "<td><a class='btn btn-primary' href='javascript:void(0);'>รายละเอียด</a></td>";

            

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
        </div>
    </div>

<br>
<br>
    <?php include '../../Footer/footer.php'; ?>
</body>

</html>