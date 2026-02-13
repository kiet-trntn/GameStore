@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý Thể loại</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory">Thêm mới</button>
</div>

<table class="table table-hover bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên thể loại</th>
            <th>Slug</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->slug }}</td>
            <td>
                <a href="#" class="btn btn-sm btn-warning">Sửa</a>
                <form action="{{ route('admin.categories.destroy', $item->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="addCategory" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Thêm thể loại mới</h5></div>
            <div class="modal-body">
                <input type="text" name="name" class="form-control" placeholder="Nhập tên thể loại (VD: RPG)" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Lưu lại</button>
            </div>
        </form>
    </div>
</div>
@endsection