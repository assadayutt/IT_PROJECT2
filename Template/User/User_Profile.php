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
    <link rel="stylesheet" href="../../CSS/User_Profilee.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<style>
    /* Custom styles for this page */
    body {
        background-color: #f3f3f3;
        font-family: 'Roboto', sans-serif;
    }

    .profile-container {
        text-align: center;
        /* Center the content horizontally */
    }

    .profile-img {
        width: 200px;
        /* Adjust the image size as needed */
        height: 200px;
        border-radius: 50%;
        border: 4px solid #fff;
        margin: 0 auto;
        /* Center the image horizontally */
        object-fit: cover;
        /* Ensure the image fills the circular container */
    }

    .profile-head {
        margin-top: 20px;
    }

    .profile-edit-btn {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-align: center;
        font-size: 16px;
        text-decoration: none;
    }

    .profile-edit-btn:hover {
        background-color: #0056b3;
    }

    .card {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .card-title {
        color: #007bff;
    }

    * {
        box-sizing: border-box;
    }

    </style>
<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>

    <div class="container emp-profile">
        <div class="row profile-container">
            <div class="col-md-12">
                <!-- Full width for the image -->
                <div class="profile-img">
                    <img src="../../Images/User/<?php echo $picture ?>" alt="picture"
                        style=" border-radius: 50%;  width: 200px;  height: 200px;" />
                </div>
            </div>
            <div class="col-md-8 offset-md-2">
                <!-- Centered content below the image -->
                <div class="profile-head">
                    <h2><?php echo $username; ?></h2>
                    <h4>นิสิต / อาจารย์ / บุคลากร</h4>
                    <br>
                    <a href="/project/Template/User/User_Profile_Edit.php"
                        class="profile-edit-btn">Edit Profile</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ชื่อ - สกุล</h5>
                        <p class="card-text"><?php echo $username; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">E-mail</h5>
                        <p class="card-text"><?php echo $email; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Line Token</h5>
                        <p class="card-text"><?php echo $user_linetoken; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ID_Card</h5>
                        <p class="card-text"><?php echo $id_card; ?></p>
                    </div>
                </div>
            </div>



        </div>
    </div>
    <br>
    <br>
    <?php include '../../Footer/footer.php' ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>