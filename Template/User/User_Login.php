<?php 
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/loginuser.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- เพิ่ม script ของ SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <div class="login-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2 class="text-center">Login</h2>
            <p>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</p>
            <br>
            <div class="form-group has-error">
            <input type="text" class="form-control" name="username" placeholder="Student_id or email " required="required" autocomplete="username">
            </div>
            <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="ID Card" required="required" autocomplete="current-password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
            </div>
            <p><a href="#">ลืมรหัสผ่าน</a></p>

        </form>
    </div>
<br>
<br>
<br>
<br>
 <?php include '../../Footer/footer.php' ?> 
</body>

</html>
<?php
require_once("../../Database/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        
        $sql = "SELECT * FROM User WHERE user_stu = :username OR user_email =:username AND user_pass = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['user_stu'] = $username;

            echo "<script>
                Swal.fire({
                 title: 'เข้าสู่ระบบสำเร็จ!',
                 icon: 'success',
                 confirmButtonText: 'ตกลง'
                    }).then((result) => {
                 if (result.isConfirmed) {
                window.location = 'User_Index.php'; // ลิ้งค์ไปยังหน้าที่ต้องการ
      }
                    })
                </script>";
        } else {
            $error = "Username หรือ Password ไม่ถูกต้อง";
            echo "<script>
                Swal.fire({
                  title: 'เข้าสู่ระบบไม่สำเร็จ!',
                 text: 'Username หรือ Password ไม่ถูกต้อง',
                 icon: 'error',
                 confirmButtonText: 'ตกลง'
                    })
                </script>";
        }
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
?>
