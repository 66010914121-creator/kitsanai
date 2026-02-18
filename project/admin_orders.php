<?php
session_start();
require_once 'config.php';

// อัปเดตสถานะออเดอร์
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status   = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$order_id");
    header("Location: admin_orders.php?msg=updated");
    exit();
}

// ดึงออเดอร์ทั้งหมด พร้อมชื่อลูกค้า
$orders = mysqli_query($conn, "
    SELECT o.*, u.fullname, u.email, u.phone 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC
");

// ดูรายละเอียดออเดอร์
$detail_items = null;
$detail_order = null;
if (isset($_GET['view'])) {
    $view_id = (int)$_GET['view'];
    $detail_order = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT o.*, u.fullname, u.email, u.phone, u.address
        FROM orders o JOIN users u ON o.user_id = u.id
        WHERE o.id = $view_id
    "));
    $detail_items = mysqli_query($conn, "
        SELECT oi.*, p.name, p.image FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = $view_id
    ");
}

$status_list = ['Pending', 'Paid', 'Shipping', 'Completed', 'Cancelled'];

function statusBadge($s) {
    $map = [
        'Pending'   => ['bg-warning-subtle text-warning-emphasis', 'bi-hourglass-split', 'รอการตรวจสอบ'],
        'Paid'      => ['bg-info-subtle text-info-emphasis', 'bi-cash', 'ชำระเงินแล้ว'],
        'Shipping'  => ['bg-primary-subtle text-primary-emphasis', 'bi-truck', 'กำลังจัดส่ง'],
        'Completed' => ['bg-success-subtle text-success-emphasis', 'bi-check-circle', 'สำเร็จ'],
        'Cancelled' => ['bg-danger-subtle text-danger-emphasis', 'bi-x-circle', 'ยกเลิก'],
    ];
    $d = $map[$s] ?? ['bg-secondary-subtle text-secondary-emphasis', 'bi-question', $s];
    return "<span class='badge {$d[0]} px-3 py-2 rounded-pill'><i class='bi {$d[1]} me-1'></i>{$d[2]}</span>";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการออเดอร์ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a1a2e; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 8px; margin: 2px 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .product-img { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; }
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
            <a href="admin_orders.php" class="nav-link active"><i class="bi bi-receipt me-2"></i>จัดการออเดอร์</a>
            <a href="admin_customers.php" class="nav-link"><i class="bi bi-people me-2"></i>จัดการลูกค้า</a>
            <hr class="border-secondary">
            <a href="shop.php" class="nav-link"><i class="bi bi-shop me-2"></i>หน้าร้านค้า</a>
            <a href="logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-receipt text-success me-2"></i>จัดการออเดอร์ทั้งหมด</h4>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                ✅ อัปเดตสถานะออเดอร์เรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- รายละเอียดออเดอร์ -->
        <?php if($detail_order): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-dark text-white fw-bold py-3">
                <i class="bi bi-receipt me-2"></i>
                รายละเอียด ออเดอร์ #<?php echo str_pad($detail_order['id'], 5, "0", STR_PAD_LEFT); ?>
                <a href="admin_orders.php" class="btn btn-sm btn-outline-light float-end">✕ ปิด</a>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">ชื่อลูกค้า</p>
                        <p class="fw-bold mb-0"><?php echo htmlspecialchars($detail_order['fullname']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">อีเมล</p>
                        <p class="fw-bold mb-0"><?php echo htmlspecialchars($detail_order['email']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted small">เบอร์โทร</p>
                        <p class="fw-bold mb-0"><?php echo $detail_order['phone'] ?: '-'; ?></p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1 text-muted small">ที่อยู่จัดส่ง</p>
                        <p class="fw-bold mb-0"><?php echo $detail_order['address'] ?: '-'; ?></p>
                    </div>
                </div>
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>สินค้า</th>
                            <th class="text-center">จำนวน</th>
                            <th>ราคา/ชิ้น</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($it = mysqli_fetch_assoc($detail_items)): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php
                                    $src = empty($it['image']) ? 'https://via.placeholder.com/45' :
                                           (str_starts_with($it['image'], 'http') ? $it['image'] : 'img/'.$it['image']);
                                    ?>
                                    <img src="<?php echo $src; ?>" class="product-img border">
                                    <span class="fw-semibold"><?php echo htmlspecialchars($it['name']); ?></span>
                                </div>
                            </td>
                            <td class="text-center"><?php echo $it['qty']; ?></td>
                            <td>฿<?php echo number_format($it['price'], 2); ?></td>
                            <td class="fw-bold text-primary">฿<?php echo number_format($it['price'] * $it['qty'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold">ยอดรวมทั้งสิ้น:</td>
                            <td class="fw-bold text-primary fs-5">฿<?php echo number_format($detail_order['total_price'], 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- ตารางออเดอร์ -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">เลขออเดอร์</th>
                            <th>ลูกค้า</th>
                            <th>วันที่สั่ง</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                            <th class="text-center">เปลี่ยนสถานะ</th>
                            <th class="text-center pe-4">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($orders) > 0): ?>
                            <?php while($o = mysqli_fetch_assoc($orders)): ?>
                            <tr>
                                <td class="ps-4 fw-bold">#<?php echo str_pad($o['id'], 5, "0", STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($o['fullname']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($o['email']); ?></small>
                                </td>
                                <td class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($o['order_date'])); ?></td>
                                <td class="fw-bold text-primary">฿<?php echo number_format($o['total_price'], 2); ?></td>
                                <td><?php echo statusBadge($o['status']); ?></td>
                                <td class="text-center">
                                    <form action="" method="POST" class="d-flex gap-1 justify-content-center">
                                        <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" style="width:150px;">
                                            <?php foreach($status_list as $s): ?>
                                                <option value="<?php echo $s; ?>" <?php echo $o['status'] === $s ? 'selected' : ''; ?>>
                                                    <?php echo $s; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="admin_orders.php?view=<?php echo $o['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> ดู
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                    ยังไม่มีออเดอร์ในระบบ
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
