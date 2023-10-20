<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>แทงจำหน่าย</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="../../Template/Officer/css/List_equipment.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />




</head>

<?php include 'nav.php'; ?>
<style>
.btn-spacing {
    margin-left: 10px;
}
</style>
<?php
            $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment WHERE equipment_sale = 1")->fetchColumn();
            ?>
<div class="content-wrapper">
    <div class="content">
        <div class="card card-default">
            <div class="card-header align-items-center px-3 px-md-5 d-flex justify-content-between">
                <h2 class="mb-0">รายการแทงจำหน่ายครุภัณฑ์ <?php echo $totalItems?> รายการ</h2>
                <a class="btn btn-primary ml-auto mt-3" id="sale_equipment">แทงจำหน่าย</a>

            </div>
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
                                <th>รายละเอียด</th>
                                <th>วันที่แทงจำหน่าย</th>
                                <th>เพิ่มเติม</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                require_once("../../Database/db.php");

                $itemsPerPage = 10;
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

                $startFrom = ($currentPage - 1) * $itemsPerPage;

                $sql = "SELECT e.equipment_id, e.equipment_name, e.equipment_number, e.equipment_brand, e.equipment_model, SE.detail, SE.date_sale
                FROM Equipment AS e
                JOIN Sale_Equipment AS SE ON e.equipment_id = SE.equipment_id
                WHERE e.equipment_sale = 1
                ORDER BY SE.date_sale DESC, SE.equipment_id DESC
                LIMIT $startFrom, $itemsPerPage";
        
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
                    echo "<td>" . $row["detail"] . "</td>";
                    echo "<td>" . $row["date_sale"] . "</td>";


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
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Equipment WHERE equipment_sale = 1 ")->fetchColumn();
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
<!-- modal -->
<div class="modal fade" id="Modal_sale">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">แทงจำหน่ายครุภัณฑ์</h4>
            </div>
            <div class="modal-body">

                <table id="equipmentsale" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30%">ชื่อครุภัณฑ์</th>
                            <th style="width: 20%">หมายเลขครุภัณฑ์</th>
                            <th>ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close_Modal_sale" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal detial -->
<div class="modal fade" id="equipment_detail_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ข้อมูลรายละเอียดครุภัณฑ์ที่แทงจำหน่ายแล้ว</h5>
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
                <button type="button" class="btn btn-secondary" id="close_popup_type_equipment">ปิด</button>
            </div>
        </div>
    </div>
</div>
<br><br><br><br><br>
<?php include '../../Footer/footer.php'; ?>

</body>
<script>
$(document).ready(function() {
    $("#sale_equipment").click(function() {
        $("#Modal_sale").modal('show');
    });
    $("#close_Modal_sale").click(function() {
        $("#Modal_sale").modal('hide');
    });

    $("#close_popup_type_equipment").click(function() {
        $("#equipment_detail_Modal").modal('hide');
    });

});
GetEquipment();

function GetEquipment() {
    $.ajax({
        type: "GET",
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_Equipment_fore_sale.php",
        dataType: "json",
        success: function(response) {
            var tableHtml = '';

            $.each(response, function(index, type) {
                tableHtml += '<tr>';
                tableHtml += '<td>' + type.equipment_name + '</td>';
                tableHtml += '<td>' + type.equipment_number + '</td>';

                tableHtml +=
                    '<td>' +
                    '<input type="text" placeholder="รายละเอียดแทงจำหน่าย"  id="sale_equipment_detail' +
                    type.equipment_id +
                    '" style="width: 200px;">' +
                    '<span class="btn-spacing"></span>' +
                    '<button class="btn btn-danger delete-btn" data-type-id="' + type.equipment_id +
                    '" data-equipment-number="' + type.equipment_number + '">แทงจำหน่าย</button>' +
                    '</td>';
                tableHtml += '</tr>';
            });

            // เรียกใช้ ID ของตาราง HTML และใส่ข้อมูลลงใน tbody
            $("#equipmentsale tbody").html(tableHtml);
        },
        error: function(xhr, status, error) {
            console.error("เกิดข้อผิดพลาดในการดึงข้อมูล: " + error);
        }
    });
}

$("#equipmentsale").on("click", ".delete-btn", function() {
    var equipment_id = $(this).data("type-id");
    var equipment_number = $(this).data("equipment-number");
    var sale_equipment_detail = $("#sale_equipment_detail" + equipment_id).val().trim();

    console.log("รหัสชนิดเพื่อแก้ไข : " + equipment_id);
    console.log("หมายเลขครุภัณฑ์ : " + equipment_number);
    console.log("ชื่อที่จะแก้ : " + sale_equipment_detail);

    if (sale_equipment_detail.trim() === "") {
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด',
            text: 'กรุณากรอกรายละเอียด',
            confirmButtonText: 'ตกลง'
        });
        return;
    }
    $.ajax({
        type: "POST",
        url: location.origin + "/project/AJAX/Officer_AJAX/Sale_Equipment.php",
        data: {
            equipment_id: equipment_id,
            equipment_number: equipment_number,
            sale_equipment_detail: sale_equipment_detail

        },
        dataType: "json",
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'แทงจำหน่ายครุภัณฑ์สำเร็จ',
                confirmButtonText: 'ตกลง'
            }).then(function() {
                GetEquipment();
            });
        },
        error1: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามาถแทงจำหน่ายได้',
                text: 'ครุภัณฑ์กำลังดำเนินการซ่อมอยู่:' + error,
                confirmButtonText: 'ตกลง'
            });
        }
    });
});

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
    $("#equipment_detail_Modal").modal("show");
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
</script>

</html>