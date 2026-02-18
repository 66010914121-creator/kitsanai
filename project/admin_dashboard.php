<?php
session_start();
require_once 'config.php';

// สถิติสรุป
$total_products  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
$total_orders    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$total_customers = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$total_revenue   = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(total_price),0) FROM orders WHERE status != 'Cancelled'"))[0];
$pending_orders  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='Pending'"))[0];

// ออเดอร์ล่าสุด 5 รายการ
$recent_orders = mysqli_query($conn, "
    SELECT o.*, u.fullname FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC LIMIT 5
");

function statusBadge($s) {
    $map = [
        'Pending'   => ['bg-warning-subtle text-warning-emphasis', 'รอตรวจสอบ'],
        'Paid'      => ['bg-info-subtle text-info-emphasis', 'ชำระแล้ว'],
        'Shipping'  => ['bg-primary-subtle text-primary-emphasis', 'จัดส่งแล้ว'],
        'Completed' => ['bg-success-subtle text-success-emphasis', 'สำเร็จ'],
        'Cancelled' => ['bg-danger-subtle text-danger-emphasis', 'ยกเลิก'],
    ];
    $d = $map[$s] ?? ['bg-secondary-subtle text-secondary-emphasis', $s];
    return "<span class='badge {$d[0]} px-2 py-1 rounded-pill'>{$d[1]}</span>";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a1a2e; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 8px; margin: 2px 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .stat-card { border: none; border-radius: 15px; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3" style="width:220px; min-width:220px;">
        <h5 class="text-white fw-bold mb-4 mt-2">⚙️ Admin Panel</h5>
        <nav class="nav flex-column gap-1">
            <a href="admin_dashboard.php" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i>แดชบอร์ด</a>
            <a href="admin_manage.php" class="nav-link"><i class="bi bi-box-seam me-2"></i>จัดการสินค้า</a>
            <a href="admin_add.php" class="nav-link"><i class="bi bi-plus-circle me-2"></i>เพิ่มสินค้า</a>
            <a href="admin_orders.php" class="nav-link"><i class="bi bi-receipt me-2"></i>จัดการออเดอร์</a>
            <a href="admin_customers.php" class="nav-link"><i class="bi bi-people me-2"></i>จัดการลูกค้า</a>
            <hr class="border-secondary">
            <a href="shop.php" class="nav-link"><i class="bi bi-shop me-2"></i>หน้าร้านค้า</a>
            <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h4 class="fw-bold mb-4">📊 แดชบอร์ด</h4>

        <!-- สถิติ -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100 text-center p-3">
                    <div class="text-primary fs-1 mb-2"><i class="bi bi-bag-heart-fill"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $total_products; ?></h3>
                    <p class="text-muted mb-0 small">สินค้าทั้งหมด</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100 text-center p-3">
                    <div class="text-success fs-1 mb-2"><i class="bi bi-receipt-cutoff"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $total_orders; ?></h3>
                    <p class="text-muted mb-0 small">ออเดอร์ทั้งหมด</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100 text-center p-3">
                    <div class="text-info fs-1 mb-2"><i class="bi bi-people-fill"></i></div>
                    <h3 class="fw-bold mb-0"><?php echo $total_customers; ?></h3>
                    <p class="text-muted mb-0 small">ลูกค้าทั้งหมด</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm h-100 text-center p-3">
                    <div class="text-warning fs-1 mb-2"><i class="bi bi-cash-coin"></i></div>
                    <h3 class="fw-bold mb-0" style="font-size:1.4rem;">฿<?php echo number_format($total_revenue, 0); ?></h3>
                    <p class="text-muted mb-0 small">รายได้รวม</p>
                </div>
            </div>
        </div>

        <?php if($pending_orders > 0): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <span>มีออเดอร์รอการตรวจสอบ <strong><?php echo $pending_orders; ?> รายการ</strong></span>
            <a href="admin_orders.php" class="btn btn-warning btn-sm ms-auto rounded-pill">ดูออเดอร์</a>
        </div>
        <?php endif; ?>

        <!-- ออเดอร์ล่าสุด -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white py-3 px-4">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>ออเดอร์ล่าสุด</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">เลขออเดอร์</th>
                            <th>ลูกค้า</th>
                            <th>วันที่</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                            <th class="pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($recent_orders) > 0): ?>
                            <?php while($o = mysqli_fetch_assoc($recent_orders)): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?php echo str_pad($o['id'], 5, "0", STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($o['fullname']); ?></td>
                                <td class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($o['order_date'])); ?></td>
                                <td class="fw-bold text-primary">฿<?php echo number_format($o['total_price'], 2); ?></td>
                                <td><?php echo statusBadge($o['status']); ?></td>
                                <td class="pe-4">
                                    <a href="admin_orders.php?view=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> ดู
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">ยังไม่มีออเดอร์</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center bg-white">
                <a href="admin_orders.php" class="btn btn-link text-decoration-none">ดูออเดอร์ทั้งหมด →</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
