<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <aside class="w-64 bg-indigo-900 text-white hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-indigo-800">
                🚀 AdminPro
            </div>
            <nav class="flex-grow p-4 space-y-2">
                <a href="#" class="block py-2.5 px-4 rounded bg-indigo-700 transition">Dashboard</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Người dùng</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Sản phẩm</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Đơn hàng</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-indigo-800 transition">Cài đặt</a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b">
                <div class="flex items-center">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 ml-2">Tổng quan</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Xin chào, <strong>Admin</strong></span>
                    <img class="h-8 w-8 rounded-full border" src="https://ui-avatars.com/api/?name=Admin" alt="Avatar">
                </div>
            </header>

            @yield('content')
        </main>
    </div>

</body>
</html>