# 🎮 GAMEX - HỆ THỐNG CỬA HÀNG GAME ĐIỆN TỬ
Đồ án chuyên ngành CNTT.

## 🚀 Công nghệ sử dụng
- Backend: Laravel 11, PHP 8.x, MySQL
- Frontend: Tailwind CSS, AlpineJS, Blade Template
- Tích hợp tính năng AI: Chatbot tư vấn cấu hình game bằng Gemini AI.

## 🛠️ Hướng dẫn cài đặt để chấm điểm
1. Clone dự án về máy: `git clone [link-github]`
2. Cài đặt thư viện: `composer install` và `npm install`
3. Copy file môi trường: `cp .env.example .env` (Nhớ điền thông tin Database và GEMINI_API_KEY)
4. Cấp khóa bảo mật: `php artisan key:generate`
5. Tạo bảng và bơm dữ liệu mẫu: `php artisan migrate:fresh --seed`
6. Khởi động server: `php artisan serve`

## 🔑 Tài khoản Test
- **Admin:** admin@gmail.com / Pass: 12345678
- **User:** player1@gmail.com / Pass: 12345678