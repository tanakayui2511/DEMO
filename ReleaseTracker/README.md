# ReleaseTracker — Đề tài 5 (Lab 05 mở rộng)

Ứng dụng PHP 8.1+ và PostgreSQL để theo dõi lỗi/công việc và quản lý kế hoạch phát hành phần mềm. Cấu trúc giữ cách tổ chức Lab 05: `src/`, `public/`, `database/`, `tests/`, truy vấn PostgreSQL có tham số.

## Yêu cầu
- PHP 8.1+ và extension `pgsql`
- PostgreSQL, `psql`, Git

## Cấu hình và chạy
Mở Ubuntu/WSL tại thư mục `ReleaseTracker`:

```bash
sudo service postgresql start
bash lab.sh db-config
bash lab.sh check
bash lab.sh sql
\i database/schema.sql
\i database/seed.sql
\q
bash lab.sh run
```

Mở `http://127.0.0.1:8105`. Dừng máy chủ bằng Ctrl+C. Dữ liệu demo chỉ nạp một lần hoặc dùng `ON CONFLICT` để tránh trùng tài khoản; không chạy trên cơ sở dữ liệu production.

## Tài khoản demo
Tất cả mật khẩu demo là `demo1234`: `dev_a`, `tester_a`, `release_a`, `dev_b`. Hai tài khoản `dev_a/tester_a/release_a` thuộc dự án ReleaseTracker Core; `dev_b` thuộc Mobile Client. Đây là tài khoản học tập, không dùng khi triển khai công khai.

## Chức năng hiện có
- Quản lý hai dự án, thành viên và vai trò theo dự án.
- Tạo và cập nhật lỗi/công việc; bước tái hiện, phân công, ưu tiên, bình luận, commit/PR và kết quả kiểm thử.
- Kiểm tra quan hệ phụ thuộc, từ chối chu trình và liệt kê công việc đang bị chặn.
- Lập phiên bản, gắn công việc, kiểm tra blocker và phụ thuộc, chốt phát hành, xuất ghi chú từ snapshot.
- Kiểm soát xung đột cập nhật bằng trường `version`, kiểm tra CSRF, truy vấn tham số, phân quyền theo dự án và audit log.

## Kiểm thử
```bash
php tests/run.php
php tests/integration.php
```
Cần CSDL đã tạo schema và seed. `tests/integration.php` dùng transaction để kiểm tra quan hệ vòng. Chạy lại kiểm thử sau khi thay đổi.

## Giới hạn cần biết
Đây là bản MVP phục vụ bài lab, chưa phải hệ thống production. Việc chốt phát hành kiểm tra điều kiện và đóng băng danh sách snapshot; cần bổ sung kiểm thử tích hợp sâu hơn, migration có phiên bản, CSRF/rate limit toàn diện, chính sách quyền chi tiết và review bảo mật trước khi triển khai thực tế.

## Giấy phép
Giữ giấy phép của mã nguồn Lab 05 gốc trong `LICENSE`; kiểm tra các nguồn và ghi công bổ sung trước khi phân phối.
