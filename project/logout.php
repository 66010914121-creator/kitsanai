<?php
session_start();

// 1. ล้างค่าตัวแปร Session ทั้งหมดในหน่วยความจำ
$_SESSION = array();

// 2. ทำลาย Cookie ของ Session (ถ้ามี) เพื่อความสะอาดหมดจด
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. ทำลาย Session ที่ฝั่ง Server
session_destroy();

// 4. ส่งผู้ใช้กลับไปที่หน้าล็อกอิน พร้อมแจ้งเตือนสั้นๆ (ถ้าต้องการ)
header("Location: index.php");
exit();
?>