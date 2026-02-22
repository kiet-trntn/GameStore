@extends('layouts.admin')

@section('title', 'Thùng rác Game')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thùng rác <span class="text-red-600">GAME</span></h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách các trò chơi đã tạm xóa, bạn có thể khôi phục hoặc xóa vĩnh viễn.</p>
        </div>
        <a href="{{ route('admin.games.index') }}" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i> Quay lại danh sách
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Thông tin Game</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày xóa</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($games as $game)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="w-20 h-12 rounded-lg overflow-hidden border border-gray-100 bg-gray-50">
                            @if($game->image)
                                <img src="{{ asset('storage/' . $game->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full"><i class="fas fa-image text-gray-300"></i></div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $game->title }}</div>
                        <div class="text-xs text-gray-400">{{ $game->category->name ?? 'Không rõ' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 italic">
                        {{ $game->deleted_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.games.restore', $game->id) }}" 
                           class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm"
                           title="Khôi phục">
                            <i class="fas fa-undo-alt"></i>
                        </a>

                        <form action="{{ route('admin.games.force_delete', $game->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Cảnh báo: Hành động này sẽ xóa vĩnh viễn dữ liệu và hình ảnh trên Server. Bạn có chắc chắn không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="confirmDelete(event)" class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" title="Xóa vĩnh viễn">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-trash-restore text-gray-200 text-5xl mb-4"></i>
                            <p class="text-gray-400 font-medium">Thùng rác đang trống rỗng</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($games->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $games->links() }}
        </div>
        @endif
    </div>
</div>
@endsection