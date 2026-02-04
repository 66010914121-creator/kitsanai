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
// ตรวจสอบว่ามีข้อมูลใน Session หรือไม่ก่อนแสดงผล เพื่อป้องกัน Error
echo (isset($_SESSION['name']) ? $_SESSION['name'] : "ไม่มีข้อมูลชื่อ") . "<br>";
echo (isset($_SESSION['nickname']) ? $_SESSION['nickname'] : "ไม่มีข้อมูลชื่อเล่น") . "<br>";
echo (isset($_SESSION['p1']) ? $_SESSION['p1'] : "-") . "<br>";
echo (isset($_SESSION['p2']) ? $_SESSION['p2'] : "-") . "<br>";
?>

</body>
</html>