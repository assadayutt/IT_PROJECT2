<!DOCTYPE html>
<html>

<head>
    <title>หน้าแรก</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/Repairman_Index.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<script>
  const cards = document.querySelectorAll('.custom-card');

  cards.forEach(card => {
    card.addEventListener('mouseover', () => {
      card.classList.add('hover');
    });

    card.addEventListener('mouseout', () => {
      card.classList.remove('hover');
    });
  });
</script>


<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/repairman_menubar.php'; ?>

    <div class="container-fluid mt-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-1">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมครุภัณฑ์</h5>
                        <p class="card-text">การแจ้งซ่อมครุภัณฑ์ที่ต้องซ่อม</p>
                        <h1 class="card-text number">0</h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-2">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">จำนวนแจ้งซ่อมพื้นที่</h5>
                        <p class="card-text">การแจ้งซ่อมพื้นที่ที่ต้องซ่อม</p>
                        <h1 class="card-text number">0</h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-4">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">รอการซ่อม</h5>
                        <p class="card-text">จำนวนการแจ้งซ่อมที่ต้องซ่อมต่อ</p>
                        <h1 class="card-text number">0</h1>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card custom-card card-3">
                    <div class="card-body custom-card-body">
                        <h5 class="card-title">เสร็จสิ้น</h5>
                        <p class="card-text">การแจ้งซ่อมที่เสร็จสิ้นแล้ว</p>
                        <h1 class="card-text number">0</h1>
                    </div>
                </div>
            </div>

                

        </div>
    </div>
    <br>
    <br>

    <?php include '../../Footer/footer.php'; ?>
</body>

</html>