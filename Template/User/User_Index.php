<?php
require_once("../../Database/db.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['id'];


// ส่งคำสั่ง SQL ในการนับ record
$sql = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE user_id = $user_id AND status_id IN (2, 3, 4)";
$result = mysqli_query($conn, $sql);

// ตรวจสอบผลลัพธ์
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $totalRecords = $row['total'];
} else {
  $totalRecords = 0;
}


// ส่งคำสั่ง SQL ในการนับ record
$sql1 = "SELECT COUNT(*) AS total FROM area_repair WHERE user_id = $user_id AND status_id IN (2, 3, 4)";
$result = mysqli_query($conn, $sql1);

// ตรวจสอบผลลัพธ์
if ($result) {
  $row = mysqli_fetch_assoc($result);
  $totalRecords1 = $row['total'];
} else {
  $totalRecords1 = 0;
}


$sql3 = "SELECT COUNT(*) AS total FROM area_repair WHERE user_id = $user_id AND status_id = 1";
$result3 = mysqli_query($conn, $sql3);




$sql4 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE user_id = $user_id AND status_id = 1";
$result4 = mysqli_query($conn, $sql4);

$totalRecords3 = 0;
$totalRecords4 = 0;

if ($result3) {
    $row3 = mysqli_fetch_assoc($result3);
    $totalRecords3 = $row3['total'];
}

// ตรวจสอบผลลัพธ์ของคำสั่ง SQL 4
if ($result4) {
    $row4 = mysqli_fetch_assoc($result4);
    $totalRecords4 = $row4['total'];
}

$totalRecordstotal=  $totalRecords3 + $totalRecords4;











?>


<!DOCTYPE html>
<html>

<head>
    <title>หน้าแรก</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/Index_user.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<style>
body {
    background: -webkit-linear-gradient(left, #FFEBCD, #E6E6FA);
}
</style>
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

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-1">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมครุภัณฑ์</h5>
                        <p class="card-text">การแจ้งซ่อมครุภัณฑ์ที่กำลังดำเนินการ</p>
                        <h1 class="card-text number"> <?php echo $totalRecords; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-2">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมพื้นที่</h5>
                        <p class="card-text">การแจ้งซ่อมพื้นที่ที่กำลังดำเนินการ</p>
                        <h1 class="card-text number"> <?php echo $totalRecords1; ?></h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-4">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">รอการซ่อม</h5>
                        <p class="card-text">รออุปกรณ์การซ่อม</p>
                        <h1 class="card-text number">0</h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-3">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">เสร็จสิ้น</h5>
                        <p class="card-text">การแจ้งซ่อมที่เสร็จสิ้นแล้ว</p>
                        <h1 class="card-text number"> <?php echo $totalRecordstotal; ?></h1>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>
</body>

</html>