<?php
// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
require_once("../../Database/db.php");

// เริ่ม session
session_start();

// ตรวจสอบว่าผู้ใช้งานล็อกอินหรือไม่
if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/Officer/Officer_Login.php");
    exit();
}
$officer_id = $_SESSION['id'];

try {
 
    // ดึงข้อมูลผู้ใช้งานจากตาราง "user" โดยใช้ session id
    $sql = "SELECT * FROM Officer WHERE officer_id  = :officer_id ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':officer_id',$officer_id);
    $stmt->execute();

    // ตรวจสอบว่ามีข้อมูลผู้ใช้งานหรือไม่
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $officer_name = $row['officer_name'];
        $offer_pic = $row['offer_pic'];
     
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
} finally {
    $conn = null; // Close the connection
}
?>
<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>โปรไฟล์</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<?php include '../../Template/Officer/nav.php'; ?>

<body>
    <style>
    body {
        font-family: 'Kanit', sans-serif;
    }
    </style>
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


    <div class="content">
        <div class="col-xl-9">
            <div class="card card-default">
                <div class="card-header">
                    <h2 class="mb-5">ตั้งค่าโปรไฟล์</h2>

                </div>
                <div class="card-body">
                    <div class="media media-sm">
                    </div>
                    <div class="form-group">
                        <center>
                            <div class="mt-2">
                                <img id="previewImage" class="preview-image rounded-circle"
                                    src="<?php echo isset($offer_pic) ? '../../Images/Officer/' . $offer_pic : '../../Images/blank-image.jpeg'; ?>"
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


                    <div class="form-group row mb-6">
                        <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">ชื่อ-นามสกุล:</label>
                        <div class="col-sm-8 col-lg-10">
                            <input type="text" class="form-control" id="nameText" value="<?php echo $officer_name; ?>">
                        </div>
                    </div>
                    <button class="btn btn-warning" data-toggle="modal"
                        data-target="#modal_reset_password">เปลี่ยนรหัสผ่าน</button>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="updateProfile1()">อัพเดตโปรไฟล์</button>

                    </div>
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
    <br>
    <br>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>

</body>
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


var officer_id = <?php echo json_encode($_SESSION['id']); ?>;

function password_reset(password) {
    console.log("รหัสเจ้าหน่าที่เพื่อแก้ไขรหัสผ่าน : " + officer_id);
    console.log("รหัสผ่าน : " + password);

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/reset_password_officer.php",
        type: "POST",
        data: {
            password: password,
            officer_id: officer_id
        },
        success: function(response) {
            Swal.fire({
                title: "Success!",
                text: "เปลี่ยนรหัสผ่านสำเร็จ",
                icon: "success",
                confirmButtonText: "OK"
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.reload();
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
function updateProfile1() {
    var formData = new FormData();
    var officer_id = <?php echo json_encode($_SESSION['id']); ?>;
    var profileImage = document.getElementById("profileImage").files[0];
    var nameText = document.getElementById("nameText").value;

    formData.append("profileImage", profileImage);
    formData.append("nameText", nameText);
    formData.append("officer_id", officer_id);

    $.ajax({
        url: location.origin + "/project/AJAX/Officer_AJAX/Edit_profile.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                title: "Success!",
                text: "อัพเดทข้อมูลสำเร็จ",
                icon: "success",
                confirmButtonText: "OK"
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "error!",
                text: "เกิดข้อผิดพลาด" + error,
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    });
}


</script>

</html>