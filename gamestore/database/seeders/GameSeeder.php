<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GameSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách ID của các danh mục đang có để gán ngẫu nhiên cho Game
        // Đảm bảo không bị lỗi khóa ngoại (Foreign Key)
        $categoryIds = Category::pluck('id')->toArray();

        // Nếu chưa có danh mục nào thì ngưng, báo lỗi (phòng hờ)
        if (empty($categoryIds)) {
            $this->command->info('Ba phải chạy CategorySeeder trước nha!');
            return;
        }

        $games = [
            [
                'title' => 'Black Myth: Wukong',
                'description' => '<p>Siêu phẩm hành động nhập vai lấy cảm hứng từ Tây Du Ký. Đồ họa Unreal Engine 5 đỉnh cao.</p>',
                'price' => 1299000,
                'sale_price' => null,
                'developer' => 'Game Science',
                'trailer_link' => 'https://www.youtube.com/embed/v=xyz',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subMonths(2),
            ],
            [
                'title' => 'Cyberpunk 2077: Phantom Liberty',
                'description' => '<p>Bản mở rộng xuất sắc nhất của Cyberpunk. Khám phá quận Dogtown đầy rẫy hiểm nguy và tội phạm.</p>',
                'price' => 990000,
                'sale_price' => 790000, // Đang Sale nè
                'developer' => 'CD PROJEKT RED',
                'trailer_link' => 'https://www.youtube.com/embed/v=abc',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(1),
            ],
            [
                'title' => 'EA SPORTS FC 24',
                'description' => '<p>Kỷ nguyên mới của game bóng đá với công nghệ HyperMotionV siêu chân thực.</p>',
                'price' => 1090000,
                'sale_price' => 545000, // Giảm 50%
                'developer' => 'EA Sports',
                'trailer_link' => 'https://www.youtube.com/embed/v=def',
                'is_featured' => 0,
                'release_date' => Carbon::now()->subMonths(6),
            ],
            [
                'title' => 'Resident Evil 4 Remake',
                'description' => '<p>Sự hồi sinh của huyền thoại kinh dị sinh tồn. Theo chân Leon cứu con gái tổng thống.</p>',
                'price' => 1140000,
                'sale_price' => null,
                'developer' => 'CAPCOM',
                'trailer_link' => 'https://www.youtube.com/embed/v=ghi',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(1)->addMonths(2),
            ],
            [
                'title' => 'Red Dead Redemption 2',
                'description' => '<p>Thế giới mở miền Tây hoang dã vĩ đại nhất từng được tạo ra. Một kiệt tác nghệ thuật.</p>',
                'price' => 1000000,
                'sale_price' => 330000, // Sale sập sàn
                'developer' => 'Rockstar Games',
                'trailer_link' => 'https://www.youtube.com/embed/v=jkl',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(5),
            ],
            [
                'title' => 'Hollow Knight: Silksong',
                'description' => '<p>Siêu phẩm Indie được mong chờ nhất. Khám phá vương quốc của lụa và bài hát.</p>',
                'price' => 350000,
                'sale_price' => null,
                'developer' => 'Team Cherry',
                'trailer_link' => 'https://www.youtube.com/embed/v=mno',
                'is_featured' => 0,
                'release_date' => Carbon::now()->addMonths(3), // Sắp ra mắt (Tương lai)
            ],
            [
                'title' => 'Elden Ring',
                'description' => '<p>Hành trình trở thành Elden Lord trong thế giới mở kỳ vĩ do Hidetaka Miyazaki và nhà văn George R. R. Martin kiến tạo. Siêu phẩm GOTY 2022.</p>',
                'price' => 1090000,
                'sale_price' => null, // Game hot không thèm sale
                'developer' => 'FromSoftware',
                'trailer_link' => 'https://www.youtube.com/embed/E3Huy2cdih0',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(2),
            ],
            [
                'title' => 'Baldur\'s Gate 3',
                'description' => '<p>Kiệt tác nhập vai D&D. Tự do định hình câu chuyện của riêng bạn với hàng ngàn ngã rẽ và kết cục khác nhau.</p>',
                'price' => 1250000,
                'sale_price' => 990000, // Đang sale nhẹ
                'developer' => 'Larian Studios',
                'trailer_link' => 'https://www.youtube.com/embed/1T22NuDjsNc',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subMonths(8),
            ],
            [
                'title' => 'Grand Theft Auto V',
                'description' => '<p>Tựa game huyền thoại không cần giới thiệu. Ba nhân vật, một thành phố Los Santos rộng lớn và điên rồ.</p>',
                'price' => 450000,
                'sale_price' => 225000, // Sale 50%
                'developer' => 'Rockstar North',
                'trailer_link' => 'https://www.youtube.com/embed/QkkoHAzjnUs',
                'is_featured' => 0, // Game cũ rồi, không cho lên top banner nữa
                'release_date' => Carbon::now()->subYears(10),
            ],
            [
                'title' => 'Ghost of Tsushima',
                'description' => '<p>Hóa thân thành Samurai cuối cùng của đảo Tsushima. Đồ họa đẹp như một bài thơ, chặt chém cực kỳ sướng tay.</p>',
                'price' => 1399000,
                'sale_price' => 899000,
                'developer' => 'Sucker Punch',
                'trailer_link' => 'https://www.youtube.com/embed/MUz539AeC5Y',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(3),
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => '<p>Săn lùng quái vật, khám phá lục địa Continent đầy rẫy ma thuật và những câu chuyện đẫm máu cùng Geralt.</p>',
                'price' => 750000,
                'sale_price' => 150000, // Sale sập sàn dọn kho
                'developer' => 'CD PROJEKT RED',
                'trailer_link' => 'https://www.youtube.com/embed/c0i88t0Kacs',
                'is_featured' => 0,
                'release_date' => Carbon::now()->subYears(8),
            ],
            [
                'title' => 'Where Winds Meet (Yến Vân Thập Lục Thanh)',
                'description' => '<p>Tuyệt tác thế giới mở lấy bối cảnh Thập Quốc loạn lạc. Hóa thân thành hiệp khách giang hồ, tự do hành tẩu, học tuyệt học võ công và định đoạt số phận của chính mình.</p>',
                'price' => 990000,
                'sale_price' => null, // Game sắp ra mắt nên chưa sale
                'developer' => 'Everstone Studio',
                'trailer_link' => 'https://www.youtube.com/embed/0gI2N10QyOA',
                'is_featured' => 1,
                'release_date' => Carbon::now()->addMonths(2), // Đẩy ngày ra mắt lên tương lai
            ],
            [
                'title' => 'Bloody Roar: Resurgence',
                'description' => '<p>Huyền thoại đấu võ thú đã trở lại! Nền đồ họa Unreal Engine 5 hoàn toàn mới, giữ nguyên lối chơi bạo liệt và khả năng hóa thú kinh điển làm nức lòng game thủ.</p>',
                'price' => 850000,
                'sale_price' => 450000, // Đang sale mạnh
                'developer' => 'Hudson Soft',
                'trailer_link' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'is_featured' => 0,
                'release_date' => Carbon::now()->subMonths(5),
            ],
            [
                'title' => 'God of War Ragnarök',
                'description' => '<p>Cùng Kratos và Atreus du hành qua Cửu Giới để ngăn chặn tận thế Ragnarök. Câu chuyện cha con đầy cảm xúc cùng hệ thống chiến đấu mãn nhãn.</p>',
                'price' => 1450000,
                'sale_price' => 1150000,
                'developer' => 'Santa Monica Studio',
                'trailer_link' => 'https://www.youtube.com/embed/hfJ4Km46A-0',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subYears(1),
            ],
            [
                'title' => 'Helldivers 2',
                'description' => '<p>Tham gia Lực lượng Helldivers, chiến đấu vì Siêu Trái Đất và lan tỏa Dân chủ khắp thiên hà trong tựa game bắn súng co-op bùng nổ nhất năm.</p>',
                'price' => 850000,
                'sale_price' => null, 
                'developer' => 'Arrowhead Game Studios',
                'trailer_link' => 'https://www.youtube.com/embed/csFBtHdsL04',
                'is_featured' => 1,
                'release_date' => Carbon::now()->subMonths(1),
            ],
            [
                'title' => 'Palworld',
                'description' => '<p>Game sinh tồn thế giới mở cực cuốn. Thu thập những sinh vật bí ẩn gọi là "Pal", xây dựng căn cứ, chế tạo súng đạn và khám phá thế giới.</p>',
                'price' => 450000,
                'sale_price' => 390000, 
                'developer' => 'Pocketpair',
                'trailer_link' => 'https://www.youtube.com/embed/4GjA_FWeA78',
                'is_featured' => 0,
                'release_date' => Carbon::now()->subMonths(2),
            ],
        ];

        foreach ($games as $game) {
            Game::create([
                'title' => $game['title'],
                'slug' => Str::slug($game['title']),
                'description' => $game['description'],
                'price' => $game['price'],
                'sale_price' => $game['sale_price'],
                'developer' => $game['developer'],
                'requirements' => '{"os":"Windows 10 64-bit","cpu":"Core i5-11400H","ram":"16GB","gpu":"RTX 3050"}', // Lấy cấu hình laptop của ba nhét vô luôn cho ngầu :))
                'image' => null, // Mình sẽ úp ảnh bằng tay trong Admin sau
                'screenshots' => json_encode([]), 
                'trailer_link' => $game['trailer_link'],
                'category_id' => $categoryIds[array_rand($categoryIds)], // Random một danh mục bất kỳ
                'is_active' => 1,
                'is_featured' => $game['is_featured'],
                'views' => rand(100, 5000), // Cho lượt view ngẫu nhiên
                'release_date' => $game['release_date'],
            ]);
        }
    }
}