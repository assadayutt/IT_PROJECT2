<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>รายการซ่อม</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../CSS/Repairman_Listrepair.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



</head>
<script>
function showPopup1(data) {
    var popupContent = "<h2 class='mb-3'>รายละเอียดการแจ้งซ่อมครุภัณฑ์</h2>";
    popupContent += "<p><strong>ครุภัณฑ์ : </strong> " + data.equipment_number + "</p>";
    popupContent += "<p><strong>รายละเอียด :</strong> " + data.repair_detail + "</p>";
    popupContent += "<p><strong>วันที่แจ้งซ่อม :</strong> " + data.repair_date + "</p>";
    popupContent += "<p><strong>ผู้แจ้งซ่อม :</strong> " + data.user_name + "</p>";
    popupContent += "<img src='/project/Images/Repair_equipment/" + data.repair_imagesbefor +
        "' alt='รูปภาพก่อนซ่อม' class='popup-image' style='max-width: 100%; max-height: 300px;'>";


    $("#popup-content").html(popupContent);
    $("#popup1").modal('show');
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

$(document).ready(function() {

    $("#popup-close").click(function() {
        $("#popup1").modal('hide');
    });

    $("#show_equipment_details").click(function() {
        $("#popup1").modal("hide");
    });

    $("#close_details_equipment").click(function() {
        $("#modal_details_equipment").modal("hide");
        $("#popup1").modal("show");
    });


});

function GetEquipment_Repair(repair_id, equipment_id) {
    console.log(repair_id);
    selectedRepairId = repair_id;
    equipment_for_details = equipment_id;

    $.ajax({
        url: location.origin + "/project/AJAX/Repairman_AJAX/List_Repair_get_data_Equipment.php",
        method: "POST",
        data: {
            repair_id: repair_id
        },
        dataType: "json",
        success: function(response) {
            // เพิ่มการเช็คค่า response ที่ได้รับกลับมา
            if (response) {
                // กรณีได้รับค่า response จาก List_Repair_get_data_Equipment.php
                if (response.status === "success") {
                    showPopup1(response.data);
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

var selectedRepairId;

function Savedata(repair_id) {
    var completionDate = $("#completionDate").val();
    var repair_id = selectedRepairId;
    var session_id = $("#session_id").val();

    console.log("รหัสช่างซ่อม " + session_id);
    console.log("รหัสงานซ่อม " + repair_id);
    console.log("วันที่คาดว่าจะเสร็จ " + completionDate);

    if (completionDate === '') {
        Swal.fire({
            title: "Error!",
            text: "กรุณากรอกวันที่คาดว่าน่าจะเสร็จ",
            icon: "error",
            confirmButtonText: "OK"
        });
    } else {
        // ตรวจสอบจำนวนข้อมูลที่ตรงเงื่อนไขใน repair_Assign_work และ Equipment_repair
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/Repairman_AJAX/Check_Equipment_Work_Count.php",
            data: {
                repairman_id: session_id
            },
            success: function(response) {
                var workCount = parseInt(response);

                console.log("workCount : " + response); // จำนวนงานที่ตรงเงื่อนไข

                if (workCount >= 3) {
                    Swal.fire({
                        title: "ไม่สามารถรับงานได้",
                        text: "คุณไม่สามารถรับงานแจ้งซ่อมครุภัณฑ์ได้เกิน 3 งาน",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                } else {
                    // บันทึกลงฐานข้อมูล
                    var formData = {
                        completionDate: completionDate,
                        repair_id: repair_id,
                        session_id: session_id,
                        // Add more data if needed
                    };

                    console.log(formData);

                    $.ajax({
                        type: "POST",
                        url: location.origin +
                            "/project/AJAX/Repairman_AJAX/Save_Date_Complete_Equipment.php",
                        data: formData,
                        success: function(response) {
                            sendLineNotify(repair_id);
                            Swal.fire({
                                title: "Success!",
                                text: "รับงานสำเร็จ",
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

var equipment_for_details;

function show_equipment_details() {
    var equipment_id = equipment_for_details;

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

function sendLineNotify(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_Accep_work.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ช่างได้ทำการรับงานซ่อมแล้ว รับงานโดย: ${data.session_id} คาดว่าจะเสร็จใน: ${data.completionDate}`;

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
</script>


<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php'; ?>

    <div class="container mt-4">
        <h2>รายการแจ้งซ่อมครุภัณฑ์</h2>
        <div class="table-responsive">
            <?php
require_once("../../Database/db.php");

$repairman_id = $_SESSION['id'];

$equipmentRepairSql = "SELECT Equipment.equipment_id, Equipment_repair.repairman_id, Equipment_repair.repair_id, Equipment_repair.status_id, User.user_name, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
FROM Equipment_repair
JOIN User ON Equipment_repair.user_id = User.user_id
JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
LEFT JOIN Equipment ON Equipment.equipment_id = Equipment_repair.equipment_id
WHERE Statuss.status_id = 4";

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
    echo "<th>หมายเลขครุภัณฑ์</th>";
    echo "<th>รายละเอียด</th>";
    echo "<th>วันที่แจ้งซ่อม</th>";
    echo "<th>ผู้แจ้งซ่อม</th>";
    echo "<th>เพิ่มเติม</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $equipmentRepairNumRows = $equipmentRepairStart + 1;
    while ($equipmentRepairRow = $equipmentRepairResult->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $equipmentRepairNumRows . "</td>";
        echo "<td>" . wordwrap($equipmentRepairRow["equipment_number"], 10, "<br>", true) . "</td>";
        echo "<td>" . $equipmentRepairRow["repair_detail"] . "</td>";
        echo "<td>" . $equipmentRepairRow["repair_date"] . "</td>";
        echo "<td>" . $equipmentRepairRow["user_name"] . "</td>";

        echo "<td><a class='btn btn-primary' style='text-decoration: none; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='GetEquipment_Repair(" . $equipmentRepairRow['repair_id'] . ", " . $equipmentRepairRow['equipment_id'] . ");'>รายละเอียด</a></td>";
    
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


    <div id="popup1" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">รายละเอียดการแจ้งซ่อม</h5>
                </div>
                <div class="modal-body" id="popup-content"></div>
                <div class="form-group">
                    <label for="completionDate">วันที่คาดว่าจะเสร็จ:</label>
                    <input type="date" class="form-control" id="completionDate" name="completionDate">
                </div>

                <input type="hidden" id="session_id" value="<?php echo $repairman_id; ?>">
                <input type="hidden" id="hidden_repair_id" value="<?php echo $equipmentRepairRow['repair_id']; ?>">

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="show_equipment_details"
                        onclick="show_equipment_details()">
                        <i class="fas fa-info-circle"></i> รายละเอียดครุภัณฑ์
                    </button>
                    <button type="button" class="btn btn-danger" id="popup-close">
                        <i class="fas fa-times-circle"></i> ปิด
                    </button>
                    <button type="submit" class="btn btn-success" onclick="Savedata()">
                        <i class="fas fa-check-circle"></i> รับงาน
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------  modal รายละเอียดครุภัณฑ์  ------------------------------------------------------ !-->


    <div class="modal fade" id="modal_details_equipment" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">รายละเอียดครุภัณฑ์</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h5>รายละเอียด</h5>
                        <p>หมายเลขครุภัณฑ์ : <span id="equipment_number1"></span></p>
                        <p>ชื่อครุภัณฑ์ : <span id="name_equipment"></span></p>
                        <p>ยี่ห้อครุภัณฑ์ : <span id="brand_equipment"></span></p>
                        <p>รุ่นครุภัณฑ์ : <span id="model_equipment"></span></p>
                        <p>สีครุภัณฑ์ : <span id="color_equipment"></span></p>
                        <p>วันที่เพิ่มเข้าระบบ : <span id="date_add_equipment"></span></p>
                        <p>Serial Number : <span id="Serial_number"></span></p>
                        <p>สถาะนครุภัณฑ์ : <span id="status_equipment"></span></p>
                        <p>ราคาต่อหน่วย : <span id="price_equipment"></span></p>
                        <p>วันหมดประกัน : <span id="date_exp_equipment"></span></p>
                        <p>ผู้ครอบครอง : <span id="owner_equipment"></span></p>
                        <p>จำนวนครั้งที่ซ่อม : <span id="count_repair_equipment"></span></p>
                        <p>ที่อยู่ครุภัณฑ์ : <span id="address_equipment"></span></p>
                        <p>รายละเอียดต่าง ๆ : <span id="details_equipment"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-close" id="close_details_equipment"
                        data-bs-dismiss="modal" aria-label="Close">ปิด</button>
                </div>
            </div>
        </div>
    </div>











    <!-- ------------------------------------------------------  แจ้งซ่อมพื้นที่  ------------------------------------------------------ !-->
    <br>
    <br>
    <script>
    function showPopup2(data) {
        var popupContent = "<h2 class='mb-3'>รายละเอียดการแจ้งซ่อมพื้นที่</h2>";
        popupContent += "<p><strong>รายละเอียด: </strong> " + data.area_detail + "</p>";
        popupContent += "<p><strong>ปัญหาที่พบ: </strong> " + data.area_problem + "</p>";
        popupContent += "<p><strong>บริเวณ: </strong> " + data.area_address + "</p>";
        popupContent += "<p><strong>วันที่แจ้งซ่อม: </strong> " + data.area_date + "</p>";
        popupContent += "<p><strong>ผู้แจ้งซ่อม :</strong> " + data.user_name1 + "</p>";
        popupContent += "<img src='/project/Images/Repair_Address/" + data.area_imagesbefor +
            "' alt='รูปภาพก่อนซ่อม' class='popup-image' style='max-width: 100%; max-height: 300px;'>";

        $("#popup2-content").html(popupContent);
        $("#popup2").modal('show');
    }

    $(document).ready(function() {
        $("#popup2-close").click(function() {
            $("#popup2").modal('hide');
        });
    });

    function GetArea_Repair(area_id) {
        console.log(area_id);
        selectedareaId = area_id;

        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/List_Repair_get_data_Area.php",
            method: "POST",
            data: {
                area_id: area_id
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopup2(response.data);
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


    var selectedareaId;

    function saveDataarea(area_id) {
        var completionDate2 = $("#completionDate2").val();
        var area_id = selectedareaId;
        var session_id = $("#session_id").val();

        console.log("รหัสช่างซ่อม " + session_id);
        console.log("รหัสงานซ่อม " + area_id);
        console.log("วันที่คาดว่าจะเสร็จ " + completionDate2);

        if (completionDate2 === '') {
            Swal.fire({
                title: "Error!",
                text: "กรุณากรอกวันที่คาดว่าน่าจะเสร็จ",
                icon: "error",
                confirmButtonText: "OK"
            });
        } else {
            // ตรวจสอบจำนวนข้อมูลที่ตรงเงื่อนไขใน Area_Assign_work และ Area_repair
            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Repairman_AJAX/Check_Area_Work_Count.php",
                data: {
                    repairman_id: session_id
                },
                success: function(response) {
                    var workCount = parseInt(response);

                    console.log("workCount : " + response); // จำนวนงานที่ตรงเงื่อนไข

                    if (workCount >= 3) {
                        Swal.fire({
                            title: "ไม่สามารถรับงานได้",
                            text: "คุณไม่สามารถรับงานแจ้งซ่อมพื้นที่ได้เกิน 3 งาน",
                            icon: "warning",
                            confirmButtonText: "OK"
                        });
                    } else {
                        // บันทึกลงฐานข้อมูล
                        var formData = {
                            completionDate2: completionDate2,
                            area_id: area_id,
                            session_id: session_id,
                            // Add more data if needed
                        };
                        console.log(formData);

                        $.ajax({
                            type: "POST",
                            url: location.origin +
                                "/project/AJAX/Repairman_AJAX/Save_date_Complete_Area.php",
                            data: formData,
                            success: function(response) {
                                sendLineNotify1(area_id)
                                Swal.fire({
                                    title: "Success!",
                                    text: "รับงานสำเร็จ",
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


function sendLineNotify1(area_id) {
    console.log("repair_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_notify_Accep_work_area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ช่างได้ทำการรับงานซ่อมแล้ว รับงานโดย: ${data.session_id} คาดว่าจะเสร็จใน: ${data.completionDate}`;

            sendLineMessage1(lineTokens, message);
        },
        error: function(xhr, status, error) {
            const lineToken = xhr.getResponseHeader('Authorization');
            console.error(
                `sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: ${error} (Line Token: ${lineToken})`
            );
        }
    });
}



 function sendLineMessage1(lineTokens, message) {
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
    </script>

    <div class="container mt-4">
        <h2>รายการแจ้งซ่อมพื้นที่</h2>
        <div class="table-responsive">
            <?php
require_once("../../Database/db.php");

$repairman_id = $_SESSION['id'];

$areaRepairSql = "SELECT Area_repair.area_id, Statuss.status_id, User.user_name, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date
    FROM Area_repair
    JOIN User ON Area_repair.user_id = User.user_id
    JOIN Statuss ON Area_repair.Status_id = Statuss.status_id
    WHERE Statuss.status_id  = 4";
$areaRepairResult = $conn->query($areaRepairSql);

$areaRepairLimit = 10;
$areaRepairTotalRows = $areaRepairResult->rowCount();
$areaRepairTotalPages = ceil($areaRepairTotalRows / $areaRepairLimit);

if (isset($_GET['area_page'])) {
    $areaRepairPage = $_GET['area_page'];
} else {
    $areaRepairPage = 1;
}

$areaRepairStart = ($areaRepairPage - 1) * $areaRepairLimit;
$areaRepairSql .= " LIMIT $areaRepairStart, $areaRepairLimit";

$areaRepairResult = $conn->query($areaRepairSql);

if ($areaRepairResult->rowCount() > 0) {
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
    while ($areaRepairRow = $areaRepairResult->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $areaRepairNumRows . "</td>";
        echo "<td>" . $areaRepairRow["area_detail"] . "</td>";
        echo "<td>" . $areaRepairRow["area_problem"] . "</td>";
        echo "<td>" . $areaRepairRow["area_date"] . "</td>";
        echo "<td>" . $areaRepairRow["user_name"] . "</td>";
        echo "<td><a class='btn btn-primary' style='text-decoration: none; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='GetArea_Repair(" . $areaRepairRow['area_id'] . ")'>รายละเอียด</a><td>";

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

$conn = null;
?>

        </div>
    </div>


    <div id="popup2" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">รายละเอียดการแจ้งซ่อม</h5>
                </div>
                <div class="modal-body" id="popup2-content"></div>
                <div class="form-group" style="width: 50%">
                    <label for="completionDate">วันที่คาดว่าจะเสร็จ:</label>
                    <input type="date" class="form-control" id="completionDate2" name="completionDate">

                    <input type="hidden" id="session_id" value="<?php echo $repairman_id; ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="popup2-close">
                        <i class="fas fa-times-circle"></i> ปิด</button>
                    <button type="submit" class="btn btn-success" onclick="saveDataarea()">
                        <i class="fas fa-check-circle"></i> รับงาน</button>

                </div>
            </div>
        </div>
    </div>



    <br>
    <br>
    <br><br><br><br><br><br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <?php include '../../Footer/footer.php'; ?>
</body>

</html>