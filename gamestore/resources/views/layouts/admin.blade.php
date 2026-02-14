<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ openModal: false }">
    <div class="flex h-screen">
        <aside class="w-64 bg-indigo-900 text-white hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-indigo-800">🚀 AdminPro</div>
            <nav class="flex-grow p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Dashboard</a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/categories*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">Danh mục Game</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Sản phẩm (Games)</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Đơn hàng</a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b">
                <h2 class="text-xl font-semibold text-gray-800">Quản trị hệ thống</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Xin chào, <strong>Admin</strong></span>
                    <img class="h-8 w-8 rounded-full border" src="https://ui-avatars.com/api/?name=Admin" alt="Avatar">
                </div>
            </header>

            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Định nghĩa cấu hình Toast (Thông báo góc phải)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Vị trí: Góc trên bên phải
            showConfirmButton: false, // Ẩn nút OK
            timer: 3000, // Tự tắt sau 3 giây
            timerProgressBar: true, // Có thanh thời gian chạy
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // --- Logic 1: Hiển thị thông báo Success (nếu có) ---
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // --- Logic 2: Hiển thị thông báo Lỗi (nếu có) ---
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // --- Logic 3: Hàm xác nhận Xóa (Giữ nguyên dạng Popup giữa màn hình) ---
        // Vì xóa là hành động nguy hiểm nên cần hiện to ở giữa để cảnh báo
        function confirmDelete(event) {
            event.preventDefault();
            let form = event.target.closest('form');

            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Dữ liệu sẽ không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }

        
        window.confirmForceDelete = function(event) {
        event.preventDefault(); // 1. Chặn việc gửi form ngay lập tức
        let form = event.target.closest('form'); // 2. Tìm cái form chứa nút bấm

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Dữ liệu sẽ không thể khôi phục! (Mất vĩnh viễn)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6', // Màu xanh cho nút Đồng ý
            cancelButtonColor: '#d33',    // Màu đỏ cho nút Hủy
            confirmButtonText: 'Vâng, xóa nó!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng bấm "Vâng", lúc này mới gửi form đi
                form.submit();
            }
        })
    }
    </script>
</body>
</html>