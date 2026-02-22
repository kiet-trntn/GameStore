@extends('layouts.admin')

@section('title', 'Thùng rác Danh mục')

@section('content')
<div class="max-w-6xl mx-auto pb-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Thùng rác <span class="text-red-600">DANH MỤC</span></h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách các danh mục đã tạm xóa, bạn có thể khôi phục hoặc xóa vĩnh viễn.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i> Quay lại danh sách
        </a>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-16 text-center">STT</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Thông tin Danh mục</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Ngày xóa</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50/50 transition duration-200">
                    <td class="px-6 py-4 text-sm text-gray-400 text-center font-medium">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>
                    
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $category->name }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-tighter mt-0.5">Slug: {{ $category->slug ?? 'N/A' }}</div>
                    </td>
                    
                    <td class="px-6 py-4 text-sm text-gray-500 italic">
                        {{ $category->deleted_at->format('d/m/Y H:i') }}
                    </td>
                    
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.restore', $category->id) }}" 
                           class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm"
                           title="Khôi phục">
                            <i class="fas fa-undo-alt"></i>
                        </a>

                        <form action="{{ route('admin.categories.force_delete', $category->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(event)" 
                                    class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" 
                                    title="Xóa vĩnh viễn">
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
        
        @if($categories->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection