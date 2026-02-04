<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kitsanai - Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; padding-top: 20px; }
        .container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .product-img { object-fit: cover; border-radius: 5px; }
    </style>
</head>

<body>

<div class="container">
    <h1 class="mb-4 text-primary">Kitsanai - รายการสินค้า</h1>

    <form method="post" action="" class="row g-3 mb-4">
        <div class="col-auto">
            <input type="text" name="a" class="form-control" placeholder="ค้นหาข้อมูล..." autofocus value="<?php echo @$_POST['a']; ?>">
        </div>
        <div class="col-auto">
            <button type="submit" name="Submit" class="btn btn-primary">ค้นหา</button>
        </div>
    </form>

    <div class="table-responsive">
        <table id="myTable" class="table table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>ชื่อสินค้า</th>
                    <th>ประเภทสินค้า</th>
                    <th>วันที่</th>
                    <th>ประเทศ</th>
                    <th class="text-end">จำนวนเงิน</th>
                    <th class="text-center">รูปภาพ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include_once("connectdb.php");
            @$kw = $_POST['a'];
            
            // ปรับ SQL ให้เว้นวรรคให้ถูกต้องตรง p_product_name
            $sql = "SELECT * FROM `popsupermarket` 
                    WHERE p_country LIKE '%{$kw}%' 
                    OR p_category LIKE '%{$kw}%' 
                    OR p_product_name LIKE '%{$kw}%'";
            
            $rs = mysqli_query($conn, $sql);
            $total = 0;

            if ($rs) {
                while($data = mysqli_fetch_array($rs)) {
                    $total += $data['p_amount']; // แก้จาก + = เป็น +=
            ?>
                <tr>
                    <td><?php echo $data['p_order_id']; ?></td>
                    <td><?php echo $data['p_product_name']; ?></td>
                    <td><span class="badge bg-info text-dark"><?php echo $data['p_category']; ?></span></td>
                    <td><?php echo $data['p_date']; ?></td>
                    <td><?php echo $data['p_country']; ?></td>
                    <td class="text-end fw-bold"><?php echo number_format($data['p_amount'], 0); ?></td>
                    <td class="text-center">
                        <img src="images/<?php echo $data['p_product_name']; ?>.jfif" width="55" height="55" class="product-img shadow-sm">
                    </td>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
            <tfoot class="table-secondary">
                <tr>
                    <th colspan="5" class="text-end">ยอดรวมทั้งสิ้น:</th>
                    <th class="text-end text-danger"><?php echo number_format($total, 0); ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" // เมนูภาษาไทย
            }
        });
    });
</script>

<?php mysqli_close($conn); ?>
</body>
</html>