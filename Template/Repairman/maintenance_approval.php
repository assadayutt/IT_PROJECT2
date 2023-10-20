<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_login.php");
    exit();
}
$repairman_id = $_SESSION['id'];

?>
 
<!DOCTYPE html>
<html>

<head>
    <title>ขออนุมัติซ่อมบำรุง</title>
    <meta charset="utf-8">
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../CSS/maintenance_approval.css">
    <link rel="stylesheet" href="../../Template/Officer/css/List_All_repair.css">


</head>
<script>
$(document).ready(function() {
    $('#openmodel1').click(function() {
        $("#Modal1").modal("show");
    });

    $('#close_modal1').click(function() {
        $('#Modal1').modal("hide");
    });

    $('#openmodel2').click(function() {
        $("#Modal2").modal("show");
    });

    $('#close_modal2').click(function() {
        $('#Modal2').modal("hide");
    });
    // เมื่อคลิกที่ปุ่ม "บันทึก"
    $("#Modal1_save").click(function() {
        // รับข้อมูลจาก textarea
        var detail = $("#Textdetail").val();
        var repairman_id = <?php echo $repairman_id; ?>;

        console.log("รหัสช่างซ่อม : " + repairman_id);
        console.log("รายละเอียด : " + detail);

        // เช็คว่าข้อความไม่เป็นค่าว่าง
        if (detail.trim() === "") {
            Swal.fire({
                icon: 'error',
                title: 'ข้อความว่างเปล่า',
                text: 'กรุณากรอกข้อความ',
                confirmButtonText: "OK"
            });
            return; // หยุดการดำเนินการต่อ
        }

        // ส่งข้อมูลผ่าน AJAX
        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/Approve_Request_Tools.php",
            method: "POST",
            data: {
                detail: detail,
                repairman_id: repairman_id
            },
            dataType: "json", // ระบุ dataType เป็น JSON
            success: function(response) {
                sendLineNotify(repairman_id);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งข้อมูลสำเร็จ',
                        text: response.message, // แสดงข้อความจากเซิร์ฟเวอร์
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // รีเฟรชหน้าเว็บ
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: response.message, // แสดงข้อความจากเซิร์ฟเวอร์
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการส่งข้อมูล',
                });
            }
        });
    });


    function sendLineNotify(repairman_id) {
        console.log("repairman_id สำหรับดึงค่า : " + repairman_id);
        $.ajax({
            url: '/project/AJAX/Repairman_AJAX/Get_Officer_token_request.php',
            method: 'POST',
            data: {
                repairman_id: repairman_id
            },
            dataType: 'json',
            success: function(data) {
                console.log(data.lineTokens);

                const lineTokens = data.lineTokens;
                const message =
                    `มีการขอเบิกอุปกรณ์การซ่อมใหม่จาก : ${data.repairman_name} วันที่ : ${data.date} โปรดเช็ครายละเอียดในระบบแจ้งซ่อม - IMS`;

                sendLineMessage(lineTokens, message);
            },
            error: function(xhr, status, error) {
                const lineToken = xhr.getResponseHeader('Authorization');
                console.error(
                    `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
                );
            }
        });
    }

     function sendLineMessage(lineTokens, message) {
    const formData = new URLSearchParams();
    formData.append('message', message);
    formData.append('lineToken', lineTokens);

    var requestUrl = 'https://ims-project-server.vercel.app/send-line-notify'; // URL ถูกต้องและคงที่
    
    $.ajax({
        url: requestUrl,
        method: 'POST',
        data: formData.toString(), // แปลง FormData เป็น string
        contentType: 'application/x-www-form-urlencoded',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log("ส่งข้อความ Line Notify สำเร็จ!");
            } else {
                console.error("ส่งข้อความ Line Notify ไม่สำเร็จ!");
            }
        },
        error: function(xhr, status, error) {
            const lineTokens = xhr.getResponseHeader('Authorization');
            console.error("เกิดข้อผิดพลาดในการส่งข้อความ Line Notify: " + error +
                " (Line Token: " + lineTokens + ")");
        }
    });
}

    $("#Modal2_save").click(function() {
        // รับข้อมูลจาก textarea
        var detail = $("#Textdetail2").val();
        var repairman_id = <?php echo $repairman_id; ?>;
        var Image = document.querySelector('#profileImage').files[0];

        console.log("รหัสช่างซ่อม : " + repairman_id);
        console.log("รายละเอียด : " + detail);
        console.log("รูปภาพ : " + (Image ? Image.name : 'ไม่มีรูปภาพ'));

        // เช็คว่าข้อความไม่เป็นค่าว่าง
        if (detail.trim() === "" || !Image) {
            Swal.fire({
                icon: 'error',
                title: 'ข้อความว่างเปล่า',
                text: 'กรุณากรอกข้อความ',
                confirmButtonText: "OK"
            });
            return; // หยุดการดำเนินการต่อ
        }

        var formData = new FormData();
        formData.append('detail', detail);
        formData.append('repairman_id', repairman_id);
        formData.append('Image', Image);

        // ส่งข้อมูลผ่าน AJAX
        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/Request_out_repairman.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                sendLineNotify1(repairman_id);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ส่งข้อมูลสำเร็จ',
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการส่งข้อมูล',
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการส่งข้อมูล',
                });
            }
        });
    });


    function sendLineNotify1(repairman_id) {
        console.log("repairman_id สำหรับดึงค่า : " + repairman_id);
        $.ajax({
            url: '/project/AJAX/Repairman_AJAX/Get_Officer_token_outside.php',
            method: 'POST',
            data: {
                repairman_id: repairman_id
            },
            dataType: 'json',
            success: function(data) {
                console.log(data.lineTokens);

                const lineTokens = data.lineTokens;
                const message =
                    `มีการขอใช้ช่างซ่อมจากภายนอก จาก : ${data.repairman_name} วันที่ : ${data.date} โปรดเช็ครายละเอียดในระบบแจ้งซ่อม - IMS`;

                sendLineMessage(lineTokens, message);
            },
            error: function(xhr, status, error) {
                const lineToken = xhr.getResponseHeader('Authorization');
                console.error(
                    `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
                );
            }
        });
    }


});



