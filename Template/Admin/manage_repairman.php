<!DOCTYPE html>


<html lang="en" dir="ltr">

<head>
    <title>จัดการช่างซ่อม</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="../../Template/Officer/css/List_equipment.css" rel="stylesheet" />
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="../../Template/Officer/css/pagination.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<?php include '../../Template/Admin/nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">


    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>จัดการช่างซ่อม </h2>

                    <button type="button" class="btn btn-primary" data-toggle="modal" id='addrepairman'> เพิ่มช่างซ่อม
                    </button>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="productsTable" class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>อีเมล</th>
                                    <th>ดำเนินการ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="py-0">

                                        <?php
                require_once("../../Database/db.php");

                $itemsPerPage = 10;
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

                $startFrom = ($currentPage - 1) * $itemsPerPage;

                $sql = "SELECT * FROM Repairman WHERE repairman_name <> 'รอช่างรับงาน'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $num_rows = $startFrom + 1;

                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>" . $num_rows . "</td>";
                    echo "<td>" . $row["repairman_name"] . "</td>";
                    echo "<td>" . $row["repairman_Email"] . "</td>";
                
                
                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px; width: 120px' onclick='repairman_detail(" . $row['repairman_id'] . ");'>แก้ไข</a></td>";
                    echo "<td><a class='button1' style='text-decoration: none; background-color: red; color: white; margin:7px; width: 70px' onclick='deleteRepairman(" . $row['repairman_id'] . ");'>ลบ</a></td>";

                    echo "<td></td>";
                    echo "</tr>";
                    $num_rows++;
                }
                ?>
                                </tr>
                            </tbody>
                        </table>
                        <div class="pagination">
                        <?php
                        $totalItems = $conn->query("SELECT COUNT(*) FROM Repairman WHERE repairman_name <> 'รอช่างรับงาน'")->fetchColumn();
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

        <!------------------------ Modal addrepairman ----------------------->
        <div class="modal fade" id="Modal_addrepairman">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">เพิ่มช่างซ่อม</h4>
                    </div>
                    <div class="modal-body">
                        <form>

                            <div class="form-group">
                                <label for="full_name"><span style="color: red;">*</span>ชื่อ-นามสกุล:</label>
                                <input type="text" class="form-control" id="repairman_name" placeholder="ชื่อ-นามสกุล">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>รหัสผ่าน:</label>
                                <input type="text" class="form-control" id="repairman_pass" placeholder="รหัสผ่าน">
                            </div>
                            <div class="form-group">
                                <label for="email"><span style="color: red;">*</span>อีเมล:</label>
                                <input type="email" class="form-control" id="repairman_Email" placeholder="อีเมล">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close_Modal"
                            data-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-success" id="savedata">บันทึก</button>

                    </div>
                </div>
            </div>
        </div>

        <!------------------------ Modal editRepairman ----------------------->
        <div class="modal fade" id="Modal_detail">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">แก้ไขข้อมูล</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <center>
                                    <div class="mt-2">
                                        <img id="previewImage" class="preview-image rounded-circle"
                                            alt="รูปภาพ" style="width: 300px; height: 300px; object-fit: cover;">
                                    </div>
                                </center>

                                <br>
                                <label for="picture"><span style="color: red;">*</span> อัพโหลดรูปภาพ</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="profileImage" name="profileImage"
                                        onchange="showPreview()" value="<?php echo $offer_pic; ?>">
                                    <label class="custom-file-label" for="profileImage"
                                        id="profileImageLabel">เลือกไฟล์รูปภาพ</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="full_name"><span style="color: red;">*</span>ชื่อ-นามสกุล:</label>
                                <input type="text" class="form-control" id="repairman_name1" placeholder="ชื่อ-นามสกุล">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>Email:</label>
                                <input type="text" class="form-control" id="repairman_email" placeholder="email">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>Line Token:</label>
                                <input type="text" class="form-control" id="repairman_linetoken"
                                    placeholder="Line Token">
                            </div>

                        </form>
                        <button type="button" class="btn btn-warning" id="change_password">เปลี่ยนรหัสผ่าน</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close_Modal"
                            data-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-success" id="savedata"
                            onclick="edit_repairman()">บันทึก</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- ---------------------------- เปลี่ยนรหัสผ่าน ------------------------------- -->

        <div class="modal fade" id="modal_reset_password" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">เปลี่ยนรหัสผ่าน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                            <label for="password1">ยืนยันรหัสผ่าน</label>
                            <input type="password" class="form-control" id="password1">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <p style="color: red;"> * กรุณากรอกรหัสผ่านให้ตรงกัน</p>
                        <button type="button" id="close_modal_extend_repair_date" class="btn btn-danger"
                            data-dismiss="modal">ยกเลิก</button>
                        <button type="button" id="save_password_reset" class="btn btn-primary" disabled
                            onclick="performPasswordReset()">บันทึก</button>

                    </div>
                </div>
            </div>
        </div>

        <br><br><br><br> <br><br><br>
        <?php include '../../Footer/footer.php'; ?>

</body>
<script>
$(document).ready(function() {
    $("#addrepairman").click(function() {
        $("#Modal_addrepairman").modal('show');
    });
    $("#close_Modal").click(function() {
        $("#Modal_addrepairman").modal('hide');
    });
    $("#change_password").click(function() {
        $("#modal_reset_password").modal('show');
    });
    $("#change_password").click(function() {
        $("#Modal_detail").modal('hide');
    });

    $("#password1").on("input", function() {
        var password = $("#password").val();
        var password1 = $(this).val();

        if (password === password1) {
            // รหัสผ่านตรงกัน
            $("#save_password_reset").prop("disabled", false);
        } else {
            // รหัสผ่านไม่ตรงกัน
            $("#save_password_reset").prop("disabled", true);
        }
    });
});

