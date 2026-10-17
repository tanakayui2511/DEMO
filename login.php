<?php
$sessionDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';
if (!is_dir($sessionDirectory)) {
    mkdir($sessionDirectory, 0700, true);
}
session_save_path($sessionDirectory);
session_start();

if (!empty($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Tài khoản mẫu cho phần demo. Sau này có thể thay bằng truy vấn CSDL.
    if (hash_equals('linh.tran@smartedu.vn', $email) && hash_equals('123456', $password)) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'name' => 'Linh Trần',
            'email' => $email,
            'role' => 'Project manager',
        ];
        header('Location: index.php');
        exit;
    }

    $error = 'Email hoặc mật khẩu chưa đúng.';
}
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập — BugFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="login.css" />
  </head>
  <body>
    <main class="login-layout">
      <section class="login-intro">
        <a class="brand" href="login.php"><span class="brand-mark">B</span><span>bugflow</span></a>
        <div class="intro-copy"><span class="intro-tag">QUẢN LÝ DỰ ÁN HIỆU QUẢ</span><h1>Mỗi lỗi đều có<br />một hướng giải quyết.</h1><p>Theo dõi lỗi, phối hợp đội ngũ và phát hành sản phẩm tự tin hơn với BugFlow.</p></div>
        <div class="intro-footer"><span>●</span> SmartEdu Platform &nbsp; · &nbsp; Version 2.4.0</div>
      </section>
      <section class="login-panel">
        <form class="login-card" method="post" novalidate>
          <p class="eyebrow">CHÀO MỪNG TRỞ LẠI</p>
          <h2>Đăng nhập BugFlow</h2>
          <p class="muted">Nhập thông tin để tiếp tục vào không gian làm việc.</p>
          <?php if ($error): ?><div class="error-message" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          <label>Email công việc<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="ban@congty.com" autocomplete="email" required autofocus /></label>
          <label>Mật khẩu<input type="password" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password" required /></label>
          <div class="form-options"><label class="remember"><input type="checkbox" /> Ghi nhớ đăng nhập</label><a href="#">Quên mật khẩu?</a></div>
          <button class="login-button" type="submit">Đăng nhập <span>→</span></button>
          <div class="demo-account"><strong>Tài khoản dùng để demo</strong><span>Email: linh.tran@smartedu.vn</span><span>Mật khẩu: 123456</span></div>
        </form>
      </section>
    </main>
  </body>
</html>
