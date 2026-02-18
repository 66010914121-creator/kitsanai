<?php
session_start();
require_once 'config.php';

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลออเดอร์ของลูกค้าคนนี้ เรียงจากใหม่ไปเก่า
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; }
        .card-order { border: none; border-radius: 15px; overflow: hidden; }
        .table thead { background-color: #f8f9fa; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand" href="shop.php"><i class="bi bi-bag-heart-fill"></i> CLOTH-SHOP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="shop.php">หน้าร้านค้า</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="order_history.php">ประวัติการสั่งซื้อ</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <span class="text-light me-3 small"><i class="bi bi-person-circle"></i> <?php echo $_SESSION['user_name']; ?></span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">ออกจากระบบ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold"><i class="bi bi-clock-history text-primary"></i> ประวัติการสั่งซื้อ</h3>
            <p class="text-muted">ตรวจสอบสถานะรายการสั่งซื้อทั้งหมดของคุณได้ที่นี่</p>
        </div>
    </div>
    
    <div class="card card-order shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">เลขที่ออเดอร์</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>ยอดรวมสุทธิ</th>
                        <th>สถานะ</th>
                        <th class="text-center pe-4">รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            #<?php echo str_pad($order['id'], 5, "0", STR_PAD_LEFT); ?>
                        </td>
                        <td class="text-muted">
                            <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                        </td>
                        <td class="fw-bold text-primary">
                            ฿<?php echo number_format($order['total_price'], 2); ?>
                        </td>
                        <td>
                            <?php 
                            switch($order['status']) {
                                case 'Pending':
                                    echo '<span class="status-badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-hourglass-split"></i> รอการตรวจสอบ</span>';
                                    break;
                                case 'Paid':
                                    echo '<span class="status-badge bg-info-subtle text-info-emphasis"><i class="bi bi-cash"></i> ชำระเงินแล้ว</span>';
                                    break;
                                case 'Shipping':
                                    echo '<span class="status-badge bg-primary-subtle text-primary-emphasis"><i class="bi bi-truck"></i> กำลังจัดส่ง</span>';
                                    break;
                                case 'Completed':
                                    echo '<span class="status-badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle"></i> สำเร็จ</span>';
                                    break;
                                default:
                                    echo '<span class="status-badge bg-secondary-subtle text-secondary-emphasis">'.$order['status'].'</span>';
                            }
                            ?>
                        </td>
                        <td class="text-center pe-4">
                            <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-light border rounded-pill px-3">
                                ดูรายการ <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(mysqli_num_rows($query) == 0) { ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted">คุณยังไม่มีประวัติการสั่งซื้อในขณะนี้</p>
                                <a href="shop.php" class="btn btn-primary">ไปเลือกซื้อสินค้าเลย</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>