<?php
require_once 'config.php';
if (!is_dir('img')) mkdir('img', 0755, true);

$products_images = [
    1=>'10', 2=>'20', 3=>'30', 4=>'40',
    5=>'50', 6=>'60', 7=>'70', 8=>'80',
    9=>'90', 10=>'100', 11=>'110', 12=>'120',
    13=>'130', 14=>'140', 15=>'150', 16=>'160',
];

echo "<h2>กำลังดาวน์โหลดรูป...</h2>";
$success = 0;

foreach ($products_images as $pid => $pic_id) {
    $filename = "product_{$pid}.jpg";
    $filepath = "img/" . $filename;
    $url = "https://picsum.photos/id/{$pic_id}/600/800";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($data && $code == 200 && strlen($data) > 1000) {
        file_put_contents($filepath, $data);
        mysqli_query($conn, "UPDATE products SET image='$filename' WHERE id=$pid");
        echo "✅ ID $pid สำเร็จ<br>";
        $success++;
    } else {
        echo "❌ ID $pid ล้มเหลว (HTTP $code)<br>";
    }
}

echo "<h3>เสร็จ! สำเร็จ $success/16 รูป</h3>";
echo "<a href='shop.php'>→ ไปหน้าร้านค้า</a>";
?>