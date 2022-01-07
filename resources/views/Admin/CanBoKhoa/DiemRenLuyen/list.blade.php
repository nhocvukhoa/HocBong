@extends('admin_layout')
@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-center">Danh sách điểm rèn luyện</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="loaihocbong_table" width="100%" cellspacing="0">
                <div class="d-flex align-items-center">
                    <form action="{{route('search_diemrenluyen')}}" method="GET">
                        <input type="search" name="search" class="form-control col-md-2 mr-1" placeholder="Nhập tên lớp...">
                       
                        <button type="submit" class="btn btn-primary ml-2">Search</button>
                    </form>
                    <div class="d-flex p-2" style="border: 1px solid gray; margin-left: 360px;">
                        <form action="{{route('import_diemrenluyen')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" accept=".xlsx"><br>
                            <input type="submit" value="Import file excel" name="import_csv" class="btn btn-warning">
                        </form>
                    </div>
                </div>

                <div class="d-flex justify-content-end">

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
                        <th>Học kỳ</th>
                        <th>Mã sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th>Ngày sinh</th>
                        <th>Ngành</th>
                        <th>Lớp</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                        <th>Người đăng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diemrenluyen as $key => $item)
                    <tr>
                        <td>{{ $key + $diemrenluyen->firstItem() }}</td>
                        <td>{{$item->diemrenluyen_hocky}}</td>
                        <td>{{$item->diemrenluyen_msv}}</td>
                        <td>{{$item->diemrenluyen_tensv}}</td>
                        <td>{{date('d-m-Y', strtotime($item->diemrenluyen_ngaysinh));}}</td>
                        <td>{{$item->diemrenluyen_nganh}}</td>
                        <td>{{$item->diemrenluyen_lop}}</td>
                        <td>{{$item->diemrenluyen_diem}}</td>
                        <td>{{$item->diemrenluyen_xeploai}}</td>
                        <td>{{$item->fullname}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="col-sm-12 text-right text-center-xs mt-2">
                <div class="pagination d-flex justify-content-center"> {{$diemrenluyen->links('paginationlinks')}}</div>
            </div>
        </div>
    </div>
</div>


@endsection