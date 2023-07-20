<?php
require_once("../../Database/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบและดำเนินการกับข้อมูลที่ส่งมาจากแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Name']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['linetoken']) && isset($_FILES['profileImage'])) {
        $Name = $_POST['Name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $linetoken = $_POST['linetoken'];
        $picture = $_FILES['profileImage'];
        $user_id = $_SESSION['id'];

        // ตรวจสอบและบันทึกไฟล์ภาพ
        if ($_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            // เช็คว่ามีการอัปโหลดไฟล์ภาพใหม่หรือไม่
            if ($_FILES['profileImage']['tmp_name'] !== '') {
                $picture = $_FILES['profileImage'];
                // ทำการบันทึกไฟล์ภาพใหม่
                $uploadDir = '../../Images/User/';
                $fileName = uniqid() . '_' . $picture['name'];
                if (move_uploaded_file($picture['tmp_name'], $uploadDir . $fileName)) {
                    $filePath = $fileName;
                } else {
                    $filePath = '';
                }
            } else {
                $filePath = ''; // ไม่มีการอัปโหลดไฟล์ภาพใหม่
            }
        } else {
            $filePath = ''; // ไม่มีการอัปโหลดไฟล์ภาพ
        }

        $stmt = $conn->prepare("UPDATE User SET user_name = ?, user_pass = ?, user_pic = ?, user_email = ?, user_linetoken	= ? WHERE user_id = ?");
        $stmt->execute([$Name, $password, $filePath, $email, $linetoken, $user_id]);
    }
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

    $(document).ready(function() {
        $("form").submit(function(e) {
    e.preventDefault();
    var profileImage = $("#profileImage").val();
    var Name = $("#Name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var linetoken = $("#linetoken").val();

    if (profileImage === '' || Name === '' || email === '' || password === '' || linetoken === '') {
      Swal.fire({
        title: "Error!",
        text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
        icon: "error",
        confirmButtonText: "OK"
      });
    } else {
      // สร้าง FormData object เพื่อรวมข้อมูลและไฟล์ที่ต้องการส่ง
      var formData = new FormData();
      formData.append("profileImage", $("#profileImage")[0].files[0]);
      formData.append("Name", Name);
      formData.append("email", email);
      formData.append("password", password);
      formData.append("linetoken", linetoken);

      // ส่งคำขอ HTTP POST โดยใช้ AJAX
      $.ajax({
        url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
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
          });
          document.getElementById('profileForm').reset();
          document.getElementById('profileImageLabel').innerHTML = 'เลือกไฟล์รูปภาพ';
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

  
});

        $("#resetBtnn").click(function(e) {
            e.preventDefault();
            // ล้างค่าที่กล่องข้อความ
            $("#Name").val("");
            $("#password").val("");
            $("#email").val("");
            $("#profileImage").val("");
            $("#linetoken").val("");
            $("#previewImage").attr("src", "../../Images/User_profile.png");
            $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
        });

        $("form").submit(function(e) {
            e.preventDefault();
            var profileImage = $("#profileImage").val();
            var Name = $("#Name").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var linetoken = $("#linetoken").val();

            if (profileImage === '' || Name === '' || email === '' || password === '' || linetoken === '') {
                Swal.fire({
                    title: "Error!",
                    text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                $("form").off("submit").submit();
                Swal.fire({
                    title: "Success!",
                    text: "บันทึกข้อมูลสำเร็จ",
                    icon: "success",
                    confirmButtonText: "OK"
                });
                document.getElementById('profileForm').reset();
                document.getElementById('profileImageLabel').innerHTML = 'เลือกไฟล์รูปภาพ';
            }
        });

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
    <?php include '../../Menubar/menubar.php' ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">แก้ไขข้อมูลส่วนตัว</h5>
                <form id="profileForm" enctype="multipart/form-data">
                    <center>
                        <div class="mt-2">
                            <img id="previewImage" class="preview-image" src="../../Images/User_profile.png"
                                alt="รูปภาพ"
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
                        <input type="text" class="form-control" id="Name" name="Name">
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสบัตรประชาชน</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="linetoken">Line Token</label>
                        <input type="text" class="form-control" id="linetoken" name="linetoken">
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary" type="submit">บันทึก</button>
                        <button class="btn btn-secondary" id="resetBtnn">ล้างค่า</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>
</body>

</html>