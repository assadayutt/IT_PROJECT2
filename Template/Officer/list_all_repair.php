<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>การแจ้งซ่อมทั้งหมด</title>

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../Template/Officer/css/List_All_repair.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />

    <style>
    body {
        font-family: 'Kanit', sans-serif;
    }

    .waiting-box {
        width: 100%;
        height: 50px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-size: 17px;
        line-height: 50px;
        color: #000000;
        padding: 0 10px;
        /* เพิ่มขอบระยะห่างเข้ามาด้านใน */
    }
    </style>
</head>

<?php include 'nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">
    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>รายการซ่อมครุภัณฑ์</h2>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ครุภัณฑ์</th>
                                    <th>รายการแจ้งซ่อม</th>
                                    <th>วันที่แจ้ง</th>
                                    <th>ช่างซ่อมที่รับงาน</th>
                                    <th>สถานะ</th>
                                    <th>รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
           
           require_once("../../Database/db.php");

           $itemsPerPage = 10;
           $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

           $startFrom = ($currentPage - 1) * $itemsPerPage;


           $sql = "SELECT Equipment_repair.repair_id, User.user_name, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
                   FROM Equipment_repair
                   JOIN User ON Equipment_repair.user_id = User.user_id
                   JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
                   WHERE Statuss.status_id in (2,3,4,5,6,7)
                   ORDER BY repair_id DESC LIMIT $startFrom, $itemsPerPage";
           
           $stmt = $conn->prepare($sql);
           $stmt->execute();
           $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           $num_rows = $startFrom + 1;

           if (count($result) > 0) {
               foreach ($result as $row) {
           
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
                $statusText = "เสร็จสิ้น";
                break;
            case 2:
                $statusText = "กำลังดำเนินการ";
                break;
            case 3:
                $statusText = "รอการซ่อม";
                break;
            case 4:
                $statusText = "รอรับงาน";
                break;
            case 5:
                $statusText = "รอให้คะแนน";
                break;
            case 6:
                $statusText = "รอช่างภายนอก";
                break;
            case 7:
                $statusText = "รออะไหล่";
                break;
            default:
                $statusText = "";
                break;
        }
                     echo "<td>" . $row["repair_date"] . "</td>";

                    echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

                    $repair_id = $row['repair_id'];

                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' href='javascript:void(0);' onclick='repair_detail(" . $repair_id . ");'>เพิ่มเติม</a></td>";

                    echo "</tr>";
                    $num_rows++;
                }
            } else {
                echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
            }
            ?>

                            </tbody>
                        </table>

                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment_repair ")->fetchColumn();
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        for ($page = 1; $page <= $totalPages; $page++) {
                            echo "<a href='?page=$page' class='page-link'>$page</a> ";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  ------------------------------------------------ ปุ่มเพิ่มเติม ------------------------------------------------     -->

    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดการแจ้งซ่อมครุภัณฑ์</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>ผู้แจ้งซ่อม</h5>
                                <div class="waiting-box" id="user_name"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>หมายเลขครุภัณฑ์</h5>
                                <div class="waiting-box" id="equipment_number"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="repair_detail"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่แจ้งซ่อม</h5>
                                <div class="waiting-box" id="repair_date"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ซ่อมโดยช่าง</h5>
                                <div class="waiting-box" id="repairman_name"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่คาดว่าจะเสร็จ</h5>
                                <div class="waiting-box" id="date_comp_repair"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ข้อความจากช่างซ่อม</h5>
                                <div class="waiting-box" id="message_repair"></div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <h5>รูปภาพ : BEFORE</h5>
                                    <div class="image-box">
                                        <img class="preview-image rounded" id="repair_imagesbefor_display"
                                            src="../../Images/Repair_equipment/"
                                            style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                            alt="รูปภาพ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="delete_repair" class="btn btn-danger"
                        onclick="delete_repair()">ลบการแจ้งซ่อม</button>

                    <button type="button" id="popup-close" class="btn btn-secondary">ปิด</button>

                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $("#popup-close").click(function() {
            $("#Modal").modal('hide');
        });

        $("#closePopupButtonarea1").click(function() {
            $("#Modal_detail").modal('hide');
        });


    });

    function showPopupp(data) {
        $("#user_name").text(data.user_name);
        $("#equipment_number").text(data.equipment_number);
        $("#repair_detail").text(data.repair_detail);
        $("#repair_date").text(data.repair_date);
        $("#repair_imagesbefor_display").attr("src", data.repair_imagesbefor);
        $("#repairman_name").text(data.repairman_name);
        $("#date_comp_repair").text(data.date_comp_repair);
        $("#message_repair").text(data.message_repair);


        $("#Modal").modal("show");

    }
    var repair_id_for_delete;

    function repair_detail(repair_id) {
        console.log("handleButton1Click : " + repair_id);
        repair_id_for_delete = repair_id;


        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Get_repair_details_more.php",
            method: "POST",
            data: {
                repair_id: repair_id
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopupp(response.data);
                } else {
                    console.log(response.message);
                    Swal.fire({
                        title: "Error!",
                        text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            },
        });
    }

    function delete_repair() {
        console.log("รหัสการแจ้งซ่อมที่จะลบ : " + repair_id_for_delete);
        Swal.fire({
            title: "ยืนยันการลบ",
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ใช่, ลบเลย!",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: location.origin + "/project/AJAX/Officer_AJAX/Delete_repair.php",
                    method: "POST",
                    data: {
                        repair_id: repair_id_for_delete
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: "สำเร็จ!",
                                text: "รายการถูกลบเรียบร้อยแล้ว",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "เกิดข้อผิดพลาดในการลบ: " + response.message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: "ไม่สามารถลบได้เนื่องจากช่างรับงานแล้ว",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    }
    </script>


    <!--------------------------------- การแจ้งซ่อมพื้นที่ --------------------------------->
    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>รายการซ่อมพื้นที่</h2>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รายการแจ้งซ่อม</th>
                                    <th>ปัญหาที่พบ</th>
                                    <th>วันที่แจ้ง</th>
                                    <th>สถานที่ / บริเวณ</th>
                                    <th>ช่างซ่อมที่รับงาน</th>
                                    <th>สถานะ</th>
                                    <th>รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            require_once("../../Database/db.php");

                            $itemsPerPage = 10;
                            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                 
                            $startFrom = ($currentPage - 1) * $itemsPerPage;
                 

                            $sql = "SELECT Area_repair.area_id, Area_repair.status_id, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date, Area_repair.area_address
                                    FROM Area_repair
                                    JOIN User ON Area_repair.user_id = User.user_id
                                    JOIN Statuss ON Area_repair.status_id = Statuss.status_id
                                    WHERE Statuss.status_id in (2,3,4,5,6,7)
                                    ORDER BY area_id DESC LIMIT $startFrom, $itemsPerPage";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $num_rows = 1;
                            if (count($result) > 0) {
                                foreach ($result as $row) {
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
                                            $statusText = "เสร็จสิ้น";
                                            break; 
                                        case 2:
                                            $statusText = "กำลังดำเนินการ";
                                            break;
                                        case 3:
                                            $statusText = "รอการซ่อม";
                                            break;
                                        case 4:
                                            $statusText = "รอรับงาน";
                                            break;
                                        case 5:
                                            $statusText = "รอให้คะแนน";
                                            break;
                                        case 6: 
                                            $statusText = "รอช่างภายนอก";
                                            break;
                                        case 7:
                                            $statusText = "รออะไหล่";
                                            break;
                                        default:
                                            $statusText = "";
                                            break;
                                    }
                                    echo "<td>" . $row["area_address"] . "</td>";
                                    
                                    echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";
                                    
                                    

                                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' onclick='getAddressMore(" . $row['area_id'] . ");'>เพิ่มเติม</a></td>";

                                    echo "</tr>";
                                    $num_rows++;
                                }
                            } else {
                                echo "<tr><td colspan='7'>ไม่พบข้อมูล</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Area_repair ")->fetchColumn();
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        for ($page = 1; $page <= $totalPages; $page++) {
                            echo "<a href='?page=$page' class='page-link'>$page</a> ";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--  ------------------------------------------------ ปุ่มเพิ่มเติม ------------------------------------------------     -->

    <div class="modal fade" id="Modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดการแจ้งซ่อมพื้นที่</h4>

                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>ผู้แจ้งซ่อม</h5>
                                <div class="waiting-box" id="username"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="area_detail"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ปัญหาที่พบ</h5>
                                <div class="waiting-box" id="area_problem"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่แจ้งซ่อม</h5>
                                <div class="waiting-box" id="area_date"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>สถานที่ / บริเวณ</h5>
                                <div class="waiting-box" id="area_address"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ซ่อมโดยช่าง</h5>
                                <div class="waiting-box" id="repairman_name3"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่คาดว่าจะเสร็จ</h5>
                                <div class="waiting-box" id="date_comp_area"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ข้อความจากช่างซ่อม</h5>
                                <div class="waiting-box" id="message_area"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <h5>รูปภาพ : BEFORE</h5>
                                    <div class="image-box">
                                        <img class="preview-image rounded" id="area_imagesbefor"
                                            src="../../Images/Repair_Address/"
                                            style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                            alt="รูปภาพ">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br>
                    <div class="row">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="delete_repair" class="btn btn-danger"
                        onclick="delete_area()">ลบการแจ้งซ่อม</button>
                    <button type="button" id="closePopupButtonarea1" class="btn btn-secondary">ปิด</button>

                </div>
            </div>
        </div>
    </div>

</body>
<script>
var area_id_for_delete;

function getAddressMore(area_id) {
    console.log("getAddressMore : " + area_id);
    area_id_for_delete = area_id;
    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_address_repair_more.php",
        method: "POST",
        data: {
            area_id: area_id
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopup1(response.data);
            } else {
                console.log(response.message);
                Swal.fire({
                    title: "Error!",
                    text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
            $("#Modal_detail").modal("show");

        },
    });
}

function showPopup1(data) {
    username
    $("#username").text(data.username);
    $("#area_detail").text(data.area_detail);
    $("#area_problem").text(data.area_problem);
    $("#area_date").text(data.area_date);
    $("#area_address").text(data.area_address);
    $("#area_imagesbefor").attr("src", "../../Images/Repair_Address/" + data.area_imagesbefor);
    $("#repairman_name3").text(data.repairman_name_area1);
    $("#date_comp_area").text(data.date_comp_area);
    $("#message_area").text(data.message_area);


}

function delete_area() {
    console.log("รหัสการแจ้งซ่อมที่จะลบ : " + area_id_for_delete);
    Swal.fire({
        title: "ยืนยันการลบ",
        text: "คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ใช่, ลบเลย!",
        cancelButtonText: "ยกเลิก"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: location.origin + "/project/AJAX/Officer_AJAX/Delete_area.php",
                method: "POST",
                data: {
                    area_id: area_id_for_delete
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            title: "สำเร็จ!",
                            text: "รายการถูกลบเรียบร้อยแล้ว",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: "ไม่สามารถลบได้เนื่องจากช่างรับงานแล้ว ",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "ไม่สามารถลบได้เนื่องจากช่างรับงานแล้ว",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
}
</script>
<br>
<?php include '../../Footer/footer.php'; ?>
</body>

</html>