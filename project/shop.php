<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function getImageSrc($image) {
    if (empty($image)) return null;
    if (str_starts_with($image, 'http')) return $image;
    return 'img/' . $image;
}

function getCardColor($id) {
    $colors = [
        1 => ['#667eea','#764ba2'], 2 => ['#f093fb','#f5576c'],
        3 => ['#4facfe','#00f2fe'], 4 => ['#43e97b','#38f9d7'],
        5 => ['#fa709a','#fee140'], 6 => ['#a18cd1','#fbc2eb'],
        7 => ['#fccb90','#d57eeb'], 8 => ['#a1c4fd','#c2e9fb'],
        9 => ['#fd7043','#ff8a65'], 10 => ['#26c6da','#00acc1'],
        11 => ['#66bb6a','#43a047'], 12 => ['#ab47bc','#8e24aa'],
        13 => ['#ef5350','#e53935'], 14 => ['#5c6bc0','#3949ab'],
        15 => ['#8d6e63','#6d4c41'], 16 => ['#78909c','#546e7a'],
    ];
    $idx = (($id - 1) % 16) + 1;
    return $colors[$idx] ?? ['#667eea','#764ba2'];
}

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
            transition: all 0.3s ease; border: none;
            border-radius: 15px; background: #fff;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
        }
        .product-img-box {
            height: 280px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            overflow: hidden; position: relative;
        }
        .product-img-box img { width: 100%; height: 100%; object-fit: cover; }
        .product-img-placeholder {
            width: 100%; height: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: white;
        }
        .product-img-placeholder .icon { font-size: 3.5rem; }
        .product-img-placeholder span {
            font-size: 0.85rem; margin-top: 8px;
            opacity: 0.95; font-weight: 600;
            text-align: center; padding: 0 10px;
        }
        .hero-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white; padding: 80px 0;
        }
        .navbar-brand { font-size: 1.5rem; letter-spacing: 1px; }
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
        <h1 class="display-4 fw-bold mb-3">✨ NEW COLLECTION 2026</h1>
        <p class="lead mb-0">ค้นพบสไตล์ที่ใช่ ในราคาที่ชอบ พร้อมส่งทั่วไทย</p>
    </div>
</header>

<div class="container mb-5">
    <?php if($search): ?>
        <h4 class="mb-4">ผลการค้นหา: <span class="text-primary">"<?php echo htmlspecialchars($search); ?>"</span></h4>
    <?php endif; ?>

    <div class="row g-4">
        <?php if(mysqli_num_rows($query) > 0): ?>
            <?php while($product = mysqli_fetch_assoc($query)):
                $imgSrc = getImageSrc($product['image']);
                $colors = getCardColor($product['id']);
                $icon = '👕';
                if (str_contains($product['name'], 'กางเกง')) $icon = '👖';
                elseif (str_contains($product['name'], 'แจ็คเก็ต')) $icon = '🧥';
                elseif (str_contains($product['name'], 'เชิ้ต')) $icon = '👔';
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-img-box">
                        <?php if ($imgSrc): ?>
                            <img src="<?php echo $imgSrc; ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="product-img-placeholder" style="display:none; background: linear-gradient(135deg, <?php echo $colors[0]; ?>, <?php echo $colors[1]; ?>);">
                                <div class="icon"><?php echo $icon; ?></div>
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="product-img-placeholder" style="background: linear-gradient(135deg, <?php echo $colors[0]; ?>, <?php echo $colors[1]; ?>);">
                                <div class="icon"><?php echo $icon; ?></div>
                                <span><?php echo htmlspecialchars($product['name']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <h6 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                        <p class="text-primary fw-bold fs-5 mb-3">฿<?php echo number_format($product['price'], 2); ?></p>
                        <div class="mt-auto d-grid">
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