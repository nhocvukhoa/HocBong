<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HocBong;
use App\Models\DangKyHocBong;
use App\Models\HocKy;
use App\Models\DiemRenLuyen;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    protected $dangKyHocBong;
    protected $hocBong;
    protected $hocKy;
    public function __construct( DangKyHocBong $dangKyHocBong, HocBong $hocBong, HocKy $hocKy)
    {
            $this->dangKyHocBong = $dangKyHocBong;
            $this->hocBong       = $hocBong;
            $this->hocKy      = $hocKy;
    }

    //TODO: 1. Thống kê Top 5 học bổng được xem nhiều nhất
    public function index() {
        $title = 'Thống kê 1';
        DB::statement("SET SQL_MODE=''");
        $hocbong = HocBong::orderBy('hocbong_luotxem', 'desc')->limit(5)->get();
        $data = "";
        foreach($hocbong as $val) {
            $data.="['".$val->hocbong_ten."', ".$val->hocbong_luotxem."],";
        }
        $chartData = $data;
        return view('Admin.CTSV.ThongKe.index', compact('title', 'hocbong', 'chartData'));
    }

    //TODO: 2. Thống kê top 10 học bổng được đăng kí nhiều nhất
    public function maxRegister() {
        $title = 'Thống kê 2';
        DB::statement("SET SQL_MODE=''");
        $danhsach = $this->dangKyHocBong->select(
            'hocbong.hocbong_ten',
            DB::raw('COUNT(dangkyhocbong.hocbong_id) AS total_amount')
        )
        ->join('hocbong', 'hocbong.hocbong_id', 'dangkyhocbong.hocbong_id')
        // ->groupBy('dangkyhocbong.hocbong_id')
        ->get();
        
        return view('Admin.CTSV.ThongKe.max_register', compact('title', 'danhsach'));
    }

    //TODO: 3. Thống kê số lượng học bổng trong từng học kỳ
    public function totalBySemester() {
        $title = 'Thống kê 2';
        DB::statement("SET SQL_MODE=''");
        $total_bysemester = $this->hocBong->select(
            'hocky.hocky_ten',
            DB::raw('COUNT(hocbong.hocky_id) AS total_bySemester')
        )
        ->join('hocky', 'hocky.hocky_id', 'hocbong.hocky_id')
        ->groupBy('hocbong.hocky_id')
        ->get();
        $data = "";
        foreach($total_bysemester as $val) {
            $data.="['".$val->hocky_ten."', ".$val->total_bySemester."],";
        }
        $chartData = $data;
        return view('Admin.CTSV.ThongKe.total_bySemester', compact('title', 'total_bysemester', 'chartData'));
    }

    //TODO: 4. Thống kê số lượng sinh viên đăng ký của mỗi học bổng
    public function totalStudentApply() {
        $title= 'Thống kê 3';
        DB::statement("SET SQL_MODE=''");
        $total_studentApply = $this->dangKyHocBong->select(
            'hocbong.hocbong_ten',
            DB::raw('COUNT(dangkyhocbong.hocbong_id) AS total_apply, 
                     SUM(dangkyhocbong.dangky_ketqua = 1) as total_accept'),
            //DB::raw('COUNT(dangkyhocbong.dangky_ketqua) as total_accept')
        )
        ->join('hocbong', 'hocbong.hocbong_id', '=', 'dangkyhocbong.hocbong_id')
        ->groupBy('dangkyhocbong.hocbong_id')
        ->get();
        //dd($total_studentApply);
        $data = "";
        foreach($total_studentApply as $val) {
            $data.="['".$val->hocbong_ten."', ".$val->total_apply.",  ".$val->total_accept.",],";
        }
        $chartData = $data;
        return view('Admin.CTSV.ThongKe.total_studentApply', compact('title', 'total_studentApply', 'chartData'));
    }

    //TODO: 5. Thống kê tổng loại điểm rèn luyện của từng lớp
    public function pointTraining() {
        $title = 'Thống kê điểm rèn luyện';
        DB::statement("SET SQL_MODE=''");
        $diemrenluyen = DiemRenLuyen::orderBy('diemrenluyen_id', 'asc')
        ->select(
            'diemrenluyen_lop', 'diemrenluyen_diem', 'diemrenluyen_hocky',
             DB::raw('SUM(diemrenluyen_diem >= 90 AND diemrenluyen_diem <= 100) as tong_xuatsac,
                      SUM(diemrenluyen_diem >= 80 AND diemrenluyen_diem < 90) as tong_tot,
                      SUM(diemrenluyen_diem >= 65 AND diemrenluyen_diem < 80) as tong_kha,
                      SUM(diemrenluyen_diem >= 50 AND diemrenluyen_diem < 65) as tong_trungbinh,
                      SUM(diemrenluyen_diem >= 35 AND diemrenluyen_diem < 50) as tong_yeu,
                      SUM(diemrenluyen_diem < 35) as tong_kem')
        )
        ->groupBy('diemrenluyen_lop')
        ->get();
        return view('Admin.CanBoKhoa.ThongKe.index', compact('title', 'diemrenluyen'));
    }
}
