<?php include '../Navbar/navbar.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>หน้าแรก</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


</head>
<script>
$(document).ready(function() {
    $("#searchButton").click(function() {
        var searchText = $("#searchInput").val();

        // ตรวจสอบค่า searchText ก่อนทำการค้นหา
        if (searchText === "") {
            Swal.fire({
                icon: 'error',
                title: 'โปรดกรอกข้อมูล',
                text: 'คุณต้องกรอกคำค้นหาก่อนที่จะค้นหา',
            });
            return; // หยุดการดำเนินการ
        }
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/User_AJAX/Search.php",
            data: {
                searchText: searchText
            },
            success: function(result) {
                // แปลง JSON ที่ได้รับมาเป็น JavaScript object
                var searchResults = JSON.parse(result);

                // แสดงผลลัพธ์ที่รับมา
                var searchResultsDiv = $("#searchResults");
                searchResultsDiv.empty(); // เคลียร์เนื้อหาที่มีอยู่ก่อนหน้า

                // วน loop เพื่อแสดงผลข้อมูลที่ค้นหาได้
                for (var i = 0; i < searchResults.length; i++) {
                    var resultItem = searchResults[i];

                    // สร้าง HTML สำหรับแสดงข้อมูล
                    var resultHTML = "<div class='search-result-item'>";
                    resultHTML += "<table>";
                    resultHTML +=
                        "<tr><th>แจ้งซ่อม หรือ รหัสครุภัณฑ์</th><th>รายละเอียด</th><th>วันที่แจ้งซ่อม</th><th>ผู้แจ้งซ่อม</th><th>สถานะ</th></tr>";
                    resultHTML += "<tr>";
                    resultHTML += "<td style='width: 20%;'>" + resultItem.area_detail +
                        "</td>";
                    resultHTML += "<td style='width: 20%;'>" + resultItem.area_problem +
                        "</td>";
                    resultHTML += "<td style='width: 20%;'>" + resultItem.area_date +
                        "</td>";
                    resultHTML += "<td style='width: 20%;'>" + resultItem.user_name +
                        "</td>";

                    var statusColorClass = "";
                    var statusText = "";
                    var statusId = resultItem.status_id;
                    switch (statusId) {
                        case 1:
                            statusColorClass = "status-completed";
                            statusText = "เสร็จสิ้น";
                            break;
                        case 2:
                            statusColorClass = "status-in-progress";
                            statusText = "กำลังดำเนินการ";
                            break;
                        case 3:
                            statusColorClass = "status-awaiting-equipment";
                            statusText = "รออะไหล่";
                            break;
                        case 4:
                            statusColorClass = "status-pending";
                            statusText = "รอรับงาน";
                            break;
                        case 5:
                            statusColorClass = "status-awaiting-equipment";
                            statusText = "รอให้คะแนน";
                            break;
                        case 6:
                            statusColorClass = "status-awaiting-equipment123";
                            statusText = "รอช่างภายนอก";
                            break;
                        case 7:
                            statusColorClass = "status-awaiting-equipment456";
                            statusText = "รออะไหล่";
                            break;
                        default:
                            statusColorClass = "";
                            statusText = "";
                            break;
                    }

                    resultHTML += "<td class='" + statusColorClass + "'>" + statusText +
                        "</td>";
                    resultHTML += "</tr>";
                    resultHTML += "</table>";
                    resultHTML += "</div>";

                    searchResultsDiv.append(resultHTML);
                }
            }
        });
    });
});
</script>



<body>

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" id="carousel-inner">
            <!-- JavaScript จะแทรกรูปภาพที่นี่ -->
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
    <script>
    // ตรวจสอบว่า .carousel-inner มีอยู่ใน DOM
    var carouselInner = document.querySelector('.carousel-inner');
    if (carouselInner) {
        // ดำเนินการกับ .carousel-inner ต่อได้ตามปกติ
        // ดึงรายการรูปภาพ, สร้าง carousel items, และแทรกรูปภาพ
        var imageFolder = 'https://imsproject.online/project/Images/Index/'; // ตั้งค่าโฟลเดอร์รูปภาพของคุณ

        fetch(imageFolder)
            .then(response => response.text())
            .then(html => {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');

                var imageList = Array.from(doc.querySelectorAll('a')).map(link => link.getAttribute('href'));

                var imageListFiltered = imageList.filter(item => item.endsWith('.png') || item.endsWith('.jpg') ||
                    item
                    .endsWith('.jpeg') || item.endsWith('.gif'));

                imageListFiltered.forEach(function(image, index) {
                    var carouselItem = document.createElement('div');
                    carouselItem.classList.add('carousel-item');
                    if (index === 0) {
                        carouselItem.classList.add('active');
                    }

                    var img = document.createElement('img');
                    img.src = imageFolder + image;
                    img.classList.add('d-block', 'w-100');

                    carouselItem.appendChild(img);
                    carouselInner.appendChild(carouselItem);
                });
            })
            .catch(error => console.error('เกิดข้อผิดพลาดในการดึงรายการรูปภาพ: ' + error));
    } else {
        console.error('.carousel-inner ไม่พบใน DOM'); // พิมพ์ข้อความข้อผิดพลาดในกรณีที่ไม่พบ .carousel-inner
    }
    </script>


    <div class="container mt-4">
        <div class="input-group mb-3 justify-content-end">

            <input type="text" class="form-control" id="searchInput"
                placeholder="กรอกรหัสครุภัณฑ์ , รายละเอียดการแจ้งซ่อมพื้นที่ หรือชื่อผู้แจ้ง">
            <div class="input-group-append">
                <button class="btn btn-primary" id="searchButton" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div id="searchResults">
            <!-- ส่วนที่แสดงผลการค้นหา -->
        </div>
    </div>




    <h3 style="margin: 30px">รายการแจ้งซ่อมครุภัณฑ์</h3>

    <?php
