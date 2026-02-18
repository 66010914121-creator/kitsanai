<?php
session_start();
require_once 'config.php';

// รับค่าไอดีสินค้าที่ส่งมาจากหน้า shop.php
if (isset($_GET['add'])) {
    $p_id = $_GET['add'];

    // ถ้ายังไม่มีตะกร้า ให้สร้างอาร์เรย์ว่างขึ้นมา
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // ถ้ามีสินค้านี้ในตะกร้าแล้ว ให้บวกจำนวนเพิ่ม
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++;
    } else {
        // ถ้ายังไม่มี ให้เริ่มที่ 1 ชิ้น
        $_SESSION['cart'][$p_id] = 1;
    }

    header("Location: cart.php"); // เพิ่มเสร็จให้เด้งไปหน้าตะกร้าสินค้า
    exit();
}

// ลบสินค้าทีละชิ้น หรือลบออกจากตะกร้า
if (isset($_GET['remove'])) {
    $p_id = $_GET['remove'];
    unset($_SESSION['cart'][$p_id]);
    header("Location: cart.php");
    exit();
}
?>