function displayFileName() {
    const fileInput = document.getElementById("profileImage");
    const fileNameLabel = document.getElementById("profileImageLabel");

    if (fileInput.files.length > 0) {
        fileNameLabel.textContent = fileInput.files[0].name;
    } else {
        fileNameLabel.textContent = "เลือกไฟล์ PDF";
    }
}
</script>

<body>
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">ขอเบิกอุปกรณ์การซ่อม</h3>
                        <p class="card-text">ขออุปกรณ์ที่ไม่เพียงพอต่อการซ่อม</p>
                        <a class="btn btn-success" id="openmodel1">เลือก</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">ขอรับบริการช่างซ่อมจากบริษัทภายนอก</h3>
                        <p class="card-text">เนื่องจากจำเป็นต้องใช้ช่างเฉพาะทาง</p>
                        <a class="btn btn-danger" id="openmodel2">เลือก</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------------------- Modal1 ---------------------------------- -->

    <div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalTitle">ขอเบิกรายการอุปกรณ์</h5>
                </div>
                <div class="modal-body">
                    <p style="color: black;"><span style="color: red;">*</span> กรอกรายละเอียด</p>

                    <textarea class="form-control" id="Textdetail" rows="3"
                        placeholder="กรอกรายละเอียดขอเบิก หรือ รายการอุปกรณ์ที่ต้องการ"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close_modal1">ปิด</button>
                    <button type="button" id="Modal1_save" class="btn btn-primary" id="save_button">บันทึก</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ---------------------------------- Modal2 ---------------------------------- -->
    <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalTitle">ขอรับบริการช่างซ่อมจากบริษัทภายนอก</h5>
                </div>
                <div class="modal-body">
                    <p style="color: black;"><span style="color: red;">*</span> แจ้งรายละเอียดการขอซ่อม</p>
                    <textarea class="form-control" id="Textdetail2" rows="3"
                        placeholder="กรอกรายละเอียด หรือเหตุผลที่ต้องใช้ช่างภายนอก"></textarea>
                    <br>
                    <div class="col-md-12 mb-4">
                        <p style="color: black;"><span style="color: red;">*</span> อัพโหลดใบเสนอราคา PDF</p>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profileImage" multiple accept=".pdf"
                                name="profileImage" onchange="displayFileName()">

                            <label class="custom-file-label" for="profileImage" id="profileImageLabel">เลือกไฟล์
                                PDF</label>

                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close_modal2">ปิด</button>
                    <button type="button" id="Modal2_save" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br>

    <div class="container mt-4">
        <h2>รายการขอเบิกอุปกรณ์การซ่อม</h2>
        <div class="table-responsive">

            <?php
require_once("../../Database/db.php");

$repairman_id = $_SESSION['id'];

$equipmentRepairSql = "SELECT Approve_Request_Tools.*, Repairman.*, Statuss.*
FROM Approve_Request_Tools
JOIN Repairman ON Approve_Request_Tools.repairman_id = Repairman.repairman_id
JOIN Statuss ON Approve_Request_Tools.status = Statuss.status_id
ORDER BY Approve_Request_Tools.approve_id DESC"; // ใช้ ORDER BY เพื่อเรียงลำดับแถวในลำดับล่าสุดและตอนนี้มากกว่า


$equipmentRepairResult = $conn->query($equipmentRepairSql);

$equipmentRepairLimit = 10;
$equipmentRepairTotalRows = $equipmentRepairResult->rowCount();
$equipmentRepairTotalPages = ceil($equipmentRepairTotalRows / $equipmentRepairLimit);

if (isset($_GET['equipment_page'])) {
    $equipmentRepairPage = $_GET['equipment_page'];
} else {
    $equipmentRepairPage = 1;
}

