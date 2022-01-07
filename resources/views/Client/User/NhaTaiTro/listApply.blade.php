@extends('Client.Layout.index')
@section('content')
<section class="post-history">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul id="breadcrumb" style="width: 100%;">
                    <li><a href="{{route('home')}}"><span class="icon fas fa-home mr-2"></span>Trang chủ</a></li>
                    <li><a href="{{route('lichsu_ntt')}}"><span class="icon far fa-list-alt mr-2"></span>Quản lý bài đăng</a></li>
                    <li><a href="#"><span class="icon far fa-list-alt mr-2"></span>Danh sách sinh viên đăng ký học bổng</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="line3"></div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-center">Danh sách sinh viên đăng ký học bổng</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dangkyhocbong_data" width="100%" cellspacing="0">
                        <div class="d-flex justify-content-end mb-3">

                            <form action="{{route('filter.note', ['hocbong_id'=> $hocbong_id])}}" method="GET">
                                <select name="dangky_ghichu" class="form-control col-md-2" placeholder="Chọn ghi chú">
                                    @foreach($dangky_ghichu as $key => $item)
                                    <option @if($paramSearch==$item->dangky_ghichu) selected @endif value="{{$item->dangky_ghichu}}">
                                        {{$item->dangky_ghichu}}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="submit" class="btn btn-info ml-2 mr-2" value="Lọc"></input>
                            </form>
                            <form action="{{route('apply_export', ['hocbong_id' => $hocbong_id])}}" method="POST" enctype="multipart/form-data">
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
                                <th>Ghi chú hồ sơ</th>
                                <th>Hành động</th>
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
                                    @if($user->dangky_ketqua == 0)
                                    Chưa xác định
                                    @else
                                    Được nhận
                                    @endif
                                </td>
                                <td>
                                    @if($user->dangky_ghichu)
                                    {{$user->dangky_ghichu}}
                                    @else
                                    Chưa có
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('apply_detail_hocbong_ntt',['dangky_id' => $user->dangky_id])}}" class="btn btn-info text-uppercase mb-1 detail" title="Xem hồ sơ">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{route('apply_accept_hocbong_ntt',['dangky_id' => $user->dangky_id])}}" class="btn btn-info text-uppercase mb-1 detail" title="Xem hồ sơ">
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
    </div>

</section>


@endsection