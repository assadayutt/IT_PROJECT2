<!DOCTYPE html>


<html lang="en" dir="ltr">

<head>
    <title>จัดการผู้ใช้</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="../../Template/Officer/css/List_equipment.css" rel="stylesheet" />
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
<script>
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
</script>
<?php include '../../Template/Admin/nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>จัดการผู้ใช้ </h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" id='addUser'> เพิ่มผู้ใช้
                    </button>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="productsTable" class="table table-hover table-product" style="width:100%">

                            <thead>

                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>รหัสนิสิต</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>อีเมล</th>
                                    <th>รหัสบัตรประชาชน</th>
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

                $sql = "SELECT * FROM User ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $num_rows = $startFrom + 1;

                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>" . $num_rows . "</td>";
                    echo "<td>" . $row["user_stu"] . "</td>";
                    echo "<td>" . $row["user_name"] . "</td>";
                    echo "<td>" . $row["user_email"] . "</td>";
                    echo "<td>" . $row["user_pass"] . "</td>";
                
                    echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white; margin:7px; width: 120px' onclick='user_detail(" . $row['user_id'] . ");'>แก้ไข</a></td>";
                    echo "<td><a class='button1' style='text-decoration: none; background-color: red; color: white; margin:7px; width: 70px' onclick='deleteUser(" . $row['user_id'] . ");'>ลบ</a></td>";

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
                        $totalItems = $conn->query("SELECT COUNT(*) FROM User")->fetchColumn();
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

        <!------------------------ Modal addUser ----------------------->
        <div class="modal fade" id="Modal_addUser">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">เพิ่มสมาชิก</h4>
                    </div>
                    <div class="modal-body">
                        <form>

                            <div class="form-group">
                                <label for="student_id"><span style="color: red;">*</span>รหัสนิสิต:</label>
                                <input type="text" class="form-control" id="user_stu" placeholder="รหัสนิสิต">
                            </div>
                            <div class="form-group">
                                <label for="full_name"><span style="color: red;">*</span>ชื่อ-นามสกุล:</label>
                                <input type="text" class="form-control" id="user_name" placeholder="ชื่อ-นามสกุล">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>รหัสบัตรประชาชน:</label>
                                <input type="text" class="form-control" id="user_pass" placeholder="รหัสบัตรประชาชน">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <label for="national_id">เพิ่มผู้ใช้หลายคนผ่าน Excel</label>
                        <button type="button" class="btn btn-primary" id="uplodefile"
                            data-dismiss="modal">Upload</button>
                        <label for="tag">/</label>
                        <button type="button" class="btn btn-secondary" id="close_Modal"
                            data-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-success" id="savedata">บันทึก</button>

                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Upload -->
        <div class="modal fade" id="UploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">User File Upload</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formFileMultiple"
                                class="form-label">อัพโหลดไฟล์เพื่อเพิ่มผู้ใช้งานหลายรายการ</label>
                            <input class="form-control" type="file" id="formFileMultiple" multiple accept=".xls, .xlsx">
                            <small class="text-muted">เฉพาะไฟล์ Excel (.xls, .xlsx) เท่านั้น</small>
                        </div>
                        <a href="../../Form/User_form.xlsx" download="User_form.xlsx" class="btn btn-light">
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



        <!------------------------ Modal editUser ----------------------->
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
                                            src="<?php echo isset($offer_pic) ? '../../Images/User/' . $offer_pic : '../../Images/blank-image.jpeg'; ?>"
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
                                <label for="student_id"><span style="color: red;">*</span>รหัสนิสิต:</label>
                                <input type="text" class="form-control" id="user_stu123" placeholder="รหัสนิสิต">
                            </div>
                            <div class="form-group">
                                <label for="full_name"><span style="color: red;">*</span>ชื่อ-นามสกุล:</label>
                                <input type="text" class="form-control" id="user_name123" placeholder="ชื่อ-นามสกุล">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>รหัสบัตรประชาชน:</label>
                                <input type="text" class="form-control" id="user_pass123" placeholder="รหัสบัตรประชาชน">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>Email:</label>
                                <input type="text" class="form-control" id="user_email123" placeholder="email">
                            </div>
                            <div class="form-group">
                                <label for="national_id"><span style="color: red;">*</span>Line Token:</label>
                                <input type="text" class="form-control" id="user_linetoken123" placeholder="Line Token">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="close_Modal"
                            data-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-success" id="savedata"
                            onclick="edit_user()">บันทึก</button>

                    </div>
                </div>
            </div>
        </div>
        <br><br><br><br> <br><br><br><br><br> <br>

        <?php include '../../Footer/footer.php'; ?>
