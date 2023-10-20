<?php
session_start();
?>

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
            <h2 class="text-center">Officer Login</h2>
            <p>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</p>
            <br>
            <div class="form-group has-error">
                <input type="text" class="form-control" name="officer_id" placeholder="Officer_id, Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="password">
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $officer_id = $_POST['officer_id'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM Officer WHERE officer_id = :officer_id OR officer_email = :officer_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':officer_id', $officer_id);
        $stmt->execute();

        $count = $stmt->rowCount();

        if ($count == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['offer_pass']; // Get the hashed password from the database

            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION['offer_admin'] = $row['offer_admin'];
                $_SESSION['id'] = $row['officer_id'];
                $_SESSION['officer_name'] = $row['officer_name'];
                $_SESSION['officer_id'] = $officer_id;

                if ($_SESSION["offer_admin"] == "Admin") { // If it's an admin, redirect to admin_page.php
                    echo "<script>
                        Swal.fire({
                            title: 'เข้าสู่ระบบสำเร็จ!',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = '../Admin/dashboard.php'; // Link to the desired page
                            }
                        });
                    </script>";
                    exit();
                } else if ($_SESSION["offer_admin"] == "administrative_officer" ||$_SESSION["offer_admin"] == "Dean_it" ) { // If it's a member, redirect to Officer_Index.php
                    echo "<script>
                        Swal.fire({
                            title: 'เข้าสู่ระบบสำเร็จ!',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = '../Officer/dashboard.php'; // Link to the desired page
                            }
                        });
                    </script>";
                    exit();
                }
            } else {
                // Password is incorrect
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
        } else {
            // No matching user found
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
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}
 
?>
