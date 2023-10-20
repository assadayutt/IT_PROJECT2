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


    $id_equipment = isset($_POST['id_equipment']) ? $_POST['id_equipment'] : '';
    $problem = isset($_POST['problem']) ? $_POST['problem'] : '';
    $picture = isset($_FILES['profileImage']) ? $_FILES['profileImage'] : '';
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $id_eq = isset($_POST['id_eq']) ? $_POST['id_eq'] : '';

    // ตรวจสอบและบันทึกไฟล์ภาพ
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $picture = $_FILES['profileImage'];
        if (is_uploaded_file($picture['tmp_name'])) {
            // กำหนดตำแหน่งและชื่อไฟล์ในระบบไฟล์
            $uploadDir = '../../Images/Repair_equipment/'; // เปลี่ยนเป็นตำแหน่งที่คุณต้องการเก็บไฟล์
            $fileName = uniqid() . '_' . $picture['name'];

            // บันทึกไฟล์ภาพในระบบไฟล์
            if (move_uploaded_file($picture['tmp_name'], $uploadDir . $fileName)) {
                // บันทึกที่อยู่ของไฟล์ภาพลงในฐานข้อมูล
                $filePath = $uploadDir . $fileName;
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
   
            // โค้ดส่วนที่มีการเชื่อมต่อฐานข้อมูลและประมวลผลคำสั่ง SQL
        $stmt = $conn->prepare("INSERT INTO Equipment_repair(repair_detail, equipment_id, equipment_number, repair_date,  repair_imagesbefor, user_id, repairman_id, status_id) 
        VALUES (?, ?, ?, CURDATE(), ?, ?, 999, 4)");
        $success = $stmt->execute([$problem, $id_eq, $id_equipment, $filePath, $user_id]);


    }
  
?>
<!DOCTYPE html>
<html>

<head>
    <title>เพิ่มรายการแจ้งซ่อมครุภัณฑ์</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../CSS/Add_repair_equipment.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
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

        $("#resetBtn").click(function(e) {
            e.preventDefault();

            // ล้างค่าที่กล่องข้อความ
            $("#id_equipment").val("");
            $("#problem").val("");
            $("#profileImage").val("");
            $("#name").val("");
            $("#type").val("");
            $("#color").val("");
            $("#address").val("");
            $("#previewImage").attr("src", "../../Images/blank-image.jpeg");
            $("#profileImageLabel").html("เลือกไฟล์รูปภาพ");
        });

        $("#checkBtn").click(function(e) {
            e.preventDefault();

            // ดึงค่ารหัสครุภัณฑ์จาก input
            var equipmentCode = $("#id_equipment").val();

            // ตรวจสอบการกรอกข้อมูล
            if (equipmentCode === '') {
                Swal.fire({
                    title: "Error!",
                    text: "กรุณากรอกเลขครุภัณฑ์",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                // สร้าง AJAX request
                $.ajax({
                    url: "../../AJAX/User_AJAX/AJAX_get_Equipment.php",
                    type: "GET",
                    data: {
                        equipmentCode: equipmentCode
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#id_eq").val(response.equipmentID);
                            $("#name").val(response.equipmentName);
                            $("#type").val(response.equipmentType);
                            $("#color").val(response.equipmentColor);
                            $("#address").val(response.equipmentAddress);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "ไม่พบครุภัณฑ์ที่คุณกรอก",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        Swal.fire({
                            title: "Error!",
                            text: "เกิดข้อผิดพลาดในการดึงข้อมูล: " + error,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });

        $("form").submit(function(e) {
            e.preventDefault();

            // ดึงค่าที่กล่องข้อความ
            var id_equipment = $("#id_equipment").val();
            var problem = $("#problem").val();
            var picture = $("#profileImage").val();
            var id_eq = $("#id_eq").val();

            // ตรวจสอบข้อมูลว่าถูกกรอกหรือไม่
            if (id_equipment === "" || problem === "" || picture === "" || id_eq === "") {
                Swal.fire({
                    title: "Error!",
                    text: "กรุณากรอกข้อมูลให้ครบทุกช่อง",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                var formData = new FormData(this);
                var id_eq = $("#id_eq").val();
                formData.append("id_eq", id_eq);

                $.ajax({
                    url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        sendLineNotify();
                        Swal.fire({
                            title: "Success!",
                            text: "ข้อมูลถูกบันทึกแล้ว",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((result) => {
                            if (result.isConfirmed) {

                                window.location.href =
                                    "../../Template/User/User_ListRepair.php";
                            }
                        });
                    },
                    error: function(xhr, status, error) {}
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
                    const message = "มีการแจ้งซ่อมครุภัณฑ์ใหม่";
 
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
        min-height: 800px;
    }
    </style>
</head>

<body>
    <?php include '../../Navbar/navbar.php' ?>
    <?php include '../../Menubar/menubar.php' ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="card mt-5"
                    enctype="multipart/form-data">
                    <div class="card-header">
                        <h2 class="text-center">เพิ่มรายการแจ้งซ่อมครุภัณฑ์</h2>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id_equipment"><span style="color: red;">*</span> กรอกรหัส /
                                หมายเลขครุภัณฑ์</label><br>
                            <input type="text" id="id_equipment" name="id_equipment" class="form-control">
                        </div>
                        <p> กดปุ่ม ตรวจสอบข้อมูล เพื่อดูรายละเอียดครุภัณฑ์ </P>

                        <button type="button" id="checkBtn" class="btn btn-primary">ตรวจสอบข้อมูล</button>
                        <br>
                        <div class="form-group">
                            <label for="problem"><span style="color: red;">*</span> อธิบายปัญหาที่พบ</label>
                            <input type="text" id="problem" name="problem" class="form-control">
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
                    <br>
                    <div class="card-footer text-center">
                        <button class="btn btn-success" type="submit">บันทึก</button>
                        <a class="btn btn-danger" id="resetBtn">ล้างค่า</a>

                    </div>

            </div>
            <div class="col-lg-6">

                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="text-center">รายละเอียดครุภัณฑ์</h2>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="name">รหัสครุภัณฑ์</label>
                            <input type="text" disabled="disabled" id="id_eq" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="name">ชื่อครุภัณฑ์</label>
                            <input type="text" disabled="disabled" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="type">ยี่ห้อครุภัณฑ์</label>
                            <input type="text" disabled="disabled" id="type" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="color">สี</label>
                            <input type="text" disabled="disabled" id="color" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">ที่อยู่ครุภัณฑ์</label>
                            <input type="text" disabled="disabled" id="address" class="form-control">
                        </div>
                        <p> โปรดตรวจสอบรายละเอียดครุภัณฑ์ก่อนบันทึกการแจ้งซ่อม </P>
                    </div>
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