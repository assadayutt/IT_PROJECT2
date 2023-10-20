<?php
require_once("../../Database/db.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_Login.php");
    exit();
}
$user_id = $_SESSION['id'];


try {
 
    // ดึงข้อมูลผู้ใช้งานจากตาราง "user" โดยใช้ session id
    $sql = "SELECT * FROM User WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['id']);
    $stmt->execute();

    // ตรวจสอบว่ามีข้อมูลผู้ใช้งานหรือไม่
    if ($stmt->rowCount() > 0) {
        // ดึงข้อมูลผู้ใช้งาน
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $username = $row['user_name'];
        $id = $row['user_stu'];
        $id_card = $row['user_pass'];
        $email = $row['user_email'];
        $picture = $row['user_pic'];
        $user_linetoken = $row['user_linetoken'];
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
            $("#password").val("");
            $("#email").val("");
            $("#profileImage").val("");
            $("#linetoken").val("");
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

    function updateProfile(user_id) {

        console.log("รหัสผู้ใช้เพื่อแก้ไขข้อมูล : " + user_id);

        var profileImage = $("#profileImage").val();
        var Name = $("#Name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var linetoken = $("#linetoken").val();

        console.log("รูปภาพ :", profileImage);
        console.log("ชื่อ :", Name);
        console.log("E=Mail :", email);
        console.log("รหัสผ่าน :", password);
        console.log("ไลน์ Token :", linetoken);



        if (Name === '' || email === '' || password === '' || linetoken === '') {
            Swal.fire({
                title: "Error!",
                text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        } else {
            var formData = new FormData();
            formData.append("profileImage", $("#profileImage")[0].files[0]);
            formData.append("Name", Name);
            formData.append("email", email);
            formData.append("password", password);
            formData.append("linetoken", linetoken);
            formData.append("user_id", user_id);

 
            $.ajax({
                url: location.origin + "/project/AJAX/User_AJAX/update_profile.php",
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
                            window.location.href = "../../Template/User/User_Profile.php";
                        }
                    });

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
    <?php include '../../Menubar/menubar.php' ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">แก้ไขข้อมูลส่วนตัว</h5>
                <form id="profileForm" enctype="multipart/form-data">
                    <center>
                        <div class="mt-2">

                            <img id="previewImage" class="preview-image" src="../../Images/User/<?php echo $picture; ?>"
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
                        <input type="text" class="form-control" id="Name" name="Name" value="<?php echo $username; ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสบัตรประชาชน</label>
                        <input type="text" class="form-control" id="password" name="password"
                            value="<?php echo $id_card; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <label for="linetoken">Line Token</label>
                        <input type="text" class="form-control" id="linetoken" name="linetoken"
                            value="<?php echo $user_linetoken; ?>">
                    </div>

                    <div class="card-footer text-center">
                        <button class="btn btn-primary" type="button"
                            onclick="updateProfile(<?php echo $_SESSION['id']; ?>)">บันทึก</button>
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