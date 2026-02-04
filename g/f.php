<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>kitsanai - รายงานยอดขายรายเดือน</title>
</head>

<body>
<table border="1" width="400">
<tr>
   <th>เดือนที่</th>
   <th>ยอดขายรวม</th>
</tr>

<?php
include_once("connectdb.php");

// 1. แก้ SQL: ลบเครื่องหมาย , หลัง Order By ออก และตรวจสอบการ Group By
$sql = "SELECT 
            MONTH(p_date) AS Month, 
            SUM(p_amount) AS Total_Sales
        FROM popsupermarket
        GROUP BY MONTH(p_date)
        ORDER BY Month ASC"; // ลบเครื่องหมายคอมม่าออก

$rs = mysqli_query($conn, $sql);

// ตรวจสอบว่า Query ทำงานได้หรือไม่
if (!$rs) {
    die("Query Failed: " . mysqli_error($conn));
}

while($data = mysqli_fetch_array($rs)){
?>
<tr>
     <td align="center"><?php echo $data['Month'];?></td>
     
     <td align="right"><?php echo number_format($data['Total_Sales'], 2);?></td>
</tr>
<?php
}
mysqli_close($conn);
?>
</table>
</body>
</html>