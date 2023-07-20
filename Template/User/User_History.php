<!DOCTYPE html>
<html>

<head>
    <title>ประวัติการแจ้งซ่อม</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../CSS/User_History.css">



    <?php include '../../Navbar/navbar.php'; ?>
    <?php include '../../Menubar/menubar.php' ?>

</head>

<body>


    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รายการแจ้งซ่อม</th>
                <th>รายละเอียด</th>
                <th>วันที่แจ้งซ่อม</th>
                <th>สถานะแจ้งซ่อม</th>
                <th style="width: 16.5%;">ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
        require_once("../../Database/db.php");
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['id'];

        $sql = "SELECT Equipment_repair.repair_id, User.user_name, Equipment_repair.status_id, Equipment_repair.equipment_number, Equipment_repair.repair_detail, Equipment_repair.repair_date
        FROM Equipment_repair
        JOIN User ON Equipment_repair.user_id = User.user_id
        JOIN Statuss ON Equipment_repair.status_id = Statuss.status_id
        WHERE Equipment_repair.user_id = $user_id AND Statuss.status_id = 1";


        
       $result = $conn->query($sql);

         
       $num_rows = 1;
       if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $num_rows . "</td>";
            echo "<td> ครุภัณฑ์ : " . $row["equipment_number"] . "</td>";
            echo "<td>" . $row["repair_detail"] . "</td>";
            echo "<td>" . $row["repair_date"] . "</td>";

            $statusId = $row["status_id"];
            $statusColorClass = "";
            $statusText = "";

            switch ($statusId) {
                case 1:
                    $statusColorClass = "status-completed";
                    $statusText = "เสร็จสิ้น";
                    break;
                case 2:
                    $statusColorClass = "status-in-progress";
                    $statusText = "กำลังดำเนินการ";
                    break;
                case 3:
                    $statusColorClass = "status-awaiting-equipment";
                    $statusText = "รอการซ่อม";
                    break;
                case 4:
                    $statusColorClass = "status-pending";
                    $statusText = "รอรับงาน";
                    break;
                default:
                    $statusColorClass = "";
                    $statusText = "";
                    break;
            }
        
            echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

            echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;'data-repair-id='" . $row['repair_id'] . "' href='javascript:void(0);'>เพิ่มเติม</a></td>";

            echo "</tr>";
            $num_rows++;
        }
    } else {
            echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
        }
       
        ?>
        </tbody>
    </table>


    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รายการแจ้งซ่อม</th>
                <th>รายละเอียด</th>
                <th>วันที่แจ้งซ่อม</th>
                <th>บริเวณ / พื้นที่</th>
                <th>สถานะแจ้งซ่อม</th>
                <th>ตรวจสอบ</th>
            </tr>
        </thead>
        <tbody>
            <?php
require_once("../../Database/db.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['id'];

$sql = "SELECT  area_repair.area_id ,area_repair.status_id, area_repair.area_detail,area_repair.area_problem, area_repair.area_date, area_repair.area_address
        FROM area_repair
        JOIN User ON area_repair.user_id = User.user_id
        JOIN Statuss ON area_repair.status_id = Statuss.status_id
        WHERE area_repair.user_id = $user_id AND Statuss.status_id = 1";

$result = $conn->query($sql);

$num_rows = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $num_rows . "</td>";
        echo "<td>" . $row["area_detail"] . "</td>";
        echo "<td>" . $row["area_problem"] . "</td>";
        echo "<td>" . $row["area_date"] . "</td>";
        echo "<td>" . $row["area_address"] . "</td>";

        $statusId = $row["status_id"];
        $statusColorClass = "";
        $statusText = "";

        switch ($statusId) {
            case 1:
                $statusColorClass = "status-completed";
                $statusText = "เสร็จสิ้น";
                break;
            case 2:
                $statusColorClass = "status-in-progress";
                $statusText = "กำลังดำเนินการ";
                break;
            case 3:
                $statusColorClass = "status-awaiting-equipment";
                $statusText = "รอการซ่อม";
                break;
            case 4:
                $statusColorClass = "status-pending";
                $statusText = "รอรับงาน";
                break;
            default:
                $statusColorClass = "";
                $statusText = "";
                break;
        }

        echo "<td class='status-cell " . $statusColorClass . "'>" . $statusText . "</td>";

        echo "<td><a class='button' style='text-decoration: none; background-color: green; color: white;'data-repair-id='" . $row['area_id'] . "' href='javascript:void(0);'>เพิ่มเติม</a></td>";


        echo "</tr>";
        $num_rows++;
    }
} else {
    echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

        </tbody>
    </table>

    <?php include '../../Footer/footer.php' ?>
</body>

</html>