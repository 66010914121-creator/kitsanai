<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>kitsanai - รายงานยอดขายรายเดือน</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f7f6; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .card { background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { background: #007bff; color: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        .chart-box { width: 450px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">รายงานยอดขายรายเดือน</h2>

<div class="container">
    <div class="card">
        <table>
            <tr>
                <th>เดือนที่</th>
                <th>ยอดขายรวม</th>
            </tr>
            <?php
            include_once("connectdb.php");
            $sql = "SELECT MONTH(p_date) AS Month, SUM(p_amount) AS Total_Sales 
                    FROM popsupermarket GROUP BY MONTH(p_date) ORDER BY Month ASC";
            $rs = mysqli_query($conn, $sql);
            
            $labels = [];
            $sales = [];
            $monthNames = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];

            while($data = mysqli_fetch_array($rs)){
                $m_name = $monthNames[$data['Month']];
                $labels[] = $m_name;
                $sales[] = (float)$data['Total_Sales'];
            ?>
            <tr>
                <td><?php echo $m_name; ?></td>
                <td align="right"><?php echo number_format($data['Total_Sales'], 2); ?></td>
            </tr>
            <?php } mysqli_close($conn); ?>
        </table>
    </div>

    <div class="card chart-box">
        <canvas id="barChart"></canvas>
    </div>

    <div class="card chart-box">
        <canvas id="doughnutChart"></canvas>
    </div>
</div>

<script>
const ctxLabels = <?php echo json_encode($labels); ?>;
const ctxData = <?php echo json_encode($sales); ?>;
const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF', '#46BFBD', '#F7464A', '#949FB1', '#4D5360', '#AC64AD'];

// กราฟแท่ง (Bar)
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ctxLabels,
        datasets: [{
            label: 'ยอดขายรายเดือน',
            data: ctxData,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    }
});

// กราฟโดนัท (Doughnut)
new Chart(document.getElementById('doughnutChart'), {
    type: 'doughnut',
    data: {
        labels: ctxLabels,
        datasets: [{
            data: ctxData,
            backgroundColor: colors
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

</body>
</html>