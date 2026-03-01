<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review; // Nhớ sửa thành Model của ba (Review hoặc Rating)
use App\Models\User;
use App\Models\Game;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách ID của User và Game hiện có trong Database
        $userIds = User::pluck('id')->toArray();
        $gameIds = Game::pluck('id')->toArray();

        // Kiểm tra an toàn: Phải có User và Game thì mới có người đánh giá được
        if (empty($userIds) || empty($gameIds)) {
            $this->command->info('Ba phải chạy UserSeeder và GameSeeder trước nha!');
            return;
        }

        // Một rổ những câu khen ngợi mồi chuẩn "chim mồi"
        $comments = [
            'Siêu phẩm 10 điểm không có nhưng! Đồ họa cháy máy.',
            'Cốt truyện cuốn cực kỳ, chơi xong vẫn còn thấy bồi hồi.',
            'Game hơi nặng so với máy mình nhưng gameplay quá sướng tay.',
            'Đáng từng đồng bạc cắc. Mua ngay đi anh em!',
            'Shop bán game uy tín, tải nhanh, chơi mượt. Sẽ ủng hộ dài dài.',
            'Tuyệt tác của năm! Âm thanh và hình ảnh không chê vào đâu được.',
            'Chơi online cùng bạn bè bao vui. Must play!!!'
        ];

        // Tạo ra khoảng 20 cái đánh giá ngẫu nhiên
        for ($i = 0; $i < 20; $i++) {
            $randomUserId = $userIds[array_rand($userIds)];
            $randomGameId = $gameIds[array_rand($gameIds)];

            // Dùng updateOrCreate để 1 User chỉ được review 1 Game 1 lần thôi (Chuẩn logic thực tế)
            Review::updateOrCreate(
                [
                    'user_id' => $randomUserId,
                    'game_id' => $randomGameId,
                ],
                [
                    'rating' => rand(4, 5), // Hack toàn 4 với 5 sao cho web uy tín =))
                    'comment' => $comments[array_rand($comments)],
                ]
            );
        }
    }
}