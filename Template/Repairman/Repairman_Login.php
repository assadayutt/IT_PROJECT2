<?php
session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>เข้าสู่ระบบช่างซ่อม</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/loginrepairman.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- เพิ่ม script ของ SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <div class="login-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h3 class="text-center"> เข้าสู่ระบบ สำหรับช่างซ่อม </h3>
            <p>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</p>
            <br>
            <div class="form-group has-error">
                <input type="text" class="form-control" name="Email" placeholder="Email" required="required">
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
<br>
    <?php include '../../Footer/footer.php' ?> 
</body>

</html>

<?php
require_once("../../Database/db.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repairman_Email = $_POST['Email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM Repairman WHERE repairman_Email = :repairman_Email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':repairman_Email', $repairman_Email);
        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['repairman_pass'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $row['repairman_id'];
                $_SESSION['repairman_name'] = $row['repairman_name'];
                $_SESSION['repairman_ID'] = $repairman_ID;

                echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'Repairman_Index.php'; // ลิ้งค์ไปยังหน้าที่ต้องการ
                        }
                    })
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบไม่สำเร็จ!',
                        text: 'Username หรือ Password ไม่ถูกต้อง',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    })
                </script>";
            }
        } else {
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
