<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DiemRenLuyen;
use App\Imports\ImportDanhSachDRL;
use App\Models\Khoa;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class DiemRenLuyenController extends Controller
{
    //TODO: 1. Chuyển sang trang danh sách điểm rèn luyện
    public function list() {
        $title = 'Danh sách điểm rèn luyện';
        $diemrenluyen = DiemRenLuyen::orderBy('diemrenluyen_id','asc')
         ->join('users', 'users.id', '=', 'diemrenluyen.user_id')
        ->paginate(5);
        return view('Admin.CanBoKhoa.DiemRenLuyen.list', compact('title', 'diemrenluyen'));
    }

    //TODO:2. Import file excel điểm rèn luyện
    public function import(Request $request) {
       
        $path = $request->file('file')->getRealPath();
        Excel::import(new ImportDanhSachDRL, $path);
        return redirect()->back();
    }

    //TODO: 3. Tìm kiếm
    public function search(Request $request) {
        $title = "Tìm kiếm";
        $search = $request->get('search');
        $diemrenluyen_hocky= DiemRenLuyen::orderBy('diemrenluyen_hocky', 'asc')->get();
        $diemrenluyen= DiemRenLuyen::orderBy('diemrenluyen_id', 'asc')
                                    ->join('users', 'users.id', '=', 'diemrenluyen.user_id')
                                    ->where('diemrenluyen_lop', 'like', '%'.$search.'%')
                                    ->paginate(20);
        return view('Admin.CanBoKhoa.DiemRenLuyen.list', compact('title', 'diemrenluyen', 'diemrenluyen_hocky'));
    }

}
