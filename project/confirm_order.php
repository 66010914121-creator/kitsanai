<?php
session_start();
require_once 'config.php';

// 1. ตรวจสอบความพร้อมก่อนเริ่ม (ต้องล็อกอิน, ต้องมีของในตะกร้า, ต้องส่งค่าแบบ POST)
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: shop.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_price = mysqli_real_escape_string($conn, $_POST['total_price']);

// เริ่มต้น Transaction (ถ้าพังจุดหนึ่ง จะยกเลิกทั้งหมดเพื่อความปลอดภัยของข้อมูล)
mysqli_begin_transaction($conn);

try {
    // 2. บันทึกข้อมูลลงตาราง orders
    // เพิ่มฟิลด์ order_date (ถ้ามี) หรือใช้ฟังก์ชัน NOW() ของ SQL
    $sql_order = "INSERT INTO orders (user_id, total_price, status, order_date) 
                  VALUES ('$user_id', '$total_price', 'Pending', NOW())";
    
    if (!mysqli_query($conn, $sql_order)) {
        throw new Exception("ไม่สามารถสร้างออเดอร์ได้");
    }

    $order_id = mysqli_insert_id($conn); // ดึง ID ของออเดอร์ที่เพิ่งสร้าง

    // 3. วนลูปบันทึกสินค้าแต่ละชิ้นลง order_items
    foreach ($_SESSION['cart'] as $p_id => $qty) {
        $p_id = (int)$p_id;
        
        // ดึงราคาล่าสุดจากฐานข้อมูลเพื่อความแม่นยำ
        $sql_p = "SELECT price FROM products WHERE id = $p_id";
        $res_p = mysqli_query($conn, $sql_p);
        $row_p = mysqli_fetch_assoc($res_p);
        
        if ($row_p) {
            $price = $row_p['price'];
            $sql_item = "INSERT INTO order_items (order_id, product_id, qty, price) 
                         VALUES ('$order_id', '$p_id', '$qty', '$price')";
            
            if (!mysqli_query($conn, $sql_item)) {
                throw new Exception("ไม่สามารถบันทึกรายการสินค้าได้");
            }
        }
    }

    // ถ้าทำงานมาถึงจุดนี้โดยไม่มี Error ให้ยืนยันการบันทึกทั้งหมด
    mysqli_commit($conn);

    // 4. สั่งซื้อสำเร็จ ล้างตะกร้าทิ้ง
    unset($_SESSION['cart']);
    echo "<script>alert('ยืนยันการสั่งซื้อเรียบร้อยแล้ว! ขอบคุณที่ใช้บริการครับ'); window.location='order_history.php';</script>";

} catch (Exception $e) {
    // หากเกิดข้อผิดพลาด ให้ยกเลิกสิ่งที่ทำมาทั้งหมด (Rollback)
    mysqli_rollback($conn);
    echo "<script>alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>