<?php
require_once("../../Database/db.php");

try {
    
    $count_query = "SELECT COUNT(*) as count FROM Equipment WHERE equipment_sale = 0";
    $stmt = $conn->query($count_query);
    $result = $stmt->fetch();

    $sql = "SELECT COUNT(*) AS total FROM Equipment_repair";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $totalRecords = $stmt->fetchColumn();

    $sql1 = "SELECT COUNT(*) AS total FROM Area_repair ";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $totalRecords1 = $stmt1->fetchColumn();

    $totalnoti =  $totalRecords + $totalRecords1;


    // ส่งคำสั่ง SQL ในการนับ record
    $sql3 = "SELECT COUNT(*) AS total FROM Area_repair WHERE  status_id = 1";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->execute();
    $totalRecords3 = $stmt3->fetchColumn();


    // ส่งคำสั่ง SQL ในการนับ record
    $sql4 = "SELECT COUNT(*) AS total FROM Equipment_repair WHERE status_id = 1";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->execute();
    $totalRecords4 = $stmt4->fetchColumn();

    $complete = $totalRecords3 + $totalRecords4;

    $sql5 = "SELECT COUNT(*) AS total FROM Equipment WHERE 	equipment_sale = 1";  // = 1 แทงจำหน่ายแล้ว = 0 ยังไม่แทงจำหน่าย
    $stmt5 = $conn->prepare($sql5);
    $stmt5->execute();
    $sale = $stmt5->fetchColumn();

    $sql6 = "SELECT COUNT(*) AS total FROM User";  
    $stmt6 = $conn->prepare($sql6);
    $stmt6->execute();
    $user = $stmt6->fetchColumn();

    $sql7 = "SELECT COUNT(*) AS total FROM Repairman WHERE repairman_name <> 'รอช่างรับงาน'";  
    $stmt7 = $conn->prepare($sql7);
    $stmt7->execute();
    $repairman = $stmt7->fetchColumn();

    $sql8 = "SELECT COUNT(*) AS total FROM Officer WHERE offer_admin <> 'Admin' ";  
    $stmt8 = $conn->prepare($sql8);
    $stmt8->execute();
    $officer = $stmt8->fetchColumn();

    $sql9 = "SELECT COUNT(*) AS total FROM Approve_Outside_repairman";  
    $stmt9 = $conn->prepare($sql9);
    $stmt9->execute();
    $request = $stmt9->fetchColumn();

    $sql10 = "SELECT COUNT(*) AS total FROM Approve_Request_Tools";  
    $stmt10 = $conn->prepare($sql10);
    $stmt10->execute();
    $outside = $stmt10->fetchColumn();

    $alltotal = $request +  $outside;

    $query = "SELECT equipment_name, equipment_count
    FROM Equipment
    ORDER BY equipment_count DESC
    LIMIT 5"; 

    $stmt1 = $conn->prepare($query);
    $stmt1->execute();
    $data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // ขั้นตอน 3: สร้างข้อมูลกราฟ
    $labels = [];
    $counts = [];
    foreach ($data as $row) {
    $labels[] = $row['equipment_name'];
    $counts[] = $row['equipment_count'];
    }

    $query1 = "SELECT Repairman.repairman_id, Repairman.repairman_name, SUM(total_score) AS total_score FROM ( SELECT repairman_id,score AS total_score FROM Equipment_Assign_work UNION ALL SELECT repairman_id,Score AS total_score FROM Area_Assign_work ) AS combined_scores JOIN Repairman ON combined_scores.repairman_id = Repairman.repairman_id GROUP BY Repairman.repairman_id, Repairman.repairman_name;";



    $stmt13 = $conn->prepare($query1);
    $stmt13->execute();
    $data = $stmt13->fetchAll(PDO::FETCH_ASSOC);

    // สร้างอาร์เรย์สำหรับเก็บข้อมูล repairman_id และ total_score
    $repairmanIds = [];
    $totalScores = [];

    foreach ($data as $row) {
        $repairmanIds[] = $row['repairman_name'];
        $totalScores[] = $row['total_score'];
    }



} catch (PDOException $e) {
    die("การเชื่อมต่อล้มเหลว: " . $e->getMessage());
}

$conn = null;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <title>หน้าแรก</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="../../Template/Officer/css/add_Equipment.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js"></script>
</head>
<?php include '../../Template/Admin/nav.php'; ?>

<body>
    <style>
    body {
        font-family: 'Kanit', sans-serif;
    }
    </style>

    <div class="content">
        <div class="row justify-content-between">

            <div class="col-6 col-sm-3">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-history"></i>
                        รายการแจ้งซ่อมทั้งหมด
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white bg-warning mb-3">จำนวน <?php echo $totalnoti ; ?> รายการ</h5>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-stream"></i>
                        รายการครุภัณฑ์
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white bg-primary">จำนวน <?php echo $result["count"]; ?> รายการ</h5>

                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-minus-circle"></i>
                        แทงจำหน่ายแล้ว
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white ">จำนวน <?php echo $sale ; ?> รายการ</h5>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-check"></i>
                        การแจ้งซ่อมที่แล้วเสร็จ
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white ">จำนวน <?php echo $complete ; ?> รายการ</h5>

                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-users"></i>
                        ผู้ใช้งาน
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white ">จำนวน <?php echo $user ; ?> บัญชี</h5>

                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-user-tie"></i>
                        เจ้าหน้าที่
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white">จำนวน <?php echo $officer ; ?> บัญชี</h5>
                    </div>
                </div>
            </div>

            <div class="col-6 col-sm-3">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-wrench"></i>
                        ช่างซ่อม
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white ">จำนวน <?php echo $repairman ; ?> บัญชี</h5>

                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-3">
                <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">
                        <i class="fas fa-list-alt"></i>
                        รายการขอซ่อมเพิ่มเติม
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white ">ทั้งหมดจำนวน <?php echo $alltotal; ?> รายการ</h5>
                    </div>
                </div>
            </div>
        </div>

        <br><br>

        <div style="display: flex; justify-content: space-between;">

            <div style="width: 40%; border: 1px solid #ccc; padding: 10px; border-radius: 10px;">
                <h3 style="text-align: center;">ครุภัณฑ์ที่มีการซ่อมมากสุด 5 รายการ</h3>
                <canvas id="myChart"></canvas>
            </div>

            <div style="width: 55%;  border: 1px solid #ccc; padding: 10px; border-radius: 10px;">
                <h3 style="text-align: center;">คะแนนช่างซ่อมสูงสุด 3 รายการ</h3>
                <canvas id="barChart"></canvas>

            </div>

        </div>

        <br>
        <br>

    </div>
</body>
<?php include '../../Footer/footer.php'; ?>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($counts); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
            ],
        }],
    }, 
});
</script>
<script>
var repairmanIds = <?php echo json_encode($repairmanIds); ?>;
var totalScores = <?php echo json_encode($totalScores); ?>;
var colors = ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)'];

var ctx = document.getElementById('barChart').getContext('2d');
var barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: repairmanIds,
        datasets: [{
            label: 'คะแนนช่างซ่อม',
            data: totalScores,
            backgroundColor: colors,
            borderColor: colors.map(color => color.replace('0.2', '1')),
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: false,
                stepSize: 1
            }
        }
    }
});
</script>


</html>