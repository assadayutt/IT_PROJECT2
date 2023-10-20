<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_login.php");
    exit();
}
$user_id = $_SESSION['id'];

?>

<!DOCTYPE html>
<html>

<head>
    <title>ประวัติการแจ้งซ่อม</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/repairman_History.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


</head>

<script>
$(document).ready(function() {
    $("#popup-close").click(function() {
        $("#history_repair").modal('hide');
    });
});

function showPopup_repair(data) {
    $("#equipment_number1").text(data.equipment_number);
    $("#repair_detail1").text(data.repair_detail);
    $("#repair_date1").text(data.repair_date);
    $("#repair_imagesbefor_display1").attr("src", data.repair_imagesbefor);
    $("#repair_imagesafter_display1").attr("src", "../../Images/Send_Work_Equipment/" + data.image_after);
    $("#user_name_repair").text(data.user_name_repair);
    $("#date_complete_area1").text(data.date_complete_area1);
    $("#date_comp_repair1").text(data.date_comp_repair1);
    $("#message_repair1").text(data.message_repair1);
    $("#Score").text(parseInt(data.Score));


}

function history_repair(repair_id) {
    console.log("history_repair : " + repair_id);
    $.ajax({
        url: location.origin + "/project/AJAX/Repairman_AJAX/Get_repair_complete_history.php",
        method: "POST",
        data: {
            repair_id: repair_id
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopup_repair(response.data);
                console.log(response.data);
                applyStars();
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
    $("#history_repair").modal("show");
}
function applyStars() {
    const stars = document.querySelectorAll(".stars i");
    let allowClick = false;

    stars.forEach((star, index) => {
        star.addEventListener("click", () => {
            if (allowClick) {
                resetStars();
                applyActiveStars(index);
                const rating = index + 1;
                $("#Score").text(rating);
                allowClick = false;

                console.log("Score:", rating);
            }
        });
    });

    const dataScore = parseInt($("#Score").text());
    console.log("dataScore:", dataScore);

    if (!isNaN(dataScore) && dataScore >= 1 && dataScore <= 5) {
        resetStars();
        applyActiveStars(dataScore - 1);
        allowClick = false;
    } else if (dataScore === 0) {
        resetStars(); 
        allowClick = false; 
    }

    function resetStars() {
        stars.forEach(star => {
            star.classList.remove("active");
        });
    }

    function applyActiveStars(index) {
        stars.forEach((star, i) => {
            if (i <= index) {
                star.classList.add("active");
            }
        });
    }

    applyActiveStars(dataScore - 1);
}

</script>

<body>
     <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php' ?>
    <table>
        <thead>
            <tr>
                <th colspan="8">
                    <h2>ประวัติการซ่อมครุภัณฑ์</h2>
                </th>
            </tr>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 20%;">รายการแจ้งซ่อม</th>
                <th style="width: 20%;">รายละเอียด</th>
                <th style="width: 34%;">วันที่แจ้งซ่อม</th>
                <th style="width: 10%;">สถานะแจ้งซ่อม</th>
                <th style="width: 18%;">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
           
           require_once("../../Database/db.php");

           $sql = "SELECT Equipment_repair.repair_id, Repairman.repairman_id, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date 
                FROM Equipment_repair 
                JOIN Repairman ON Equipment_repair.repairman_id = Repairman.repairman_id 
                JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id 
                WHERE Equipment_repair.repairman_id = :repairman_id AND Statuss.status_id = 1 
                ORDER BY Equipment_repair.repair_id DESC";
   
           
           $stmt = $conn->prepare($sql);
           $stmt->bindParam(":repairman_id", $user_id, PDO::PARAM_INT);
           $stmt->execute();
           $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           $num_rows = 1;
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
                            $statusColorClass = "status-completed";
                            $statusText = "เสร็จสิ้น";
                            break;
                        default:
                            $statusColorClass = "";
                            $statusText = "";
                            break;
                    }

                    echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

                    $repair_id = $row['repair_id'];

                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' href='javascript:void(0);' onclick='history_repair(" . $repair_id . ");'>เพิ่มเติม</a></td>";

                    echo "</tr>";
                    $num_rows++;
                }
            } else {
                echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!--  ----------------------------------------------------- modal แสดงประวัติการซ่อม  -----------------------------------------------------   -->


    <div class="modal fade" id="history_repair" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">ประวัติการแจ้งซ่อมครุภัณฑ์</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 mb-4">
                                        <h5>แจ้งซ่อมโดย</h5>
                                        <div class="waiting-box" id="user_name_repair"></div>
                                    </div>
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
                            <div class="rating-box">
                                <header>คะแนนที่ได้รับ <span id="Score"></span> คะแนน</header>
                                <div class="stars">
                                    <i class="fas fa-star" data-rating="1"></i>
                                    <i class="fas fa-star" data-rating="2"></i>
                                    <i class="fas fa-star" data-rating="3"></i>
                                    <i class="fas fa-star" data-rating="4"></i>
                                    <i class="fas fa-star" data-rating="5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                </div>
                <div class="modal-footer">
                    <button type="button" id="popup-close" class="btn btn-secondary">ปิด</button>
                </div>
            </div>
        </div>
    </div>




    <!--  ----------------------------------------------------- พื้นที่  -----------------------------------------------------   -->

    <script>
    $(document).ready(function() {
        $("#popup-close1").click(function() {
            $("#history_area").modal('hide');
        });
    });

    function showPopup_area(data) {
        $("#area_detail1").text(data.area_detail);
        $("#area_problem1").text(data.area_problem);
        $("#area_date1").text(data.area_date);
        $("#area_address1").text(data.area_address);
        $("#area_imagesbefor1").attr("src", "../../Images/Repair_Address/" + data.area_imagesbefor);
        $("#area_imagesafter").attr("src", "../../Images/Send_Work_Area/" + data.image_after);
        $("#user_name_area").text(data.user_name_area);
        $("#date_complete_area").text(data.date_complete_area);
        $("#date_comp_area1").text(data.date_comp_area1);
        $("#message_area1").text(data.message_area1);
        $("#Score1").text(parseInt(data.Score1));




    }

    function history_area(area_id) {
        console.log("history_repair : " + area_id);
        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/Get_area_complete_history.php",
            method: "POST",
            data: {
                area_id: area_id
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    showPopup_area(response.data);
                    console.log(response.data);
                    applyStars1();
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

        $("#history_area").modal("show");
    }

    function applyStars1() {
    const stars1 = document.querySelectorAll(".stars1 i");
    let allowClick1 = false;

    stars1.forEach((star1, index) => {
        star1.addEventListener("click", () => {
            if (allowClick1) {
                resetStars1();
                applyActiveStars1(index);
                const rating = index + 1;
                $("#Score1").text(rating);
                allowClick1 = false;

                console.log("Score:", rating);
            }
        });
    });

    const dataScore1 = parseInt($("#Score1").text());
    console.log("dataScore1:", dataScore1);

    if (!isNaN(dataScore1) && dataScore1 >= 1 && dataScore1 <= 5) {
        resetStars1();
        applyActiveStars1(dataScore1 - 1);
        allowClick1 = false;
    } else if (dataScore1 === 0) {
        resetStars1(); 
        allowClick1 = false; 
    }

    function resetStars1() {
        stars1.forEach(star1 => {
            star1.classList.remove("active");
        });
    }

    function applyActiveStars1(index) {
        stars1.forEach((star1, i) => {
            if (i <= index) {
                star1.classList.add("active");
            }
        });
    }

    applyActiveStars1(dataScore1 - 1);
}

    </script>
    <table>
        <thead>
            <tr>
                <th colspan="8">
                    <h2>ประวัติการซ่อมพื้นที่</h2>
                </th>
            </tr>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 20%;">รายการแจ้งซ่อม</th>
                <th style="width: 20%;">รายละเอียด</th>
                <th style="width: 20%;">วันที่แจ้งซ่อม</th>
                <th style="width: 14%;">บริเวณ / พื้นที่</th>
                <th style="width: 10%;">สถานะแจ้งซ่อม</th>
                <th style="width: 11%;">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once("../../Database/db.php");

            
            
            $sql = "SELECT Area_repair.area_id, Area_repair.status_id, Area_repair.area_detail, Area_repair.area_problem, Area_repair.area_date, Area_repair.area_address
            FROM Area_repair
            JOIN Repairman ON Area_repair.repairman_id = Repairman.repairman_id
            JOIN Statuss ON Area_repair.status_id = Statuss.status_id
            WHERE Area_repair.repairman_id = :repairman_id AND Statuss.status_id = 1
            ORDER BY Area_repair.area_id DESC"; 


            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":repairman_id", $user_id, PDO::PARAM_INT);
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
                            $statusColorClass = "status-completed";
                            $statusText = "เสร็จสิ้น";
                            break;
                        default:
                            $statusColorClass = "";
                            $statusText = "";
                            break;
                    }

                    echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;' onclick='history_area(" . $row['area_id'] . ");'>เพิ่มเติม</a></td>";

                    echo "</tr>";
                    $num_rows++;
                }
            } else {
                echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>



    <div class="modal fade" id="history_area" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
 <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                 <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">ประวัติการแจ้งซ่อมพื้นที่</h4>
                </div>
                <div class="modal-body" id="popup-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 mb-4">
                                        <h5>แจ้งซ่อมโดย</h5>
                                        <div class="waiting-box" id="user_name_area"></div>
                                    </div>
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
                            <div class="rating-box">
                                <header>คะแนนที่ได้รับ <span id="Score1"></span> คะแนน</header>
                                <div class="stars1">
                                    <i class="fas fa-star" data-rating="1"></i>
                                    <i class="fas fa-star" data-rating="2"></i>
                                    <i class="fas fa-star" data-rating="3"></i>
                                    <i class="fas fa-star" data-rating="4"></i>
                                    <i class="fas fa-star" data-rating="5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                </div>
                <div class="modal-footer">
                    <button type="button" id="popup-close1" class="btn btn-secondary">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <?php include '../../Footer/footer.php' ?>
</body>

</html>