<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run()
    {
        $posts = [
            [
                'title' => 'Lễ trao giải Game of the Year 2026: Những ứng cử viên sáng giá nhất đã lộ diện',
                'category' => 'Sự kiện',
                'summary' => 'Đêm vinh danh những siêu phẩm ngành game sắp bắt đầu. Cùng điểm qua danh sách những tựa game đang dẫn đầu cuộc đua năm nay...',
                'content' => '<p>Chỉ còn vài tuần nữa là đến sự kiện lớn nhất năm của làng game thế giới. Năm 2025-2026 chứng kiến sự bùng nổ của hàng loạt bom tấn từ nhập vai, hành động cho đến thế giới mở. Liệu ai sẽ là người đăng quang?</p>',
                'is_published' => 1,
                'created_at' => Carbon::now(), // Bài mới nhất, sẽ lên hình to đùng bên trái
            ],
            [
                'title' => 'Bản cập nhật 2.0 của Cyber Strike: Thêm hệ thống Ray-tracing thế hệ mới',
                'category' => 'Cập nhật',
                'summary' => 'Nhà phát triển vừa tung ra bản patch khổng lồ, đại tu toàn bộ đồ họa và sửa hơn 500 lỗi tồn đọng từ lúc ra mắt.',
                'content' => '<p>Bản cập nhật 2.0 mang đến một trải nghiệm hoàn toàn mới với công nghệ dò tia (Ray-tracing) tối tân. Game thủ giờ đây có thể chiêm ngưỡng thành phố sống động đến từng chi tiết...</p>',
                'is_published' => 1,
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'title' => 'Lộ diện console thế hệ tiếp theo: Hiệu năng gấp đôi, hỗ trợ 8K Native',
                'category' => 'Phần cứng',
                'summary' => 'Những hình ảnh rò rỉ đầu tiên về cỗ máy chơi game thế hệ mới đang làm chao đảo cộng đồng công nghệ toàn cầu.',
                'content' => '<p>Theo các nguồn tin uy tín, hệ máy console tiếp theo sẽ được trang bị chip xử lý tùy chỉnh cực mạnh, cho phép chơi game ở độ phân giải 8K với tốc độ khung hình 60FPS ổn định...</p>',
                'is_published' => 1,
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'title' => 'Giải đấu E-Sports quốc tế sắp sửa đổ bộ vào Việt Nam vào mùa hè này',
                'category' => 'Cộng đồng',
                'summary' => 'Lần đầu tiên, một sự kiện Thể thao điện tử mang tầm cỡ khu vực sẽ được tổ chức tại TP.HCM với giải thưởng lên đến hàng tỷ đồng.',
                'content' => '<p>Cộng đồng game thủ Việt Nam đang sục sôi trước thông tin giải đấu lớn nhất khu vực Đông Nam Á sẽ chọn TP.HCM làm điểm dừng chân. Hàng loạt đội tuyển chuyên nghiệp đang ráo riết tập luyện...</p>',
                'is_published' => 1,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Đánh giá Where Winds Meet: Khi võ hiệp phương Đông vươn tầm thế giới',
                'category' => 'Cộng đồng',
                'summary' => 'Tuyệt tác kiếm hiệp đang làm mưa làm gió có thực sự xuất sắc như lời đồn? Cùng đọc bài review chi tiết từ đội ngũ GameX.',
                'content' => '<p>Ngay từ những phút giây đầu tiên bước vào thế giới của Yến Vân Thập Lục Thanh, người chơi sẽ bị choáng ngợp bởi khung cảnh thiên nhiên hùng vĩ và hệ thống khinh công lướt gió như chim bay...</p>',
                'is_published' => 1,
                'created_at' => Carbon::now()->subDays(3),
            ]
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'category' => $post['category'],
                    'image' => null, // Sau này ba up ảnh bìa thật trong Admin sau nha
                    'summary' => $post['summary'],
                    'content' => $post['content'],
                    'is_published' => $post['is_published'],
                    'created_at' => $post['created_at'],
                    'updated_at' => $post['created_at'],
                ]
            );
        }
    }
}