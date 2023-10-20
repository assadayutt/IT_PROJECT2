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
?>
<!DOCTYPE html>
<html>


<head>
    <title>เพิ่มรายการแจ้งซ่อม</title>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
    body {
        background: -webkit-linear-gradient(left, #FFEBCD, #E6E6FA);
    }
    </style>
</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>
    <br>
    <br><br>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <img src="../../Images/Image2.jpg" class="card-img-top" alt="card-image">
                    <div class="card-body">
                        <h2 class="card-title">แจ้งซ่อมครุภัณฑ์</h2>
                        <p class="card-text">เพิ่มรายการแจ้งซ่อมครุภัณฑ์ที่อยู่ในคณะวิทยาการสารสนเทศ</p>
                        <a class="btn btn-success" href="./User_Add_repair_equipment.php">เลือก</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <img src="../../Images/Image1.jpg" class="card-img-top" alt="card-image">
                    <div class="card-body">
                        <h2 class="card-title">แจ้งซ่อมพื้นที่</h2>
                        <p class="card-text">เพิ่มรายการแจ้งซ่อมบริเวณคณะวิทยาการสารสนเทศ</p>
                        <a class="btn btn-danger" href="./User_Add_repair_address.php">เลือก</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    <br><br> <br><br> <br>
    <?php include '../../Footer/footer.php' ?>
</body>

</html>