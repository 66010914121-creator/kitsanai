<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ / สมัครสมาชิก - CLOTH-SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
        }
        .auth-card { 
            width: 100%; 
            max-width: 450px; 
            margin: auto; 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }
        .nav-pills .nav-link { color: #6c757d; font-weight: 600; border-radius: 12px; }
        .nav-pills .nav-link.active { background-color: #0d6efd; color: #fff; }
        .form-control { border-radius: 10px; padding: 12px; }
        .input-group-text { border-radius: 10px; background-color: #f8f9fa; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card auth-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">👕 CLOTH-SHOP</h3>
                <p class="text-muted small">ยินดีต้อนรับสู่ร้านค้าออนไลน์ของเรา</p>
            </div>

            <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#login" type="button">เข้าสู่ระบบ</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#register" type="button">สมัครสมาชิก</button>
                </li>
            </ul>
            
            <div class="tab-content">
                <div class="tab-pane fade show active" id="login">
                    <form action="auth_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">อีเมล</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">รหัสผ่าน</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่าน" required>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            เข้าสู่ระบบ <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="register">
                    <form action="auth_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ชื่อ-นามสกุล</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="เช่น สมชาย ใจดี" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">อีเมล</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="name@email.com" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">รหัสผ่าน</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="อย่างน้อย 6 ตัวอักษร" minlength="6" required>
                            </div>
                        </div>
                        <button type="submit" name="register" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            ยืนยันสมัครสมาชิก <i class="bi bi-person-plus ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>