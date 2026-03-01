<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ openModal: false }">
    <div class="flex h-screen">
        <aside class="w-64 bg-indigo-900 text-white hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-indigo-800">🚀 AdminPro</div>
            <nav class="flex-grow p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/dashboard*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Dashboard
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/categories*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Danh mục Game
                </a>
                
                <a href="{{ route('admin.games.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/games*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Sản phẩm (Games)
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/orders*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Đơn hàng
                </a>
            
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/users*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Thành viên
                </a>
            
                <a href="{{ route('admin.reviews.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/reviews*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Đánh giá
                </a>

                <a href="{{ route('admin.posts.index') }}" class="block py-2.5 px-4 rounded {{ Request::is('admin/posts*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }} transition">
                    Bài viết
                </a>
            
                {{-- Vách ngăn cách điệu --}}
                <div class="border-t border-indigo-500/30 my-4"></div>
            
                {{-- Nút "Búng" ra ngoài Trang chủ --}}
                <a href="{{ route('home') }}" target="_blank" class="block py-2.5 px-4 rounded hover:bg-indigo-800 text-indigo-200 hover:text-white transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Về Trang cửa hàng
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm z-10">
                <h2 class="text-xl font-black text-gray-800 tracking-tight">Quản trị <span class="text-indigo-600">Hệ thống</span></h2>
                
                <div class="flex items-center space-x-6">
                    {{-- Phần chào hỏi lấy tên THẬT từ Database --}}
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden md:block">
                            <span class="block text-sm text-gray-600">Xin chào,</span>
                            <strong class="block text-sm text-indigo-700 font-black">{{ auth()->user()->name }}</strong>
                        </div>
                        
                        {{-- Avatar tự động render chữ cái đầu của tên --}}
                        <img class="h-10 w-10 rounded-full border-2 border-indigo-200 shadow-sm" 
                             src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4338ca&color=fff&bold=true" 
                             alt="Avatar">
                    </div>
            
                    {{-- Vách ngăn --}}
                    <div class="h-6 w-px bg-gray-200"></div>
            
                    {{-- Form Đăng xuất chuẩn bảo mật Laravel --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-600 transition-colors flex items-center gap-2 group" title="Đăng xuất">
                            <span class="hidden md:inline group-hover:underline">Thoát</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
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
    @yield('scripts')
</body>
</html>