$equipmentRepairStart = ($equipmentRepairPage - 1) * $equipmentRepairLimit;
$equipmentRepairSql .= " LIMIT $equipmentRepairStart, $equipmentRepairLimit";

$equipmentRepairResult = $conn->query($equipmentRepairSql);

if ($equipmentRepairResult->rowCount() > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ลำดับ</th>";
    echo "<th>รายการขอเบิก</th>";
    echo "<th>วันที่ยื่นเรื่อง</th>";
    echo "<th>ช่างซ่อมที่ขอเบิก</th>";
    echo "<th>สถานะ</th>";
    echo "<th>วัน เวลาที่ อนุมัติ/ไม่อนุมัติ</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $equipmentRepairNumRows = $equipmentRepairStart + 1;
    while ($equipmentRepairRow = $equipmentRepairResult->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $equipmentRepairNumRows . "</td>";
        echo "<td>" . $equipmentRepairRow["details"] . "</td>";
        echo "<td>" . $equipmentRepairRow["date"] . "</td>";
        echo "<td>" . $equipmentRepairRow["repairman_name"] . "</td>";
        echo "<td>" . $equipmentRepairRow["status_name"] . "</td>";

        $approve_id =  $equipmentRepairRow["approve_id"];



            echo "<td>";
            if ($equipmentRepairRow["date_approve"] != 0) {
                echo $equipmentRepairRow["date_approve"];
            }
            echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete(' . $approve_id . ')"></i></td>';

         echo "</td>";
    
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
        <h2>รายการขอรับบริการช่างซ่อมจากบริษัทภายนอก</h2>
        <div class="table-responsive">

            <?php
require_once("../../Database/db.php");

$equipmentRepairSql = "SELECT Approve_Outside_repairman.*, Repairman.*, Statuss.*
FROM Approve_Outside_repairman
JOIN Repairman ON Approve_Outside_repairman.repairman_id = Repairman.repairman_id
JOIN Statuss ON Approve_Outside_repairman.status = Statuss.status_id
ORDER BY Approve_Outside_repairman.approve_o_id DESC";

 

$equipmentRepairResult = $conn->query($equipmentRepairSql);

$equipmentRepairLimit = 10;
$equipmentRepairTotalRows = $equipmentRepairResult->rowCount();
$equipmentRepairTotalPages = ceil($equipmentRepairTotalRows / $equipmentRepairLimit);

if (isset($_GET['equipment_page'])) {
    $equipmentRepairPage = $_GET['equipment_page'];
} else {
    $equipmentRepairPage = 1;
}

$equipmentRepairStart = ($equipmentRepairPage - 1) * $equipmentRepairLimit;
$equipmentRepairSql .= " LIMIT $equipmentRepairStart, $equipmentRepairLimit";

$equipmentRepairResult = $conn->query($equipmentRepairSql);

if ($equipmentRepairResult->rowCount() > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ลำดับ</th>";
    echo "<th >รายละเอีนด</th>";
    echo "<th>วันที่แจ้ง</th>";
    echo "<th>ช่างที่แจ้ง</th>";
    echo "<th>สถานะ</th>";
    echo "<th>วัน เวาลาที่ อนุมัติ/ไม่อนุมัติ</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $equipmentRepairNumRows = $equipmentRepairStart + 1;
    while ($equipmentRepairRow = $equipmentRepairResult->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $equipmentRepairNumRows . "</td>";
        echo "<td>" . $equipmentRepairRow["details"]. "</td>";
        echo "<td>" . $equipmentRepairRow["date"] . "</td>";
        echo "<td>" . $equipmentRepairRow["repairman_name"] . "</td>";
        echo "<td>" . $equipmentRepairRow["status_name"] . "</td>";

        $approve_o_id  = $equipmentRepairRow["approve_o_id"];

        echo "<td>";
        if ($equipmentRepairRow["date_approve"] != 0) {
            echo $equipmentRepairRow["date_approve"];
        }
        echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete1(' . $approve_o_id . ')"></i></td>';

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
                            <br>
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
                    <button type="button" id="save_PDF" class="btn btn-success" onclick="Save_to_PDF()">
                        <i class="fas fa-file-pdf"></i> Save to PDF
                    </button>
                    <button type="button" id="close_button" class="btn btn-danger">ปิด</button>
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
                            <br>
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
                    <button type="button" id="close_button1" class="btn btn-danger">ปิด</button>
                </div>
            </div>
        </div>
    </div>


    <br>
    <br>
    <br>
    <br>

    <?php include '../../Footer/footer.php' ?>
</body>
<script>
 $(document).ready(function() {
        $("#close_button").click(function() {
            $("#Modal_complete").modal('hide');
        });
         $("#close_button1").click(function() {
            $("#Modal_complete1").modal('hide');
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
    approve_id_PDF = approve_id

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
<script>

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
    approve_o_id_PDF = approve_o_id;
    console.log("รหัสการดึงข้อมูล : " + approve_o_id);

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