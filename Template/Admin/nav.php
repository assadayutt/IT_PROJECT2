<?php
    session_start();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <link id="main-css-href" rel="stylesheet" href="../Officer/css/style.css" />
     <link id="main-css-href" rel="stylesheet" href="/project/Template/Admin/plugins/material/css/materialdesignicons.css" />
    
    


</head>


<body class="navbar-fixed sidebar-fixed" id="body">


    <div class="wrapper">

        <aside class="left-sidebar sidebar-dark" id="left-sidebar">
            <div id="sidebar" class="sidebar sidebar-with-footer">
                <!-- Aplication Brand -->
                <div class="app-brand">
                    <a href="dashboard.php">
                        <img src="https://upload.wikimedia.org/wikipedia/th/thumb/b/bb/Informatics_MSU_Logo.svg/1200px-Informatics_MSU_Logo.svg.png"
                            width="30" height="30" class="d-inline-block align-top" alt="">
                        <p class="brand-name">ADMIN</p>
                    </a>
                </div>
                <!-- begin sidebar scrollbar -->
                <div class="sidebar-left" data-simplebar style="height: 100%;">
                    <!-- sidebar menu -->
                    <ul class="nav sidebar-inner" id="sidebar-menu">



                        <li>
                            <a class="sidenav-item-link" href="dashboard.php">
                                <i class="mdi mdi-chart-line"></i>
                                <span class="nav-text"> Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a class="sidenav-item-link" href="list_all_repair.php">
                                <i class="fas fa-wrench"></i>
                                <span class="nav-text"> การแจ้งซ่อมทั้งหมด</span>
                            </a>
                        </li>
                        <li>
                            <a class="sidenav-item-link" href="Approve_Equipment_Request.php">
                                <i class="fas fa-check"></i>
                                <span class="nav-text"> รายการขออนุมัติ</span>
                            </a>
                        </li>

                        <li class="section-title">
                            อื่น ๆ
                        </li>

                        <li>
                            <a class="sidenav-item-link" href="add_equipment.php">
                                <i class="fas fa-plus-circle"></i>
                                <span class="nav-text">เพิ่มครุภัณฑ์</span>
                            </a>
                        </li>


                        <li>
                            <a class="sidenav-item-link" href="repair_history.php">
                                <i class="fas fa-history"></i>
                                <span class="nav-text">ประวัติการซ่อม</span>
                            </a>
                        </li>
                        <li>
                            <a class="sidenav-item-link" href="list_equipment.php">
                                <i class="fas fa-stream"></i>
                                <span class="nav-text">รายการครุภัณฑ์</span>
                            </a>
                        </li>



                        <li>
                            <a class="sidenav-item-link" href="manage_officer.php">
                                <i class="fas fa-briefcase"></i>
                                <span class="nav-text">จัดการเจ้าหน้าที่</span>
                            </a>
                        </li>



                        <li>
                            <a class="sidenav-item-link" href="manage_repairman.php">
                                <i class="fas fa-toolbox"></i>
                                <span class="nav-text">จัดการช่างซ่อม</span>
                            </a>
                        </li>

                        <li>
                            <a class="sidenav-item-link" href="manage_user.php">
                                <i class="fas fa-users"></i>
                                <span class="nav-text">จัดการผู้ใช้</span>
                            </a>
                        </li>



                        <li>
                            <a class="sidenav-item-link" href="sale_equipment.php">
                                <i class="fas fa-minus-circle"></i>
                                <span class="nav-text">แทงจำหน่ายครุภัณฑ์</span>
                            </a>
                        </li>
                        <li>
                            <a class="sidenav-item-link" href="Ads.php">
                                <i class="fas fa-ad"></i>
                                <span class="nav-text">Ads</span>
                            </a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <!-- Header -->
            <header class="main-header" id="header">
                <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
                    <!-- Sidebar toggle button -->
                    <button id="sidebar-toggler" class="sidebar-toggle">
                        <span class="sr-only">navigation</span>
                    </button>
                    <h5>ระบบแจ้งซ่อมในคณะวิทยาการสารสนเทศ</h5>
                    <div class="navbar-right">

                            <div class="user-info">
                               <h4> <p><span><?php echo $_SESSION['officer_name']; ?></span></p> </h4>
                        </div>

                    </div>

                </nav>


            </header>


            </header>

</body>

<script>
var body = $("#body");
if ($(window).width() >= 768) {
    if (body.hasClass("sidebar-mobile-in sidebar-mobile-out")) {
        body.removeClass("sidebar-mobile-in sidebar-mobile-out");
    }

    window.isMinified = false;
    window.isCollapsed = false;

    $("#sidebar-toggler").on("click", function() {
        if (
            body.hasClass("sidebar-fixed-offcanvas") ||
            body.hasClass("sidebar-static-offcanvas")
        ) {
            $(this)
                .addClass("sidebar-offcanvas-toggle")
                .removeClass("sidebar-toggle");
            if (window.isCollapsed === false) {
                body.addClass("sidebar-collapse");
                window.isCollapsed = true;
                window.isMinified = false;
            } else {
                body.removeClass("sidebar-collapse");
                body.addClass("sidebar-collapse-out");
                setTimeout(function() {
                    body.removeClass("sidebar-collapse-out");
                }, 300);
                window.isCollapsed = false;
            }
        }

        if (body.hasClass("sidebar-fixed") || body.hasClass("sidebar-static")) {
            $(this)
                .addClass("sidebar-toggle")
                .removeClass("sidebar-offcanvas-toggle");
            if (window.isMinified === false) {
                body
                    .removeClass("sidebar-collapse sidebar-minified-out")
                    .addClass("sidebar-minified");
                window.isMinified = true;
                window.isCollapsed = false;
            } else {
                body.removeClass("sidebar-minified");
                body.addClass("sidebar-minified-out");
                window.isMinified = false;
            }
        }
    });
}

if ($(window).width() >= 768 && $(window).width() < 992) {
    if (body.hasClass("sidebar-fixed") || body.hasClass("sidebar-static")) {
        body
            .removeClass("sidebar-collapse sidebar-minified-out")
            .addClass("sidebar-minified");
        window.isMinified = true;
    }
}
</script>

</html>