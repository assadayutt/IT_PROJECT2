<?php
require_once("../../Database/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>รายการที่รับงาน</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../CSS/Repairman_working.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php'; ?>
    <style>
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
<script>
$(document).ready(function() {

    $('#modal_extend_repair_date').on('hidden.bs.modal', function() {
        // รีเซ็ตค่าในฟอร์มเมื่อปิดโมเดล
        $('#datepicker').val('');
        $('#message').val('');
    });

    $('#save_modal_extend_repair_date').click(function() {
        // ดึงค่าวันที่และข้อความจากฟอร์ม
        var selectedDate = $('#datepicker').val();
        var message = $('#message').val();

        // เรียกใช้งานฟังก์ชันเพื่อบันทึกข้อมูล
        save_message_and_date(selectedDate, message);
    });


    // เพิ่ม Event Listener เมื่อปุ่มปิดของ popup ถูกคลิก
    $("#closePopupButton").click(function() {
        // ปิด popup ที่มี ID "exampleModalCenter"
        $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
        $("#profileImage").val("");
        $("#previewImage").val("");
        $("#exampleModal").modal("hide");

    });

    $("#show_equipment_details").click(function() {
        $("#exampleModal").modal("hide");

    });

    

    $("#close_details_equipment").click(function() {
        $("#exampleModal").modal("show");

    });


    $(".button").click(function() {
        var repairId = $(this).attr("data-repair-id");
        var equipment_id = $(this).attr("data-equipment-id");

        repair_id_for_sentwork = repairId;
        equipment_id_for_detail = equipment_id;


        console.log("รหัสครุภัณฑ์ : " + repairId);
        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/Get_Equipment_repair.php",
            method: "POST",
            data: {
                repair_id: repairId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopupp(response.data);
                    console.log(response.status);

                } else {
                    console.log(response.message);
                    Swal.fire({
                        title: "Error!",
                        text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " +
                            response.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            },
        });
    });


    function showPopupp(data) {
        console.log(data);
        // ใส่ข้อมูลใน <span> โดยเรียกตาม ID ของแต่ละ <span>
        $("#equipment_number").text(data.equipment_number);
        $("#user_name").text(data.user_name);
        $("#repair_detail").text(data.repair_detail);
        $("#repair_date").text(data.repair_date);
        $("#repair_imagesbefor_display").attr("src", data.repair_imagesbefor);


        // สั่งเปิด Modal
        $("#exampleModal").modal("show");
    }


    $('.submit').on('click', function() {
        var repair_id = $(this).data('repair_id');
        saveData(repair_id);
    });

    $("#btnExtendRepairDate").click(function() {
        $("#modal_extend_repair_date").modal("show");
        $("#exampleModal").modal("hide");
    });

    $("#modal_extend_repair_date").on("hidden.bs.modal", function() {
        $("#exampleModal").modal("show");
    });

    $("#close_modal_extend_repair_date").click(function() {
        $("#modal_extend_repair_date").modal("hide");
    });


});

function showPreview() {
    var fileInput = document.getElementById('profileImage');
    var previewImage = document.getElementById('previewImage');
    var fileNameElement = document.getElementById('profileImageLabel');

    if (fileInput.files && fileInput.files[0]) {
        var fileName = fileInput.files[0].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
        }

        reader.readAsDataURL(fileInput.files[0]);
        fileNameElement.innerHTML = fileName;

        return fileName; // Return the fileName from showPreview
    } else {
        previewImage.src = '/Images/blank-image.jpeg';
        fileNameElement.innerHTML = 'เลือกไฟล์รูปภาพ';

        return ''; // Return an empty string when no file selected
    }
}

var repair_id_for_sentwork;

