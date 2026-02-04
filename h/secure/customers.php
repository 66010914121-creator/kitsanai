<?php
include_once("check_login.php");
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการลูกค้า admin - กฤษนัย สรพิมพ์</title>
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
        .admin-name {
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fa-solid fa-users-gear me-2"></i> กฤษนัย สรพิมพ์ (ไกด์)
        </a>
        <div class="ms-auto d-flex align-items-center">
            <span class="admin-name me-3">แอดมิน: <?php echo htmlspecialchars($_SESSION['aname']); ?></span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0 pt-3">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="index2.php"><i class="fa-solid fa-house me-2"></i> หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php"><i class="fa-solid fa-box me-2"></i> จัดการสินค้า</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fa-solid fa-cart-shopping me-2"></i> จัดการออเดอร์</a></li>
                <li class="nav-item"><a class="nav-link active" href="customers.php"><i class="fa-solid fa-users me-2"></i> จัดการลูกค้า</a></li>
                <hr class="mx-3">
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fa-solid fa-power-off me-2"></i> ออกจากระบบ</a></li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2" style="color: var(--pink-dark);">จัดการข้อมูลลูกค้า</h1>
                <button class="btn btn-success shadow-sm"><i class="fa-solid fa-user-plus me-1"></i> เพิ่มลูกค้าใหม่</button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-pink">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4">#</th>
                                    <th class="py-3">ชื่อ-นามสกุล</th>
                                    <th class="py-3">อีเมล</th>
                                    <th class="py-3">เบอร์โทรศัพท์</th>
                                    <th class="py-3">สถานะ</th>
                                    <th class="py-3 text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4">1</td>
                                    <td>สมชาย ใจดี</td>
                                    <td>somchai@email.com</td>
                                    <td>081-234-5678</td>
                                    <td><span class="badge bg-success">ปกติ</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
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