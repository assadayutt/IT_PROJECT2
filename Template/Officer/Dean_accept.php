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
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
           
           require_once("../../Database/db.php");

           $sql = "SELECT Approve_Request_Tools.date_approve,Approve_Request_Tools.status,Approve_Request_Tools.approve_id,Statuss.status_name,  Approve_Request_Tools.details, Approve_Request_Tools.date, Repairman.repairman_name
                   FROM Approve_Request_Tools
                   JOIN Repairman ON Approve_Request_Tools.repairman_id = Repairman.repairman_id
                   JOIN Statuss ON Approve_Request_Tools.status = Statuss.status_id
                   WHERE Status IN (9,10,11)
                   ORDER BY approve_id  DESC";
           
           $stmt = $conn->prepare($sql);
           $stmt->execute();
           $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           $num_rows = 1;
           if (count($result) > 0) {
               foreach ($result as $row) {
            
                    echo "<tr>";
                    echo "<td>" . $num_rows . "</td>";
                    echo "<td>" . $row["details"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . $row["repairman_name"] . "</td>";
                    echo "<td>" . $row["status_name"] . "</td>";

                    $approve_id = $row['approve_id'];

                    if ($row['status'] === '9' || $row['status'] === '10' ) {
                        // ถ้า status_id เป็น 9, 10, 11 ให้แสดงค่าจาก date_approve แทน
                            echo "<td>" . $row["date_approve"] . "</td>";
                            echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete(' . $approve_id . ')"></i></td>';

                    } else {
                        // ถ้า status_id ไม่ใช่ 9, 10, 11 ให้แสดงปุ่ม "ดำเนินการ"
                        echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' href='javascript:void(0);' onclick='Show_data(" . $approve_id . ");'>ดำเนินการ</a></td>";
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
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดขอซื้ออุปกรณ์การซ่อมเพิ่มเติม</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="repairman_name"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="details"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="date"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;"> ผู้อนุมัติคนที่ 1</span> </h5>
                                <div class="waiting-box" id="1st_approver"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;"> ตำแหน่ง : ผู้อนุมัติคนที่
                                        1</span>
                                </h5>
                                <div class="waiting-box" id="1st_position"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;"> ผู้อนุมัติคนที่ 2</span> </h5>
                                <div class="waiting-box" id="2nd_approver"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;"> ตำแหน่ง : ผู้อนุมัติคนที่
                                        2</span>
                                </h5>
                                <div class="waiting-box" id="2nd_position"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" id="save1" class="btn btn-danger"
                        onclick="ConfirmNoAccept()">ไม่อนุมัติ</button>
                    <button type="button" id="save" class="btn btn-success" onclick="ConfirmAccept()">อนุมัติ</button>

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
                                    <h5>คุณได้ทำการ</h5>
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
                <button type="button" id="popup" class="btn btn-success" onclick="Save_to_PDF()">
                        <i class="fas fa-file-pdf"></i> Save to PDF
                    </button>
                    <button type="button" id="close_button" class="btn btn-danger">ปิด</button>
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

    function showPopup(data) {
        $("#repairman_name").text(data.repairman_name);
        $("#details").text(data.details);
        $("#date").text(data.date);
        $("#1st_approver").text(data.onest_approver);
        $("#1st_position").text(data.onest_position);
        $("#2nd_approver").text(data.twond_approver);
        $("#2nd_position").text(data.twond_position);

    }


    function Show_data(approve_id) {
        console.log("รหัสการขอ : " + approve_id);
        approve_id_foraccept = approve_id;

        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Get_data_request.php",
            method: "POST",
            data: {
                approve_id: approve_id,
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopup(response.data);
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
        $("#Modal").modal("show");
    }

    var approve_id_foraccept;

    function ConfirmAccept() {
        Swal.fire({
            title: 'ยืนยันอนุมัติ',
            text: 'คุณแน่ใจหรือไม่ที่ต้องการที่จะอนุมัติคำขอการซ่อมนี้?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Accept_Data12();
            }
        });
    }

    function Accept_Data12() {
        var approve_id = approve_id_foraccept;
        console.log("รหัสอนุมัติคำขอ : " + approve_id);

        var dean_id = <?php echo $_SESSION['id']; ?>;
        console.log("รหัสคณะบดี : " + dean_id);


        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Accept_request_dean.php",
            method: 'POST',
            data: {
                approve_id: approve_id,
                dean_id: dean_id
            },
            success: function(response) {
                console.log(response);
                sendLineNotify_to_repairman(approve_id);
                sendLineNotify_to_Officer(approve_id);
                if (response.trim() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        text: 'ทำการอนุมัติคำขอการซ่อมเสร็จสมบูรณ์',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            error: function(xhr, status, error) {
                // กรณีเกิดข้อผิดพลาดใน AJAX
                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                });
            }
        });
    }



    function sendLineNotify_to_repairman(approve_id) {
        console.log("approve_id สำหรับดึงค่า : " + approve_id);
        $.ajax({
            url: '/project/AJAX/Officer_AJAX/Get_Repairman_token_request.php',
            method: 'POST',
            data: {
                approve_id: approve_id
            },
            dataType: 'json',
            success: function(data) {
                console.log(data.lineTokens);

                const lineTokens = data.lineTokens;
                const message =
                    `รายการขอเบิกอุปกรณ์ของคุณได้รับการอนุมัติจากคณะบดีแล้ว 2/2 อนุมัติวันที่ ${data.date_approve}`;

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

    function sendLineNotify_to_Officer(approve_id) {
        console.log("approve_id สำหรับดึงค่า : " + approve_id);
        $.ajax({
            url: '/project/AJAX/Officer_AJAX/Get_Officer_token_request.php',
            method: 'POST',
            data: {
                approve_id: approve_id
            },
            dataType: 'json',
            success: function(data) {
                console.log(data.lineTokens);

                const lineTokens = data.lineTokens;
                const message =
                    `รายการขอเบิกอุปกรณ์ได้รับการอนุมัติจากคณะบดีแล้ว อนุมัติวันที่ ${data.date_approve}`;


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

    function ConfirmNoAccept() {
        Swal.fire({
            title: 'ยืนยันการไม่อนุมัติ',
            text: 'คุณแน่ใจหรือไม่ที่ต้องการที่จะไม่อนุมัติคำขอการซ่อมนี้?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                Accept_NOData();
            }
        });
    }

    function Accept_NOData() {
        var approve_id = approve_id_foraccept;
        console.log("รหัสไม่อนุมัติคำขอ : " + approve_id);

        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/No_accept_Request_dean.php",
            method: 'POST',
            data: {

                approve_id: approve_id
            },
            success: function(response) {
                console.log(response);
                if (response.trim() === "success") {
                    // แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        text: 'คุณไม่อนุมัติคำขอการซ่อม',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            error: function(xhr, status, error) {
                // กรณีเกิดข้อผิดพลาดใน AJAX
                console.error(error);

                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                });
            }
        });
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
                                    <th style="width: 19%">ช่างซ่อมที่แจ้ง</th>
                                    <th>สถานะ</th>
                                    <th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            require_once("../../Database/db.php");

                            $sql = "SELECT Approve_Outside_repairman.date_approve,Approve_Outside_repairman.status,Approve_Outside_repairman.approve_o_id, Statuss.status_name ,Approve_Outside_repairman.details, Approve_Outside_repairman.date, Repairman.repairman_name
                                    FROM Approve_Outside_repairman
                                    JOIN Repairman ON Approve_Outside_repairman.repairman_id = Repairman.repairman_id
                                    JOIN Statuss ON Approve_Outside_repairman.status = Statuss.status_id
                                    WHERE Status IN (9,10,11)
                                    ORDER BY approve_o_id DESC";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $num_rows = 1;
                            if (count($result) > 0) {
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $num_rows . "</td>";
                                    echo "<td>" .  $row["details"] . "</td>";
                                    echo "<td>" . $row["date"] . "</td>";
                                    echo "<td>" . $row["repairman_name"] . "</td>";
                                    echo "<td>" . $row["status_name"] . "</td>";

                                    if ($row['status'] === '9' || $row['status'] === '10' ) {
                                        // ถ้า status_id เป็น 9, 10, 11 ให้แสดงค่าจาก date_approve แทน
                                            echo "<td>" . $row["date_approve"] . "</td>";
                                            echo '<td><i class="fa fa-info-circle" style="color: gray" onclick="Show_data_complete1(' . $row['approve_o_id'] . ')"></i></td>';

                                    } else {
                                        // ถ้า status_id ไม่ใช่ 9, 10, 11 ให้แสดงปุ่ม "ดำเนินการ"
                                        echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' href='javascript:void(0);' onclick='Show_data1(" . $row['approve_o_id'] . ");'>ดำเนินการ</a></td>";
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  ------------------------------------------------ ปุ่มเพิ่มเติม ------------------------------------------------     -->

    <div class="modal fade" id="Modal123" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดขอช่างภายนอก</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="repairman_name11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="details11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="date11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;">ผู้อนุมัติคนที่ 1</span> </h5>
                                <div class="waiting-box" id="1st_approver11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;">ตำแหน่ง : ผู้อนุมัติคนที่ 1</span>
                                </h5>
                                <div class="waiting-box" id="1st_position11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;">ผู้อนุมัติคนที่ 2</span> </h5>
                                <div class="waiting-box" id="2nd_approver11"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: green;">ตำแหน่ง : ผู้อนุมัติคนที่ 2</span>
                                </h5>
                                <div class="waiting-box" id="2nd_position11"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <h5>ใบเสนอราคา</h5>
                                        <div class="image-box">
                                            <iframe id="file" src="../../Files/quotation/" width="730" height="500"
                                                frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save" class="btn btn-danger"
                        onclick="ConfirmNoAccept1()">ไม่อนุมัติ</button>
                    <button type="button" id="save" class="btn btn-success" onclick="ConfirmAccept1()">อนุมัติ</button>
                </div>
            </div>
        </div>
    </div>

    <!--  ------------------------------------------------ รายละเอียด ------------------------------------------------     -->

    <div class="modal fade" id="Modal_complete12345" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                    <h5>คุณได้ทำการ</h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="repairman_name12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="details12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่ยื่นเรื่อง</h5>
                                <div class="waiting-box" id="date12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 1</h5>
                                <div class="waiting-box" id="onest_approver12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง :
                                    ผู้อนุมัติคนที่ 1
                                </h5>
                                <div class="waiting-box" id="onest_position12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ผู้อนุมัติคนที่ 2</h5>
                                <div class="waiting-box" id="twond_approver12345"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5 style="color: black;"><span style="color: red;">*</span> ตำแหน่ง :
                                    ผู้อนุมัติคนที่ 2
                                </h5>
                                <div class="waiting-box" id="twond_position12345"></div>
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




</body>
<br>
<br><br><br>
<?php include '../../Footer/footer.php'; ?>
</body>
<script>
$(document).ready(function() {
    $("#close_button1").click(function() {
        $("#Modal_complete12345").modal('hide');
    });

});

function displayStatus11(status) {
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

function showPopup_complete11(data) {
    $("#repairman_name12345").text(data.repairman_name1);
    $("#details12345").text(data.details1);
    $("#date12345").text(data.date1);
    $("#onest_approver12345").text(data.onest_approver1);
    $("#onest_position12345").text(data.onest_position2);
    $("#twond_approver12345").text(data.twond_approver3);
    $("#twond_position12345").text(data.twond_position4);
    $("#file1").attr("src", "../../Files/quotation/" + data.file1);


    $("#status12").text(data.status);
    displayStatus11(data.status);

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
                showPopup_complete11(response.data);
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
    $("#Modal_complete12345").modal("show");
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




function showPopup1(data) {
    $("#repairman_name11").text(data.repairman_name1);
    $("#details11").text(data.details1);
    $("#date11").text(data.date1);
    $("#file").attr("src", "../../Files/quotation/" + data.file1);
    $("#1st_approver11").text(data.onest_approver1);
    $("#1st_position11").text(data.onest_position2);
    $("#2nd_approver11").text(data.twond_approver3);
    $("#2nd_position11").text(data.twond_position4);

}

function Show_data1(approve_o_id) {
    console.log("รหัสการขอ : " + approve_o_id);
    approve_o_id_for_accept = approve_o_id;

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_data_request_outside.php",
        method: "POST",
        data: {
            approve_o_id: approve_o_id,
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopup1(response.data);
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
    $("#Modal123").modal("show");
}
var approve_o_id_for_accept;

function ConfirmAccept1() {
    Swal.fire({
        title: 'ยืนยันอนุมัติ',
        text: 'คุณแน่ใจหรือไม่ที่ต้องการที่จะอนุมัติคำขอการซ่อมนี้?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            Accept_Data();
        }
    });
}

function Accept_Data() {
    var approve_o_id = approve_o_id_for_accept
    console.log("รหัสอนุมัติคำขอ : " + approve_o_id);
    var dean_id = <?php echo $_SESSION['id']; ?>;
    console.log("รหัสคณะบดี : " + dean_id);


    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Accept_Outside_dean.php",
        method: 'POST',
        data: {

            approve_o_id: approve_o_id,
            dean_id: dean_id
        },
        success: function(response) {
            console.log(response);
            sendLineNotify_to_repairman12(approve_o_id);
            sendLineNotify_to_Officer12(approve_o_id);
            if (response.trim() === "success") {
                // แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    text: 'ทำการอนุมัติคำขอการซ่อมเสร็จสมบูรณ์',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                });
            }
        },
        error: function(xhr, status, error) {
            // กรณีเกิดข้อผิดพลาดใน AJAX
            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            });
        }
    });
}

function sendLineNotify_to_repairman12(approve_o_id) {
    console.log("approve_id สำหรับดึงค่า : " + approve_o_id);
    $.ajax({
        url: '/project/AJAX/Officer_AJAX/Get_Repairman_token_outside.php',
        method: 'POST',
        data: {
            approve_o_id: approve_o_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens);

            const lineTokens = data.lineTokens;
            const message =
                `รายการขอใช้ช่างภายนอกของคุณได้รับการอนุมัติจากคณะบดีแล้ว 2/2 อนุมัติวันที่ ${data.date_approve}`;

            sendLineMessage12(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}

function sendLineNotify_to_Officer12(approve_o_id) {
    console.log("approve_o_id สำหรับดึงค่า : " + approve_o_id);
    $.ajax({
        url: '/project/AJAX/Officer_AJAX/Get_Officer_token_outside.php',
        method: 'POST',
        data: {
            approve_o_id: approve_o_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens);

            const lineTokens = data.lineTokens;
            const message =
                `รายการใช้ช่างภายนอกได้รับการอนุมัติจากคณะบดีแล้ว อนุมัติวันที่ ${data.date_approve}`;


            sendLineMessage12(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}

 function sendLineMessage12(lineTokens, message) {
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





function ConfirmNoAccept1() {
    Swal.fire({
        title: 'ยืนยันการไม่อนุมัติ',
        text: 'คุณแน่ใจหรือไม่ที่ต้องการที่จะไม่อนุมัติคำขอการซ่อมนี้?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            NOAccept_Data();
        }
    });
}

function NOAccept_Data() {
    var approve_o_id = approve_o_id_for_accept
    console.log("รหัสอนุมัติคำขอ : " + approve_o_id);

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/No_accept_Outside_dean.php",
        method: 'POST',
        data: {

            approve_o_id: approve_o_id
        },
        success: function(response) {
            console.log(response);
            if (response.trim() === "success") {
                // แสดงข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    text: 'คุณไม่อนุมัติคำขอช่างภายนอก',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ตกลง'
                });
            }
        },
        error: function(xhr, status, error) {
            // กรณีเกิดข้อผิดพลาดใน AJAX
            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ตกลง'
            });
        }
    });
}
</script>

</html>