function saveData(repair_id) {
    var repair_id = repair_id_for_sentwork;
    var equipment_id = equipment_id_for_detail;
    var formData = new FormData();

    // ดึงไฟล์รูปภาพจาก input[type="file"]
    var image_after = document.querySelector('input[type="file"]').files[0];

    // ใส่ข้อมูลเข้า FormData
    formData.append('image_after', image_after);
    formData.append('repair_id', repair_id);
    formData.append('equipment_id', equipment_id);


    console.log("รหัสงานซ่อม " + repair_id);
    console.log("รูปภาพหลังซ่อม " + image_after);
    console.log("รหัสครุภัณฑ์ " + equipment_id);

    if (!image_after) {
        Swal.fire({
            title: "Error!",
            text: "กรุณาอัพโหลดรูปภาพ",
            icon: "error",
            confirmButtonText: "OK"
        });
    } else {
        // บันทึกลงฐานข้อมูล
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/Repairman_AJAX/Sent_Work_equipment.php",
            data: formData,
            processData: false, // ไม่ต้องประมวลผลข้อมูล
            contentType: false, // ไม่ต้องตั้งค่า Content-Type
            success: function(response) {
                sendLineNotify(repair_id)
                Swal.fire({
                    title: "Success!",
                    text: "ส่งงานสำเร็จ",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload(); // รีเฟรชหน้าเว็บ
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "error" + error,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    }
}


function sendLineNotify(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_sent_work_Equipment.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ครุภัณฑ์หมายเลข : ${data.equipment_number}, รายละเอียด: ${data.repair_detail}, แจ้งซ่อมเมื่อวันที่: ${data.completionDate},  ได้ซ่อมเสร็จแล้ว โดยช่าง: ${data.session_id}, วันที่เสร็จ: ${data.datecomplete} โปรดทำการให้คะแนนการซ่อมในระบบแจ้งซ่อม - IMS`;

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

function save_message_and_date(date, message) {
    var repair_id = repair_id_for_sentwork;

    console.log("รหัสงานซ่อมบันทึกข้อความ " + repair_id);
    console.log('Date:', date);
    console.log('Message:', message);


    // บันทึกลงฐานข้อมูล
    $.ajax({
        type: "POST",
        url: location.origin + "/project/AJAX/Repairman_AJAX/extend_repair_date.php",
        data: {
            repair_id: repair_id,
            date: date,
            message: message

        },
        success: function(response) {
            sendLineNotify3(repair_id);
            Swal.fire({
                title: "Success!",
                text: "ขยายวันซ่อมสำเร็จ",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload(); // รีเฟรชหน้าเว็บ
                }
            });
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Error!",
                text: "error" + error,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    });
}

function sendLineNotify3(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_message_from_repairman_Equipment.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ช่างได้ขยายวันซ่อมครุภัณฑ์หมายเลข : ${data.equipment_number} เป็นวันที่ : ${data.assign_datecomp} และมีข้อความจากช่าง : ${data.message_work}  ข้อความโดย : ${data.repairman_name} +++โปรดอดทนรอ+++!!!`;

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


function repairman_outside_repair() {
    var repair_id = repair_id_for_sentwork;
    console.log("รหัสงานซ่อมรอช่างภายนอก " + repair_id);

    Swal.fire({
        title: "ยืนยันการเปลี่ยนสถานะ",
        text: "คุณต้องการเปลี่ยนสถานะเป็นรอช่างภายนอกใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ใช่",
        cancelButtonText: "ไม่ใช่"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Repairman_AJAX/repairman_outside_repair.php",
                data: {
                    repair_id: repair_id,
                },
                success: function(response) {
                    sendLineNotify6(repair_id);
                    Swal.fire({
                        title: "Success!",
                        text: "เปลี่ยนสถานะเป็นรอช่างภายนอกสำเร็จ",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // รีเฟรชหน้าเว็บ
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "error" + error,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
}


function sendLineNotify6(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_outside_repair.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `การแจ้งซ่อมครุภัณฑ์ : ${data.equipment_name}, หมายเลข: ${data.equipment_number} จำเป็นต้องใช้ช่างจากภายนอกคณะ โปรดอดทนรอ!!`;

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

function waiting_for_spare_parts_repair() {
    var repair_id = repair_id_for_sentwork;
    console.log("รหัสงานซ่อมรออะไหล่ครุภัณฑ์ " + repair_id);

    Swal.fire({
        title: "ยืนยันการเปลี่ยนสถานะ",
        text: "คุณต้องการเปลี่ยนสถานะเป็นรออะไหล่ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ใช่",
        cancelButtonText: "ไม่ใช่"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: location.origin +
                    "/project/AJAX/Repairman_AJAX/Set_waiting_spare_parts_repair.php",
                data: {
                    repair_id: repair_id,
                },
                success: function(response) {
                    sendLineNotify7(repair_id);
                    Swal.fire({
                        title: "Success!",
                        text: "เปลี่ยนสถานะเป็นรออะไหล่สำเร็จ",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload(); // รีเฟรชหน้าเว็บ
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "error" + error,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
}


function sendLineNotify7(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_watting_repair.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `การแจ้งซ่อมครุภัณฑ์ : ${data.equipment_name}, หมายเลข: ${data.equipment_number} จำเป็นต้องอ่ะไหล่ โปรดอดทนรอ!!`;

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
var equipment_id_for_detail;

function show_equipment_details() {
    var equipment_id = equipment_id_for_detail;

    console.log("equipment_id = " + equipment_id);

    $.ajax({
        url: location.origin + "/project/AJAX/Repairman_AJAX/Get_Equipment_details.php",
        method: "POST",
        data: {
            equipment_id: equipment_id
        },
        dataType: "json",
        success: function(response) {
            // เพิ่มการเช็คค่า response ที่ได้รับกลับมา
            if (response) {
                // กรณีได้รับค่า response จาก List_Repair_get_data_Equipment.php
                if (response.status === "success") {
                    showPopupp_details(response.data);
                    console.log("มีข้อมูลส่งกลับมา");
                } else {
                    console.log(response.message);
                    Swal.fire({
                        title: "Error!",
                        text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            } else {
                // กรณีไม่ได้รับค่า response จาก List_Repair_get_data_Equipment.php
                console.log("ไม่สามารถรับค่า response จาก List_Repair_get_data_Equipment.php ได้");
                Swal.fire({
                    title: "Error!",
                    text: "ไม่สามารถรับค่า response จากเซิร์ฟเวอร์ได้",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            // กรณีเกิดข้อผิดพลาดในการส่ง request ไปยังเซิร์ฟเวอร์
            console.log("เกิดข้อผิดพลาดในการส่ง request: " + textStatus);
            Swal.fire({
                title: "Error!",
                text: "เกิดข้อผิดพลาดในการส่งคำขอไปยังเซิร์ฟเวอร์",
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    });

}

function showPopupp_details(data) {
    console.log(data);
    // ใส่ข้อมูลใน <span> โดยเรียกตาม ID ของแต่ละ <span>
    $("#equipment_number1").text(data.equipment_number1);
    $("#name_equipment").text(data.name_equipment);
    $("#brand_equipment").text(data.brand_equipment);
    $("#model_equipment").text(data.model_equipment);
    $("#color_equipment").text(data.color_equipment);
    $("#date_add_equipment").text(data.date_add_equipment);
    $("#Serial_number").text(data.Serial_number);
    $("#status_equipment").text(data.status_equipment);
    $("#price_equipment").text(data.price_equipment);
    $("#date_exp_equipment").text(data.date_exp_equipment);
    $("#owner_equipment").text(data.owner_equipment);
    $("#count_repair_equipment").text(data.count_repair_equipment);
    $("#address_equipment").text(data.address_equipment);
    $("#details_equipment").text(data.details_equipment);



    $("#modal_details_equipment").modal("show");


}
</script>

<body>
   

    <div class="container mt-4">
        <h2>รายการรับงานซ่อมครุภัณฑ์</h2>
        <div class="table-responsive">
            <?php
$repairman_id = $_SESSION['id'];

$equipmentRepairSql = "SELECT Equipment.equipment_id, Equipment_repair.repair_id, Equipment_repair.status_id, User.user_name, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
    FROM Equipment_repair
    JOIN Equipment ON Equipment_repair.equipment_id = Equipment.equipment_id
    JOIN User ON Equipment_repair.user_id = User.user_id
    JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
    JOIN Repairman ON Equipment_repair.repairman_id = Repairman.repairman_id
    WHERE Statuss.status_id IN (2, 6, 7) AND Repairman.repairman_id = :repairman_id";


$equipmentRepairStmt = $conn->prepare($equipmentRepairSql);
$equipmentRepairStmt->bindValue(':repairman_id', $repairman_id, PDO::PARAM_INT);
$equipmentRepairStmt->execute();

$equipmentRepairLimit = 10;
$equipmentRepairTotalRows = $equipmentRepairStmt->rowCount();
$equipmentRepairTotalPages = ceil($equipmentRepairTotalRows / $equipmentRepairLimit);

if (isset($_GET['equipment_page'])) {
    $equipmentRepairPage = $_GET['equipment_page'];
} else {
    $equipmentRepairPage = 1;
}

$equipmentRepairStart = ($equipmentRepairPage - 1) * $equipmentRepairLimit;
$equipmentRepairSql .= " LIMIT $equipmentRepairStart, $equipmentRepairLimit";

$equipmentRepairStmt = $conn->prepare($equipmentRepairSql);
$equipmentRepairStmt->bindValue(':repairman_id', $repairman_id, PDO::PARAM_INT);
$equipmentRepairStmt->execute();

if ($equipmentRepairStmt->rowCount() > 0) {
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
    while ($equipmentRepairRow = $equipmentRepairStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $equipmentRepairNumRows . "</td>";
        echo "<td>" . wordwrap($equipmentRepairRow["equipment_number"], 10, "<br>", true) . "</td>";

        echo "<td>" . $equipmentRepairRow["repair_detail"] . "</td>";
        echo "<td>" . $equipmentRepairRow["repair_date"] . "</td>";
        echo "<td>" . $equipmentRepairRow["user_name"] . "</td>";

        echo "<td><a class='button' style='text-decoration: none; color: white; margin:7px;' data-repair-id='" . $equipmentRepairRow['repair_id'] . "' data-equipment-id='" . $equipmentRepairRow['equipment_id'] . "' data-toggle='modal' data-target='#exampleModal' href='javascript:void(0);'><i class='fa fa-send'></i> ส่งงาน</a></td>";

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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">ส่งงานซ่อมครุภัณฑ์</h4>
                    <button type="button" class="close" id="closePopupButton" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>หมายเลขครุภัณฑ์</h5>
                                <div class="waiting-box" id="equipment_number"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ชื่อผู้แจ้งซ่อม</h5>
                                <div class="waiting-box" id="user_name"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียด</h5>
                                <div class="waiting-box" id="repair_detail"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่แจ้งซ่อม</h5>
                                <div class="waiting-box" id="repair_date"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5>อัพโหลดรูปภาพ</h5>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profileImage"
                                            name="profileImage" onchange="showPreview()">

                                        <label class="custom-file-label" for="profileImage"
                                            id="profileImageLabel">เลือกไฟล์รูปภาพ</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mt-2">

                                    <h5>BEFORE</h5>
                                    <div class="image-container">
                                        <img class="preview-image rounded" id="repair_imagesbefor_display"
                                            src="../../Images/blank-image.jpeg"
                                            style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                            alt="รูปภาพ">

                                        <br><br>
                                        <h5>AFTER</h5>
                                        <div class="image-container">
                                            <img class="preview-image rounded" id="previewImage"
                                                src="../../Images/blank-image.jpeg"
                                                style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                                alt="รูปภาพ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="show_equipment_details"
                        onclick="show_equipment_details()">
                        <i class="fas fa-info-circle"></i> รายละเอียดครุภัณฑ์
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="repairman_outside_repair()">
                        <i class="fas fa-clock"></i> รอช่างภายนอก
                    </button>

                    <button type="button" class="btn btn-warning" onclick="waiting_for_spare_parts_repair()">
                        <i class="fas fa-wrench"></i> รออะไหล่
                    </button>

                    <button type="button" class="btn btn-secondary" id="btnExtendRepairDate">
                        <i class="fas fa-calendar-plus"></i> ขยายวันซ่อม
                    </button>

                    <button type="submit" class="btn btn-success submit">
                        <i class="fas fa-check"></i> ส่งงาน
                    </button>


                </div>
            </div>
        </div>
    </div>

    <!--  ----------------------------------------------------------  modal ขยายวันซ่อม ---------------------------------------------------------- -->

    <div class="modal fade" id="modal_extend_repair_date" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">ขยายวันซ่อม</h5>

                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="datepicker">เลือกวันที่:</label>
                        <input type="date" class="form-control" id="datepicker">
                    </div>
                    <div class="form-group">
                        <label for="message">ข้อความ:</label>
                        <textarea class="form-control" id="message" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_modal_extend_repair_date" class="btn btn-danger"
                        data-dismiss="modal">ยกเลิก</button>
                    <button type="button" id="save_modal_extend_repair_date" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <!--  ----------------------------------------------------------  รายละเอียดครุภัณฑ์ ---------------------------------------------------------- -->

    <div class="modal fade" id="modal_details_equipment" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">รายละเอียดครุภัณฑ์</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="col-md-12 mb-4">
                                <h5>หมายเลขครุภัณฑ์</h5>
                                <div class="waiting-box" id="equipment_number1"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ชื่อครุภัณฑ์</h5>
                                <div class="waiting-box" id="name_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ยี่ห้อครุภัณฑ์</h5>
                                <div class="waiting-box" id="brand_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รุ่นครุภัณฑ์</h5>
                                <div class="waiting-box" id="model_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>สีครุภัณฑ์</h5>
                                <div class="waiting-box" id="color_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่เพิ่มเข้าระบบ</h5>
                                <div class="waiting-box" id="date_add_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>Serial Number</h5>
                                <div class="waiting-box" id="Serial_number"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>สถานะครุภัณฑ์</h5>
                                <div class="waiting-box" id="status_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ราคาต่อหน่วย</h5>
                                <div class="waiting-box" id="price_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันหมดประกัน</h5>
                                <div class="waiting-box" id="date_exp_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ผู้ครอบครอง</h5>
                                <div class="waiting-box" id="owner_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>จำนวนครั้งที่ซ่อม</h5>
                                <div class="waiting-box" id="count_repair_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ที่อยู่ครุภัณฑ์</h5>
                                <div class="waiting-box" id="address_equipment"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียดต่าง ๆ</h5>
                                <div class="waiting-box" id="details_equipment"></div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-close" id="close_details_equipment"
                        data-bs-dismiss="modal" aria-label="Close_details">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>



    <!--  ----------------------------------------------------------  แจ้งซ่อมพื้นที่ ---------------------------------------------------------- -->
    <!--  ----------------------------------------------------------  แจ้งซ่อมพื้นที่ ---------------------------------------------------------- -->

    <script>
    $(document).ready(function() {

        $('#modal_extend_area_date').on('hidden.bs.modal', function() {
            // รีเซ็ตค่าในฟอร์มเมื่อปิดโมเดล
            $('#datepicker').val('');
            $('#message').val('');
        });

        $('#save_modal_extend_area_date').click(function() {
            // ดึงค่าวันที่และข้อความจากฟอร์ม
            var selectedDate1 = $('#datepicker1').val();
            var message1 = $('#message1').val();

            // เรียกใช้งานฟังก์ชันเพื่อบันทึกข้อมูล
            save_message_and_date1(selectedDate1, message1);
        });

        $("#closePopupButton1").click(function() {
            // ปิด popup ที่มี ID "exampleModalCenter"
            $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
            $("#profileImage").val("");
            $("#previewImage").val("");
            $("#exampleModallll").modal("hide");

        });

        $(".button1").click(function() {
            var area_id = $(this).attr("data-area-id");
            area_id_for_sentwork = area_id;

            console.log("รหัสการซ่อมพื้นที่ : " + area_id);
            $.ajax({
                url: location.origin + "/project/AJAX/Repairman_AJAX/Get_Area_repair.php",
                method: "POST",
                data: {
                    area_id: area_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        showPopup(response.data);
                        console.log(response.status);

                    } else {
                        console.log(response.message);
                        Swal.fire({
                            title: "Error!",
                            text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " +
                                response.message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                },
            });
        });

        function showPopup(data) {
            console.log(data);
            // ใส่ข้อมูลใน <span> โดยเรียกตาม ID ของแต่ละ <span>
            $("#area_detail").text(data.area_detail);
            $("#area_problem").text(data.area_problem);
            $("#user_namee").text(data.user_name);
            $("#area_date").text(data.area_date);
            $("#area_address").text(data.area_address);
            $("#area_imagesbefor").attr("src", "../../Images/Repair_Address/" + data.area_imagesbefor);

            console.log(data.area_imagesbefor)
            // สั่งเปิด Modal
            $("#exampleModallll").modal("show");
        }


        $('.submit1').on('click', function() {
            var area_id = $(this).data('repair_id');
            saveDataa(area_id);
        });



        $("#btnExtendareaDate").click(function() {
            $("#modal_extend_area_date").modal("show");
            $("#exampleModallll").modal("hide");
        });

        $("#modal_extend_area_date").on("hidden.bs.modal", function() {
            $("#exampleModallll").modal("show");
        });

        $("#close_modal_extend_area_date").click(function() {
            $("#modal_extend_area_date").modal("hide");
        });


    });

    function showPrevieww() {
        var fileInput = document.getElementById('profileImage1');
        var previewImage = document.getElementById('previewImage1');
        var fileNameElement = document.getElementById('profileImageLabel1');

        if (fileInput.files && fileInput.files[0]) {
            var fileName = fileInput.files[0].name;
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            }

            reader.readAsDataURL(fileInput.files[0]);
            fileNameElement.innerText = fileName; // ใช้ innerText แทน innerHTML
            return fileName;
        } else {
            previewImage.src = '../../Images/blank-image.jpeg';
            fileNameElement.innerText = 'เลือกไฟล์รูปภาพ'; // ใช้ innerText แทน innerHTML
            return '';
        }
    }



    var area_id_for_sentwork;

    function saveDataa(area_id) {
        var area_id = area_id_for_sentwork;
        var selectedImage = document.querySelector('#profileImage1').files[0];

        console.log("รหัสงานซ่อม " + area_id);
        console.log("รูปภาพหลังซ่อม " + selectedImage);

        if (!selectedImage) {
            Swal.fire({
                title: "Error!",
                text: "กรุณาอัพโหลดรูปภาพ",
                icon: "error",
                confirmButtonText: "OK"
            });
        } else {
            var formData = new FormData();
            formData.append('image_after', selectedImage);
            formData.append('area_id', area_id);

            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Repairman_AJAX/Sent_Work_area.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    sendLineNotify5(area_id);
                    Swal.fire({
                        title: "Success!",
                        text: "ส่งงานสำเร็จ",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: "error" + error,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    }


function sendLineNotify5(area_id) {
    console.log("repair_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_sent_work_area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `การแจ้งซ่อมพื้นที่ : ${data.area_detail}, ปัญหาที่พบ: ${data.area_problem}, บริเวณ: ${data.area_address},แจ้งซ่อมเมื่อวันที่: ${data.completionDate},  ได้ซ่อมเสร็จแล้ว โดยช่าง: ${data.session_id}, วันที่เสร็จ: ${data.date_complete} โปรดทำการให้คะแนนการซ่อมในระบบแจ้งซ่อม - IMS`;

            sendLineMessage5(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}


 function sendLineMessage5(lineTokens, message) {
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

    function save_message_and_date1(date, message) {
        var area_id = area_id_for_sentwork;

        console.log("รหัสงานซ่อมบันทึกข้อความ " + area_id);
        console.log('Date:', date);
        console.log('Message:', message);


        // บันทึกลงฐานข้อมูล
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/Repairman_AJAX/extend_area_date.php",
            data: {
                area_id: area_id,
                date: date,
                message: message

            },
            success: function(response) {
                sendLineNotify5(area_id)
                Swal.fire({
                    title: "Success!",
                    text: "ขยายวันซ่อมสำเร็จ",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload(); // รีเฟรชหน้าเว็บ
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "error" + error,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    }
    function sendLineNotify5(area_id) {
    console.log("repair_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_message_from_repairman_Area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ช่างได้ขยายวันที่คาดว่าจะเสร็จเป็นวันที่ : ${data.assign_datecomp} สำหรับการแจ้งซ่อม ${data.area_detail} ที่มัปัญหา : ${data.area_problem} โดยมีข้อความจากช่าง : ${data.message_work} ข้อความโดย : ${data.repairman_name} +++โปรดอดทนรอ+++!!!`;

            sendLineMessage5(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}

    function repairman_outside_area() {
        var area_id = area_id_for_sentwork;
        console.log("รหัสงานซ่อมรอช่างภายนอก " + area_id);

        Swal.fire({
            title: "ยืนยันการเปลี่ยนสถานะ",
            text: "คุณต้องการเปลี่ยนสถานะเป็นรอช่างภายนอกใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ใช่",
            cancelButtonText: "ไม่ใช่"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: location.origin + "/project/AJAX/Repairman_AJAX/repairman_outside_area.php",
                    data: {
                        area_id: area_id,
                    },
                    success: function(response) {
                        sendLineNotify10(area_id);
                        Swal.fire({
                            title: "Success!",
                            text: "เปลี่ยนสถานะเป็นรอช่างภายนอกสำเร็จ",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // รีเฟรชหน้าเว็บ
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: "error" + error,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    }

    function sendLineNotify10(area_id) {
    console.log("repair_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_outside_area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
            `การแจ้งซ่อมพื้นที่ : ${data.area_detail} ปัญหา : ${data.area_problem} บริเวณ : ${data.area_address} จำเป็นต้องใช้ช่างจากภายนอกคณะ โปรดอดทนรอ!!`;

            sendLineMessage5(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}

    function waiting_for_spare_parts_area() {
        var area_id = area_id_for_sentwork;
        console.log("รหัสงานซ่อมรออะไหล่ " + area_id);

        Swal.fire({
            title: "ยืนยันการเปลี่ยนสถานะ",
            text: "คุณต้องการเปลี่ยนสถานะเป็นรออะไหล่ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ใช่",
            cancelButtonText: "ไม่ใช่"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: location.origin +
                        "/project/AJAX/Repairman_AJAX/Set_waiting_spare_parts_area.php",
                    data: {
                        area_id: area_id,
                    },
                    success: function(response) {
                        sendLineNotify11(area_id);
                        Swal.fire({
                            title: "Success!",
                            text: "เปลี่ยนสถานะเป็นรออะไหล่สำเร็จ",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload(); // รีเฟรชหน้าเว็บ
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Error!",
                            text: "error" + error,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    }
    function sendLineNotify11(area_id) {
    console.log("repair_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_watting_area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
            `การแจ้งซ่อมพื้นที่ : ${data.area_detail} ปัญหา : ${data.area_problem} บริเวณ : ${data.area_address} จำเป็นต้องรออ่ะไหล่ โปรดอดทนรอ!!`;

            sendLineMessage5(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}
    </script>



    <div class="container mt-4">
        <h2>รายการรับงานแจ้งซ่อมพื้นที่</h2>
        <div class="table-responsive">
            <?php
// สร้าง SQL query สำหรับดึงข้อมูลรายการแจ้งซ่อมพื้นที่
$areaRepairSql = "SELECT Area_repair.repairman_id, Area_repair.area_id, Statuss.status_id, User.user_name, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
    FROM Area_repair
    JOIN User ON Area_repair.user_id = User.user_id
    JOIN Statuss ON Area_repair.Status_id = Statuss.status_id
    JOIN Repairman ON Area_repair.repairman_id = Repairman.repairman_id
    WHERE Statuss.status_id  IN (2, 6, 7)  AND Repairman.repairman_id = :repairman_id";

$areaRepairStmt = $conn->prepare($areaRepairSql);
$areaRepairStmt->bindValue(':repairman_id', $repairman_id, PDO::PARAM_INT);
$areaRepairStmt->execute();

$areaRepairLimit = 10;
$areaRepairTotalRows = $areaRepairStmt->rowCount();
$areaRepairTotalPages = ceil($areaRepairTotalRows / $areaRepairLimit);

if (isset($_GET['area_page'])) {
    $areaRepairPage = $_GET['area_page'];
} else {
    $areaRepairPage = 1;
}

$areaRepairStart = ($areaRepairPage - 1) * $areaRepairLimit;
$areaRepairSql .= " LIMIT $areaRepairStart, $areaRepairLimit";

$areaRepairStmt = $conn->prepare($areaRepairSql);
$areaRepairStmt->bindValue(':repairman_id', $repairman_id, PDO::PARAM_INT);
$areaRepairStmt->execute();

if ($areaRepairStmt->rowCount() > 0) {
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
    while ($areaRepairRow = $areaRepairStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $areaRepairNumRows . "</td>";
        echo "<td>" . $areaRepairRow["area_detail"] . "</td>";
        echo "<td>" . $areaRepairRow["area_problem"] . "</td>";
        echo "<td>" . $areaRepairRow["area_date"] . "</td>";
        echo "<td>" . $areaRepairRow["user_name"] . "</td>";

        echo "<td> <a class='button1' style='text-decoration: none; color: white; margin:7px;' data-area-id='" . $areaRepairRow['area_id'] . "' href='javascript:void(0);'>ส่งงาน</a></td>";

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
?>

        </div>
    </div>


    <div class="modal fade" id="exampleModallll" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">ส่งงานซ่อมพื้นที่</h4>
                    <button type="button" class="close" id="closePopupButton1" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 mb-4">
                                <h5>รายละเอียดการแจ้งซ่อม</h5>
                                <div class="waiting-box" id="area_detail"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ผู้แจ้งซ่อม</h5>
                                <div class="waiting-box" id="user_namee"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>ปัญหาที่พบ</h5>
                                <div class="waiting-box" id="area_problem"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>บริเวณที่พบ</h5>
                                <div class="waiting-box" id="area_address"></div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <h5>วันที่แจ้งซ่อม</h5>
                                <div class="waiting-box" id="area_date"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5>อัพโหลดรูปภาพ</h5>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profileImage1"
                                            name="profileImage1" onchange="showPrevieww()">
                                        <label class="custom-file-label" for="profileImageLabel1"
                                            id="profileImageLabel1">เลือกไฟล์รูปภาพ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mt-2">

                                    <h5>BEFORE</h5>
                                    <div class="image-container">
                                        <img class="preview-image rounded" id="area_imagesbefor"
                                            src="../../Images/blank-image.jpeg"
                                            style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                            alt="รูปภาพ">

                                        <br><br>
                                        <h5>AFTER</h5>
                                        <div class="image-container">
                                            <img class="preview-image rounded" id="previewImage1"
                                                src="../../Images/blank-image.jpeg"
                                                style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                                alt="รูปภาพ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-warning" onclick="repairman_outside_area()">
                        <i class="fas fa-clock"></i> รอช่างภายนอก
                    </button>


                    <button type="button" class="btn btn-warning" onclick="waiting_for_spare_parts_area()">
                        <i class="fas fa-wrench"></i> รออะไหล่
                    </button>

                    <button type="button" class="btn btn-secondary" id="btnExtendareaDate">
                        <i class="fas fa-calendar-plus"></i> ขยายวันซ่อม
                    </button>
                    <button type="submit1" class="btn btn-success submit1">
                        <i class="fas fa-check"></i> ส่งงาน
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--  ----------------------------------------------------------  modal ขยายวันซ่อม ---------------------------------------------------------- -->

    <div class="modal fade" id="modal_extend_area_date" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">ขยายวันซ่อม</h5>

                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="datepicker">เลือกวันที่:</label>
                        <input type="date" class="form-control" id="datepicker1">
                    </div>
                    <div class="form-group">
                        <label for="message">ข้อความ:</label>
                        <textarea class="form-control" id="message1" rows="4"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_modal_extend_area_date" class="btn btn-danger"
                        data-dismiss="modal">ยกเลิก</button>
                    <button type="button" id="save_modal_extend_area_date" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
        </div>
    </div>


    <br>
    <br>
    <br><br><br><br><br><br>

    <?php include '../../Footer/footer.php'; ?>
</body>

</html>