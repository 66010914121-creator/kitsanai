<?php
session_start();
require_once 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// เมื่อมีการกดปุ่มบันทึกข้อมูล
if(isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $update_sql = "UPDATE users SET fullname='$fullname', phone='$phone', address='$address' WHERE id='$user_id'";
    
    if(mysqli_query($conn, $update_sql)) {
        $_SESSION['user_name'] = $fullname; // อัปเดตชื่อในระบบทันที
        echo "<script>alert('บันทึกการเปลี่ยนแปลงเรียบร้อยแล้ว'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');</script>";
    }
}

// ดึงข้อมูลปัจจุบันมาแสดงในฟอร์ม
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f0f2f5; }
        .profile-card { border-radius: 15px; border: none; }
        .form-label { font-weight: 600; color: #495057; }
        .form-control:focus { box-shadow: none; border-color: #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="shop.php">👕 CLOTH-SHOP</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="shop.php">ไปที่ร้านค้า</a>
            <a class="nav-link" href="order_history.php">ประวัติการสั่งซื้อ</a>
            <a class="nav-link text-danger" href="logout.php">ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card profile-card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                        <h3 class="fw-bold">แก้ไขข้อมูลส่วนตัว</h3>
                        <p class="text-muted">ข้อมูลนี้จะถูกใช้ในการจัดส่งสินค้า</p>
                    </div>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">อีเมลผู้ใช้งาน</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="text" class="form-control bg-light" value="<?php echo $user['email']; ?>" disabled>
                            </div>
                            <div class="form-text text-danger">* ไม่สามารถเปลี่ยนอีเมลได้</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="fullname" class="form-control" value="<?php echo $user['fullname']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="เช่น 0812345678" value="<?php echo $user['phone']; ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">ที่อยู่สำหรับการจัดส่ง</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                <textarea name="address" class="form-control" rows="3" placeholder="ระบุบ้านเลขที่ ถนน แขวง/ตำบล..."><?php echo $user['address']; ?></textarea>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update_profile" class="btn btn-primary py-2 fw-bold">
                                <i class="bi bi-save me-2"></i> บันทึกข้อมูล
                            </button>
                            <a href="shop.php" class="btn btn-outline-secondary py-2">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>