require_once("../Database/db.php");

try {
    // สร้างการเชื่อมต่อฐานข้อมูล MySQL โดยใช้ PDO
    
    // สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมครุภัณฑ์
    $equipmentRepairSql = "SELECT Equipment.equipment_sale,  Equipment_repair.repair_id, User.user_name, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
    FROM Equipment_repair
    JOIN Equipment ON Equipment_repair.equipment_id = Equipment.equipment_id
    JOIN User ON Equipment_repair.user_id = User.user_id
    JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
    ORDER BY Equipment_repair.repair_id DESC
    ";


    $equipmentRepairStmt = $conn->query($equipmentRepairSql);
    $equipmentRepairResult = $equipmentRepairStmt->fetchAll(PDO::FETCH_ASSOC);

    $equipmentRepairLimit = 10;
    $equipmentRepairTotalRows = count($equipmentRepairResult);
    $equipmentRepairTotalPages = ceil($equipmentRepairTotalRows / $equipmentRepairLimit);

    if (isset($_GET['equipment_page'])) {
        $equipmentRepairPage = $_GET['equipment_page'];
    } else {
        $equipmentRepairPage = 1;
    }

    $equipmentRepairStart = ($equipmentRepairPage - 1) * $equipmentRepairLimit;
    $equipmentRepairSql .= " LIMIT $equipmentRepairStart, $equipmentRepairLimit";

    $equipmentRepairStmt = $conn->query($equipmentRepairSql);
    $equipmentRepairResult = $equipmentRepairStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($equipmentRepairResult)) {
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
        foreach ($equipmentRepairResult as $equipmentRepairRow) {
            $equipment_sale  = $equipmentRepairRow["equipment_sale"];
            echo "<tr>";
            echo "<td>" . $equipmentRepairNumRows . "</td>";
            if ($equipment_sale == 1) {
                echo "<td><del>" . $equipmentRepairRow["equipment_number"] . "</del> <span style='color: red;'>แทงจำหน่ายแล้ว</span></td>";
            } else {
                echo "<td>" . $equipmentRepairRow["equipment_number"] . "</td>";
            }            
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
                case 5:
                    $statusColorClass = "status-awaiting-equipment";
                    $statusText = "รอให้คะแนน";
                    break;
                case 6:
                    $statusColorClass = "status-awaiting-equipment123";
                    $statusText = "รอช่างภายนอก";
                        break;
                case 7:
                    $statusColorClass = "status-awaiting-equipment456";
                    $statusText = "รออะไหล่";
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

} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage();
}
?>


    <br>
    <br>

    <h3 style="margin: 30px">รายการแจ้งซ่อมพื้นที่</h3>

    <?php
try {
    // ... โค้ดสร้างการเชื่อมต่อฐานข้อมูลแบบ PDO ...

    // สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมพื้นที่
    $areaRepairSql = "SELECT Area_repair.area_id, User.user_name, Area_repair.status_id, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
        FROM Area_repair
        JOIN User ON Area_repair.user_id = User.user_id
        JOIN Statuss ON Area_repair.status_id = Statuss.status_id
        ORDER BY Area_repair.area_id DESC"; // เพิ่ม ORDER BY ในการเรียงลำดับตาม area_id ที่มากสุดขึ้นก่อน

    $areaRepairStmt = $conn->query($areaRepairSql);
    $areaRepairResult = $areaRepairStmt->fetchAll(PDO::FETCH_ASSOC);

    $areaRepairLimit = 10;
    $areaRepairTotalRows = count($areaRepairResult);
    $areaRepairTotalPages = ceil($areaRepairTotalRows / $areaRepairLimit);

    if (isset($_GET['area_page'])) {
        $areaRepairPage = $_GET['area_page'];
    } else {
        $areaRepairPage = 1;
    }

    $areaRepairStart = ($areaRepairPage - 1) * $areaRepairLimit;
    $areaRepairSql .= " LIMIT $areaRepairStart, $areaRepairLimit";

    $areaRepairStmt = $conn->query($areaRepairSql);
    $areaRepairResult = $areaRepairStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($areaRepairResult)) {
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
        foreach ($areaRepairResult as $areaRepairRow) {
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
                case 5:
                    $statusColorClass = "status-awaiting-equipment";
                    $statusText = "รอให้คะแนน";
                    break;
                case 6:
                        $statusColorClass = "status-awaiting-equipment123";
                        $statusText = "รอช่างภายนอก";
                        break;
                case 7:
                        $statusColorClass = "status-awaiting-equipment456";
                        $statusText = "รออะไหล่";
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

} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$pdo = null;
?>


    <br>
    <br>
    <br>
    <br>

    <?php include '../Footer/footer.php'; ?>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>