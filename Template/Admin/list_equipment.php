<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>รายการครุภัณฑ์</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<style>
p {
    font-size: 20px;
    color: #000000;
}

.btn-spacing {
    margin-left: 10px;
}

#edit_equipment_type {
    height: 45px;
    width: 15px;
}
</style>

<?php include 'nav.php'; ?>
<?php
require_once("../../Database/db.php");

            $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment WHERE equipment_sale = 0")->fetchColumn();?>

<body class="navbar-fixed sidebar-fixed" id="body">

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5 d-flex justify-content-between">
                    <h2 class="mb-0">รายการครุภัณฑ์ <?php echo $totalItems?> รายการ</h2>
                    <div class="input-group mt-3">
                        <div class="input-group mt-3">
                            <input type="text" class="form-control" placeholder="กรอกชื่อ หรือ หมายเลขครุภัณฑ์"
                                aria-label="ค้นหาครุภัณฑ์" id="searchInput">
                            <div class="input-group-append">
                                <button class="btn btn-primary" style="height: 38px;" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary ml-auto mt-3" id="Equipment_type">ชนิดครุภัณฑ์</a>

                </div>

                <div id="searchResults"></div>

                <div class="row">
                    <div class="card-body">
                        <table id="myTable" class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>ชื่อครุภัณฑ์</th>
                                    <th>หมายเลขครุภัณฑ์</th>
                                    <th>ยี่ห้อ</th>
                                    <th>รุ่น</th>
                                    <th>ราคาต่อหน่วย</th>
                                    <th>ที่อยู่</th>
                                    <th>รายละเอียด</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                require_once("../../Database/db.php");

                $itemsPerPage = 10;
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

                $startFrom = ($currentPage - 1) * $itemsPerPage;

                $sql = "SELECT * FROM Equipment where equipment_sale = 0 LIMIT $startFrom, $itemsPerPage";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $num_rows = $startFrom + 1;

                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>" . $num_rows . "</td>";
                    echo "<td>" . $row["equipment_name"] . "</td>";
                    echo "<td>" . $row["equipment_number"] . "</td>";
                    echo "<td>" . $row["equipment_brand"] . "</td>";
                    echo "<td>" . $row["equipment_model"] . "</td>";
                    echo "<td>" . $row["equipment_price"] . "</td>";
                    echo "<td>" . $row["equipment_address"] . "</td>";


                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px; width: 120px' onclick='equipment_detail(" . $row['equipment_id'] . ");'>เพิ่มเติม</a></td>";

                    echo "<td></td>";
                    echo "</tr>";
                    $num_rows++;
                }
                ?>
                            </tbody>
                        </table>
                        <div class="pagination">
                            <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment")->fetchColumn();
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

    <!-- Modal detial -->
    <div class="modal fade" id="equipment_detial_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ข้อมูลรายละเอียดครุภัณฑ์</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row row-cols-2">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <p>ชื่อครุภัณฑ์</p>
                                        <div class="waiting-box" id="equipment_name"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>หมายเลขครุภัณฑ์</p>
                                        <div class="waiting-box" id="equipment_number"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ยี่ห้อครุภัณฑ์</p>
                                        <div class="waiting-box" id="equipment_brand"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>รุ่น</p>
                                        <div class="waiting-box" id="equipment_model"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>สีครุภัณฑ์</p>
                                        <div class="waiting-box" id="equipment_color"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ผู้ครองครอง</p>
                                        <div class="waiting-box" id="equipment_owner"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <p>รายละเอียดครุภัณฑ์</p>
                                        <div class="waiting-box" id="equipment_detail"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>Serial Number</p>
                                        <div class="waiting-box" id="equipment_serial"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>สถานะ</p>
                                        <div class="waiting-box" id="equipment_status"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ราคา</p>
                                        <div class="waiting-box" id="equipment_price"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>วันที่เพิ่มเข้าระบบ</p>
                                        <div class="waiting-box" id="equipment_dateadd"></div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>วันหมดอายุ</p>
                                        <div class="waiting-box" id="equipment_exp"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 mb-4">
                                <p>จำนวนครั้งที่ซ่อม</p>
                                <div class="waiting-box" id="equipment_count"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <p>ที่อยู่</p>
                                <div class="waiting-box" id="equipment_address"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <p>สถานะแทงจำหน่าย</p>
                                <div class="waiting-box" id="equipment_sale">
                                    <?php
                                        if ($equipment_sale == 0) {
                                            echo "ยังไม่จำหน่าย";
                                        } elseif ($equipment_sale == 1) {
                                            echo "จำหน่ายแล้ว";
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <p>ชนิดครุภัณฑ์</p>
                                <div class="waiting-box" id="equipment_type"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="edit_equipment">แก้ไข</button>
                    <button type="button" class="btn btn-secondary" id="close_popup_type_equipment">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไขครุภัณฑ์ -->
    <div class="modal fade" id="equipment_edit_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แก้ไขข้อมูลครุภัณฑ์</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row row-cols-2">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <p>ชื่อครุภัณฑ์</p>
                                        <input type="text" class="form-control" id="equipment_name1"
                                            name="equipment_name1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>หมายเลขครุภัณฑ์</p>
                                        <input type="text" class="form-control" id="equipment_number1"
                                            name="equipment_number1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ยี่ห้อครุภัณฑ์</p>
                                        <input type="text" class="form-control" id="equipment_brand1"
                                            name="equipment_brand1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>รุ่น</p>
                                        <input type="text" class="form-control" id="equipment_model1"
                                            name="equipment_model1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>สีครุภัณฑ์</p>
                                        <input type="text" class="form-control" id="equipment_color1"
                                            name="equipment_color1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ผู้ครองครอง</p>
                                        <input type="text" class="form-control" id="equipment_owner1"
                                            name="equipment_owner1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <p>รายละเอียดครุภัณฑ์</p>
                                        <input type="text" class="form-control" id="equipment_detail1"
                                            name="equipment_detail1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>Serial Number</p>
                                        <input type="text" class="form-control" id="equipment_serial1"
                                            name="equipment_serial1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>สถานะ</p>
                                        <input type="text" class="form-control" id="equipment_status1"
                                            name="equipment_status1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>ราคา</p>
                                        <input type="text" class="form-control" id="equipment_price1"
                                            name="equipment_price1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>วันที่เพิ่มเข้าระบบ</p>
                                        <input type="text" class="form-control" id="equipment_dateadd1"
                                            name="equipment_dateadd1">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p>วันหมดอายุ</p>
                                        <input type="text" class="form-control" id="equipment_exp1"
                                            name="equipment_exp1">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <p>ที่อยู่</p>
                                <input type="text" class="form-control" id="equipment_address1"
                                    name="equipment_address1">
                            </div>
                            <div class="col-md-6 mb-4">
                                <p>ชนิดครุภัณฑ์</p>
                                <select class="form-control" id="equipment_type123" name="equipment_type123">
                                    <option value="">&#9650; เลือก</option>
                                </select>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="save_equipment">บันทึก</button>
                    <button type="button" class="btn btn-secondary" id="close_popup1">กลับ</button>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal ชนิดครุภัณฑ์ หลัก -->
    <div class="modal fade" id="equipment_type_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ชนิดครุภัณฑ์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="equipmentTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50%">ชื่อครุภัณฑ์</th>
                                <th>ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="add_type" placeholder="กรอกชนิดครุภัณฑ์">
                        <button type="button" id="add_type_button" class="btn btn-success float-right"
                            style="height: 38px">เพิ่ม</button>
                    </div>
                    <button type="button" id="close_equipment_type_Modal" class="btn btn-secondary">ปิด</button>
                </div>

            </div>
        </div>
    </div>


</body>
<script>
function confirmDelete(equipment_id) {
    console.log("ยืนยันลบครุภัณฑ์ : " + equipment_id);
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'คุณต้องการลบครุภัณฑ์นี้หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteEquipment(equipment_id);
        }
    });
}


