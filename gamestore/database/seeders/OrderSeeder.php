<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách ID của mấy khách hàng (User) hiện có
        $userIds = User::where('role', 'user')->pluck('id')->toArray();

        // Kiểm tra xem có User chưa
        if (empty($userIds)) {
            $this->command->info('Ba chưa có User dân thường kìa, chạy UserSeeder trước nha!');
            return;
        }

        // Tạo sẵn 10 cái mã đơn hàng cố định
        $orderCodes = [
            'GAMEX-83J2X', 'GAMEX-99K1A', 'GAMEX-12M4B', 'GAMEX-77X9P', 'GAMEX-55L2Q',
            'GAMEX-44B1Z', 'GAMEX-88C3Y', 'GAMEX-22V9N', 'GAMEX-11D4M', 'GAMEX-66F5K'
        ];

        // Các phương thức thanh toán và trạng thái ngẫu nhiên
        $paymentMethods = ['vnpay', 'momo', 'cod'];
        $statuses = ['pending', 'completed', 'completed', 'completed', 'cancelled']; // Cho tỉ lệ Thành công cao hơn xíu

        foreach ($orderCodes as $index => $code) {
            
            // Random ngày mua hàng (rải rác trong 30 ngày đổ lại đây cho thật)
            $randomDate = Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 24));

            Order::updateOrCreate(
                ['order_code' => $code], // Tìm xem có đơn này chưa
                [
                    'user_id' => $userIds[array_rand($userIds)], // Lấy random 1 ông khách
                    'total_amount' => rand(20, 200) * 10000, // Random tổng tiền từ 200k đến 2 triệu
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]
            );
        }
    }
}