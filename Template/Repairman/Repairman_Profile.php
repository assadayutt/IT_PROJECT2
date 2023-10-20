<?php
require_once("../../Database/db.php");
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: /project/Template/User/User_login.php");
    exit();
}
$repairman_id = $_SESSION['id'];



try {
 
    $sql = "SELECT * FROM Repairman WHERE repairman_id = :repairman";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':repairman',$repairman_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $repairman_name = $row['repairman_name'];
        $repairman_pic = $row['repairman_pic'];
        $repairman_Email = $row['repairman_Email'];
        $Line_Token = $row['Line_Token'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
} 

$totalScore = 0;

try {
    $sql1 = "SELECT SUM(score) AS total_score
            FROM (
                SELECT score FROM Equipment_Assign_work WHERE repairman_id = :repairmanId
                UNION ALL
                SELECT score FROM Area_Assign_work WHERE repairman_id = :repairmanId
            ) AS combined_scores;";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':repairmanId', $repairman_id, PDO::PARAM_INT);
    $stmt1->execute();

    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($result1 && isset($result1['total_score'])) {
        $totalScore = $result1['total_score'];
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/User_Profilee.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <style>
    body {
        background-color: #f3f3f3;
        font-family: 'Roboto', sans-serif;
    }

    .profile-container {
        text-align: center;
    }

    .profile-img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        border: 4px solid #fff;
        margin: 0 auto;
        object-fit: cover;
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



    .heading {
        font-size: 25px;
        margin-right: 25px;
    }

    .fa {
        font-size: 25px;
    }

    .checked {
        color: orange;
    }

    .side {
        float: left;
        width: 15%;
        margin-top: 10px;
    }

    .middle {
        margin-top: 10px;
        float: left;
        width: 70%;
    }

    .right {
        text-align: right;
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .bar-container {
        width: 100%;
        background-color: #f1f1f1;
        text-align: center;
        color: white;
    }

    .bar-5 {
        width: 60%;
        height: 18px;
        background-color: #04AA6D;
    }

    .bar-4 {
        width: 30%;
        height: 18px;
        background-color: #2196F3;
    }

    .bar-3 {
        width: 10%;
        height: 18px;
        background-color: #00bcd4;
    }

    .bar-2 {
        width: 4%;
        height: 18px;
        background-color: #ff9800;
    }

    .bar-1 {
        width: 15%;
        height: 18px;
        background-color: #f44336;
    }

    @media (max-width: 700px) {

        .side,
        .middle {
            width: 100%;
        }

        .right {
            display: none;
        }
    }
    </style>

</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php' ?>

    <div class="container emp-profile">
        <div class="row profile-container">
            <div class="col-md-12">
                <div class="profile-img">
                    <img src="../../Images/repairman/<?php echo $repairman_pic ?>" alt="picture"
                        style=" border-radius: 50%;  width: 200px;  height: 200px;" />
                </div>
            </div>
            <div class="col-md-8 offset-md-2">
                <div class="profile-head">
                    <h2><?php echo $repairman_name; ?></h2>
                    <h4>ช่างซ่อมภายในคณะ</h4>
                    <br>
                    <a href="/project/Template/Repairman/Repairman_Profile_edit.php"
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
                        <p class="card-text"><?php echo $repairman_name; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">E-mail</h5>
                        <p class="card-text"><?php echo $repairman_Email; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Line Token</h5>
                        <p class="card-text"><?php echo $Line_Token; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">คะแนนที่ได้รับ</h5>
                        <p class="card-text">คะแนนรวม : <span style="font-weight: bold; color: green;"><?php echo $totalScore; ?></span> คะแนน</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>




</html>