</script>
<script>
$(document).ready(function() {
    $('#searchButton').click(function() {
        var searchText = $('#searchInput').val();

        if (searchText.trim() === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'กรุณากรอกข้อมูล',
            });
            return;
        }

        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Search_equipment.php",
            type: 'GET',
            data: {
                searchText: searchText
            },
            success: function(response) {
                $('#searchResults').html(response);
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {

    $("#close_popup_edit_equipment").click(function() {
        $("#equipment_detial_Modal").modal('hide');
    });

    $("#Equipment_type").click(function() {
        $("#equipment_type_Modal").modal('show');
    });

    $("#close_equipment_type_Modal").click(function() {
        $("#equipment_type_Modal").modal('hide');
    });

    $("#edit_equipment").click(function() {
        $("#equipment_edit_Modal").modal('show');
    });
    $("#close_popup_edit_equipment").click(function() {
        $("#equipment_edit_Modal").modal('hide');
    });

    $("#edit_equipment").click(function() {
        $("#equipment_detial_Modal").modal('hide');
    });

    $("#close_popup1").click(function() {
        $("#equipment_edit_Modal").modal('hide');
    });

    $("#close_popup_type_equipment").click(function() {
        $("#equipment_detial_Modal").modal('hide');
    });

    $("#close_popup1").click(function() {
        $("#equipment_detial_Modal").modal('show');
    });

});
</script>
<script>
function showPopupp_edit(data) {
    $("#equipment_name1").val(data.equipment_name);
    $("#equipment_number1").val(data.equipment_number);
    $("#equipment_brand1").val(data.equipment_brand);
    $("#equipment_model1").val(data.equipment_model);
    $("#equipment_color1").val(data.equipment_color);
    $("#equipment_dateadd1").val(data.equipment_dateadd);
    $("#equipment_detail1").val(data.equipment_detail);
    $("#equipment_serial1").val(data.equipment_serial);
    $("#equipment_status1").val(data.equipment_status);
    $("#equipment_price1").val(data.equipment_price);
    $("#equipment_exp1").val(data.equipment_exp);
    $("#equipment_owner1").val(data.equipment_owner);
    $("#equipment_count1").val(data.equipment_count);
    $("#equipment_address1").val(data.equipment_address);
    $("#equipment_sale1").val(data.equipment_sale);
}

function showPopupp(data) {
    $("#equipment_name").text(data.equipment_name);
    $("#equipment_number").text(data.equipment_number);
    $("#equipment_brand").text(data.equipment_brand);
    $("#equipment_model").text(data.equipment_model);
    $("#equipment_color").text(data.equipment_color);
    $("#equipment_dateadd").text(data.equipment_dateadd);
    $("#equipment_detail").text(data.equipment_detail);
    $("#equipment_serial").text(data.equipment_serial);
    $("#equipment_status").text(data.equipment_status);
    $("#equipment_price").text(data.equipment_price);
    $("#equipment_exp").text(data.equipment_exp);
    $("#equipment_owner").text(data.equipment_owner);
    $("#equipment_count").text(data.equipment_count);
    $("#equipment_address").text(data.equipment_address);
    $("#equipment_type").text(data.equipment_type);

    var equipmentSaleValue = data.equipment_sale;

    var equipmentSaleElement = $("#equipment_sale");

    // เปลี่ยนข้อความของ element โดยใช้เงื่อนไข
    if (equipmentSaleValue == 0) {
        equipmentSaleElement.text("ยังไม่จำหน่าย");
    } else if (equipmentSaleValue == 1) {
        equipmentSaleElement.text("จำหน่ายแล้ว");
    }

}
$(document).ready(function() {
    // ใช้ AJAX เพื่อดึงข้อมูลจากไฟล์ PHP
    // ใช้ AJAX เพื่อดึงข้อมูลจากไฟล์ PHP
    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_Equipment_type.php",
        method: "GET",
        dataType: "json",
        success: function(data) {
            // สร้าง dropdown options จากข้อมูลที่ได้
            var select = $("#equipment_type123");
            $.each(data, function(index, item) {
                select.append(new Option(item.type_name, item
                    .type_id)); // เพิ่ม ID เป็นค่า value ของแต่ละตัวเลือก
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("เกิดข้อผิดพลาดในการดึงข้อมูล: " + textStatus);
        }
    });

});
$('#equipment_type123').on('change', function() {
    var selectedTypeId = $(this).val(); // รับ ID ที่เลือก
    // ทำสิ่งที่คุณต้องการด้วย selectedTypeId เช่น ส่งไปยัง PHP หรือทำการบันทึกลงในฐานข้อมูล
    console.log("ID type: " + selectedTypeId);
});
</script>
<script>
function equipment_detail(equipment_id) {
    console.log("รหัสครุภัณฑ์ : " + equipment_id);
    equipment_id_for_edit = equipment_id; // นำค่า equipment_id ไปใส่ในตัวแปร equipment_id_for_edit

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_equipment_detail.php",
        method: "POST",
        data: {
            equipment_id: equipment_id,
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopupp(response.data);
                showPopupp_edit(response.data);
                console.log(response);
                console.log("ข้อมูล : " + response.data);
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
    $("#equipment_detial_Modal").modal("show");
}

var equipment_id_for_edit;

$('#save_equipment').on('click', function() {
    var equipmentName = $('#equipment_name1').val();
    var equipmentNumber = $('#equipment_number1').val();
    var equipmentBrand = $('#equipment_brand1').val();
    var equipmentModel = $('#equipment_model1').val();
    var equipmentColor = $('#equipment_color1').val();
    var equipmentOwner = $('#equipment_owner1').val();
    var equipmentDetail = $('#equipment_detail1').val();
    var equipmentSerial = $('#equipment_serial1').val();
    var equipmentStatus = $('#equipment_status1').val();
    var equipmentPrice = $('#equipment_price1').val();
    var equipmentDateAdd = $('#equipment_dateadd1').val();
    var equipmentExp = $('#equipment_exp1').val();
    var equipmentAddress = $('#equipment_address1').val();
    var equipmentType = $('#equipment_type123').val();

    if (
        equipmentName.trim() === "" ||
        equipmentNumber.trim() === "" ||
        equipmentBrand.trim() === "" ||
        equipmentModel.trim() === "" ||
        equipmentColor.trim() === "" ||
        equipmentOwner.trim() === "" ||
        equipmentDetail.trim() === "" ||
        equipmentSerial.trim() === "" ||
        equipmentStatus.trim() === "" ||
        equipmentPrice.trim() === "" ||
        equipmentDateAdd.trim() === "" ||
        equipmentExp.trim() === "" ||
        equipmentAddress.trim() === "" ||
        equipmentType.trim() === ""
    ) {
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด',
            text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
        });
        return;
    }

    var data = {
        equipment_id: equipment_id_for_edit,
        equipmentName: equipmentName,
        equipmentNumber: equipmentNumber,
        equipmentBrand: equipmentBrand,
        equipmentModel: equipmentModel,
        equipmentColor: equipmentColor,
        equipmentOwner: equipmentOwner,
        equipmentDetail: equipmentDetail,
        equipmentSerial: equipmentSerial,
        equipmentStatus: equipmentStatus,
        equipmentPrice: equipmentPrice,
        equipmentDateAdd: equipmentDateAdd,
        equipmentExp: equipmentExp,
        equipmentAddress: equipmentAddress,
        equipmentType: equipmentType
    };

    console.log("ข้อมูล:", data);

    $.ajax({
        type: 'POST',
        url: location.origin + "/project/AJAX/Officer_AJAX/Edit_equipment.php",
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'ข้อมูลถูกอัพเดทแล้ว',
                confirmButtonText: 'ตกลง'
            }).then(function() {

                location.reload();
            });
        },
        error: function(xhr, status, error) {
            // จัดการข้อผิดพลาดที่เกิดขึ้นในการส่ง Ajax
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด',
                text: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                confirmButtonText: 'ตกลง' // เพิ่มปุ่ม "ตกลง"
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $("#add_type_button").click(function() {
        var type = $("#add_type").val();

        console.log("ชื่อชนิดครุภัณฑ์ : " + type)

        if (type.trim() === "") {
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด',
                text: 'กรุณากรอกชนิดครุภัณฑ์',
                confirmButtonText: 'ตกลง'
            });
            return;
        }

        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/Officer_AJAX/Add_equipment_type.php",
            data: {
                type: type
            },
            success: function(response) {
                if (response.trim() ===
                    "success") { // เพิ่ม .trim() เพื่อลบช่องว่างหน้าและหลังข้อความ
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'เพิ่มชนิดครุภัณฑ์สำเร็จ',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetchEquipmentTypes(); // ดึงข้อมูลใหม่หลังจากเพิ่ม
                        }
                    });
                    $("#add_type").val("");
                } else if (response.trim() === "error_type_exists") {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: 'ชนิดครุภัณฑ์นี้มีอยู่แล้ว',
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการเพิ่มชนิดครุภัณฑ์',
                        confirmButtonText: 'ตกลง'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการส่งข้อมูล',
                    confirmButtonText: 'ตกลง'
                });
            }
        });

    });

    function fetchEquipmentTypes() {
        $.ajax({
            type: "GET",
            url: location.origin + "/project/AJAX/Officer_AJAX/Fetch_equipment_types.php",
            dataType: "json",
            success: function(response) {
                var tableHtml = '';

                $.each(response, function(index, type) {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + type.type_name + '</td>';
                    tableHtml +=
                        '<td>' +
                        '<input type="text" id="edit_equipment_type' + type.type_id +
                        '" style="width: 150px;">' +
                        '<span class="btn-spacing"></span>' +
                        '<button class="btn btn-warning edit-btn" data-type-id="' + type
                        .type_id + '">แก้ไข</button>' +
                        '<span class="btn-spacing"></span>' +
                        '<button class="btn btn-danger delete-btn" data-type-id="' + type
                        .type_id + '">ลบ</button>' +
                        '</td>';
                    tableHtml += '</tr>';
                });



                $("#equipmentTable tbody").html(tableHtml);
            },
            error: function(xhr, status, error) {
                console.error("เกิดข้อผิดพลาดในการดึงข้อมูล: " + error);
            }
        });
    }



    function deleteEquipmentType(typeId) {
        console.log("รหัสชนิดครุภัณฑ์ที่จะลบ : " + typeId);

        $.ajax({
            type: "POST",
            url: location.origin +
                "/project/AJAX/Officer_AJAX/Delete_equipment_type.php",
            data: {
                type_id: typeId
            },
            success: function(response) {
                if (response === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'ลบชนิดครุภัณฑ์สำเร็จ',
                        confirmButtonText: 'ตกลง'
                    });
                    // ย้ายการเรียก fetchEquipmentTypes() ไปยังตำแหน่งที่เหมาะสม
                } else if (response === "delete_error") {
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สามารถลบได้',
                        text: 'เกิดข้อผิดพลาดในการลบชนิดครุภัณฑ์',
                        confirmButtonText: 'ตกลง'
                    });
                }
                fetchEquipmentTypes(); // อาจต้องเรียกในทุกกรณี
            },
            error: function(xhr, status, error) {
                console.error("เกิดข้อผิดพลาดในการส่งข้อมูล: " + error);
            }
        });
    }


    fetchEquipmentTypes();

    $("#equipmentTable").on("click", ".delete-btn", function() {
        var typeId = $(this).data("type-id");

        Swal.fire({
            title: 'ยืนยันการลบ',
            text: 'คุณต้องการลบชนิดครุภัณฑ์นี้หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteEquipmentType(typeId);
            }
        });
    });

    $("#equipmentTable").on("click", ".edit-btn", function() {
        var type_id = $(this).data("type-id");
        var edited_type_name = $("#edit_equipment_type" + type_id).val();

        console.log("รหัสชนิดเพื่อแก้ไข : " + type_id);
        console.log("ชื่อที่จะแก้ : " + edited_type_name);

        if (edited_type_name.trim() === "") {
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด',
                text: 'กรุณากรอกชื่อที่ต้องการแก้ไข',
                confirmButtonText: 'ตกลง'
            });
            return;
        }
        $.ajax({
            type: "POST",
            url: location.origin + "/project/AJAX/Officer_AJAX/Edit_equipment_type.php",
            data: {
                type_id: type_id,
                edited_type_name: edited_type_name
            },
            dataType: "json",
            success: function(response) {
                fetchEquipmentTypes();
                console.log("รหัสชนิดครุภัณฑ์ที่จะแก้ไข : " + type_id);
                console.log("ข้อมูลการแก้ไข: ", response);
            },
            error: function(xhr, status, error) {
                console.error("เกิดข้อผิดพลาดในการส่งข้อมูล: " + error);
            }
        });
    });


});
</script>


<?php include '../../Footer/footer.php'; ?>

</html>