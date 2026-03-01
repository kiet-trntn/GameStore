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
        // Lấy ID danh mục để random
        $categoryIds = Category::pluck('id')->toArray();
        if (empty($categoryIds)) {
            $this->command->info('Ba nhớ chạy CategorySeeder trước nha!');
            return;
        }

        // Cấu hình chung cho gọn
        $requirements = [
            'os' => 'Windows 10 64-bit',
            'cpu' => 'Core i5-11400H',
            'ram' => '16GB',
            'gpu' => 'RTX 3050'
        ];

        $games = [
            [
                'title' => 'Baldur\'s Gate 3',
                'description' => '<p>Kiệt tác nhập vai D&D. Tự do định hình câu chuyện của riêng bạn với hàng ngàn ngã rẽ và kết cục khác nhau.</p>',
                'price' => 1250000,
                'sale_price' => 990000,
                'developer' => 'Larian Studios',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1086940/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => '1T22NuDjsNc',
            ],
            [
                'title' => 'Black Myth: Wukong',
                'description' => '<p>Siêu phẩm hành động nhập vai lấy cảm hứng từ Tây Du Ký. Đồ họa Unreal Engine 5 đỉnh cao.</p>',
                'price' => 1299000,
                'sale_price' => null,
                'developer' => 'Game Science',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2358720/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'v=xyz',
            ],
            [
                'title' => 'Cyberpunk 2077',
                'description' => '<p>Khám phá Night City rộng lớn và đầy rẫy tội phạm trong siêu phẩm nhập vai thế giới mở này.</p>',
                'price' => 990000,
                'sale_price' => 495000,
                'developer' => 'CD PROJEKT RED',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'QkkoHAzjnUs',
            ],
            [
                'title' => 'Resident Evil 4 Remake',
                'description' => '<p>Sự hồi sinh của huyền thoại kinh dị sinh tồn. Theo chân Leon cứu con gái tổng thống trong một ngôi làng đầy kinh hãi.</p>',
                'price' => 1140000,
                'sale_price' => 750000,
                'developer' => 'CAPCOM',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2050650/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'j5Xv2lM9SNo',
            ],
            [
                'title' => 'Elden Ring',
                'description' => '<p>Hành trình trở thành Elden Lord trong thế giới mở kỳ vĩ. Siêu phẩm khó nhằn nhưng cực kỳ gây nghiện.</p>',
                'price' => 1090000,
                'sale_price' => null,
                'developer' => 'FromSoftware',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'E3Huy2cdih0',
            ],
            [
                'title' => 'Red Dead Redemption 2',
                'description' => '<p>Câu chuyện sử thi về lòng trung thành và sự cứu rỗi ở bình minh của thời đại hiện đại tại miền Tây nước Mỹ.</p>',
                'price' => 1000000,
                'sale_price' => 330000,
                'developer' => 'Rockstar Games',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'eaW0tYpxn0k',
            ],
            [
                'title' => 'Where Winds Meet',
                'description' => '<p>Tuyệt tác thế giới mở lấy bối cảnh Thập Quốc loạn lạc. Hóa thân thành hiệp khách giang hồ, tự do hành tẩu và định đoạt số phận của chính mình.</p>',
                'price' => 990000,
                'sale_price' => null, // Game sắp ra mắt nên chưa sale
                'developer' => 'Everstone Studio',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2607890/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => '0gI2N10QyOA',
                'release_date' => Carbon::create(2027, 5, 20), // Đẩy sang tương lai
            ],
            [
                'title' => 'Hollow Knight: Silksong',
                'description' => '<p>Siêu phẩm Indie được mong chờ nhất thập kỷ. Khám phá vương quốc của lụa và bài hát cùng nàng Hornet xinh đẹp.</p>',
                'price' => 350000,
                'sale_price' => null,
                'developer' => 'Team Cherry',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1030300/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'mno',
                'release_date' => Carbon::create(2027, 12, 30), // Sắp ra mắt
            ],
            [
                'title' => 'Grand Theft Auto VI',
                'description' => '<p>Siêu phẩm của mọi thời đại. Trở lại Vice City với quy mô lớn chưa từng có. Một cuộc cách mạng về đồ họa và lối chơi thế giới mở.</p>',
                'price' => 1750000,
                'sale_price' => null,
                'developer' => 'Rockstar Games',
                'image' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'QdBZY2fkU-0',
                'release_date' => Carbon::create(2027, 1, 1)->format('Y-m-d'),
            ],
            [
                'title' => 'Ghost of Tsushima DIRECTOR\'S CUT',
                'description' => '<p>Trải nghiệm cuộc chiến bảo vệ đảo Đối Mã trong vai samurai Jin Sakai. Một kiệt tác về hình ảnh và tinh thần võ sĩ đạo.</p>',
                'price' => 1299000,
                'sale_price' => 990000,
                'developer' => 'Sucker Punch Productions',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2215430/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1534423861386-85a16f5d13fd?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'mTiPkhzYevg',
            ],
            [
                'title' => 'Sekiro: Shadows Die Twice',
                'description' => '<p>Game hành động khó nhằn từ FromSoftware. Tập trung vào kỹ năng phản đòn (parry) cực kỳ lôi cuốn trong bối cảnh Nhật Bản thời Chiến Quốc.</p>',
                'price' => 1290000,
                'sale_price' => 645000,
                'developer' => 'FromSoftware',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/814380/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751110-97427bbecf20?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1580234811497-9bd7fd0f56ee?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1552825206-38142fb615e5?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'rXMX4YJ7Lks',
            ],
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'description' => '<p>Hành trình của thợ săn quái vật Geralt đi tìm con gái nuôi Ciri. Một trong những game nhập vai hay nhất mọi thời đại.</p>',
                'price' => 390000,
                'sale_price' => 97000,
                'developer' => 'CD PROJEKT RED',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'XHrskkqv9KY',
            ],
            [
                'title' => 'Bloody Roar 2',
                'description' => '<p>Huyền thoại võ thú thế hệ 8x, 9x. Trải nghiệm những màn biến hình mãn nhãn và các combo rực lửa giữa những chiến binh thú.</p>',
                'price' => 150000,
                'sale_price' => null,
                'developer' => 'Eighting/Raizing',
                'image' => 'https://images.unsplash.com/photo-1542751110-97427bbecf20?auto=format&fit=crop&w=1200',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'y_vS6F_G_V0',
            ],
            [
                'title' => 'God of War Ragnarök',
                'description' => '<p>Hành trình cuối cùng của cha con Kratos trong thần thoại Bắc Âu. Đối đầu với Thor và Odin để ngăn chặn ngày tận thế.</p>',
                'price' => 1290000,
                'sale_price' => 850000,
                'developer' => 'Santa Monica Studio',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2322010/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'hfJ4Km46A-0',
            ],
            [
                'title' => 'Marvel\'s Spider-Man 2',
                'description' => '<p>Đu tơ qua thành phố New York rộng lớn cùng cả Peter Parker và Miles Morales. Đối đầu với những kẻ thù truyền kiếp như Venom và Kraven.</p>',
                'price' => 1490000,
                'sale_price' => null,
                'developer' => 'Insomniac Games',
                'image' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2651280/header.jpg',
                'screenshots' => [
                    'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=1200',
                    'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1200',
                ],
                'trailer_link' => 'bgqGdajvEHQ',
            ],
            
        ];

        // Xóa sạch dữ liệu cũ trong bảng games để làm lại từ đầu cho sạch sẽ
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Game::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($games as $index => $game) {
            Game::create([
                'title' => $game['title'],
                'slug' => Str::slug($game['title']),
                'description' => $game['description'],
                'price' => $game['price'],
                'sale_price' => $game['sale_price'],
                'developer' => $game['developer'],
                'requirements' => $requirements, // Truyền thẳng mảng vô
                'image' => $game['image'],
                'screenshots' => $game['screenshots'], // Truyền thẳng mảng vô
                'trailer_link' => $game['trailer_link'],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'is_active' => 1,
                'is_featured' => 1,
                'views' => rand(100, 5000),
                'release_date' => $game['release_date'] ?? Carbon::now()->subMonths($index * 2),
            ]);
        }
    }
}