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
$repairman_id = $_SESSION['id'];

try {
    // ส่งคำสั่ง SQL ในการนับ record
    $sql = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE  status_id = 4";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $totalRecords = $stmt->fetchColumn();

    // ส่งคำสั่ง SQL ในการนับ record
    $sql1 = "SELECT COUNT(*) AS total FROM Area_repair WHERE status_id = 4";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $totalRecords1 = $stmt1->fetchColumn();

    $sql3 = "SELECT COUNT(*) AS total FROM Area_repair WHERE repairman_id = :repairman_id AND status_id = 2";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt3->execute();
    $totalRecords3 = $stmt3->fetchColumn();

    // ส่งคำสั่ง SQL ในการนับ record
    $sql4 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE repairman_id = :repairman_id AND status_id = 2";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT); // ต้องผูกค่าพารามิเตอร์เหมือนกับ $stmt3
    $stmt4->execute();
    $totalRecords4 = $stmt4->fetchColumn();

    $sql5 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE repairman_id = :repairman_id AND status_id = 6";
    $stmt5 = $conn->prepare($sql5);
    $stmt5->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt5->execute();
    $totalRecords5 = $stmt5->fetchColumn();

    $sql6 = "SELECT COUNT(*) AS total FROM Area_repair WHERE repairman_id = :repairman_id AND status_id = 6";
    $stmt6 = $conn->prepare($sql6);
    $stmt6->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt6->execute();
    $totalRecords6 = $stmt6->fetchColumn();

    $sql7 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE repairman_id = :repairman_id AND status_id = 7";
    $stmt7 = $conn->prepare($sql7);
    $stmt7->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt7->execute();
    $totalRecords7 = $stmt7->fetchColumn();

    $sql8 = "SELECT COUNT(*) AS total FROM Area_repair WHERE repairman_id = :repairman_id AND status_id = 7";
    $stmt8 = $conn->prepare($sql8);
    $stmt8->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt8->execute();
    $totalRecords8 = $stmt8->fetchColumn();

    $sql9 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE repairman_id = :repairman_id AND status_id = 1";
    $stmt9 = $conn->prepare($sql9);
    $stmt9->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt9->execute();
    $totalRecords9 = $stmt9->fetchColumn();

    $sql10 = "SELECT COUNT(*) AS total FROM Area_repair WHERE repairman_id = :repairman_id AND status_id = 1";
    $stmt10 = $conn->prepare($sql10);
    $stmt10->bindParam(":repairman_id", $repairman_id, PDO::PARAM_INT);
    $stmt10->execute();
    $totalRecords10 = $stmt10->fetchColumn();


    $complete_repair = $totalRecords9 + $totalRecords10;

    $watting_repair = $totalRecords7 + $totalRecords8;

    $repairman_out =  $totalRecords5 + $totalRecords6;

    $totalRecordstotal1 = $totalRecords3 + $totalRecords4;

} catch (PDOException $e) {
    // จัดการข้อผิดพลาดที่เกิดขึ้น
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>หน้าแรก</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/Repairman_Index.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<script>
const cards = document.querySelectorAll('.custom-card');

cards.forEach(card => {
    card.addEventListener('mouseover', () => {
        card.classList.add('hover');
    });

    card.addEventListener('mouseout', () => {
        card.classList.remove('hover');
    });
});
</script>

<script>
$(document).ready(function() {
    // เรียกฟังก์ชันเมื่อหน้าเว็บโหลดเสร็จ
    checkToken();
});

function checkToken() {
    var repairman_id = <?php echo json_encode($_SESSION['id']); ?>;
    console.log("repairman_id : " + repairman_id);
    $.ajax({
        url: '../../AJAX/Repairman_AJAX/Check_token_repairman.php',
        method: 'POST',
        data: {
            repairman_id: repairman_id
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'empty') {
                Swal.fire({
                    title: 'กรุณาใส่ Line_Token',
                    text: 'เพื่อรับการแจ้งเตือนจาก Line Notify',
                    icon: 'info',
                    input: 'text',
                    inputPlaceholder: 'กรอก Token ของคุณ',
                    showCancelButton: true,
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: (token) => {
                        if (!token) {
                            Swal.showValidationMessage('Token ต้องไม่เป็นค่าว่าง');
                        }
                        return token;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        saveToken(repairman_id, result.value);
                    }
                });
            }
        }
    });
}

function saveToken(repairman_id, token) {
    $.ajax({
        url: '../../AJAX/Repairman_AJAX/save_token_repairman.php',
        method: 'POST',
        data: {
            repairman_id: repairman_id,
            token: token
        },
        dataType: 'json',
        success: function(response) {
            sendLineNotify(repairman_id)
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Token ถูกบันทึก!',
                    text: 'โปรดรอรับการแจ้งเตือนครั้งแรก'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถบันทึก Token ได้'
                });
            }
        }
    });
}

function sendLineNotify(repairman_id) {
    $.ajax({
        url: '/project/AJAX/Repairman_AJAX/Get_repairman_lineToken_Where_id.php',
        method: 'POST',
        data: {
            repairman_id: repairman_id
        }, // ส่ง user_id ไปเพื่อใช้ใน Get_lineToken_Where_id.php
        dataType: 'json',
        success: function(data) {
            if (data.lineTokens && data.lineTokens.length > 0) {
                const lineTokens = data.lineTokens;
                const message = "คุณได้ทำการเพิ่ม Line_Token ในระบบแจ้งซ่อม - IMS เป็นที่เรียบร้อยแล้ว คุณสามารถรับการแจ้งเตือน ได้ทันที!!!";

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


<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php'; ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-1">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมครุภัณฑ์</h5>
                        <p class="card-text">การแจ้งซ่อมครุภัณฑ์ที่แจ้งเข้ามา</p>
                        <h1 class="card-text number"> <?php echo $totalRecords; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-2">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมพื้นที่</h5>
                        <p class="card-text">การแจ้งซ่อมพื้นที่แจ้งเข้ามา</p>
                        <h1 class="card-text number"> <?php echo $totalRecords1; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-3">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">รายการที่รับงานซ่อม</h5>
                        <p class="card-text">จำนวนรายการที่รับงานซ่อม</p>
                        <h1 class="card-text number"> <?php echo $totalRecordstotal1; ?></h1>

                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-4">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">รอการซ่อม</h5>
                        <p class="card-text">จำนวนการแจ้งซ่อมที่ต้องใช้ช่างภายนอก</p>
                        <h1 class="card-text number"> <?php echo $repairman_out; ?></h1>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-3">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">รายการที่ต้องรออะไหล่</h5>
                        <p class="card-text">รายการที่ต้องรออะไหล่</p>
                        <h1 class="card-text number"> <?php echo $watting_repair; ?></h1>
                        
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-3">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">เสร็จสิ้น</h5>
                        <p class="card-text">การแจ้งซ่อมที่เสร็จสิ้นแล้ว</p>
                        <h1 class="card-text number"> <?php echo $complete_repair; ?></h1>
                        
                    </div>
                </div>
            </div>



        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <?php include '../../Footer/footer.php'; ?>
</body>

</html>