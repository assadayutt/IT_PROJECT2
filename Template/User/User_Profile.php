<?php
// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
require_once("../../Database/db.php");

// เริ่ม session
session_start();

// ตรวจสอบว่าผู้ใช้งานล็อกอินหรือไม่
if (!isset($_SESSION['id'])) {
    // ถ้าไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้าล็อกอินหรือที่ต้องการ
    header("Location: /project/Template/User/User_login.php");
    exit();
}

// ดึงข้อมูลผู้ใช้งานจากตาราง "user" โดยใช้ session id
$sql = "SELECT * FROM User WHERE user_id = " . $_SESSION['id'];
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลผู้ใช้งานหรือไม่
if ($result->num_rows > 0) {
    // ดึงข้อมูลผู้ใช้งาน
    $row = $result->fetch_assoc();
    $username = $row['user_name'];
    $id = $row['user_stu'];
    $id_card = $row['user_pass'];
    $email = $row['user_email'];
    $picture = $row['user_pic'];
    $user_linetoken	 = $row['user_linetoken'];
}
    $conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/User_Profilee.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>

    <div class="container emp-profile">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-img">
                        <img src="../../Images/User/<?php echo $picture; ?> " alt="picture"
                            style="width: 300px; height: 300px; border-radius: 50%; " />
                    </div>
                    <br>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5>
                            <?php echo $username; ?>
                        </h5>
                        <h6>
                            User : นิสิต / อาจารย์
                        </h6>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Information</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="/project/Template/User/User_Profile_Edit.php" class="profile-edit-btn" style="text-decoration: none;">
                        Edit Profile
                    </a>
                </div>

                <br>

            </div>
            <div class="col-md-8">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Student ID / Teacher ID</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $id; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Name</label>
                            </div>
                            <div class="col-md-6">
                                <p> <?php echo $username; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Email</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $email; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>ID : Card</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $id_card; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Line token</label>
                            </div>
                            <div class="col-md-6">
                                <p><?php echo $user_linetoken; ?></p> 
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    </form>
    </div>



    <br>
    <br>

    <?php include '../../Footer/footer.php' ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>