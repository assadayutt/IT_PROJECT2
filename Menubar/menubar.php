<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    .navbar {
        background-color: white;

    }

    .navbar-nav .nav-link {
        margin-right: 100px;
        margin-left: 50px;

    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
                        <a class="nav-link" href="/project/Template/User/User_Index.php">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/User/User_Add_repair.php">
                            <i class="fas fa-wrench"></i> แจ้งซ่อม
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/User/User_ListRepair.php">
                            <i class="fas fa-list"></i> รายการแจ้งซ่อม
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/User/User_History.php">
                            <i class="fas fa-history"></i> ประวัติการแจ้งซ่อม
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project/Template/User/User_Profile.php">
                            <i class="fas fa-user"></i> ข้อมูลส่วนตัว
                        </a>
                    </li>
                </ul>
            </div>


        </div>
    </nav>
</body>

</html>