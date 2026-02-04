<?php
session_start();
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>กฤษนัย สรพิมพ์ - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fce4ec; /* พื้นหลังชมพูอ่อน */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.2);
        }
        .btn-pink {
            background-color: #f06292;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #ec407a;
            color: white;
        }
        .form-control:focus {
            border-color: #f06292;
            box-shadow: 0 0 0 0.25 darkred; /* ชมพูเข้ม */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="color: #ad1457;">กฤษนัย สรพิมพ์ (ไกด์)</h3>
                    <p class="text-muted">ระบบจัดการหลังบ้าน (Admin)</p>
                </div>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="auser" class="form-control" placeholder="ชื่อผู้ใช้" autofocus required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="apwd" class="form-control" placeholder="รหัสผ่าน" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="Submit" class="btn btn-pink btn-lg">เข้าสู่ระบบ</button>
                    </div>
                </form>

                <?php
                if(isset($_POST['Submit'])) {
                    include_once("connectdb.php");
                    
                    $user = $_POST['auser'];
                    $pwd = $_POST['apwd'];

                    // 1. ใช้ Prepared Statement ป้องกัน SQL Injection
                    $stmt = mysqli_prepare($conn, "SELECT a_id, a_name, a_password FROM admin WHERE a_username = ? LIMIT 1");
                    mysqli_stmt_bind_param($stmt, "s", $user);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if ($data = mysqli_fetch_array($result)) {
                        // 2. ตรวจสอบรหัสผ่านที่เข้ารหัส (Hash)
                        // หากใน DB ยังไม่เข้ารหัส ให้ใช้: if($pwd == $data['a_password'])
                        if (password_verify($pwd, $data['a_password'])) {
                            $_SESSION['aid'] = $data['a_id'];
                            $_SESSION['aname'] = $data['a_name'];
                            echo "<script>window.location='index2.php';</script>";
                        } else {
                            echo "<div class='alert alert-danger mt-3 text-center'>รหัสผ่านไม่ถูกต้อง</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-3 text-center'>ไม่พบชื่อผู้ใช้นี้</div>";
                    }
                    mysqli_stmt_close($stmt);
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>