<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>เพิ่มรายการครุภัณฑ์</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </script>
    <script>
    $(document).ready(function() {
        $("#UploadButton").click(function() {
            $("#UploadModal").modal('show');
        });

        $("#close-UploadModal").click(function() {
            $("#UploadModal").modal('hide');
        });

        $("#UploadFilesButton").click(function() {
            var fileInput = document.getElementById('formFileMultiple');
            var file = fileInput.files[0];

            uploadFile(file);
        });

        $("#addButton").click(function() {
            saveEquipment();
        });
    });

    function uploadFile(file) {
        if (!file) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด',
                icon: 'error'
            });
            return;
        }
        console.log(file);

        var formData = new FormData();
        formData.append('file', file);

        $.ajax({
            url: location.origin + "/project/AJAX/Officer_AJAX/Add_Equipment_Files.php",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'อัปโหลดไฟล์สำเร็จ',
                    text: 'ไฟล์ได้ถูกอัปโหลดเรียบร้อยแล้ว',
                    icon: 'success'
                });
                $("#response").html(response);
            },
            error: function() {
                Swal.fire({
                    title: 'เกิดข้อผิดพลาด',
                    text: 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์',
                    icon: 'error'
                });
            }
        });
    }
    </script>
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <?php include '../../Template/Admin/nav.php'; ?>

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5 d-flex justify-content-between">
                    <h2>เพิ่มครุภัณฑ์</h2>
                    <div>
                        <a type="button" class="btn btn-primary" id="UploadButton">Upload</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="equipment_name">ชื่อครุภัณฑ์</label>
                                <input type="text" class="form-control" id="equipment_name" placeholder="ชื่อครุภัณฑ์">
                            </div>
                            <div class="form-group">
                                <label for="equipment_number">หมายเลขครุภัณฑ์</label>
                                <input type="text" class="form-control" id="equipment_number"
                                    placeholder="หมายเลขครุภัณฑ์">
                            </div>
                            <div class="form-group">
                                <label for="equipment_brand">ยี่ห้อครุภัณฑ์</label>
                                <input type="text" class="form-control" id="equipment_brand"
                                    placeholder="ยี่ห้อครุภัณฑ์">
                            </div>
                            <div class="form-group">
                                <label for="equipment_model">รุ่น</label>
                                <input type="text" class="form-control" id="equipment_model" placeholder="รุ่น">
                            </div>
                            <div class="form-group">
                                <label for="equipment_color">สีครุภัณฑ์</label>
                                <input type="text" class="form-control" id="equipment_color" placeholder="สีครุภัณฑ์">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="equipment_detial">รายละเอียด</label>
                                <input type="text" class="form-control" id="equipment_detail" placeholder="รายละเอียด">
                            </div>
                            <div class="form-group ">
                                <label for="equipment_serial">Serial Number</label>
                                <input type="text" class="form-control" id="equipment_serial"
                                    placeholder="Serial Number">
                            </div>
                            <div class="form-group">
                                <label for="equipment_status">สถานะ</label>
                                <select class="form-control" id="equipment_status">
                                    <option value="">&#9650;เลือก</option>
                                    <option value="ใช้งานอยู่">ใช้งานอยู่</option>
                                    <option value="ไม่ได้ใช้งานแล้ว">ไม่ได้ใช้งานแล้ว</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="equipment_price">ราคาต่อหน่วย</label>
                                <input type="text" class="form-control" id="equipment_price" placeholder="ราคาต่อหน่วย">
                            </div>
                            <div class="form-group">
                                <label for="equipment_exp">วันเพิ่มเข้าระบบ</label>
                                <input type="date" class="form-control" id="equipment_dateadd" placeholder="วันหมดอายุ">
                            </div>
                            <div class="form-group">
                                <label for="equipment_exp">วันหมดประกัน</label>
                                <input type="date" class="form-control" id="equipment_exp" placeholder="วันหมดประกัน">
                            </div>
                            <div class="form-group">
                                <label for="equipment_owner">ผู้ครอบครอง</label>
                                <input type="text" class="form-control" id="equipment_owner" placeholder="ผู้ครอบครอง">
                            </div>
                            <div class="form-group">
                                <label for="equipment_address">ที่อยู่ครุภัณฑ์</label>
                                <input type="text" class="form-control" id="equipment_address"
                                    placeholder="ที่อยู่ครุภัณฑ์">
                            </div>
                            <div class="form-group">
                                <label for="equipment_address">สถานะการแทงจำหน่าย</label>
                                <select class="form-control" id="equipment_sale">
                                    <option value="">&#9650;เลือก</option>
                                    <option value="0">ยังไม่แทงจำหน่าย</option>
                                    <option value="1">จำหน่ายแล้ว</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="equipment_address">ชนิดครุภัณฑ์</label>
                                <select class="form-control" id="equipment_type123" name="equipment_type123">
                                    <option value="">&#9650; เลือก</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-header align-items-center px-3 px-md-5 d-flex justify-content-end">
                        <div>
                            <a type="button" class="btn btn-primary" id="addButton">บันทึก</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="UploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Equipment File Upload</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">อัพโหลดไฟล์เพื่อเพิ่มครุภัณฑ์หลายรายการ</label>
                        <input class="form-control" type="file" id="formFileMultiple" multiple accept=".xls, .xlsx">
                        <small class="text-muted">เฉพาะไฟล์ Excel (.xls, .xlsx) เท่านั้น</small>
                    </div>
                    <a href="../../Form/ฟอร์มครุภัณฑ์.xlsx" download="ฟอร์มครุภัณฑ์.xlsx" class="btn btn-light">
                        <i class="fas fa-download"></i> Download Form
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-UploadModal" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>

                    <button type="button" class="btn btn-primary" id="UploadFilesButton">
                        <i class="fas fa-upload"></i> Upload
                    </button>

                </div>
            </div>
        </div>
    </div>

