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


// ตรวจสอบและดำเนินการกับข้อมูลที่ส่งมาจากแบบฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['detail']) && isset($_POST['problem']) && isset($_POST['address']) && isset($_FILES['profileImage'])) {
        $detail = $_POST['detail'];
        $problem = $_POST['problem'];
        $address = $_POST['address'];
        $picture = $_FILES['profileImage'];
        $user_id = $_SESSION['id'];
    }
    // ตรวจสอบและบันทึกไฟล์ภาพ
   // ตรวจสอบและบันทึกไฟล์ภาพ
if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
    $picture = $_FILES['profileImage'];
    if (is_uploaded_file($picture['tmp_name'])) {
        // กำหนดตำแหน่งและชื่อไฟล์ในระบบไฟล์
        $uploadDir = '../../Images/Repair_Address/';
        $fileName = uniqid() . '_' . $picture['name'];

        // บันทึกไฟล์ภาพในระบบไฟล์
        if (move_uploaded_file($picture['tmp_name'], $uploadDir . $fileName)) {
            // ไม่ต้องบันทึกพาธเต็มในฐานข้อมูล แต่เก็บชื่อไฟล์ภาพเท่านั้น
            $filePath = $fileName;
        } else {
            // ไม่สามารถอัปโหลดไฟล์ได้
            $filePath = '';
        }
    } else {
        // ไม่พบไฟล์ที่อัปโหลด
        $filePath = '';
    }
} else {
    $filePath = '';
}

// ทำการบันทึกข้อมูล
$stmt = $conn->prepare("INSERT INTO Area_repair(area_detail, area_problem, area_address, area_date, area_imagesbefor, user_id, repairman_id, status_id) 
VALUES (?, ?, ?, CURDATE(), ?, ?,999,4)");
$stmt->execute([$detail, $problem, $address,$filePath, $user_id]);

} 

?>

<!DOCTYPE html>
<html>

<head>
    <title>เพิ่มรายการแจ้งซ่อมพื้นที่</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/TableUser.css">
    <link rel="stylesheet" href="../../CSS/Add_repair_address.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

        $("#resetBtnn").click(function(e) {
            e.preventDefault();

            // ล้างค่าที่กล่องข้อความ
            $("#detail").val("");
            $("#problem").val("");
            $("#address").val("");
            $("#previewImage").attr("src", "../../Images/blank-image.jpeg");
            $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
        });

        $("form").submit(function(e) {
            e.preventDefault();

            // ดึงค่าที่กล่องข้อความ
            var detail = $("#detail").val();
            var problem = $("#problem").val();
            var address = $("#address").val();
            var picture = $("#profileImage").val();

            // ตรวจสอบข้อมูลว่าถูกกรอกหรือไม่
            if (detail === "" || problem === "" || picture === "" || address === "" || problem === "") {
                Swal.fire({
                    title: "Error!",
                    text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                // สร้าง FormData object เพื่อรวมข้อมูลและไฟล์ที่ต้องการส่ง
                var formData = new FormData();
                formData.append("detail", detail);
                formData.append("problem", problem);
                formData.append("address", address);
                formData.append("profileImage", $("#profileImage")[0].files[0]);

                // ส่งคำขอ HTTP POST โดยใช้ AJAX
                $.ajax({
                    url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        sendLineNotify()
                        Swal.fire({
                            title: "Success!",
                            text: "บันทึกข้อมูลสำเร็จ",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href =
                                    "../../Template/User/User_ListRepair.php";
                            }
                        });
                        document.getElementById('repairForm').reset();
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

    function sendLineNotify() {
        $.ajax({
            url: '/project/AJAX/User_AJAX/Get_repairman_linetoken.php',
            method: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.lineTokens && data.lineTokens.length > 0) {
                    const lineTokens = data.lineTokens;
                    const message = "มีการแจ้งซ่อมพื้นที่ใหม่";

                    lineTokens.forEach(lineToken => {
                        sendLineMessage(lineToken, message);
                    });
                } else {
                    console.error("ไม่พบ Line Token หรือเกิดข้อผิดพลาดในการรับ Line Token");
                }
            },
            error: function(xhr, status, error) {
                const lineToken = xhr.getResponseHeader('Authorization');
                console.error("sendLineNotify_เกิดข้อผิดพลาดในการร้องขอ Line Token: " + error +
                    " (Line Token: " +
                    lineToken + ")");
            }
        });
    }

  function sendLineMessage(lineTokens, message) {
    const formData = new URLSearchParams();
    formData.append('message', message);
    formData.append('lineToken', lineTokens);

    var requestUrl = 'https://ims-project-server.vercel.app/send-line-notify'; // URL ถูกต้องและคงที่
    
    $.ajax({
        url: requestUrl,
        method: 'POST',
        data: formData.toString(), // แปลง FormData เป็น string
        contentType: 'application/x-www-form-urlencoded',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                console.log("ส่งข้อความ Line Notify สำเร็จ!");
            } else {
                console.error("ส่งข้อความ Line Notify ไม่สำเร็จ!");
            }
        },
        error: function(xhr, status, error) {
            const lineTokens = xhr.getResponseHeader('Authorization');
            console.error("เกิดข้อผิดพลาดในการส่งข้อความ Line Notify: " + error +
                " (Line Token: " + lineTokens + ")");
        }
    });
}


    </script>
    <style>
    .card {
        min-height: 850px;
    }

    .custom-file {
        margin-top: 10px;
    }

    .card-footer {
        margin-top: auto;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    </style>


</head>

<body>
    <?php include '../../Navbar/navbar.php' ?>
    <?php include '../../Menubar/menubar.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form id="repairForm" enctype="multipart/form-data" class="card mt-5">

                    <div class="card-header">
                        <h2 class="text-center">เพิ่มรายการแจ้งซ่อมพื้นที่</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="detail"><span style="color: red;">*</span> กรอกรายละเอียดการแจ้งซ่อม</label>
                            <input type="text" id="detail" name="detail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="problem"><span style="color: red;">*</span> อธิบายปัญหาที่พบ</label>
                            <input type="text" id="problem" name="problem" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address"><span style="color: red;">*</span> พื้นที่ที่พบปัญหา</label>
                            <input type="text" id="address" name="address" class="form-control">
                        </div>
                        <div class="mt-2">
                            <img id="previewImage" class="preview-image" src="../../Images/blank-image.jpeg"
                                alt="รูปภาพ" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <br>
                        <label for="picture"><span style="color: red;">*</span> อัพโหลดรูปภาพ</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profileImage" name="profileImage"
                                onchange="showPreview()">
                            <label class="custom-file-label" for="profileImage"
                                id="profileImageLabel">เลือกไฟล์รูปภาพ</label>
                        </div> 

                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-success" type="submit">บันทึก</button>
                        <a class="btn btn-danger" id="resetBtnn">ล้างค่า</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>

    <?php include '../../Footer/footer.php'; ?>
</body>

</html>