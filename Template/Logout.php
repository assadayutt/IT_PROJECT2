<?php
session_start(); // เริ่ม session
session_destroy(); // ลบ session ทั้งหมด
?>
<!DOCTYPE html>
<html>
<head>
    <title>ออกจากระบบ</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<script>
    Swal.fire({
        title: "ออกจากระบบสำเร็จ",
        icon: "success",
        confirmButtonText: "OK" 
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php";
        }
    });
</script>
</body>
</html>



