<?php
session_start();
require_once 'config.php';

// ลบลูกค้า
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: admin_customers.php?msg=deleted");
    exit();
}

// ดึงลูกค้าทั้งหมด พร้อมนับจำนวนออเดอร์
$customers = mysqli_query($conn, "
    SELECT u.*, COUNT(o.id) as order_count, COALESCE(SUM(o.total_price),0) as total_spent
    FROM users u
    LEFT JOIN orders o ON u.id = o.user_id
    GROUP BY u.id
    ORDER BY u.id DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการลูกค้า - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a1a2e; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 8px; margin: 2px 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" style="width:220px; min-width:220px;">
        <h5 class="text-white fw-bold mb-4 mt-2">⚙️ Admin Panel</h5>
        <nav class="nav flex-column gap-1">
            <a href="admin_dashboard.php" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a>
            <a href="admin_manage.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>จัดการสินค้า</a>
            <a href="admin_add.php" class="nav-link"><i class="bi bi-plus-circle me-2"></i>เพิ่มสินค้า</a>
            <a href="admin_orders.php" class="nav-link"><i class="bi bi-receipt me-2"></i>จัดการออเดอร์</a>
            <a href="admin_customers.php" class="nav-link active"><i class="bi bi-people me-2"></i>จัดการลูกค้า</a>
            <hr class="border-secondary">
            <a href="shop.php" class="nav-link"><i class="bi bi-shop me-2"></i>หน้าร้านค้า</a>
            <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-people text-primary me-2"></i>จัดการลูกค้าทั้งหมด</h4>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                🗑️ ลบบัญชีลูกค้าเรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>ชื่อลูกค้า</th>
                            <th>อีเมล</th>
                            <th>เบอร์โทร</th>
                            <th class="text-center">ออเดอร์</th>
                            <th>ยอดซื้อรวม</th>
                            <th class="text-center pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($customers) > 0): ?>
                            <?php while($c = mysqli_fetch_assoc($customers)): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?php echo $c['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;font-size:14px;">
                                            <?php echo mb_substr($c['fullname'], 0, 1); ?>
                                        </div>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($c['fullname']); ?></span>
                                    </div>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($c['email']); ?></td>
                                <td><?php echo $c['phone'] ?: '<span class="text-muted">-</span>'; ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill"><?php echo $c['order_count']; ?> ออเดอร์</span>
                                </td>
                                <td class="fw-bold text-success">฿<?php echo number_format($c['total_spent'], 2); ?></td>
                                <td class="text-center pe-4">
                                    <a href="admin_orders.php?user=<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-receipt"></i> ออเดอร์
                                    </a>
                                    <a href="admin_customers.php?delete=<?php echo $c['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('ยืนยันการลบบัญชีลูกค้านี้? ข้อมูลออเดอร์จะยังคงอยู่')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    ยังไม่มีลูกค้าในระบบ
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
