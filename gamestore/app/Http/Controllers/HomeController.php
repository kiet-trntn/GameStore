<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Hiển thị trang chủ
    public function index() {
        return view('home.index');
    }
}
