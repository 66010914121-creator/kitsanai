<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>กฤษนัย สรพิมพ์</title>
</head>

<body>

<h1> กฤษนัย สรพิมพ์ (ไกด์) </h1>

<?php
// การลบค่าใน Session ที่ถูกต้อง
unset($_SESSION['name']);
unset($_SESSION['nickname']);

echo "ลบข้อมูลชื่อและชื่อเล่นเรียบร้อยแล้ว<br>";
?>

</body>
</html>