$(document).ready(function() {
    $("#savedata").click(function() {
        var repairman_name = $("#repairman_name").val();
        var repairman_pass = $("#repairman_pass").val();
        var repairman_Email = $("#repairman_Email").val();

        // ตรวจสอบว่าทุกช่องถูกกรอกค่าหรือไม่
        if (repairman_name === "" || repairman_pass === "" || repairman_Email === "") {
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                text: 'กรุณากรอกข้อมูลให้ครบถ้วนก่อนที่จะบันทึก',
            });
        } else {
            // สร้าง FormData เพื่อส่งข้อมูลไปยัง AJAX
            var formData = new FormData();
            formData.append('repairman_name', repairman_name);
            formData.append('repairman_pass', repairman_pass);
            formData.append('repairman_Email', repairman_Email);

            // ทำการ AJAX request
            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Admin_AJAX/Add_repairman.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("Response:", response);
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                            text: 'ข้อมูลได้รับการบันทึกเรียบร้อยแล้ว',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้ในขณะนี้',
                        });
                    }
                },
                error: function() {
                    console.log("เกิดข้อผิดพลาดในการส่งข้อมูล");
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้ในขณะนี้',
                    });
                }
            });
        }
    });
});

function showPreview() {
    var fileInput = document.getElementById('profileImage');
    var previewImage = document.getElementById('previewImage');
    var fileName = document.getElementById('profileImage').files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
    }

    if (fileInput.files[0]) {
        reader.readAsDataURL(fileInput.files[0]);
        document.getElementById('profileImageLabel').innerHTML = fileName;
    } else {
        previewImage.src = '#';
        document.getElementById('profileImageLabel').innerHTML = 'เลือกไฟล์รูปภาพ';
    }
}

function deleteRepairman(repairman_id) {
    console.log("รหัสช่างซ่อมงานที่จะลบ : " + repairman_id);

    Swal.fire({
        title: 'ยืนยันการลบผู้ใช้งาน',
        text: 'คุณแน่ใจหรือไม่ที่จะลบผู้ใช้งานนี้?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่, ลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Admin_AJAX/Delete_repairman.php",
                data: {
                    repairman_id: repairman_id
                },
                success: function(response) {
                    console.log("Response:", response);
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'ลบช่างซ่อมสำเร็จ',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            location.reload();
                        });
                    } else if (response.trim() === "delete_error") {
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่สามารถลบได้',
                            text: 'เกิดข้อผิดพลาดในการลบผู้ใช้งาน',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("เกิดข้อผิดพลาดในการส่งข้อมูล: " + error);
                }
            });
        }
    });
}


function showPopup_details(data) {
    $("#repairman_name1").val(data.repairman_name);
    $("#repairman_email").val(data.repairman_Email);
    $("#repairman_linetoken").val(data.Line_Token);
    if (data.repairman_pic) {
        $("#previewImage").attr("src", "../../Images/repairman/" + data.repairman_pic);
    } else {
        $("#previewImage").attr("src",
            "../../Images/blank-image.jpeg");
    }

}

function repairman_detail(repairman_id) {
    console.log("รหัสช่างซ่อม : " + repairman_id);
    repairman_id_for_edit = repairman_id;

    $.ajax({
        url: location.origin + "/project/AJAX/Admin_AJAX/Get_repairman_Details.php",
        method: "POST",
        data: {
            repairman_id: repairman_id,
        },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                showPopup_details(response.data);
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
    $("#Modal_detail").modal("show");
}

var repairman_id_for_edit;

function edit_repairman() {
    var repairman_id = repairman_id_for_edit;

    var repairman_name = $("#repairman_name1").val();
    var repairman_email = $("#repairman_email").val();
    var repairman_linetoken = $("#repairman_linetoken").val();
    var profileImage = document.getElementById("profileImage").files[0];

    console.log("รหัสช่างซ่อม : " + repairman_id);
    console.log("ชื่องช่าง : " + repairman_name);
    console.log("email : " + repairman_email);
    console.log("line token : " + repairman_linetoken);
    console.log("รูปภาพ : " + profileImage);


    var formData = new FormData();
    formData.append('repairman_name', repairman_name);
    formData.append('repairman_email', repairman_email);
    formData.append('repairman_linetoken', repairman_linetoken);
    formData.append('profileImage', profileImage);
    formData.append('repairman_id', repairman_id);


    if (profileImage) {
        formData.append('profileImage', profileImage);
    }

    $.ajax({
        url: location.origin + "/project/AJAX/Admin_AJAX/Update_repairman.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                Swal.fire({
                    title: "Success",
                    text: "แก้ไขข้อมูลสำเร็จ",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    location.reload();
                });
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

function performPasswordReset() {
    var password = $("#password").val();
    var password1 = $("#password1").val();

    password_reset(password);
}


 
function password_reset(password) {
    var repairman_id = repairman_id_for_edit;
    console.log("รหัสผู้ใช้เพื่อแก้ไขรหัสผ่าน : " + repairman_id);
    console.log("รหัสผ่าน : " + password);

    $.ajax({
        url: location.origin + "/project/AJAX/Admin_AJAX/reset_password.php",
        type: "POST",
        data: {
            password: password,
            repairman_id: repairman_id
        },
        success: function(response) {
            Swal.fire({
                title: "Success!",
                text: "เปลี่ยนรหัสผ่านสำเร็จ",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        "../../Template/Admin/manage_repairman.php";
                }
            });

        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Error!",
                text: "เกิดข้อผิดพลาดขณะบันทึกข้อมูล",
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    });
}
</script>

</html>