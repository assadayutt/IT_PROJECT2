<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
</head>

<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="/project/Template/index.php">
        <img src="https://upload.wikimedia.org/wikipedia/th/thumb/b/bb/Informatics_MSU_Logo.svg/1200px-Informatics_MSU_Logo.svg.png"
            width="30" height="30" class="d-inline-block align-top" alt="">
        <code>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</code>
    </a>
    <boby>
        <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
// เช็คสถานะของเซสชั่นและทำตามการดำเนินการที่ต้องการกับค่า session
if (isset($_SESSION['user_name'])):
?>
        <div class="user-info">
            <p href="#"><span>คุณ :</span> <span><?php echo $_SESSION['user_name']; ?></span></p>
        </div>
        <?php elseif (isset($_SESSION['repairman_name'])): ?>
        <div class="user-info">
            <p href="#"><span>คุณ :</span> <span><?php echo $_SESSION['repairman_name']; ?></span></p>
        </div>
        <?php elseif (isset($_SESSION['officer_name'])): ?>
        <div class="user-info">
            <p href="#"><span>คุณ :</span> <span><?php echo $_SESSION['officer_name']; ?></span></p>
        </div>
        <?php else: ?>
        <?php if (basename($_SERVER["PHP_SELF"]) != "User_Login.php"): ?>
        <?php if (basename($_SERVER["PHP_SELF"]) != "Officer_Login.php"): ?>
        <?php if (basename($_SERVER["PHP_SELF"]) != "Repairman_Login.php"): ?>
        <button class="btn btn-primary ml-auto flex-grow-0"
            onclick="window.location.href='/project/Template/User/User_Login.php'">เข้าสู่ระบบ</button>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>

        <?php if(basename($_SERVER["PHP_SELF"]) == "User_Login.php"): ?>
        <button class="btn btn-primary ml-auto mr-2"
            onclick="window.location.href='/project/Template/Repairman/Repairman_Login.php'">ช่างซ่อม</button>
        <button class="btn btn-primary"
            onclick="window.location.href='/project/Template/Officer/Officer_Login.php'">เจ้าหน้าที่</button>
        <?php endif; ?>

        <?php endif; ?>

</nav>
</body>

</html>