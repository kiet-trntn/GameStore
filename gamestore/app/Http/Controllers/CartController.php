<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hàm hiển thị trang Giỏ hàng
    public function index()
    {
        // Tạm thời trả về view giao diện tĩnh
        return view('cart.index');
    }
}