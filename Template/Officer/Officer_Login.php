<!DOCTYPE html>
<html>

<head>
    <title>เข้าสู่ระบบเจ้าหน้าที่</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/loginofficer.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- เพิ่ม script ของ SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php include '../../Navbar/navbar.php' ?>
    <div class="login-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2 class="text-center">Officer Login </h2>
            <p>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</p>
            <br>
            <div class="form-group has-error">
                <input type="text" class="form-control" name="officer_id" placeholder="Officer_ID" required="required">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
            </div>
            <p><a href="#">ลืมรหัสผ่าน</a></p>

        </form>
    </div>
<br>
    <?php include '../../Footer/footer.php' ?> 
</body>

</html>

<?php

require_once("../../Database/db.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $officer_id = $_POST['officer_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM officer WHERE officer_id = '$officer_id' AND offer_pass = '$password'";

    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['officer_id'];
        $_SESSION['officer_name'] = $row['officer_name'];
        $_SESSION['officer_id'] = $officer_id;
        $_SESSION['offer_admin'] = $row['offer_admin'];

        if ($_SESSION["offer_admin"] == "2") { // If it's an admin, redirect to admin_page.php
            echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '../Admin/Admin_Index.php'; // Link to the desired page
                        }
                    });
                </script>";
            exit();
        } else if ($_SESSION["offer_admin"] == "1") { // If it's a member, redirect to Officer_Index.php
            echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'Officer_Index.php'; // Link to the desired page
                        }
                    });
                </script>";
            exit();
        }
    } else {
        $error = "Username หรือ Password ไม่ถูกต้อง";
        echo "<script>
                Swal.fire({
                    title: 'เข้าสู่ระบบไม่สำเร็จ!',
                    text: 'Username หรือ Password ไม่ถูกต้อง',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
    }
}

?>