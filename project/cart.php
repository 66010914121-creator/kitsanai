<?php
session_start();
require_once 'config.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

// ฟังก์ชันช่วย: ตรวจสอบว่า image เป็น URL หรือชื่อไฟล์
function getImageSrc($image) {
    if (empty($image)) return 'https://via.placeholder.com/60x60?text=?';
    if (str_starts_with($image, 'http')) return $image;
    return 'img/' . $image;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .quantity-control { max-width: 120px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0"><i class="bi bi-cart3"></i> ตะกร้าสินค้าของคุณ</h2>
        <span class="badge bg-primary rounded-pill fs-6">
            <?php echo !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?> รายการ
        </span>
    </div>
    
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">สินค้า</th>
                        <th>ราคา</th>
                        <th class="text-center">จำนวน</th>
                        <th>รวม</th>
                        <th class="text-center pe-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_price = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $p_id => $qty) {
                            $p_id = (int)$p_id;
                            $sql = "SELECT * FROM products WHERE id = $p_id";
                            $query = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($query);
                            
                            if ($row) {
                                $sum = $row['price'] * $qty;
                                $total_price += $sum;
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?php echo getImageSrc($row['image']); ?>" 
                                     class="product-img border" 
                                     alt="<?php echo htmlspecialchars($row['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/60x60?text=?'">
                                <div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                    <small class="text-muted">ID: #<?php echo $row['id']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold">฿<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="input-group quantity-control">
                                    <a href="cart_process.php?reduce=<?php echo $p_id; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-dash"></i></a>
                                    <input type="text" class="form-control form-control-sm text-center bg-white" value="<?php echo $qty; ?>" readonly>
                                    <a href="cart_process.php?add=<?php echo $p_id; ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-plus"></i></a>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-primary">฿<?php echo number_format($sum, 2); ?></td>
                        <td class="text-center pe-4">
                            <a href="cart_process.php?remove=<?php echo $p_id; ?>" class="btn btn-light btn-sm text-danger border" onclick="return confirm('ยืนยันการลบสินค้านี้?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                            }
                        } 
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                <i class='bi bi-cart-x fs-1 d-block mb-3'></i>
                                ยังไม่มีสินค้าในตะกร้า
                              </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_price > 0) { ?>
        <div class="card-footer bg-white p-4 border-top">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <a href="shop.php" class="btn btn-link text-decoration-none p-0 text-secondary">
                        <i class="bi bi-arrow-left"></i> กลับไปเลือกซื้อสินค้า
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-bold">ยอดรวมทั้งสิ้น:</span>
                        <span class="fs-3 fw-bold text-primary">฿<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    <form action="confirm_order.php" method="POST">
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm rounded-3 py-3">
                            ดำเนินการสั่งซื้อ <i class="bi bi-check2-circle ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="card-footer bg-white text-center p-4">
                <a href="shop.php" class="btn btn-primary px-5 py-2 fw-bold">ไปที่ร้านค้าเพื่อเลือกสินค้า</a>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>