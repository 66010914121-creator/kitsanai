<?php
session_start();
require_once 'config.php';

// ลบสินค้า
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // ดึงชื่อไฟล์รูปก่อนลบ
    $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id=$id"));
    if ($res && !str_starts_with($res['image'], 'http') && file_exists('img/'.$res['image'])) {
        unlink('img/'.$res['image']);
    }
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header("Location: admin_manage.php?msg=deleted");
    exit();
}

// แก้ไขสินค้า
if (isset($_POST['edit_product'])) {
    $id    = (int)$_POST['id'];
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);

    // ถ้ามีอัปโหลดรูปใหม่
    if (!empty($_FILES['image']['name'])) {
        $filename = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        if (!is_dir('img')) mkdir('img');
        move_uploaded_file($tempname, 'img/' . $filename);
        $sql = "UPDATE products SET name='$name', price='$price', stock='$stock', image='$filename' WHERE id=$id";
    } else {
        $sql = "UPDATE products SET name='$name', price='$price', stock='$stock' WHERE id=$id";
    }
    mysqli_query($conn, $sql);
    header("Location: admin_manage.php?msg=updated");
    exit();
}

// ดึงสินค้าทั้งหมด
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

// สินค้าที่จะแก้ไข (ถ้ากดปุ่มแก้ไข)
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$edit_id"));
}

function getImageSrc($image) {
    if (empty($image)) return 'https://via.placeholder.com/50x50?text=?';
    if (str_starts_with($image, 'http')) return $image;
    return 'img/' . $image;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a1a2e; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 8px; margin: 2px 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; }
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
            <a href="admin_manage.php" class="nav-link active"><i class="bi bi-box-seam me-2"></i>จัดการสินค้า</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-box-seam text-primary me-2"></i>จัดการสินค้าทั้งหมด</h4>
            <a href="admin_add.php" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> เพิ่มสินค้าใหม่
            </a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_GET['msg'] === 'deleted' ? '🗑️ ลบสินค้าเรียบร้อยแล้ว' : '✅ แก้ไขสินค้าเรียบร้อยแล้ว'; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Modal แก้ไขสินค้า -->
        <?php if($edit_product): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-warning text-dark fw-bold">
                <i class="bi bi-pencil-square me-2"></i>แก้ไขสินค้า: <?php echo htmlspecialchars($edit_product['name']); ?>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($edit_product['name']); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">ราคา (บาท)</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $edit_product['price']; ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">สต็อก</label>
                            <input type="number" name="stock" class="form-control" value="<?php echo $edit_product['stock']; ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">รูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" name="edit_product" class="btn btn-warning fw-bold me-2">
                            <i class="bi bi-save me-1"></i> บันทึกการแก้ไข
                        </button>
                        <a href="admin_manage.php" class="btn btn-outline-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- ตารางสินค้า -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>รูป</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>สต็อก</th>
                            <th class="text-center pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($products) > 0): ?>
                            <?php while($p = mysqli_fetch_assoc($products)): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?php echo $p['id']; ?></td>
                                <td>
                                    <img src="<?php echo getImageSrc($p['image']); ?>" 
                                         class="product-img border"
                                         onerror="this.src='https://via.placeholder.com/50x50?text=?'">
                                </td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($p['name']); ?></td>
                                <td class="text-primary fw-bold">฿<?php echo number_format($p['price'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $p['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?> rounded-pill">
                                        <?php echo $p['stock']; ?> ชิ้น
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="admin_manage.php?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i> แก้ไข
                                    </a>
                                    <a href="admin_manage.php?delete=<?php echo $p['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('ยืนยันการลบสินค้านี้?')">
                                        <i class="bi bi-trash"></i> ลบ
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-box fs-1 d-block mb-2"></i>
                                    ยังไม่มีสินค้าในระบบ
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
