<?php
// ตั้งค่า Timezone ให้เป็นประเทศไทย (สำคัญมากสำหรับการบันทึกเวลาสั่งซื้อ)
date_default_timezone_set('Asia/Bangkok');

$host = "localhost";
$user = "root";     // ผู้ใช้งานฐานข้อมูล (XAMPP คือ root)
$pass = "";         // รหัสผ่าน (XAMPP ปกติจะว่างไว้)
$dbname = "clothing_db"; // ตรวจสอบให้มั่นใจว่าชื่อฐานข้อมูลใน phpMyAdmin ตรงกัน

// เชื่อมต่อฐานข้อมูล
$conn = mysqli_connect($host, $user, $pass, $dbname);

// เช็คการเชื่อมต่อหากล้มเหลว
if (!$conn) {
    // แสดงข้อความ error ที่เข้าใจง่ายขึ้น
    die("<div style='color:red; text-align:center; margin-top:50px;'>
            <h2>❌ ไม่สามารถเชื่อมต่อฐานข้อมูลได้</h2>
            <p>กรุณาตรวจสอบว่าเปิด MySQL ใน XAMPP หรือยัง? หรือชื่อฐานข้อมูลถูกต้องไหม?</p>
         </div>");
}

// ตั้งค่าการรับส่งข้อมูลให้รองรับภาษาไทยแบบสมบูรณ์
mysqli_set_charset($conn, "utf8mb4");

?>