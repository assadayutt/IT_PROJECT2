<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    .navbar {
        background-color: white;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
       
        /* ให้ navbar อยู่ด้านบนของเนื้อหา */
    }

    .navbar-nav .nav-link {
        margin-right: 20px;
        margin-left: 50px;
    }

    /* เพิ่มพื้นที่ด้านล่างของเนื้อหาเพื่อไม่ให้ navbar ปิดบังเนื้อหา */
    body {
        padding-bottom: 60px;
        /* ค่านี้ควรจะเป็นค่าความสูงของ navbar */
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid justify-content-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarToggler">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/Repairman_Index.php">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/Repairman_Listrepair.php">
                            <i class="fas fa-list"></i> รายการแจ้งซ่อมทั้งหมด
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/Repairman_working.php">
                            <i class="fas fa-tools"></i> รายการที่รับงาน
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/Repairman_History.php">
                            <i class="fas fa-history"></i> ประวัติการซ่อม
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/maintenance_approval.php">
                            <i class="fas fa-toolbox"></i> ขออนุมัติซ่อมบำรุง
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/Repairman/Repairman_Profile.php">
                            <i class="fas fa-user"></i> ข้อมูลส่วนตัว
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</body>

</html>