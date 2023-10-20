<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_login.php");
    exit();
}
$Officer_id = $_SESSION['id'];

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>อนุมัติซ่อมบำรุง</title>

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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />

    <style>
    body {
        font-family: 'Kanit', sans-serif;
    }
    </style>
</head>

<?php include 'nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">
    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>ขอซื้ออุปกรณ์การซ่อมเพิ่มเติม</h2>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th style="width: 23%">รายการขอเบิก</th>
                                    <th>วันที่ยื่นเรื่อง</th>
                                    <th>ช่างซ่อมที่ขอเบิก</th>
                                    <th>สถานะ</th>
                                    <th>วันที่อนุมัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        require_once("../../Database/db.php");

                        $itemsPerPage = 10;
                        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        
                        $startFrom = ($currentPage - 1) * $itemsPerPage;
        

                        $sql = "SELECT Approve_Request_Tools.date_approve, Approve_Request_Tools.approve_id, Statuss.status_name, Approve_Request_Tools.details, Approve_Request_Tools.date , Approve_Request_Tools.status, Repairman.repairman_name, Approve_Request_Tools.status
                                FROM Approve_Request_Tools
                                JOIN Repairman ON Approve_Request_Tools.repairman_id = Repairman.repairman_id
                                JOIN Statuss ON Approve_Request_Tools.status = Statuss.status_id
                                ORDER BY approve_id  DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $num_rows = $startFrom + 1;

                        if (count($result) > 0) {
                            foreach ($result as $row) {

                                echo "<tr>";
                                echo "<td>" . $num_rows . "</td>";
                                echo "<td>" . $row["details"] . "</td>";
                                echo "<td>" . $row["date"] . "</td>";
                                echo "<td>" . $row["repairman_name"] . "</td>";
                                echo "<td>" . $row["status_name"] . "</td>";
                                $approve_id = $row['approve_id'];

                                if ($row['status'] === '9' || $row['status'] === '10' || $row['status'] === '11') {
                                    // ถ้า status_id เป็น 9, 10, 11 ให้แสดงค่าจาก date_approve แทน
                                    if (!empty($row["date_approve"]) && $row["date_approve"] != 0) {
                                        echo "<td>" . $row["date_approve"] . "</td>";
                                        echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete(' . $approve_id . ')"></i></td>';

                                        
                                    } else {
                                        echo "<td></td>"; // ไม่แสดงข้อมูล
                                    }
                                } else {
                                }


                                echo "</tr>";
                                $num_rows++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>ไม่มีการยื่นเรื่อง</td></tr>";
                        }
                        ?>

                            </tbody>
                        </table>
                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Approve_Request_Tools")->fetchColumn();
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


    <!--  ------------------------------------------------ รายละเอียด ------------------------------------------------     -->

    <div class="modal fade" id="Modal_complete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดขอซื้ออุปกรณ์การซ่อมเพิ่มเติม</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="statusCard" class="card">
                                <div class="card-header">
                                    <h5>สถานะจากคณะบดี</h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="repairman_name1"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="details1"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="date1"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 1</h5>
                                <div class="waiting-box" id="onest_approver"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง : ผู้อนุมัติคนที่ 1
                                </h5>
                                <div class="waiting-box" id="onest_position"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 2</h5>
                                <div class="waiting-box" id="twond_approver"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง : ผู้อนุมัติคนที่ 2
                                </h5>
                                <div class="waiting-box" id="twond_position"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="popup" class="btn btn-success"  onclick="Save_to_PDF()">
                        <i class="fas fa-file-pdf"></i> Save to PDF
                    </button>
                    <button type="button" id="close_button" class="btn btn-secondary">
                        <i class="fas fa-times"></i> ปิด
                    </button>

                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $("#close_button").click(function() {
            $("#Modal_complete").modal('hide');
        });

    });

    function displayStatus(status) {
        var statusCard = $("#statusCard .card-body");

        if (status == 9) {
            statusCard.html(
                '<div class="alert alert-success"><i class="fa fa-check-circle"></i> อนุมัติ</div>');
        } else if (status == 10) {
            statusCard.html(
                '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ไม่อนุมัติ</div>');
        } else {
            statusCard.html(
                '<div class="alert alert-warning"><i class="fa fa-question-circle"></i> รอการอนุมัติ</div>');
        }
    }

    function showPopup_complete(data) {
        $("#repairman_name1").text(data.repairman_name);
        $("#details1").text(data.details);
        $("#date1").text(data.date);
        $("#onest_approver").text(data.onest_approver);
        $("#onest_position").text(data.onest_position);
        $("#twond_approver").text(data.twond_approver);
        $("#twond_position").text(data.twond_position);

        $("#status").text(data.status);
        displayStatus(data.status);

    }



    function Show_data_complete(approve_id) {
        console.log("รหัสการดึงข้อมูล : " + approve_id);
        approve_id_PDF = approve_id;
        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Get_data_request.php",
            method: "POST",
            data: {
                approve_id: approve_id,
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopup_complete(response.data);
                    console.log(response.data);

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
        $("#Modal_complete").modal("show");

    }


    var approve_id_PDF;

    function Save_to_PDF() {
        approve_id = approve_id_PDF;
        console.log("รหัสการดึงข้อมูล_PDF : " + approve_id);

        // สร้าง URL สำหรับดาวน์โหลด PDF
        var pdfDownloadUrl = location.origin + "/project/AJAX/Officer_AJAX/Print_Request_PDF.php?approve_id=" +
            approve_id;

        // เปิดหน้าต่างใหม่เพื่อดาวน์โหลด PDF
        window.open(pdfDownloadUrl, '_blank');
    }
    </script>



    <!--------------------------------- ช่างภายนอก --------------------------------->
    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>รายการที่ขอช่างจากภายนอก</h2>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th style="width: 23%">รายละเอียด</th>
                                    <th style="width: 16%">วันที่แจ้ง</th>
                                    <th style="width: 14%">ช่างซ่อมที่แจ้ง</th>
                                    <th>สถานะ</th>
                                    <th>วันที่อนุมัติ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            require_once("../../Database/db.php");

                            $itemsPerPage = 10;
                            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

                            $startFrom = ($currentPage - 1) * $itemsPerPage;

                            $sql = "SELECT Approve_Outside_repairman.date_approve,Approve_Outside_repairman.approve_o_id,Approve_Outside_repairman.status, Statuss.status_name, Approve_Outside_repairman.details, Approve_Outside_repairman.date, Repairman.repairman_name
                                    FROM Approve_Outside_repairman
                                    JOIN Repairman ON Approve_Outside_repairman.repairman_id = Repairman.repairman_id
                                    JOIN Statuss ON Approve_Outside_repairman.status = Statuss.status_id
                                    ORDER BY approve_o_id DESC LIMIT $startFrom, $itemsPerPage";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $num_rows = $startFrom + 1;
                            if (count($result) > 0) {
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $num_rows . "</td>";
                                    echo "<td>" .  $row["details"] . "</td>";
                                    echo "<td>" . $row["date"] . "</td>";
                                    echo "<td>" . $row["repairman_name"] . "</td>";
                                    echo "<td>" . $row["status_name"] . "</td>";
                                    $approve_o_id = $row['approve_o_id'];

                            
                                    if ($row['status'] === '9' || $row['status'] === '10' || $row['status'] === '11') {
                                        // ถ้า status_id เป็น 9, 10, 11 ให้แสดงค่าจาก date_approve แทน
                                        if (!empty($row["date_approve"]) && $row["date_approve"] != 0) {
                                            echo "<td>" . $row["date_approve"] . "</td>";
                                            echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete1(' . $approve_o_id . ')"></i></td>';

                                        } else {
                                            echo "<td></td>"; // ไม่แสดงข้อมูล
                                        }
                                    } 
                            
                                    echo "</tr>";
                                    $num_rows++;
                                }
                            } else {
                                echo "<tr><td colspan='7'>ไม่มีการยื่นเรื่อง</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="pagination">
                        <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Approve_Outside_repairman")->fetchColumn();
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

    <!--  ------------------------------------------------ รายละเอียด ------------------------------------------------     -->

    <div class="modal fade" id="Modal_complete1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดขอช่างภายนอก</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="statusCard_outSide" class="card">
                                <div class="card-header">
                                    <h5>สถานะจากคณะบดี</h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="repairman_name1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="details1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="date1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 1</h5>
                                <div class="waiting-box" id="onest_approver1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง : ผู้อนุมัติคนที่ 1
                                </h5>
                                <div class="waiting-box" id="onest_position1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 2</h5>
                                <div class="waiting-box" id="twond_approver1234"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง : ผู้อนุมัติคนที่ 2
                                </h5>
                                <div class="waiting-box" id="twond_position1234"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <h5>ใบเสนอราคา</h5>
                                        <div class="image-box">
                                            <iframe id="file1" src="../../Files/quotation/" width="730" height="500"
                                                frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="popup" class="btn btn-success" onclick="Save_to_PDF1()">
                        <i class="fas fa-file-pdf"></i> Save to PDF
                    </button>
                    <button type="button" id="close_button1" class="btn btn-secondary">
                        <i class="fas fa-times"></i> ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
<br>
<br>
<br>
<?php include '../../Footer/footer.php'; ?>
</body>
<script>
$(document).ready(function() {
    $("#close_button1").click(function() {
        $("#Modal_complete1").modal('hide');
    });

});

function displayStatus1(status) {
    var statusCard = $("#statusCard_outSide .card-body");

    if (status == 9) {
        statusCard.html(
            '<div class="alert alert-success"><i class="fa fa-check-circle"></i> อนุมัติ</div>');
    } else if (status == 10) {
        statusCard.html(
            '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ไม่อนุมัติ</div>');
    } else {
        statusCard.html(
            '<div class="alert alert-warning"><i class="fa fa-question-circle"></i> รอการอนุมัติ</div>');
    }
}

function showPopup_complete1(data) {
    $("#repairman_name1234").text(data.repairman_name1);
    $("#details1234").text(data.details1);
    $("#date1234").text(data.date1);
    $("#onest_approver1234").text(data.onest_approver1);
    $("#onest_position1234").text(data.onest_position2);
    $("#twond_approver1234").text(data.twond_approver3);
    $("#twond_position1234").text(data.twond_position4);
    $("#file1").attr("src", "../../Files/quotation/" + data.file1);


    $("#status12").text(data.status12);
    displayStatus1(data.status);

}

function Show_data_complete1(approve_o_id) {
    console.log("รหัสการดึงข้อมูล : " + approve_o_id);
    approve_o_id_PDF = approve_o_id;
    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_data_request_outside.php",
        method: "POST",
        data: {
            approve_o_id: approve_o_id,
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopup_complete1(response.data);
                console.log(response.data);

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
    $("#Modal_complete1").modal("show");
}


var approve_o_id_PDF;

function Save_to_PDF1() {
    approve_o_id = approve_o_id_PDF;
    console.log("รหัสการดึงข้อมูล_PDF : " + approve_o_id);

    // สร้าง URL สำหรับดาวน์โหลด PDF
    var pdfDownloadUrl = location.origin + "/project/AJAX/Officer_AJAX/Print_Outside_PDF.php?approve_o_id=" + approve_o_id;

    // เปิดหน้าต่างใหม่เพื่อดาวน์โหลด PDF
    window.open(pdfDownloadUrl, '_blank');
}
</script>

</html>