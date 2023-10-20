<?php
require_once("../../Database/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_Login.php");
    exit();
}
$user_id = $_SESSION['id'];

?>


<!DOCTYPE html>
<html>

<head>
    <title>รายการแจ้งซ่อม</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/User_List_Repair.css">
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <script>
    if (typeof jQuery == 'undefined') {
        console.log('jQuery is not loaded');
    } else {
        console.log('jQuery is loaded');
    }

    $(document).ready(function() {
        $("#popup-close").click(function() {
            $("#Modal").modal('hide');
        });

        $("#closePopupButton").click(function() {
            $("#ratingModal").modal('hide');
        });


    });

    function showPopupp(data) {
        $("#equipment_number").text(data.equipment_number);
        $("#repair_detail").text(data.repair_detail);
        $("#repair_date").text(data.repair_date);
        $("#repair_imagesbefor_display").attr("src", data.repair_imagesbefor);
        $("#repairman_name").text(data.repairman_name);
        $("#date_comp_repair").text(data.date_comp_repair);
        $("#message_repair").text(data.message_repair);


        $("#Modal").modal("show");

    }

    function showPopupp1(data) {
        $("#equipment_number1").text(data.equipment_number);
        $("#repair_detail1").text(data.repair_detail);
        $("#repair_date1").text(data.repair_date);
        $("#repair_imagesbefor_display1").attr("src", data.repair_imagesbefor);
        $("#repair_imagesafter_display1").attr("src", "../../Images/Send_Work_Equipment/" + data.image_after);
        $("#repairman_name_repair1").text(data.repairman_name_repair1);
        $("#date_complete_area1").text(data.date_complete_area1);
        $("#date_comp_repair1").text(data.date_comp_repair1);
        $("#message_repair1").text(data.message_repair1);


    }


    function deleteRepair1(repair_id) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการยกเลิกการแจ้งซ่อมนี้หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: location.origin +
                        "/project/AJAX/User_AJAX/AJAX_delete_List_repair_Equipment.php",
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
        });
    }

    //select ให้ดาว
    function Button1Click(repair_id) {
        repair_id_for_score = repair_id;
        console.log("FormButton1Click : " + repair_id);
        $.ajax({
            url: location.origin + "/project/AJAX/User_AJAX/AJAX_get_repair_details.php",
            method: "POST",
            data: {
                repair_id: repair_id
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopupp1(response.data);
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

        $("#ratingModal").modal("show");
    }

    function handleButton1Click(repair_id) {
        console.log("handleButton1Click : " + repair_id);


        $.ajax({
            url: location.origin + "/project/AJAX/User_AJAX/AJAX_get_repair_details_more.php",
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


    var repair_id_for_score;

    function insert_Score(ratingText) {
        var repair_id = repair_id_for_score;

        console.log("รหัสการซ่อมส่งไปที่ AJAX : " + repair_id);

        console.log("คะแนน : " + ratingText);
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/User_AJAX/Insert_Score_Equipment.php",
            data: {
                ratingText: ratingText,
                repair_id: repair_id
            },
            dataType: "json",
            success: function(response) {
                sendLineNotify(repair_id)
                Swal.fire({
                    title: "Success!",
                    text: "บันทึกคะแนนสำเร็จ",
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

function sendLineNotify(repair_id) {
    console.log("repair_id สำหรับดึงค่า : " + repair_id);
    $.ajax({
        url: '/project/AJAX/User_AJAX/Get_notify_insert_score_equipment.php',
        method: 'POST',
        data: {
            repair_id: repair_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ผู้แจ้งซ่อมได้ให้คะแนนการซ่อมของคุณคือ: ${data.Score} คะแนน จากการแจ้งซ่อมครุภัณฑ์หมายเลข: ${data.equipment_number}, ให้คะแนนโดย: ${data.user_name}`;

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
</head>

<body>
    <br>
    <table>

        <thead>
            <tr>
                <th colspan="8">
                    <h2>รายการแจ้งซ่อมครุภัณฑ์</h2>
                </th>
            </tr>

            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 20%;">หมายเลขครุภัณฑ์</th>
            <th style="width: 20%;">รายละเอียด</th>
            <th style="width: 36.5%;">วันที่แจ้งซ่อม</th>
            <th style="width: 10%;">สถานะ</th>
            <th style="width: 15%;">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>

            <?php
require_once("../../Database/db.php");

try {

    $sql = "SELECT * FROM Equipment_repair WHERE user_id = :user_id AND status_id IN (2, 3, 4, 5, 6, 7)  ORDER BY repair_id DESC";
    $stmt = $conn->prepare($sql);
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

        echo "<td>"; 

        if ($statusId === 2 || $statusId === 3 || $statusId === 6 || $statusId === 7) {
            echo "<a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px;'' href='javascript:void(0);' onclick='handleButton1Click(" . $row['repair_id'] . ")'>เพิ่มเติม</a>";
        } else if (($statusId === 5)){ 
            echo "<a class='button1' style='text-decoration: none; background-color: blue; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='Button1Click(\"" . $row['repair_id'] . "\")'>ให้คะแนน</a>";
        } else {
            echo "<a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px;'' href='javascript:void(0);' onclick='handleButton1Click(" . $row['repair_id'] . ")'>เพิ่มเติม</a>";
            echo "<a class='button' style='text-decoration: none; background-color: red; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='deleteRepair1(" . $row['repair_id'] . ")'>ยกเลิก</a>";
        }

        echo "</td>"; 

        echo "</tr>";
        $num_rows++;
    }
    } else {
     echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
    }
        } catch (PDOException $e) {
        echo "การเชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage();
    exit();
    }
?>
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
                            <button type="button" id="popup-close" class="btn btn-secondary">ปิด</button>

                        </div>
                    </div>
                </div>
            </div>

            <!--  ------------------------------------------------ การให้คะแนน ------------------------------------------------     -->


            <div class="modal fade" id="ratingModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดการแจ้งซ่อมครุภัณฑ์</h4>
                        </div>
                        <div class="modal-body" id="popup-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12 mb-4">
                                                <h5>หมายเลขครุภัณฑ์</h5>
                                                <div class="waiting-box" id="equipment_number1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>รายละเอียด</h5>
                                                <div class="waiting-box" id="repair_detail1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่แจ้งซ่อม</h5>
                                                <div class="waiting-box" id="repair_date1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่คาดว่าจะเสร็จ</h5>
                                                <div class="waiting-box" id="date_comp_repair1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่เสร็จ</h5>
                                                <div class="waiting-box" id="date_complete_area1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>ซ่อมโดยช่าง</h5>
                                                <div class="waiting-box" id="repairman_name_repair1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>ข้อความจากช่าง</h5>
                                                <div class="waiting-box" id="message_repair1"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mt-2">

                                            <h5>BEFORE</h5>
                                            <div class="image-container">
                                                <img class="preview-image rounded" id="repair_imagesbefor_display1"
                                                    src="../../Images/blank-image.jpeg"
                                                    style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                                    alt="รูปภาพ">

                                                <br><br>
                                                <h5>AFTER</h5>
                                                <div class="image-container">
                                                    <img class="preview-image rounded" id="repair_imagesafter_display1"
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
                            <div class="rating-box">
                                <header>โปรดให้คะแนนการซ่อมในครั้งนี้</header>
                                <div class="stars">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <p id="ratingText" class="text-start"></p>

                            <button type="button" id="closePopupButton" class="btn btn-secondary">ปิด</button>
                            <button type="submit" class="btn btn-success submit"
                                onclick="insert_Score(getSelectedRating1())">บันทึก</button>

                        </div>
                    </div>
                </div>
            </div>

            <script>
            const stars1 = document.querySelectorAll(".stars i");

            function getSelectedRating1() {
                let selectedRating = 0;
                stars1.forEach((star, index) => {
                    if (star.classList.contains("active")) {
                        selectedRating = index + 1;
                    }
                });
                return selectedRating;
            }

            stars1.forEach((star, index1) => {
                star.addEventListener("click", () => {
                    stars1.forEach((star, index2) => {
                        index1 >= index2 ? star.classList.add("active") : star.classList.remove(
                            "active");
                    });

                    const ratingText1 = document.getElementById("ratingText");
                    ratingText1.textContent = "คะแนน: " + getSelectedRating1();
                });
            });
            </script>










            <!--  ------------------------------------------------ แจ้งซ่อมพื้นที่ ------------------------------------------------     -->


            <script>
            $(document).ready(function() {
                $("#closePopupButtonarea").click(function() {
                    $("#ratingModalarea_repair").modal('hide');
                });
                $("#closePopupButtonarea1").click(function() {
                    $("#Modallarea_repair").modal('hide');
                });
            });

            function showPopup(data) {
                $("#area_detail1").text(data.area_detail);
                $("#area_problem1").text(data.area_problem);
                $("#area_date1").text(data.area_date);
                $("#area_address1").text(data.area_address);
                $("#area_imagesbefor1").attr("src", "../../Images/Repair_Address/" + data.area_imagesbefor);
                $("#area_imagesafter").attr("src", "../../Images/Send_Work_Area/" + data.image_after);
                $("#repairman_name_area").text(data.repairman_name_area);
                $("#date_complete_area").text(data.date_complete_area);
                $("#date_comp_area1").text(data.date_comp_area1);
                $("#message_area1").text(data.message_area1);




            }

            function showPopup1(data) {
                $("#area_detail").text(data.area_detail);
                $("#area_problem").text(data.area_problem);
                $("#area_date").text(data.area_date);
                $("#area_address").text(data.area_address);
                $("#area_imagesbefor").attr("src", "../../Images/Repair_Address/" + data.area_imagesbefor);
                $("#repairman_name3").text(data.repairman_name_area1);
                $("#date_comp_area").text(data.date_comp_area);
                $("#message_area").text(data.message_area);


            }


            function getAddress(area_id) {
                console.log("getAddress : " + area_id);
                area_id_for_score = area_id;

                $.ajax({
                    url: location.origin + "/project/AJAX/User_AJAX/AJAX_get_address_repair.php",
                    method: "POST",
                    data: {
                        area_id: area_id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            showPopup(response.data);
                        } else {
                            console.log(response.message);
                            Swal.fire({
                                title: "Error!",
                                text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + response.message,
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                        $("#ratingModalarea_repair").modal("show");
                    },
                });
            }

            function getAddressMore(area_id) {
                console.log("getAddressMore : " + area_id);
                $.ajax({
                    url: location.origin + "/project/AJAX/User_AJAX/AJAX_get_address_repair_more.php",
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
                        $("#Modallarea_repair").modal("show");

                    },
                });
            }

            function deleteRepair(area_id) {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการยกเลิกการแจ้งซ่อมนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: location.origin +
                                "/project/AJAX/User_AJAX/AJAX_delete_List_repair_Area.php",
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
                                        // ทำการรีเฟรชหน้าเว็บเมื่อ SweetAlert2 แสดงเสร็จสมบูรณ์
                                        window.location.reload();
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire({
                                    title: "Error!",
                                    text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + error.message,
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            },
                        });
                    }
                });
            }


            function insert_Score_area(ratingText1) {
                var area_id = area_id_for_score;

                console.log("รหัสการซ่อมส่งไปที่ AJAX : " + area_id);

                console.log("คะแนน : " + ratingText1);
                $.ajax({
                    type: "POST",
                    url: location.origin + "/project/AJAX/User_AJAX/Insert_Score_Area.php",
                    data: {
                        ratingText1: ratingText1,
                        area_id: area_id
                    },
                    dataType: "json",
                    success: function(response) {
                        sendLineNotify1(area_id);
                        Swal.fire({
                            title: "Success!",
                            text: "บันทึกคะแนนสำเร็จ",
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

function sendLineNotify1(area_id) {
    console.log("area_id สำหรับดึงค่า : " + area_id);
    $.ajax({
        url: '/project/AJAX/User_AJAX/Get_notify_insert_score_area.php',
        method: 'POST',
        data: {
            area_id: area_id
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.lineTokens); 

            const lineTokens = data.lineTokens; 
            const message =
                `ผู้แจ้งซ่อมได้ให้คะแนนการซ่อมของคุณคือ: ${data.Score} คะแนน จากการแจ้งซ่อม: ${data.area_detail}, ปัญหาที่พบ: ${data.area_problem} บริเวณ: ${data.area_address} ,ให้คะแนนโดย: ${data.user_name}`;

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
            <table>
                <thead>
                    <tr>
                        <th colspan="8">
                            <h2>รายการแจ้งซ่อมพื้นที่</h2>
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <th style="width: 15%;">รายการแจ้งซ่อม</th>
                        <th style="width: 20%;">รายละเอียด</th>
                        <th style="width: 20%;">วันที่แจ้งซ่อม</th>
                        <th style="width: 15%;">พื้นที่ / บริเวณ</th>
                        <th style="width: 10%;">สถานะ</th>
                        <th style="width: 1%;">ตรวจสอบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php       

require_once("../../Database/db.php");

try {
    
    $sql = "SELECT * FROM Area_repair WHERE user_id = :user_id AND status_id IN (2, 3, 4, 5, 6, 7) ORDER BY area_id DESC";
    $stmt = $conn->prepare($sql);
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

            echo "<td>"; 
    
          
            if ($statusId == 2 || $statusId == 3 || $statusId == 6 || $statusId == 7) {
                echo '<a class="button" style="text-decoration: none; background-color: green; color: white; margin:7px;" data-area-id="' . $row['area_id'] . '" href="javascript:void(0);" onclick="getAddressMore(' . $row['area_id'] . ')">เพิ่มเติม</a>';
            } else if  ($statusId == 5) {
                echo "<a class='button1' style='text-decoration: none; background-color: blue; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='getAddress(\"" . $row['area_id'] . "\")'>ให้คะแนน</a>";
            } else {
                echo '<a class="button" style="text-decoration: none; background-color: green; color: white; margin:7px;" data-area-id="' . $row['area_id'] . '" href="javascript:void(0);" onclick="getAddressMore(' . $row['area_id'] . ')">เพิ่มเติม</a>';
                echo "<a class='button' style='text-decoration: none; background-color: red; color: white; margin:7px; width: 100px' href='javascript:void(0);' onclick='deleteRepair(" . $row['area_id'] . ");'>ยกเลิก</a>";
            } 
        
            echo "</td>"; 
    
            echo "</tr>";
            $num_rows++;
        }
    } else {
        echo "<tr><td colspan='7'>ไม่พบข้อมูล</td></tr>";
    }

    $pdo = null;
} catch (PDOException $e) {
    echo "การเชื่อมต่อฐานข้อมูลผิดพลาด: " . $e->getMessage();
    exit();
}
?>

                </tbody>
            </table>

            <!--  ------------------------------------------------ เพิ่มเติม ------------------------------------------------     -->

            <div class="modal fade" id="Modallarea_repair" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดการแจ้งซ่อมพื้นที่</h4>

                        </div>
                        <div class="modal-body" id="popup-content">
                            <div class="row">
                                <div class="col-md-6">
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
                            <button type="button" id="closePopupButtonarea1" class="btn btn-secondary">ปิด</button>

                        </div>
                    </div>
                </div>
            </div>

            <!--  ------------------------------------------------ การให้คะแนน ------------------------------------------------     -->


            <div class="modal fade" id="ratingModalarea_repair" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle">รายละเอียดการแจ้งซ่อมพื้นที่</h4>
                        </div>
                        <div class="modal-body" id="popup-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="col-md-12 mb-4">
                                                <h5>รายละเอียด</h5>
                                                <div class="waiting-box" id="area_detail1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>ปัญหาที่พบ</h5>
                                                <div class="waiting-box" id="area_problem1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>สถานที่ / บริเวณ</h5>
                                                <div class="waiting-box" id="area_address1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่แจ้งซ่อม</h5>
                                                <div class="waiting-box" id="area_date1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่คาดว่าจะเสร็จ</h5>
                                                <div class="waiting-box" id="date_comp_area1"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>ซ่อมโดยช่าง</h5>
                                                <div class="waiting-box" id="repairman_name_area"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>วันที่เสร็จ</h5>
                                                <div class="waiting-box" id="date_complete_area"></div>
                                            </div>
                                            <div class="col-md-12 mb-4">
                                                <h5>ข้อความจากช่าง</h5>
                                                <div class="waiting-box" id="message_area1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mt-2">

                                            <h5>BEFORE</h5>
                                            <div class="image-container">
                                                <img class="preview-image rounded" id="area_imagesbefor1"
                                                    src="../../Images/blank-image.jpeg"
                                                    style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                                    alt="รูปภาพ">

                                                <br><br>
                                                <h5>AFTER</h5>
                                                <div class="image-container">
                                                    <img class="preview-image rounded" id="area_imagesafter"
                                                        src="../../Images/blank-image.jpeg"
                                                        style="width: 350px; height: auto; max-height: 80vh; max-width: 350px;"
                                                        alt="รูปภาพ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-box">
                                <header>โปรดให้คะแนนการซ่อมในครั้งนี้</header>
                                <div class="stars1">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <br>



                        </div>
                        <div class="modal-footer">
                            <p id="ratingText1" class="text-start"></p>
                            <button type="button" id="closePopupButtonarea" class="btn btn-secondary">ปิด</button>
                            <button type="submit" class="btn btn-success submit"
                                onclick="insert_Score_area(getSelectedRating())">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>


            <script>
            const stars = document.querySelectorAll(".stars1 i");
            function getSelectedRating() {
                let selectedRating = 0;
                stars.forEach((star, index) => {
                    if (star.classList.contains("active")) {
                        selectedRating = index + 1;
                    }
                });
                return selectedRating;
            }

            stars.forEach((star, index1) => {
                star.addEventListener("click", () => {
                    stars.forEach((star, index2) => {
                        index1 >= index2 ? star.classList.add("active") : star.classList.remove(
                            "active");
                    });
                    const ratingText = document.getElementById("ratingText1");
                    ratingText.textContent = "คะแนน: " + getSelectedRating();
                });
            });
            </script>


            <br>
            <br>
            <br>

            <?php include '../../Footer/footer.php' ?>
</body>

</html>