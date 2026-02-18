<?php
session_start();
require_once 'config.php';

// 1. ตรวจสอบว่าเข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// ฟังก์ชันช่วย: ตรวจสอบว่า image เป็น URL หรือชื่อไฟล์
function getImageSrc($image) {
    if (empty($image)) return 'https://via.placeholder.com/600x800?text=No+Image';
    if (str_starts_with($image, 'http')) return $image;
    return 'img/' . $image;
}

// 2. ระบบค้นหาสินค้า (Search Logic)
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
}

$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าร้านค้า - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f9fa; }
        .product-card { 
            transition: all 0.3s ease; 
            border: none; 
            border-radius: 15px; 
            background: #fff;
        }
        .product-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; 
        }
        .card-img-top { 
            height: 280px; 
            object-fit: cover; 
            border-top-left-radius: 15px; 
            border-top-right-radius: 15px; 
        }
        .navbar-brand { font-size: 1.5rem; letter-spacing: 1px; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="shop.php">👕 CLOTH-SHOP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex ms-auto me-lg-4 my-2 my-lg-0" action="shop.php" method="GET">
                <div class="input-group">
                    <input class="form-control border-0" type="search" name="search" placeholder="ค้นหาเสื้อผ้า..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link position-relative px-3" href="cart.php">
                        <i class="bi bi-cart3 fs-5"></i>
                        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo array_sum($_SESSION['cart']); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn btn-outline-light px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['user_name'] ?? 'สมาชิก'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person-gear me-2"></i>แก้ไขโปรไฟล์</a></li>
                        <li><a class="dropdown-item py-2" href="order_history.php"><i class="bi bi-bag-check me-2"></i>ประวัติการสั่งซื้อ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="hero-section text-center mb-5 shadow-inner">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">NEW COLLECTION 2026</h1>
        <p class="lead mb-0">ค้นพบสไตล์ที่ใช่ ในราคาที่ชอบ พร้อมส่งทั่วไทย</p>
    </div>
</header>

<div class="container mb-5">
    <?php if($search): ?>
        <h4 class="mb-4">ผลการค้นหาสำหรับ: <span class="text-primary">"<?php echo htmlspecialchars($search); ?>"</span></h4>
    <?php endif; ?>

    <div class="row g-4">
        <?php if(mysqli_num_rows($query) > 0): ?>
            <?php while($product = mysqli_fetch_assoc($query)): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 product-card shadow-sm border-0">
                        <div class="position-relative">
                            <img src="<?php echo getImageSrc($product['image']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='https://via.placeholder.com/600x800?text=No+Image'">
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                            <p class="text-primary fw-bold fs-5 mb-3">฿<?php echo number_format($product['price'], 2); ?></p>
                            <div class="mt-auto d-grid gap-2">
                                <a href="cart_process.php?add=<?php echo $product['id']; ?>" class="btn btn-dark rounded-pill">
                                    <i class="bi bi-plus-lg me-1"></i> หยิบใส่ตะกร้า
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h4 class="text-muted mt-3">ขออภัย ไม่พบสินค้าที่ต้องการ</h4>
                <a href="shop.php" class="btn btn-outline-primary mt-2">ดูสินค้าทั้งหมด</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center py-5 mt-5">
    <div class="container">
        <p class="mb-2">👕 CLOTH-SHOP - คุณภาพระดับพรีเมียม เพื่อคุณ</p>
        <p class="text-muted small mb-0">© 2026 Clothing Store. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>