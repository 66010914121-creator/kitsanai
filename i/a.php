<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>กฤษนัย สรพิมพ์ (ไกด์)</title>
</head>

<body>
<h1>งาน i -- กฤษนัย สรพิมพ์ (ไกด์)</h1>

<form method="post" action="">
    ชื่อภาค <input type="text" name="rname" autofocus required>
    <button type="submit" name="Submit">บันทึก</button> 
</form> <br><br>

<?php
include_once("connectdb.php");

if(isset($_POST['Submit'])) {
    $rname = $_POST['rname'];
    $sql2 = "INSERT INTO regions (r_id, r_name) VALUES (NULL, '{$rname}')";
    mysqli_query($conn, $sql2) or die("เพิ่มข้อมูลไม่ได้");
}

$sql = "SELECT * FROM regions";
$rs = mysqli_query($conn, $sql);
?>

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
        <td><?php echo $data['r_id']; ?></td>       
        <td><?php echo $data['r_name']; ?></td>
        <td width="80"><img src="../imgs/ดาวน์โหลด.jpg" width="20"></td>
    </tr>
<?php
}
mysqli_close($conn);
?>
</table>

</body>
</html>