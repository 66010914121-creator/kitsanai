<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก Admin - กฤษนัย สรพิมพ์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --pink-main: #f06292;
            --pink-light: #fce4ec;
            --pink-dark: #ad1457;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: var(--pink-main) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar {
            background-color: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .nav-link {
            color: #555;
            padding: 12px 20px;
            transition: 0.3s;
            border-radius: 5px;
            margin: 5px 10px;
        }
        .nav-link:hover {
            background-color: var(--pink-light);
            color: var(--pink-dark);
        }
        .nav-link.active {
            background-color: var(--pink-main);
            color: white !important;
        }
        .admin-badge {
            background-color: var(--pink-light);
            color: var(--pink-dark);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fa-solid fa-heart-pulse me-2"></i> กฤษนัย สรพิมพ์ (ไกด์)
        </a>
        <div class="d-flex align-items-center">
            <span class="admin-badge me-3">
                <i class="fa-solid fa-user-shield me-1"></i> Admin: <?php echo htmlspecialchars($_SESSION['aname']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 pt-3">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index2.php">
                            <i class="fa-solid fa-house me-2"></i> หน้าหลัก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">
                            <i class="fa-solid fa-box me-2"></i> จัดการสินค้า
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="fa-solid fa-cart-shopping me-2"></i> จัดการออเดอร์
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers.php">
                            <i class="fa-solid fa-users me-2"></i> จัดการลูกค้า
                        </a>
                    </li>
                    <hr class="mx-3">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> ออกจากระบบ
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="card border-0 shadow-sm p-4">
                <h2 class="fw-bold" style="color: var(--pink-dark);">ยินดีต้อนรับเข้าสู่ระบบจัดการ</h2>
                <p class="text-muted">เลือกเมนูทางด้านซ้ายเพื่อเริ่มต้นจัดการข้อมูลร้านค้าของคุณ</p>
                
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white p-3 border-0 shadow-sm" style="background: linear-gradient(45deg, #f06292, #f48fb1) !important;">
                            <h5>สินค้าทั้งหมด</h5>
                            <h2 class="fw-bold">24</h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white p-3 border-0 shadow-sm" style="background: linear-gradient(45deg, #ec407a, #f06292) !important;">
                            <h5>ออเดอร์วันนี้</h5>
                            <h2 class="fw-bold">12</h2>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white p-3 border-0 shadow-sm" style="background: linear-gradient(45deg, #d81b60, #ec407a) !important;">
                            <h5>จำนวนลูกค้า</h5>
                            <h2 class="fw-bold">150</h2>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>