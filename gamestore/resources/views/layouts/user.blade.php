<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameX Pro | Premium Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #050507; /* Đen sâu hơn một chút */
            color: #eee;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(29, 78, 216, 0.03) 0%, transparent 50%);
            background-attachment: fixed;
        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(30px) saturate(160%);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow:
                inset 0 1px rgba(255,255,255,0.05),
                0 20px 40px rgba(0,0,0,0.5);
        }
        /* Hiệu ứng hạt bụi nhỏ (tùy chọn) */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.03;
            pointer-events: none;
            z-index: -1;
        }
        /* Gradient Mesh Background */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: -2;
            background: radial-gradient(at 20% 20%, rgba(37,99,235,0.15), transparent 40%),
                        radial-gradient(at 80% 30%, rgba(59,130,246,0.12), transparent 40%),
                        radial-gradient(at 50% 80%, rgba(99,102,241,0.12), transparent 40%);
            animation: meshMove 18s ease-in-out infinite alternate;
        }

        @keyframes meshMove {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(-30px) scale(1.05); }
        }

    </style>
</head>
<body class="overflow-x-hidden">
    <div class="bg-mesh"></div>
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
        
        <div class="absolute top-[40%] right-[-5%] w-[600px] h-[600px] bg-blue-900/10 blur-[150px] rounded-full"></div>
        
        <div class="absolute bottom-[-10%] left-[20%] w-[700px] h-[700px] bg-blue-500/5 blur-[130px] rounded-full"></div>
    </div>

    <!-- Header -->
    <div class="fixed w-full z-[100] px-4 md:px-10 pt-6">
        <nav class="max-w-7xl mx-auto glass rounded-2xl py-3 px-6 flex justify-between items-center shadow-2xl shadow-black/50 border border-white/10">
            <div class="text-2xl font-bold tracking-tighter flex items-center gap-2">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white italic shadow-lg shadow-blue-500/20">G</div>
                <span class="text-white hidden sm:block">GAMEX</span>
            </div>
    
            <div class="hidden lg:flex items-center gap-10 text-[13px] font-bold uppercase tracking-[0.2em] text-gray-400">
                <a href="#" class="text-blue-500 border-b-2 border-blue-500 pb-1">Cửa hàng</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Khám phá</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Tin tức</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Cộng đồng</a>
            </div>
    
            <div class="flex items-center gap-3">
                <div class="relative hidden xl:block">
                    <input type="text" placeholder="Tìm kiếm siêu phẩm..." 
                           class="bg-white/5 border border-white/5 rounded-xl py-2.5 px-5 text-xs focus:outline-none focus:border-blue-500/50 w-64 focus:w-80 transition-all duration-500">
                    <span class="absolute right-4 top-2.5 text-gray-500">⌘K</span>
                </div>
                
                <div class="flex items-center gap-2 ml-2">
                    <button class="p-2.5 hover:bg-white/5 rounded-xl transition-all text-gray-400 hover:text-white relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border-2 border-[#08080a]"></span>
                    </button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                        Đăng nhập
                    </button>
                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>
    
    <!-- footer -->
    <footer class="container mx-auto px-4 md:px-10 pb-10 mt-20">
        <div class="glass rounded-[2.5rem] p-10 md:p-16 border border-white/10 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/10 blur-[100px] rounded-full"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-600/10 blur-[100px] rounded-full"></div>
    
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
                <div class="space-y-6">
                    <div class="text-3xl font-bold tracking-tighter flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white italic shadow-lg shadow-blue-500/30">G</div>
                        <span class="text-white">GAMEX</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Nền tảng phân phối game bản quyền hàng đầu, mang đến trải nghiệm giải trí đỉnh cao cho game thủ Việt.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-red-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:bg-indigo-600 hover:scale-110 transition-all shadow-lg">
                            <i class="bi bi-discord"></i>
                        </a>
                    </div>
                </div>
    
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Khám phá</h4>
                    <ul class="space-y-4 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Tất cả trò chơi</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Game Indie mới</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Phần mềm & Công cụ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Thẻ quà tặng</a></li>
                    </ul>
                </div>
    
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Hỗ trợ</h4>
                    <ul class="space-y-4 text-sm text-gray-400 font-medium">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Chính sách hoàn tiền</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Trạng thái máy chủ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Liên hệ bản quyền</a></li>
                    </ul>
                </div>
    
                <div class="space-y-6">
                    <h4 class="text-white font-bold mb-2 uppercase tracking-widest text-xs">Đăng ký nhận tin</h4>
                    <p class="text-gray-400 text-xs italic">Đừng bỏ lỡ các đợt Flash Sale cuối tuần!</p>
                    <div class="relative group">
                        <input type="email" placeholder="Email của bạn..." 
                               class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-sm focus:outline-none focus:border-blue-500/50 transition-all outline-none">
                        <button class="absolute right-2 top-2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:row justify-between items-center gap-4 text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                <p>&copy; 2026 GAMEX STORE. MADE BY GAMERS FOR GAMERS.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.mainHeroSwiper', {
            loop: true,
            speed: 1000, // Tăng tốc độ chuyển cảnh cho mượt
            autoplay: { 
                delay: 6000,
                disableOnInteraction: false 
            },
            pagination: { 
                el: '.swiper-pagination', 
                clickable: true,
                dynamicBullets: true // Bullet sẽ to nhỏ theo slide hiện tại
            },
            navigation: { 
                nextEl: '.swiper-button-next', 
                prevEl: '.swiper-button-prev' 
            },
            effect: 'fade',
            fadeEffect: { crossFade: true }
        });

        // Khởi tạo Swiper cho Game Hot
        const hotSwiper = new Swiper('.hotGamesSwiper', {
            slidesPerView: 1.5, // Hiển thị 1 phần của slide tiếp theo để người dùng biết là lướt được
            spaceBetween: 20,
            centeredSlides: false,
            grabCursor: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: '.hot-next',
                prevEl: '.hot-prev',
            },
            breakpoints: {
                // Mobile lớn
                480: {
                    slidesPerView: 2.2,
                },
                // Tablet
                768: {
                    slidesPerView: 3.5,
                },
                // Desktop
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 25, // Tăng khoảng cách cho thoáng
                }
            }
        });
    </script>
</body>
</html>