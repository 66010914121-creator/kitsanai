<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// ดึงข้อมูล order (ตรวจสอบว่าเป็นของ user นี้จริง)
$sql = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$order = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if (!$order) { 
    header("Location: order_history.php"); 
    exit(); 
}

// ดึงรายการสินค้าในออเดอร์นี้
$sql_items = "SELECT oi.*, p.name, p.image FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = $order_id";
$items = mysqli_query($conn, $sql_items);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออเดอร์ #<?php echo str_pad($order_id, 5, "0", STR_PAD_LEFT); ?> - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="shop.php"><i class="bi bi-bag-heart-fill"></i> CLOTH-SHOP</a>
        <a href="order_history.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> กลับ
        </a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- หัวข้อ -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">ออเดอร์ #<?php echo str_pad($order_id, 5, "0", STR_PAD_LEFT); ?></h4>
                    <p class="text-muted mb-0 small">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?> น.
                    </p>
                </div>
                <?php
                switch($order['status']) {
                    case 'Pending':
                        echo '<span class="status-badge bg-warning-subtle text-warning-emphasis"><i class="bi bi-hourglass-split me-1"></i>รอการตรวจสอบ</span>';
                        break;
                    case 'Paid':
                        echo '<span class="status-badge bg-info-subtle text-info-emphasis"><i class="bi bi-cash me-1"></i>ชำระเงินแล้ว</span>';
                        break;
                    case 'Shipping':
                        echo '<span class="status-badge bg-primary-subtle text-primary-emphasis"><i class="bi bi-truck me-1"></i>กำลังจัดส่ง</span>';
                        break;
                    case 'Completed':
                        echo '<span class="status-badge bg-success-subtle text-success-emphasis"><i class="bi bi-check-circle me-1"></i>สำเร็จ</span>';
                        break;
                    default:
                        echo '<span class="status-badge bg-secondary-subtle text-secondary-emphasis">'.$order['status'].'</span>';
                }
                ?>
            </div>

            <!-- รายการสินค้า -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white py-3 px-4">
                    <h6 class="mb-0"><i class="bi bi-bag me-2"></i>รายการสินค้า</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">สินค้า</th>
                                <th class="text-center">จำนวน</th>
                                <th>ราคา/ชิ้น</th>
                                <th>รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($item = mysqli_fetch_assoc($items)): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php
                                        $imgSrc = empty($item['image']) ? 'https://via.placeholder.com/60x60?text=?' :
                                                  (str_starts_with($item['image'], 'http') ? $item['image'] : 'img/' . $item['image']);
                                        ?>
                                        <img src="<?php echo $imgSrc; ?>" class="product-img border"
                                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                                             onerror="this.src='https://via.placeholder.com/60x60?text=?'">
                                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td class="text-center"><?php echo $item['qty']; ?></td>
                                <td>฿<?php echo number_format($item['price'], 2); ?></td>
                                <td class="fw-bold text-primary">฿<?php echo number_format($item['price'] * $item['qty'], 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold ps-4 py-3">ยอดรวมทั้งสิ้น:</td>
                                <td class="fw-bold text-primary fs-5 py-3">฿<?php echo number_format($order['total_price'], 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="text-center">
                <a href="shop.php" class="btn btn-dark rounded-pill px-4 me-2">
                    <i class="bi bi-bag me-1"></i> ช้อปปิ้งต่อ
                </a>
                <a href="order_history.php" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-clock-history me-1"></i> ดูออเดอร์ทั้งหมด
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>