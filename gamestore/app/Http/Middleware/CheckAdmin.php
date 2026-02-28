<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem đã Đăng nhập CHƯA, và cái role có phải là 'admin' KHÔNG?
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Đúng là Admin thì cho đi tiếp vào trong
        }

        // Nếu là User thường (hoặc chưa đăng nhập), đá văng ra Trang chủ kèm câu chửi
        return redirect('/')->with('error', 'Khu vực quân sự! Ba không có quyền vào đây nha.');
    }
}
