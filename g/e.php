<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Sarabun', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .table-container { margin-bottom: 30px; }
        canvas { max-height: 400px; }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold text-primary">66010914121กฤษนัย สรพิมพ์</h2>

    <?php
    include_once("connectdb.php");
    $sql = "SELECT `p_country`, SUM(`p_amount`) AS total FROM `popsupermarket` GROUP BY `p_country` ORDER BY total DESC;";
    $rs = mysqli_query($conn, $sql);

    $labels = [];
    $values = [];
    ?>

    <div class="row">
        <div class="col-lg-4 table-container">
            <div class="card p-3 h-100">
                <h5 class="card-title mb-3">ตารางสรุปยอดขาย</h5>
                <table class="table table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ประเทศ</th>
                            <th class="text-end">ยอดขาย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($data = mysqli_fetch_array($rs)): ?>
                        <tr>
                            <td><strong><?php echo $data['p_country']; ?></strong></td>
                            <td align="right" class="text-success"><?php echo number_format($data['total'], 2); ?></td>
                        </tr>
                        <?php 
                            $labels[] = $data['p_country'];
                            $values[] = (float)$data['total'];
                        ?>
                        <?php endwhile; mysqli_close($conn); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card p-4">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card p-4 text-center">
                        <div style="width: 70%; margin: auto;">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($values); ?>;

// ชุดสีแบบ Pastel ทันสมัย
const chartColors = [
    'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 
    'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 
    'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
];
const borderColors = chartColors.map(color => color.replace('0.7', '1'));

// ตั้งค่าพื้นฐานสำหรับทุกกราฟ
const commonOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'bottom' }
    }
};

// 1. กราฟแท่ง (Bar Chart)
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'ยอดขาย (Amount)',
            data: dataValues,
            backgroundColor: chartColors,
            borderColor: borderColors,
            borderWidth: 1,
            borderRadius: 8 // ทำมุมมนให้แท่งกราฟ
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});

// 2. กราฟวงกลม (Pie Chart)
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: dataValues,
            backgroundColor: chartColors,
            hoverOffset: 20 // ให้แผ่นกราฟขยายเมื่อเอาเมาส์ไปชี้
        }]
    },
    options: commonOptions
});
</script>

</body>
</html>