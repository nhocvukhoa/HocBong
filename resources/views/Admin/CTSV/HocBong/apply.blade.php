@extends('admin_layout')
@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-center">Danh sách đăng ký học bổng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="loaihocbong_table" width="100%" cellspacing="0">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{route('show_hocbong')}}" class="btn btn-danger text-uppercase mr-2" title="Quay lại">
                        <i class="fas fa-undo-alt mr-2"></i>Quay lại
                    </a>
                    <form action="{{route('export_selected_list', ['hocbong_id' => $hocbong_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="submit" value="Export file excel" name="export_csv" class="btn btn-success">
                    </form>
                </div>
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
                        <th>STT</th>
                        <th>Tên học bổng</th>
                        <th>Mã sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th>Ngành</th>
                        <th>Lớp</th>
                        <th>Thời gian đăng kí</th>
                        <th>Tình trạng</th>
                        <th>Kết quả</th>
                        <th class="col-md-1">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_apply as $key => $user)
                    <tr>
                        <input type="hidden" name="hocbong_id" value="{{$user->hocbong_id}}">
                        <td>{{ $key + 1}}</td>
                        <td>{{ $user->hocbong_ten}}</td>
                        <td>{{ $user->user_name}}</td>
                        <td>{{ $user->user_fullname}}</td>
                        <td>{{ $user->user_nganh}}</td>
                        <td>{{ $user->user_lop}}</td>
                        <td>{{date('d-m-Y H:i:s', strtotime($user->dangky_thoigiandk))}}</td>
                        <td>
                            @if($user->dangky_tinhtrang == 0)
                            Chưa duyệt
                            @else
                            Đã duyệt
                            @endif
                        </td>
                        <td>
                            @if($user->loaihocbong_id == 1)
                                @if($user->dangky_ketqua == 0)
                                    Chưa xác định
                                @else
                                    Được nhận điểm thưởng
                                @endif
                            @else
                                @if($user->dangky_ketqua == 0)
                                    Chưa xác định
                                @else
                                    Được nhận học bổng
                                @endif
                            @endif
                           
                        </td>
                        <td>
                            <a href="{{route('apply_detail_hocbong',['dangky_id' => $user->dangky_id])}}" class="btn btn-info text-uppercase mb-1 detail" title="Xem hồ sơ">
                                <i class="bi bi-eye"></i>
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