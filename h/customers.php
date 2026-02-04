<?php
include_once("check_login.php");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>จัดการลูกค้า admin - กฤษนัย</title>
</head>

<body>
<h1>จัดการลูกค้า admin - กฤษนัย</h1>

<?php echo "แอดมิน : " . $_SESSION['aname']; ?> <br>
 <ul>
    <li><a href="products.php">จัดการสินค้า</a></li>
    <li><a href="orders.php">จัดการออเดอร์</a></li>
    <li><a href="customers.php">จัดการลูกค้า</a></li>
    <li><a href="logout.php">ออกจากระบบ</a></li>
 </ul>

</body>
</html>