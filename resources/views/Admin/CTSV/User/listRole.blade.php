@extends('admin_layout')
@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-center">Thiết lập quyền</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="loaihocbong_table" width="100%" cellspacing="0">
                <div class="form-group">
                    <?php
                    $message =  session()->get('message');
                    if ($message) {
                        echo '<p class="alert alert-success mt-2" id="alert-box">' . $message . '</p>';
                        session()->put('message', null);
                    }
                    ?>
                </div>
                <thead>
                    <tr>
                        <th>Mã nhà tài trợ</th>
                        <th>Tên đăng nhập</th>
                        <th>Tên nhà tài trợ</th>
                        <th>Email</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Tình trạng</th>
                        <th class="col-md-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userRole as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->fullname}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->diachi}}</td>
                        <td>{{$user->sdt}}</td>
                        <td>
                            @if($user->tinhtrang == 0)
                                Khóa
                            @else
                                Đang hoạt động
                            @endif
                        </td>
                        <td>
                            <a  href="{{route('blocked_user', $user->id)}}" class="btn btn-danger mr-2" title="Khóa">
                                <i class="fas fa-key"></i>
                            </a>
                            <a  href="{{route('open_user', $user->id)}}" class="btn btn-success" title="Mở khóa">
                                <i class="bi bi-check2-circle"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection