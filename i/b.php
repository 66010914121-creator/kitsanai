<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kitsanai</title>
</head>
<body>

<h1>งาน i กฤษนัย สรพิมพ์ (ไกด์)</h1>

<?php
include_once("connectdb.php");

if(isset($_POST['Submit'])){
    $rname = $_POST['rname'];
    $sql2 = "INSERT INTO region (r_id, r_name) VALUES (NULL, '{$rname}')";
    mysqli_query($conn,$sql2) or die ("เพิ่มข้อมูลไม่ได้");
}

$sql = "SELECT * FROM region";
$rs = mysqli_query($conn, $sql);
?>

<form method="post" action="">
    ชื่อภาค <input type="text" name="rname" autofocus required>
    <button type="submit" name="Submit">บันทึก</button>
</form><br>

<table border="1">
    <tr>
        <th>รหัสภาค</th>
        <th>ชื่อภาค</th>
        <th>ลบ</th>
    </tr>

<?php
while ($data = mysqli_fetch_array($rs)){
?>
    <tr>
        <td><?php echo $data['r_id'];?></td>
        <td><?php echo $data['r_name'];?></td>
        <td width="80" align="center">
            <a href="delete_region.php?id=<?php echo $data['r_id'];?>" 
               onclick="return confirm('ยืนยันการลบหรือไม่');">
               <img src="../imgs/ดาวน์โหลด.jpg" width="20">
            </a>
        </td>
    </tr>
<?php
}
mysqli_close($conn);
?>

</table>

</body>
</html>