</body>
<script>
$(document).ready(function() {

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Get_Equipment_type.php",
        method: "GET",
        dataType: "json",
        success: function(data) {
            var select = $("#equipment_type123");
            $.each(data, function(index, item) {
                select.append(new Option(item.type_name, item
                    .type_id));
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("เกิดข้อผิดพลาดในการดึงข้อมูล: " + textStatus);
        }
    });

});
$(document).ready(function() {
    $("#close-UploadModal").click(function() {
        $("#UploadModal").modal('hide');
        clearForm();
    });

    function clearForm() {
        $("#formFileMultiple").val('');
    }

    $("#formFileMultiple").change(function() {
        var allowedExtensions = ['.xls', '.xlsx'];
        var fileInput = document.getElementById('formFileMultiple');
        var filePath = fileInput.value;
        var fileExtension = filePath.substring(filePath.lastIndexOf('.'));

        if (allowedExtensions.includes(fileExtension.toLowerCase())) {
            // Allowed extension, enable upload button
            $("#UploadFilesButton").prop("disabled", false);
        } else {
            // Disallowed extension, disable upload button
            $("#UploadFilesButton").prop("disabled", true);
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'อัปโหลดได้เฉพาะไฟล์ Excel (.xls, .xlsx) เท่านั้น',
                icon: 'error'
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $("#addButton").click(function() {
        saveEquipment();
    });
});
var isSaving = false;

function saveEquipment() {
    if (isSaving) {
        return;
    }

    isSaving = true;

    var equipmentData = {
        equipment_name: $("#equipment_name").val(),
        equipment_number: $("#equipment_number").val(),
        equipment_brand: $("#equipment_brand").val(),
        equipment_model: $("#equipment_model").val(),
        equipment_color: $("#equipment_color").val(),
        equipment_detail: $("#equipment_detail").val(),
        equipment_serial: $("#equipment_serial").val(),
        equipment_status: $("#equipment_status").val(),
        equipment_price: $("#equipment_price").val(),
        equipment_dateadd: $("#equipment_dateadd").val(),
        equipment_exp: $("#equipment_exp").val(),
        equipment_owner: $("#equipment_owner").val(),
        equipment_address: $("#equipment_address").val(),
        equipment_sale: $("#equipment_sale").val(),
        type_id: $("#equipment_type123").val(),
    };

    for (var key in equipmentData) {
        if (equipmentData.hasOwnProperty(key) && equipmentData[key] === "") {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
            });
            isSaving = false;
            return;
        }
    }

    $.ajax({
        type: "POST",
        url: location.origin + "/project/AJAX/Officer_AJAX/Add_Equipment.php",
        data: equipmentData,
        success: function(response) {
            console.log("บันทึกข้อมูลสำเร็จ:", response);
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'บันทึกข้อมูลสำเร็จ!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        },
        error: function(error) {
            console.error("เกิดข้อผิดพลาดในการบันทึกข้อมูล:", error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'เกิดข้อผิดพลาดขณะที่บันทึกข้อมูล',
            });
        }
    }).always(function() {
        isSaving = false;
    });
}
</script>
<?php include '../../Footer/footer.php'; ?>

</html>