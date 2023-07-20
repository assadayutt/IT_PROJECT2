<!DOCTYPE html>
<html>

<head>
    <title>รายการแจ้งซ่อม</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/List_Repair.css">
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    if (typeof jQuery == 'undefined') {
        console.log('jQuery is not loaded');
    } else {
        console.log('jQuery is loaded');
    }

    $(document).ready(function() {
        function showPopupp(data) {
            var popupContent = "<h2 class='mb-3'>รายละเอียดการแจ้งซ่อมครุภัณฑ์</h2>";
            popupContent += "<p><strong>ครุภัณฑ์:</strong> " + data.equipment_number + "</p>";
            popupContent += "<p><strong>รายละเอียด:</strong> " + data.repair_detail + "</p>";
            popupContent += "<p><strong>วันที่แจ้งซ่อม:</strong> " + data.repair_date + "</p>";
            popupContent += "<img src='/project/Images/Repair_equipment/" + data.repair_imagesbefor +
                "' alt='รูปภาพก่อนซ่อม' class='popup-image'>";

            $("#popup-content").html(popupContent);
            $("#popup").modal('show');
        }

        $(".button").click(function() {
            var repairId = $(this).attr("data-repair-id");
            console.log(repairId);
            $.ajax({
                url: "/project/AJAX/AJAX_get_repair_details.php",
                method: "POST",
                data: {
                    repair_id: repairId
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        showPopupp(response.data);
                    } else {
                        console.log(response.message);
                        Swal.fire({
                            title: "Error!",
                            text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response
                                .message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                },
            });
        });

        $("#popup-close").click(function() {
            $("#popup").modal('hide');
        });
    });
 
    function deleteRepair1(repair_id) {
        console.log(repair_id);
        $.ajax({
            type: "POST",
            url: "/Project/AJAX/AJAX_delete_List_repair_Equipment.php",
            data: {
                repair_id: repair_id
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                Swal.fire({
                    title: "Success!",
                    text: "ยกเลิกการแจ้งซ่อมสำเร็จ",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // รีเฟรชหน้าเว็บ
                            }
                        });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    title: "Error!",
                    text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            },
        });
    }
    </script>
</head>

<body>
    <br>

    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th style="width: 30%;">หมายเลขครุภัณฑ์</ะ>
                <th>รายละเอียด</th>
                <th>วันที่แจ้งซ่อม</th>
                <th>สถานะ</th>
                <th style="width: 20%;">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['id'];

require_once("../../Database/db.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=IMS-Project", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM Equipment_repair WHERE user_id = :user_id AND status_id IN (2, 3, 4)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $num_rows = 1;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $num_rows . "</td>";
            echo "<td> ครุภัณฑ์ : " . $row["equipment_number"] . "</td>";
            echo "<td>" . $row["repair_detail"] . "</td>";
            echo "<td>" . $row["repair_date"] . "</td>";

            $statusId = $row["status_id"];
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


            echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;'data-repair-id='" . $row['repair_id'] . "' href='javascript:void(0);'>เพิ่มเติม</a>
            <a class='button1' style='text-decoration: none; background-color: red; color: white;' href='javascript:void(0);' onclick='deleteRepair1(" . $row['repair_id'] . ")'>ยกเลิก</a></td>";

            


            echo "</tr>";
            $num_rows++;
        }
    } else {
        echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
    }

    $pdo = null;
} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage();
    exit();
}
?>
            <script>
            $(document).ready(function() {
                function showPopup(data) {
                    var popupContent = "<h2 class='mb-3'>รายละเอียดการแจ้งซ่อมพื้นที่</h2>";
                    popupContent += "<p><strong></strong> " + data.area_detail + "</p>";
                    popupContent += "<p><strong>รายละเอียด:</strong> " + data.area_problem + "</p>";
                    popupContent += "<p><strong>วันที่แจ้งซ่อม:</strong> " + data.area_address + "</p>";
                    popupContent += "<p><strong>วันที่แจ้งซ่อม:</strong> " + data.area_date + "</p>";
                    popupContent += "<img src='/project/Images/Repair_Address/" + data.area_imagesbefor +
                        "' alt='รูปภาพก่อนซ่อม' class='popup-image'>";

                    $("#popup-content").html(popupContent);
                    $("#popup").modal('show');
                }

                $(".button1").click(function() {
                    var areaId = $(this).attr("data-repair-id");
                    console.log(areaId);
                    $.ajax({
                        url: "/project/AJAX/AJAX_get_address_repair.php",
                        method: "POST",
                        data: {
                            area_id: areaId
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                showPopup(response.data);
                            } else {
                                console.log(response.message);
                                Swal.fire({
                                    title: "Error!",
                                    text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " +
                                        response
                                        .message,
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                    });
                });

                $("#popup-close").click(function() {
                    $("#popup").modal('hide');
                });
            });

            function deleteRepair(area_id) {
                console.log(area_id);
                $.ajax({
                    type: "POST",
                    url: "/Project/AJAX/AJAX_delete_List_repair_Area.php",
                    data: {
                        area_id: area_id
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: "Success!",
                            text: "ยกเลิกการแจ้งซ่อมสำเร็จ",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // รีเฟรชหน้าเว็บ
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: "Error!",
                            text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    },
                });
            }
            </script>
            <table>
                <thead>

                    <tr>
                        <th>ลำดับ</th>
                        <th>รายการแจ้งซ่อม</th>
                        <th>รายละเอียด</th>
                        <th>วันที่แจ้งซ่อม</th>
                        <th>พื้นที่ / บริเวณ</th>
                        <th style="width: 14%;">สถานะ</th>
                        <th style="width: 20%;">ตรวจสอบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php       
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['id'];

require_once("../../Database/db.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=IMS-Project", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM area_repair WHERE user_id = :user_id AND status_id IN (2, 3, 4)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute(); 

    $num_rows = 1;
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $num_rows . "</td>";
            echo "<td>" . $row["area_detail"] . "</td>";
            echo "<td>" . $row["area_problem"] . "</td>";
            echo "<td>" . $row["area_date"] . "</td>";
            echo "<td>" . $row["area_address"] . "</td>";

            $statusId = $row["status_id"];
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

            
            echo "<td>
            <a class='button1' style='text-decoration: none; background-color: green; color: white;' data-repair-id='" . $row['area_id'] . "' href='javascript:void(0);'>เพิ่มเติม</a>
            <a class='button1' style='text-decoration: none; background-color: red;  color: white;' href='javascript:void(0);' onclick='deleteRepair(" . $row['area_id'] . ")'>ยกเลิก</a>
        </td>";
        

            echo "</tr>";
            $num_rows++;
        }
    } else {
        echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
    }

    $pdo = null;
} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage();
    exit();
}
?>
                </tbody>
            </table>

            <div id="popup" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-white">รายละเอียดการแจ้งซ่อม</h5>
                        </div>
                        <div class="modal-body" id="popup-content"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="popup-close">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <br>

            <?php include '../../Footer/footer.php' ?>
</body>

</html>