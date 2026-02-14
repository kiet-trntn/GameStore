@extends('layouts.admin')

@section('title', 'Danh sách Game')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Quản lý kho Game</h1>
        <a href="{{ route('admin.games.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
            <i class="fas fa-plus mr-2"></i> Thêm Game Mới
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase w-24">Ảnh</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase">Tên Game</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase">Danh mục</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase text-right">Giá bán</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($games as $game)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($game->image)
                            <img src="{{ asset('storage/' . $game->image) }}" class="w-16 h-10 object-cover rounded">
                        @else
                            <div class="w-16 h-10 bg-gray-100 rounded flex items-center justify-center text-[10px] text-gray-400">No Pic</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $game->title }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-semibold">
                            {{ $game->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-right font-bold text-indigo-600">
                        {{ number_format($game->price, 0, ',', '.') }}đ
                    </td>
                    <td class="px-6 py-4 text-sm text-center space-x-3">
                        <a href="{{ route('admin.games.edit', $game->id) }}" class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(event)" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">Kho game đang trống. Hãy thêm game đầu tiên!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $games->links() }}
        </div>
    </div>
</div>
@endsection