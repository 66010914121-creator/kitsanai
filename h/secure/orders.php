<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการออเดอร์ admin - กฤษนัย สรพิมพ์</title>
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
        }
        .navbar {
            background-color: var(--pink-main) !important;
        }
        .sidebar {
            background-color: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .nav-link {
            color: #555;
            padding: 12px 20px;
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
        .table-pink thead {
            background-color: var(--pink-main);
            color: white;
        }
        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fa-solid fa-receipt me-2"></i> กฤษนัย สรพิมพ์ (ไกด์)
        </a>
        <div class="ms-auto">
            <span class="text-white me-3 d-none d-sm-inline">
                <i class="fa-solid fa-circle-user me-1"></i> <?php echo htmlspecialchars($_SESSION['aname']); ?>
            </span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 pt-3 shadow-sm">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="index2.php"><i class="fa-solid fa-house me-2"></i> หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php"><i class="fa-solid fa-box me-2"></i> จัดการสินค้า</a></li>
                <li class="nav-item"><a class="nav-link active" href="orders.php"><i class="fa-solid fa-cart-shopping me-2"></i> จัดการออเดอร์</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php"><i class="fa-solid fa-users me-2"></i> จัดการลูกค้า</a></li>
                <hr class="mx-3">
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fa-solid fa-power-off me-2"></i> ออกจากระบบ</a></li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold" style="color: var(--pink-dark);">รายการคำสั่งซื้อ (Orders)</h2>
                <div class="text-muted small">หน้าจัดการออเดอร์ทั้งหมดในระบบ</div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-pink">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4">เลขที่ออเดอร์</th>
                                    <th class="py-3">วันที่สั่งซื้อ</th>
                                    <th class="py-3">ชื่อลูกค้า</th>
                                    <th class="py-3">ราคารวม</th>
                                    <th class="py-3">สถานะ</th>
                                    <th class="py-3 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 fw-bold">#ORD-1001</td>
                                    <td>04/02/2026</td>
                                    <td>กฤษนัย สรพิมพ์</td>
                                    <td>1,250.00 ฿</td>
                                    <td><span class="status-pill bg-warning text-dark">รอดำเนินการ</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-info"><i class="fa-solid fa-eye"></i></button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>