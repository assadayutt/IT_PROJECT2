<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>ประวัติการแจ้งซ่อม</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link href="../../Template/Officer/css/History.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />



</head>
<style>
.modal-header {
    background-color: #007bff;
    color: #fff;
}

.modal-title {
    color: #fff;
}
</style>
<?php include '../Admin/nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>ประวัติการซ่อมครุภัณฑ์ </h2>

                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="productsTable" class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>รายการแจ้งซ่อม</th>
                                    <th>รายละเอียด</th>
                                    <th>ช่างซ่อม</th>
                                    <th>สถานะ</th>

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
                   WHERE  Statuss.status_id = 1
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

                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment_repair WHERE status_id = 1")->fetchColumn();
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
                                        <div class="waiting-box" id="user_name"></div>
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
                   
                    <button type="button" id="popup-close" class="btn btn-secondary">
                        <i class="fas fa-times"></i> ปิด
                    </button>

                </div>
            </div>
        </div>
    </div>


    <script>
    $(document).ready(function() {
        $("#popup-close").click(function() {
            $("#history_repair").modal('hide');
        });
    });

    function showPopup_repair(data) {
        user_name
        $("#user_name").text(data.user_name);
        $("#equipment_number1").text(data.equipment_number);
        $("#repair_detail1").text(data.repair_detail);
        $("#repair_date1").text(data.repair_date);
        $("#repair_imagesbefor_display1").attr("src", data.repair_imagesbefor);
        $("#repair_imagesafter_display1").attr("src", "../../Images/Send_Work_Equipment/" + data.image_after);
        $("#repairman_name_repair1").text(data.repairman_name_repair1);
        $("#date_complete_area1").text(data.date_complete_area1);
        $("#date_comp_repair1").text(data.date_comp_repair1);
        $("#message_repair1").text(data.message_repair1);
        $("#Score").text(data.Score);

    }

    function history_repair(repair_id) {
        console.log("history_repair : " + repair_id);
        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Get_repair_complete_history.php",
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









    <!-- ----------------------------------- แจ้งซ่อมพื้นที่ ------------------------------------- -->

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>ประวัติการซ่อมพื้นที่</h2>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="productsTable" class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รายการแจ้งซ่อม</th>
                                    <th>ปัญหาที่พบ</th>
                                    <th>วันที่แจ้ง</th>
                                    <th>สถานะ</th>
                                    <th></th>
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
                                    WHERE Statuss.status_id = 1
                                    ORDER BY area_id  DESC LIMIT $startFrom, $itemsPerPage";


                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $num_rows = $startFrom + 1;
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
                                echo "<tr><td colspan='7'>ไม่พบข้อมูล</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Area_repair WHERE status_id = 1 ")->fetchColumn();
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
                                        <div class="waiting-box" id="username"></div>
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
                                    <div class="rating-box">
                                        <header>คะแนนที่ให้ <span id="Score1"></span> คะแนน</header>
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
                    <br>

                </div>
                <div class="modal-footer">
                  
                    <button type="button" id="popup-close1" class="btn btn-secondary">
                        <i class="fas fa-times"></i> ปิด
                    </button>

                </div>
            </div>
        </div>
    </div>

</body>
<script>
$(document).ready(function() {
    $("#popup-close1").click(function() {
        $("#history_area").modal('hide');
    });
});

function showPopup_area(data) {

    $("#username").text(data.username);
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
    $("#Score1").text(data.Score1);

}

function history_area(area_id) {
    console.log("history_repair : " + area_id);
    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_area_complete_history.php",
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
<?php include '../../Footer/footer.php'; ?>


</html>