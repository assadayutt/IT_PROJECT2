<?php
require_once("../../Database/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_login.php");
    exit();
}
$repairman = $_SESSION['id'];


try {
 
    // ดึงข้อมูลผู้ใช้งานจากตาราง "user" โดยใช้ session id
    $sql = "SELECT * FROM Repairman WHERE repairman_id = :repairman_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':repairman_id', $repairman );
    $stmt->execute();

    // ตรวจสอบว่ามีข้อมูลผู้ใช้งานหรือไม่
    if ($stmt->rowCount() > 0) {
        // ดึงข้อมูลผู้ใช้งาน
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $repairman_name = $row['repairman_name'];
        $repairman_pass	= $row['repairman_pass'];
        $repairman_pic = $row['repairman_pic'];
        $repairman_Email = $row['repairman_Email'];
        $Line_Token = $row['Line_Token'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
} finally {
    $conn = null; // Close the connection
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#resetBtnn").click(function(e) {
            e.preventDefault();
            // ล้างค่าที่กล่องข้อความ
            $("#Name").val("");
            $("#profileImage").val("");
            $("#linetoken").val("");
            $("#email").val("");
            $("#previewImage").attr("src", "../../Images/User_profile.png");
            $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
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

    function updateProfile(repairman_id) {
        console.log("รหัสผู้ใช้เพื่อแก้ไขข้อมูล : " + repairman_id);

        var profileImage = $("#profileImage").val();
        var Name = $("#Name").val();
        var email = $("#email").val();
        var linetoken = $("#linetoken").val();

        console.log("รูปภาพ :", profileImage);
        console.log("ชื่อ :", Name);
        console.log("email :", email);
        console.log("ไลน์ Token :", linetoken);

        if (Name === '' || email === '' || linetoken === '') {
            Swal.fire({
                title: "Error!",
                text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        } else {
            // แสดง SweetAlert2 ยืนยันการบันทึก
            Swal.fire({
                title: "ยืนยันการบันทึกข้อมูล?",
                text: "คุณต้องการบันทึกข้อมูลหรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึก",
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งข้อมูลผ่าน AJAX
                    var formData = new FormData();
                    formData.append("profileImage", $("#profileImage")[0].files[0]);
                    formData.append("Name", Name);
                    formData.append("email", email);
                    formData.append("linetoken", linetoken);
                    formData.append("repairman_id", repairman_id);

                    $.ajax({
                        url: location.origin + "/project/AJAX/Repairman_AJAX/update_profile.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: "Success!",
                                text: "บันทึกข้อมูลสำเร็จ",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "../../Template/Repairman/Repairman_Profile.php";
                                }
                            });
                            document.getElementById('profileImageLabel').innerHTML =
                                'เลือกไฟล์รูปภาพ';
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
            });
        }
    }
    </script>
    <style>
    #profileImage {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    body {
        background: -webkit-linear-gradient(left, #FFEBCD, #DEB887);
    }
    </style>
</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php' ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">แก้ไขข้อมูลส่วนตัว</h5>
                <button class="btn btn-warning" data-toggle="modal"
                    data-target="#modal_reset_password">เปลี่ยนรหัสผ่าน</button>

                <form id="profileForm" enctype="multipart/form-data">
                    <center>
                        <div class="mt-2">

                            <img id="previewImage" class="preview-image"
                                src="../../Images/repairman/<?php echo $repairman_pic; ?>" alt="รูปภาพ"
                                style="width: 300px; height: 300px; object-fit: cover; border-radius: 50%;">
                        </div>

                    </center>
                    <div class="form-group">
                        <label for="profileImage">อัพโหลดรูปภาพ</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profileImage" name="profileImage"
                                onchange="showPreview()">
                            <label class="custom-file-label" for="profileImage"
                                id="profileImageLabel">เลือกไฟล์รูปภาพ</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Name">ชื่อ นามสกุล</label>
                        <input type="text" class="form-control" id="Name" name="Name"
                            value="<?php echo $repairman_name; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" id="email" name="email"
                            value="<?php echo  $repairman_Email; ?>">
                    </div>
                    <div class="form-group">
                        <label for="linetoken">Line Token</label>
                        <input type="text" class="form-control" id="linetoken" name="linetoken"
                            value="<?php echo $Line_Token; ?>">
                    </div>

                    <div class="card-footer text-center">

                        <button class="btn btn-primary" type="button"
                            onclick="updateProfile(<?php echo $_SESSION['id']; ?>)">บันทึก</button>
                        <button class="btn btn-secondary" id="resetBtnn">ล้างค่า</button>
                </form>
            </div>

        </div>
    </div>
    </div>




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
    <script>
    $(document).ready(function() {
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

    function performPasswordReset() {
        var password = $("#password").val();
        var password1 = $("#password1").val();

        password_reset(password);
    }

    var repairman_id = <?php echo json_encode($_SESSION['id']); ?>;

    function password_reset(password) {
        console.log("รหัสผู้ใช้เพื่อแก้ไขรหัสผ่าน : " + repairman_id);
        console.log("รหัสผ่าน : " + password);

        $.ajax({
            url: location.origin + "/project/AJAX/Repairman_AJAX/reset_password.php",
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
                            "../../Template/Repairman/Repairman_Profile.php";
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
    <br>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>
</body>

</html>