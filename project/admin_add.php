<?php
session_start();
require_once 'config.php';

// ตรวจสอบการส่งฟอร์ม
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    
    // จัดการเรื่องรูปภาพ
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "./img/" . $filename;

    // สร้าง Folder img ถ้ายังไม่มี
    if (!is_dir('img')) { mkdir('img'); }

    $sql = "INSERT INTO products (name, price, image, stock) VALUES ('$name', '$price', '$filename', '$stock')";

    if (mysqli_query($conn, $sql) && move_uploaded_file($tempname, $folder)) {
        echo "<script>alert('เพิ่มสินค้าและอัปโหลดรูปสำเร็จ!'); window.location='shop.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>เพิ่มสินค้า - Admin</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-header bg-dark text-white"><h4>เพิ่มสินค้าใหม่</h4></div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>ชื่อสินค้า</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>ราคา (บาท)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>จำนวนในสต็อก</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>รูปภาพสินค้า</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" name="add_product" class="btn btn-success w-100">บันทึกสินค้า</button>
                    <a href="shop.php" class="btn btn-link w-100 mt-2">กลับหน้าหลัก</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>