<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Tạo Tài khoản ADMIN tối cao
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Tìm xem có mail này chưa
            [
                'name' => 'Trần Tuấn Kiệt', // Tên Admin xịn xò
                'password' => Hash::make('12345678'), // Đặt pass mặc định dễ nhớ
                'role' => 'admin',
            ]
        );

        // 2. Tạo Tài khoản User số 1 (Dân thường)
        User::updateOrCreate(
            ['email' => 'player1@gmail.com'],
            [
                'name' => 'Gamer Gấu Vàng',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]
        );

        // 3. Tạo Tài khoản User số 2 (Dân thường)
        User::updateOrCreate(
            ['email' => 'player2@gmail.com'],
            [
                'name' => 'Sát Thủ Màn Đêm',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]
        );
    }
}