</body>
<script>
$(document).ready(function() {
    $("#addUser").click(function() {
        $("#Modal_addUser").modal('show');
    });
    $("#close_Modal").click(function() {
        $("#Modal_addUser").modal('hide');
    });
    $("#uplodefile").click(function() {
        $("#UploadModal").modal('show');
    });
    $("#UploadFilesButton").click(function() {
        var fileInput = document.getElementById('formFileMultiple');
        var file = fileInput.files[0];

        uploadFile(file);
    });
});
$(document).ready(function() {
    $("#savedata").click(function() {
        var user_stu = $("#user_stu").val();
        var user_name = $("#user_name").val();
        var user_pass = $("#user_pass").val();

        var formData = new FormData();
        formData.append('user_stu', user_stu);
        formData.append('user_name', user_name);
        formData.append('user_pass', user_pass);

        if (user_stu && user_name && user_pass) {
            $.ajax({
                type: "POST",
                url: location.origin + "/project/AJAX/Admin_AJAX/Add_User.php",
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
                    } else if (response.trim() === "notsuccess") {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'รหัสนิสิตนี้มีบัญชีอยู่แล้ว',
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
        } else {
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                text: 'กรุณากรอกข้อมูลให้ครบถ้วนก่อนที่จะบันทึก',
            });
        }
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
        url: location.origin + "/project/AJAX/Admin_AJAX/Add_User_Files.php",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                title: 'อัปโหลดไฟล์สำเร็จ',
                text: 'ทำการเพื่อผู้ใช้งานเรียบร้อยแล้ว',
                icon: 'success'
            }).then(() => {
                location.reload();
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

function deleteUser(user_id) {
    console.log("รหัสผู้ใช้งานที่จะลบ : " + user_id);

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
                url: location.origin + "/project/AJAX/Admin_AJAX/Delete_User.php",
                data: {
                    user_id: user_id
                },
                success: function(response) {
                    console.log("Response:", response);
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'ลบผู้ใช้งานสำเร็จ',
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
    $("#user_stu123").val(data.user_stu);
    $("#user_name123").val(data.user_name);
    $("#user_pass123").val(data.user_pass);
    $("#user_email123").val(data.user_email);
    $("#user_linetoken123").val(data.user_linetoken);
    if (data.user_pic) {
        $("#previewImage").attr("src", "../../Images/User/" + data.user_pic);
    } else {
        $("#previewImage").attr("src",
            "../../Images/blank-image.jpeg");
    }

}

function user_detail(user_id) {
    console.log("รหัสผู้ใช้งาน : " + user_id);
    user_id_for_save = user_id;

    $.ajax({
        url: location.origin + "/project/AJAX/Admin_AJAX/Get_User_Details.php",
        method: "POST",
        data: {
            user_id: user_id,
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

var user_id_for_save;

function edit_user() {
    var user_id = user_id_for_save;

    var user_stu = $("#user_stu123").val();
    var user_name = $("#user_name123").val();
    var user_pass = $("#user_pass123").val();
    var user_linetoken = $("#user_linetoken123").val();
    var user_email = $("#user_email123").val();
    var profileImage = document.getElementById("profileImage").files[0];

    console.log("รหัสนิสิต : " + user_stu);
    console.log("ชื่อ : " + user_name);
    console.log("รหัสบัตรประชาชน : " + user_pass);
    console.log("token : " + user_linetoken);
    console.log("อีเมลล์ : " + user_email);
    console.log("รูปภาพ : " + profileImage);
    console.log("รหัสผู้ใช้งาน : " + user_id);

    var formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('user_stu', user_stu);
    formData.append('user_name', user_name);
    formData.append('user_email', user_email);
    formData.append('user_pass', user_pass);
    formData.append('user_linetoken', user_linetoken);

    if (profileImage) {
        formData.append('profileImage', profileImage);
    }

    $.ajax({
        url: location.origin + "/project/AJAX/Admin_AJAX/Update_User.php",
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
</script>

</html>