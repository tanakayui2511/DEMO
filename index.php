<?php
$sessionDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'sessions';
if (!is_dir($sessionDirectory)) {
    mkdir($sessionDirectory, 0700, true);
}
session_save_path($sessionDirectory);
session_start();

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BugFlow — Theo dõi lỗi & phát hành</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <style>.nav-item{text-decoration:none}</style>
  </head>
  <body>
    <div class="app-shell">
      <aside class="sidebar">
        <a class="brand" href="#dashboard" data-page="dashboard"><span class="brand-mark">B</span><span>bugflow</span></a>
        <span class="workspace-label">KHÔNG GIAN LÀM VIỆC</span>
        <button class="project-switcher"><span class="project-icon">S</span><span>SmartEdu Platform</span><span class="chevron">⌄</span></button>

        <nav class="main-nav" aria-label="Điều hướng chính">
          <button class="nav-item active" data-page="dashboard"><span>▦</span> Tổng quan</button>
          <button class="nav-item" data-page="issues"><span>◉</span> Lỗi <b id="issueCount">12</b></button>
          <button class="nav-item" data-page="releases"><span>◈</span> Phát hành</button>
          <button class="nav-item" data-page="roadmap"><span>⌁</span> Lộ trình</button>
          <button class="nav-item" data-page="team"><span>♧</span> Thành viên</button>
        </nav>

        <div class="sidebar-bottom">
          <button class="nav-item"><span>?</span> Trợ giúp</button>
          <div class="profile"><div class="avatar avatar-linh">LT</div><div><strong><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></small></div><span>⋮</span></div>
          <a class="nav-item logout" href="logout.php"><span>↪</span> Đăng xuất</a>
        </div>
      </aside>

      <main class="main-content">
        <header class="topbar">
          <div class="breadcrumbs"><span>SmartEdu Platform</span><i>/</i><strong id="crumb">Tổng quan</strong></div>
          <div class="top-actions"><button class="icon-button" title="Tìm kiếm">⌕</button><button class="icon-button notification" title="Thông báo">♢<i></i></button><button class="new-button" id="newIssueBtn">+ <span>Tạo lỗi mới</span></button></div>
        </header>

        <section class="page active" id="dashboard">
          <div class="heading-row"><div><p class="eyebrow">THỨ HAI, 12 THÁNG 10</p><h1>Chào buổi sáng, Linh <span>👋</span></h1><p class="subtitle">Đây là tình hình dự án của bạn hôm nay.</p></div><button class="outline-button">⌄ &nbsp; 30 ngày gần đây</button></div>
          <div class="stats-grid">
            <article class="stat-card"><div class="stat-icon purple">◉</div><div><span>Tổng số lỗi</span><strong>124</strong><small class="up">↑ 12% <em>so với tháng trước</em></small></div></article>
            <article class="stat-card"><div class="stat-icon orange">!</div><div><span>Đang mở</span><strong>32</strong><small class="down">↑ 5 <em>từ tuần trước</em></small></div></article>
            <article class="stat-card"><div class="stat-icon blue">✓</div><div><span>Đã giải quyết</span><strong>87</strong><small class="up">↑ 18% <em>so với tháng trước</em></small></div></article>
            <article class="stat-card"><div class="stat-icon green">◈</div><div><span>Bản phát hành</span><strong>3</strong><small><em>trong tháng này</em></small></div></article>
          </div>
          <div class="dashboard-grid">
            <article class="panel activity-panel"><div class="panel-head"><div><h2>Hoạt động gần đây</h2><p>Cập nhật mới nhất trong dự án</p></div><button class="text-button" data-page="issues">Xem tất cả →</button></div><div class="activity-list" id="activityList"></div></article>
            <article class="panel chart-panel"><div class="panel-head"><div><h2>Trạng thái lỗi</h2><p>Phân bố theo trạng thái</p></div><button class="dots">•••</button></div><div class="donut-wrap"><div class="donut"><div><strong>124</strong><span>Tổng lỗi</span></div></div><ul class="legend"><li><i class="dot open"></i>Đang mở <b>32</b></li><li><i class="dot progress"></i>Đang xử lý <b>18</b></li><li><i class="dot testing"></i>Đang kiểm thử <b>12</b></li><li><i class="dot done"></i>Đã giải quyết <b>62</b></li></ul></div></article>
          </div>
          <article class="panel release-overview"><div class="panel-head"><div><h2>Phát hành sắp tới</h2><p>Theo dõi tiến độ các phiên bản</p></div><button class="text-button" data-page="releases">Xem kế hoạch →</button></div><div class="release-summary"><div class="release-version"><span class="release-badge">v2.4.0</span><div><strong>Phát hành tính năng học trực tuyến</strong><small>Ngày dự kiến: 25 tháng 10, 2024</small></div></div><div class="progress-block"><div><span>Tiến độ</span><b>72%</b></div><div class="progress"><i style="width:72%"></i></div></div><div class="members"><div class="avatar avatar-minh">NM</div><div class="avatar avatar-hoa">HP</div><div class="avatar avatar-tuan">TA</div><span>+3</span></div><button class="more-button">Xem chi tiết →</button></div></article>
        </section>

        <section class="page" id="issues">
          <div class="heading-row"><div><p class="eyebrow">QUẢN LÝ CÔNG VIỆC</p><h1>Theo dõi lỗi</h1><p class="subtitle">Ghi nhận, phân công và xử lý lỗi trong dự án.</p></div><button class="new-button new-button-main" id="newIssueBtn2">+ Tạo lỗi mới</button></div>
          <article class="panel issues-panel"><div class="filters"><label class="search"><span>⌕</span><input id="issueSearch" placeholder="Tìm kiếm theo tiêu đề hoặc mã lỗi..." /></label><select id="statusFilter"><option value="all">Tất cả trạng thái</option><option value="Đang mở">Đang mở</option><option value="Đang xử lý">Đang xử lý</option><option value="Đang kiểm thử">Đang kiểm thử</option><option value="Đã giải quyết">Đã giải quyết</option></select><button class="outline-button">☷ Bộ lọc</button></div><div class="table-wrap"><table><thead><tr><th>MÃ LỖI</th><th>TIÊU ĐỀ</th><th>ƯU TIÊN</th><th>TRẠNG THÁI</th><th>NGƯỜI PHỤ TRÁCH</th><th>CẬP NHẬT</th></tr></thead><tbody id="issuesTable"></tbody></table></div></article>
        </section>

        <section class="page" id="releases">
          <div class="heading-row"><div><p class="eyebrow">QUẢN LÝ PHÁT HÀNH</p><h1>Kế hoạch phát hành</h1><p class="subtitle">Chuẩn bị và theo dõi các phiên bản sản phẩm.</p></div><button class="new-button">+ Tạo phiên bản</button></div>
          <div class="release-cards" id="releaseCards"></div>
        </section>

        <section class="page simple-page" id="roadmap"><p class="eyebrow">KẾ HOẠCH SẢN PHẨM</p><h1>Lộ trình phát triển</h1><p class="subtitle">Các tính năng đang được lên kế hoạch cho SmartEdu Platform.</p><article class="panel empty-state"><span>⌁</span><h2>Lộ trình sẽ được cập nhật</h2><p>Hãy tạo phiên bản phát hành đầu tiên để bắt đầu xây dựng lộ trình.</p></article></section>
        <section class="page simple-page" id="team"><p class="eyebrow">DỰ ÁN</p><h1>Thành viên</h1><p class="subtitle">Những người đang đóng góp cho SmartEdu Platform.</p><div class="team-grid" id="teamGrid"></div></section>
      </main>
    </div>

    <div class="modal-backdrop" id="modal"><form class="modal" id="issueForm"><button type="button" class="modal-close" id="closeModal">×</button><p class="eyebrow">TẠO CÔNG VIỆC</p><h2>Báo cáo lỗi mới</h2><label>Tiêu đề lỗi<input id="newIssueTitle" required placeholder="Ví dụ: Không thể đăng nhập bằng Google" /></label><div class="form-grid"><label>Ưu tiên<select id="newIssuePriority"><option>Cao</option><option>Trung bình</option><option>Thấp</option></select></label><label>Người phụ trách<select id="newIssueAssignee"><option>Linh Trần</option><option>Nguyễn Minh</option><option>Hoàng Phúc</option></select></label></div><label>Mô tả<textarea placeholder="Mô tả ngắn về lỗi và cách tái hiện..."></textarea></label><div class="modal-actions"><button type="button" class="outline-button" id="cancelModal">Hủy</button><button class="new-button">Tạo lỗi</button></div></form></div>
    <script src="app.js"></script>
  </body>
</html>
