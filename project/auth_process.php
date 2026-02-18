<?php
session_start();
require_once 'config.php';

// ป้องกันการเข้าถึงไฟล์นี้โดยตรงผ่าน URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// --- ส่วนของการสมัครสมาชิก (Register) ---
if (isset($_POST['register'])) {
    // รับค่าและทำความสะอาดข้อมูล
    $fullname = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];

    // 1. ตรวจสอบว่ากรอกข้อมูลครบถ้วนหรือไม่
    if (empty($fullname) || empty($email) || empty($password)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง'); window.history.back();</script>";
        exit();
    }

    // 2. ตรวจสอบรูปแบบอีเมล
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('รูปแบบอีเมลไม่ถูกต้อง'); window.history.back();</script>";
        exit();
    }

    // 3. ตรวจสอบความยาวรหัสผ่าน
    if (strlen($password) < 6) {
        echo "<script>alert('รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร'); window.history.back();</script>";
        exit();
    }

    // 4. เช็คอีเมลซ้ำในฐานข้อมูล
    $check_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $result_check = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('อีเมลนี้ถูกใช้งานไปแล้ว กรุณาใช้อีเมลอื่น'); window.history.back();</script>";
        exit();
    } else {
        // 5. เข้ารหัสลับพาสเวิร์ดและบันทึก
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ'); window.location='index.php';</script>";
        } else {
            // ในกรณีใช้งานจริง ไม่ควรโชว์ mysqli_error ให้ user เห็น (เพื่อความปลอดภัย)
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
    }
}

// --- ส่วนของการเข้าสู่ระบบ (Login) ---
if (isset($_POST['login'])) {
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('กรุณากรอกอีเมลและรหัสผ่าน'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // ตรวจสอบตัวตน
    if ($user && password_verify($password, $user['password'])) {
        // สร้าง Session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['fullname'];
        
        // บันทึกสถานะการล็อกอินสำเร็จและไปหน้าสินค้า
        header("Location: shop.php");
        exit();
    } else {
        echo "<script>alert('อีเมลหรือรหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        exit();
    }
}
?>