<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Game;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');

        // Lấy danh sách game
        $games = Game::where('is_active', 1)->get(['title', 'price', 'requirements', 'description']);

        $systemPrompt = "Bạn là nữ nhân viên tư vấn dễ thương của cửa hàng game GameX. 
        Tư vấn dựa trên danh sách game sau: " . json_encode($games) . ". 
        Câu hỏi của khách: " . $userMessage;

        try {
            // Thêm ->withoutVerifying() để fix lỗi SSL trên XAMPP/Laragon
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt]]]
                ]
            ]);

            // Nếu Google trả về code 200 OK
            if ($response->successful()) {
                $reply = $response->json('candidates.0.content.parts.0.text');
                
                // Format lại chữ cho đẹp
                $reply = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $reply); 
                $reply = str_replace("\n", "<br>", $reply);

                return response()->json(['reply' => $reply]);
            } else {
                // Nếu API báo lỗi (VD: sai key, hết hạn mức...)
                return response()->json(['reply' => 'Lỗi từ Google: ' . $response->body()]);
            }

        } catch (\Exception $e) {
            // Nếu bị lỗi kết nối mạng nội bộ
            return response()->json(['reply' => 'Lỗi sập nguồn: ' . $e->getMessage()]);
        }
    }
}