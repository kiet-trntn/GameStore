@extends('layouts.admin')

@section('title', 'Thêm Game Mới')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Thêm Game mới</h1>

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label>Tên Game</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border @error('title') border-red-500 @enderror">
            @error('title')
                <span class="text-red-500 text-sm italic">{{ $message }}</span>
            @enderror
        </div>
    
        <div class="mb-4">
            <label>Giá bán</label>
            <input type="number" name="price" value="{{ old('price') }}" class="w-full border @error('price') border-red-500 @enderror">
            @error('price')
                <span class="text-red-500 text-sm italic">{{ $message }}</span>
            @enderror
        </div>
    
        <div>
            <label class="block font-semibold mb-1">Danh mục:</label>
            <select name="category_id" class="w-full border border-gray-300 rounded-lg p-2.5 @error('category_id') border-red-500 @enderror">
                {{-- Thêm option trống làm mặc định --}}
                <option value="" selected disabled>-- Chọn danh mục game --</option>
                
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            
            @error('category_id')
                <span class="text-red-500 text-sm italic">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Mô tả game</label>
            <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-lg p-2.5"></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1">Link Trailer (Youtube)</label>
            <input type="text" name="trailer_link" placeholder="https://www.youtube.com/watch?v=..." class="w-full border border-gray-300 rounded-lg p-2.5">
            @error('trailer_link')
                <span class="text-red-500 text-sm italic">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Ảnh bìa Game</label>
            <input type="file" name="image" class="w-full border border-gray-300 rounded-lg p-2 cursor-pointer bg-gray-50">
            <p class="text-xs text-gray-400 mt-1 italic">* Định dạng: jpg, png, jpeg. Tối đa 2MB.</p>
            @error('image')
                <span class="text-red-500 text-sm italic">{{ $message }}</span>
            @enderror
        </div>
    
        <button type="submit">Lưu thử</button>
    </form>
</div>
@endsection