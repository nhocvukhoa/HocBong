<?php

namespace App\Http\Controllers;

use App\Models\DangKyHocBong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Lop;
use App\Models\Nganh;
use App\Models\Khoa;
use App\Models\HocKy;
use App\Models\HocBong;
use App\Models\LoaiHocBong;
use App\Models\HoSoDangKy;
use App\Models\TruyCap;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\HocBongRequest;
use App\Exports\ExportDanhSachNhanHB;
use Maatwebsite\Excel\Facades\Excel;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;


class UserController extends Controller
{
    //TODO: ---------------------------------I. Khách vãng lai-------------------------
    //TODO: 1. Hiển thị trang Login Client
    public function showLoginHome()
    {
        $title = 'Đăng nhập';
        return view('Client.Layout.client_login', compact('title'));
    }

    //TODO: 2. Thực hiện đăng nhập
    public function loginClient(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'password' => 'required',
            ],
            [
                'name.required' => 'Vui lòng nhập tên đăng nhập',
                'password.required' => 'Vui lòng nhập mật khẩu',
            ]
        );

        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            if ((Auth::user()->quyen == 2 || Auth::user()->quyen == 3) && Auth::user()->tinhtrang == 1) {
                return Redirect::to('/trangchu');
            }
            session()->put('message', 'Tài khoản này không có quyền truy cập');
            Auth::logout();
            return Redirect::to('/user/login');
        } else {
            session()->put('message', 'Tài khoản hoặc mật khẩu sai');
            Auth::logout();
            return Redirect::to('/user/login');
        }
    }

    //TODO: 3. Hiển thị trang đăng kí Client
    public function showRegisterHome()
    {
        $title = 'Đăng kí';
        return view('Client.Layout.client_register', compact('title'));
    }

    //TODO: 4. Thực hiện đăng kí
    public function registerClient(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->quyen = $request->quyen;
        $user->fullname = $request->fullname;
        $user->diachi = $request->diachi;
        $user->sdt = $request->sdt;
        $user->tinhtrang = $request->tinhtrang;

        if (User::where('name', '=', $user->name)->count() > 0) {
            //Toastr::warning('Tên đăng nhập đã tồn tại', 'Thất bại');
            session()->put('message', 'Tên đăng nhập đã tồn tại');
            Auth::logout();
            return redirect()->back();
        }
        $user->save();
        Toastr::success('Đăng ký thành công', 'Vui lòng đợi duyệt');

        return redirect()->back();
    }

    //TODO: 5. Đăng xuất
    public function logoutClient()
    {
        Auth::logout();
        return Redirect::to('/trangchu');
    }



    //TODO: ------------------------------II. Sinh Viên-------------------------
    //TODO: 1. Hiển thị trang thông tin sinh viên
    public function studentInformation()
    {
        if (Gate::allows('sv')) {
            $title = 'Cập nhật thông tin sinh viên';
            $student_id = Auth::user()->id;

            $lop = Lop::orderBy('lop_id', 'asc')->get();
            $nganh = Nganh::orderBy('nganh_id', 'asc')->get();
            $khoa = Khoa::orderBy('khoa_id', 'asc')->get();
            $student = User::where('id', $student_id)
                ->join('lop', 'lop.lop_id', '=', 'users.lop_id')
                ->join('nganh', 'lop.nganh_id', '=', 'nganh.nganh_id')
                ->join('khoa', 'nganh.khoa_id', '=', 'khoa.khoa_id')
                ->first();
            return view('Client.User.SinhVien.showStudentInformation', compact('title', 'student', 'lop'));
        } else {
            return redirect()->back();
        }
    }

    //TODO: 2. Cập nhật thông tin sinh viên
    public function updateStudent(Request $request)
    {
        $data = $request->all();
        $data['id'] = Auth::user()->id;
        $student = User::find($data['id']);
        $student->ngaysinh = $data['ngaysinh'];
        $student->gioitinh = $data['gioitinh'];
        $student->diachi = $data['diachi'];
        $student->sdt = $data['sdt'];
        $student->email = $data['email'];
        $student->save();
        //  session()->put('message', 'Cập nhật thông tin nhà tài trợ thành công');
        Toastr::success('Cập nhật thông tin thành công', 'Thành công');
        return redirect()->back();
    }
    //TODO: 3. Hiển thị trang danh sách đã đăng ký của sinh viên
    public function listRegister()
    {
        if (Gate::allows('sv')) {
            $title = 'Danh sách đăng ký';
            $user_id = Auth::user()->id;
            $listRegistered = DangKyHocBong::orderBy('dangky_id', 'asc')
                ->join('users', 'users.id', '=', 'dangkyhocbong.user_id')
                ->join('hocbong', 'hocbong.hocbong_id', '=', 'dangkyhocbong.hocbong_id')
                ->where('dangkyhocbong.user_id', $user_id)
                ->get();
            return view('Client.User.SinhVien.listRegister', compact('title', 'listRegistered'));
        }
        return redirect()->back();
    }


    //TODO: ------------------------------III. Nhà tài trợ----------------------
    //TODO: 1. Hiển thị thông tin nhà tài trợ
    public function sponsorInformation()
    {
        if (Gate::allows('ntt')) {
            $title = 'Cập nhật thông tin nhà tài trợ';
            $sponsor_id = Auth::user()->id;
            $sponsor = User::where('id', $sponsor_id)->first();
            return view('Client.User.NhaTaiTro.showSponsorInformation', compact('title', 'sponsor'));
        } else {
            return redirect()->back();
        }
    }

    //TODO: 2. Cập nhật thông tin nhà tài trợ
    public function updateSponsor(Request $request)
    {
        if (Gate::allows('ntt')) {
            $data = $request->all();
            $data['id'] = Auth::user()->id;
            $sponsor = User::find($data['id']);
            $sponsor->fullname = $data['fullname'];
            $sponsor->diachi = $data['diachi'];
            $sponsor->sdt = $data['sdt'];
            $sponsor->email = $data['email'];
            $sponsor->save();
            // session()->put('message', 'Cập nhật thông tin nhà tài trợ thành công');
            Toastr::success('Cập nhật thông tin thành công', 'Thành công');
            return redirect()->back();
        }
        return redirect()->back();
    }

    //TODO: 3. Đăng thông tin học bổng 
    public function post()
    {
        if (Gate::allows('ntt')) {
            $title = 'Đăng thông tin học bổng';
            $hocky_hocbong = HocKy::orderBy('hocky_id', 'asc')->get();
            return view('Client.User.NhaTaiTro.uploadHocBong', compact('title', 'hocky_hocbong'));
        }
        return redirect()->back();
    }

    //TODO: 4. Thực hiện đăng thông tin học bổng
    public function save(HocBongRequest $request)
    {
        $data = array();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data['hocbong_ten'] = $request->hocbong_ten;
        $data['loaihocbong_id'] = $request->loaihocbong_id;
        $data['hocky_id'] = $request->hocky_id;
        $data['hocbong_hinhanh'] = $request->hocbong_hinhanh;
        $data['hocbong_file'] = $request->hocbong_file;
        $data['hocbong_noidung'] = $request->hocbong_noidung;
        $data['hocbong_thoigianbatdau'] = $request->hocbong_thoigianbatdau;
        $data['hocbong_thoigianketthuc'] = $request->hocbong_thoigianketthuc;
        $data['hocbong_thoigiandang'] = now();
        $data['hocbong_kinhphi'] = $request->hocbong_kinhphi;
        $data['hocbong_tongsoluong'] = $request->hocbong_tongsoluong;
        $data['hocbong_tinhtrang'] = $request->hocbong_tinhtrang;
        $data['user_id'] = $request->user_id;

        $get_image = $request->file('hocbong_hinhanh');
        if ($get_image) {
            $get_name_image =  $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move(base_path() . '/public/Upload/HocBong', $new_image);
            $data['hocbong_hinhanh'] = $new_image;
            if (HocBong::where('hocbong_ten', '=', $data['hocbong_ten'])->count() > 0) {
                session()->put('error', 'Tên học bổng này đã tồn tại');
                return redirect()->back();
            }
            DB::table('hocbong')->insert($data);
            session()->put('message', 'Đăng học bổng thành công. Vui lòng chờ duyệt!');
            //Toastr::success('Đăng tin học bổng thành công. Vui lòng chờ duyệt', 'Thành công');
            return redirect()->back();
        }
        $data['hocbong_hinhanh'] = '';
        if (HocBong::where('hocbong_ten', '=', $data['hocbong_ten'])->count() > 0) {
            session()->put('error', 'Tên học bổng này đã tồn tại');
            return redirect()->back();
        }
        DB::table('hocbong')->insert($data);
        session()->put('message', 'Đăng học bổng thành công. Vui lòng chờ duyệt!');
        return redirect()->back();
    }

    //TODO: 5. Hiển thị trang lịch sử đăng bài của nhà tài trợ
    public function showHistory()
    {
        $title = 'Lịch sử đăng bài';
        $user_id = Auth::user()->id;
        $user = User::orderBy('id', 'asc')->get();
        $list_post = HocBong::orderBy('hocbong_id')
            ->join('users', 'users.id', '=', 'hocbong.user_id')
            ->where('hocbong.user_id', $user_id)
            ->get();
        return view('Client.User.NhaTaiTro.postHistory', compact('title', 'user', 'list_post'));
    }

    //TODO: 6. Xem danh sách đăng ký học bổng của nhà tài trợ
    public function listApply(Request $request, $hocbong_id)
    {
        $title = "Danh sách đăng ký học bổng";
        $dangky_ghichu = DangKyHocBong::select('dangky_ghichu')
        ->groupBy('dangky_ghichu')
        ->orderBy('dangky_ghichu', 'desc')
        ->get();
        $paramSearch = $request->dangky_ghichu;
        $user_apply = DangKyHocBong::join('users', 'users.id', '=', 'dangkyhocbong.user_id')
            ->join('hocbong', 'hocbong.hocbong_id', '=', 'dangkyhocbong.hocbong_id')
            ->orderBy('dangkyhocbong.dangky_id', 'desc')
            ->where('dangkyhocbong.hocbong_id', $hocbong_id)
            ->get();
        return view('Client.User.NhaTaiTro.listApply', compact('title', 'user_apply', 'hocbong_id', 'dangky_ghichu', 'paramSearch'));
    }

    //TODO: 6.1 Lọc ghi chú
    public function filterNote(Request $request, $hocbong_id) {
        $title = 'Lọc ghi chú';
        $dangky_ghichu = DangKyHocBong::select('dangky_ghichu')
        ->groupBy('dangky_ghichu')
        ->orderBy('dangky_ghichu', 'desc')
        ->get();
        $paramSearch = $request->dangky_ghichu ?? null;
        $user_apply = DangKyHocBong::when($paramSearch, function ($query) use($paramSearch) {
            $query->where('dangky_ghichu', 'like', '%'.$paramSearch.'%');
        })
        // $user_apply = DangKyHocBong::where('dangky_ghichu', 'like', '%'.$paramSearch.'%')
            ->where('dangkyhocbong.hocbong_id', $hocbong_id)
            ->join('users', 'users.id', '=', 'dangkyhocbong.user_id')
            ->join('hocbong', 'hocbong.hocbong_id', '=', 'dangkyhocbong.hocbong_id')
            ->orderBy('dangkyhocbong.dangky_id', 'desc')
            ->get();
        return view('Client.User.NhaTaiTro.listApply', compact('title', 'hocbong_id', 'user_apply', 'dangky_ghichu', 'paramSearch'));
    }


    //TODO: 7. Xem hồ sơ mà sinh viên đã đăng ký
    public function detailApply($dangky_id)
    {
        $title = "Hồ sơ đăng ký";
        $detail_apply = HoSoDangKy::join('tieuchi', 'tieuchi.tieuchi_id', '=', 'hosodangky.tieuchi_id')
            ->join('dangkyhocbong', 'dangkyhocbong.dangky_id', '=', 'hosodangky.dangky_id')
            ->join('hocbong', 'hocbong.hocbong_id', '=', 'dangkyhocbong.hocbong_id')
            ->where('hosodangky.dangky_id', $dangky_id)
            ->orderBy('hosodangky_id', 'asc')
            ->get();
        return view('Client.User.NhaTaiTro.detailApply', compact('title', 'detail_apply', 'dangky_id'));
    }

    //TODO: 8. Duyệt hồ sơ mà sinh viên đã đăng ký
    public function acceptApply($dangky_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        DangKyHocBong::join('hosodangky', 'hosodangky.dangky_id', '=', 'dangkyhocbong.dangky_id')
            ->where('dangkyhocbong.dangky_id', $dangky_id)
            ->update([
                'dangky_tinhtrang' => 1,
                'dangky_ketqua' => 1,
                'dangky_nguoiduyet' => auth()->user()->fullname,
                'dangky_thoigianduyet' => now()
            ]);
        session()->put('message', 'Duyệt hồ sơ sinh viên thành công');
        return redirect()->back();
    }

    //TODO: 9. Xuất danh sách sinh viên được nhận học bổng
    public function exportSelected(Request $request, $hocbong_id)
    {
        return Excel::download(new ExportDanhSachNhanHB($hocbong_id), 'DS_SinhVien_NhanHB.xlsx');
    }

    //TODO: 10. Cập nhật bài đăng
    public function editPost($hocbong_id)
    {
        $title = "Cập nhật bài đăng";
        $hocky = HocKy::orderBy('hocky_id', 'asc')->get();
        $hocbong = HocBong::where('hocbong_id', $hocbong_id)
            ->join('hocky', 'hocky.hocky_id', '=', 'hocbong.hocky_id')
            ->first();
        return view('Client.User.NhaTaiTro.editPost', compact('title', 'hocbong', 'hocky'));
    }

    //TODO: 11. Thực hiện cập nhật bài đăng
    public function updatePost(Request $request, $hocbong_id)
    {
        $data = array();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data['hocbong_ten'] = $request->hocbong_ten;
        $data['loaihocbong_id'] = $request->loaihocbong_id;
        $data['hocky_id'] = $request->hocky_id;
        $data['hocbong_file'] = $request->hocbong_file;
        $data['hocbong_noidung'] = $request->hocbong_noidung;
        $data['hocbong_thoigianbatdau'] = $request->hocbong_thoigianbatdau;
        $data['hocbong_thoigianketthuc'] = $request->hocbong_thoigianketthuc;
        $data['hocbong_kinhphi'] = $request->hocbong_kinhphi;
        $data['hocbong_tongsoluong'] = $request->hocbong_tongsoluong;
        $data['hocbong_thoigiancapnhat'] = now();
        $data['user_id'] = $request->user_id;

        $get_image = $request->file('hocbong_hinhanh');
        if ($get_image) {
            $get_name_image =  $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move(base_path() . '/public/Upload/HocBong', $new_image);
            $data['hocbong_hinhanh'] = $new_image;
            HocBong::where('hocbong_id', $hocbong_id)->update($data);
            session()->put('message', 'Cập nhật bài đăng thành công');
            return redirect()->back();
        }
        HocBong::where('hocbong_id', $hocbong_id)->update($data);
        //session()->put('message', 'Cập nhật bài đăng thành công');
        Toastr::success('Cập nhật bài đăng thành công', 'Thành công');
        return redirect()->back();
    }

    //TODO: 12. Chú thích cho hồ sơ
    public function addNote(Request $request, $dangky_id)
    {
        $request->validate(
            [
                'hoso_ghichu' => 'required|max:255',
            ],
            [
                'hoso_ghichu.required' => 'Vui lòng nhập ghi chú',
            ]
        );
        HoSoDangKy::where('dangky_id', $dangky_id)
            ->update([
                'hoso_ghichu' => $request->hoso_ghichu,
            ]);
        DangKyHocBong::join('hosodangky', 'hosodangky.dangky_id', '=', 'dangkyhocbong.dangky_id')
            ->where('dangkyhocbong.dangky_id', $dangky_id)
            ->update([
                'dangky_ghichu' => $request->hoso_ghichu,
            ]);
    
        Toastr::success('Ghi chú thành công', 'Thành công');
        return redirect()->back();
    }

    //TODO: Đăng ký học bổng
    public function dangkyHocBong(Request $request)
    {

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $user_id = Auth::id();
        $user_name = $request->user_name;
        $user_fullname = $request->user_fullname;
        $user_nganh = $request->user_nganh;
        $user_lop = $request->user_lop;
        $hocbong_id = $request->hocbong_id;
        $dangky_thoigiandk = now();
        $dataDangKyHocBong = [
            'user_id' => $user_id,
            'hocbong_id' => $hocbong_id,
            'user_name' => $user_name,
            'user_fullname' => $user_fullname,
            'user_nganh' => $user_nganh,
            'user_lop' => $user_lop,
            'dangky_thoigiandk' => $dangky_thoigiandk,
        ];


        if ($request->file('image') == NULL) {
            $request->validate(
                [
                    'image[]' => 'required|max:255',
                ],
                [
                    'image[].required' => 'Vui lòng chọn minh chứng',
                ]
            );
            Toastr::danger('Đăng ký thất bại', 'Thất bại');
            return redirect()->back();
        }

        //TODO: Thực hiện đăng ký học bổng
        $dangKyHocBong = DangKyHocBong::create($dataDangKyHocBong);

        //TODO: Tạo hồ sơ đăng ký

        $dangky_id = $dangKyHocBong->dangky_id;
        $path_document = 'public/Upload/ThongBao/';
        $images = $request->file('image');
        $tieuchi = $request->tieuchi_id;

        foreach ($images as $key => $item) {

            //upload files
            if ($item) {
                $nameFile =  $item->getClientOriginalName();
                $name_document = current(explode('.', $nameFile));
                $fullpath = $name_document . rand(0, 99) . '.' . $item->getClientOriginalExtension();
                $item->move($path_document, $fullpath);

                //luu data ho so
                $dataHoSo[$key] = [
                    'dangky_id' => $dangky_id,
                    'tieuchi_id' => $tieuchi[$key],
                    'hoso_hinhanh' => $fullpath
                ];
            }
        }

        // $dangky_id = $dangKyHocBong->dangky_id;
        // $tieuchi = $request->tieuchi_id;
        // $image = array();
        // if($request->hasFile('image')) {
        //     $files = $request->file('image');
        //     foreach($files as $key => $file) {
        //         $image_name = md5(rand(1000, 10000));
        //         $ext = strtolower($file->getClientOriginalExtension());
        //         $image_fullname = $image_name.'.'.$ext;
        //         $upload_path = 'public/Upload/ThongBao/';
        //         $image_url = $upload_path.$image_fullname;
        //         $file->move($upload_path, $image_fullname);
        //         $image[] = $image_url;

        //         $dataHoSo[$key] = [
        //             'dangky_id' => $dangky_id,
        //             'tieuchi_id' => $tieuchi[$key],
        //             'hoso_hinhanh' => $image
        //         ];

        //     }
        // }

        // dd($dataHoSo);

        HoSoDangKy::insert($dataHoSo);

        $hocbong = HocBong::find($request->hocbong_id);
        $soluongdadangky = $hocbong->hocbong_soluongdadangky;
        $hocbong->update(['hocbong_soluongdadangky' => $soluongdadangky + 1]);
        // session()->put('message', 'Đăng ký thành công');
        Toastr::success('Đăng ký thành công', 'Thành công');
        return redirect()->back();
    }


    //TODO: --------------------------III. CTSV------------------------------------
    //TODO: 1. Chuyển sang trang thiết lập quyền
    public function listRole()
    {
        $title = 'Thiết lập quyền';
        $userRole = User::where('quyen', '=', '3')->orderBy('id', 'asc')->get();
        return view('Admin.CTSV.User.listRole', compact('title', 'userRole'));
    }

    //TODO: 2. Khóa quyền người dùng
    public function blockedUser($id)
    {
        User::where('id', $id)->update([
            'tinhtrang' => 0
        ]);
        session()->put('message', 'Đã khóa tài khoản');
        return redirect()->route('show_thietlapquyen');
    }

    //TODO: 3. Mở quyền truy cập cho người dùng
    public function openUser($id)
    {
        User::where('id', $id)->update([
            'tinhtrang' => 1
        ]);
        session()->put('message', 'Đã mở khóa tài khoản');
        return redirect()->route('show_thietlapquyen');
    }

    //TODO: 4. Chuyển sang trang danh sách tài khoản đăng kí cần duyệt
    public function listAcceptAccount()
    {
        $title = "Duyệt tài khoản đăng kí";
        $listUser = User::where('tinhtrang', '0')->orderBy('id', 'asc')->get();
        return view('Admin.CTSV.User.listAcceptAccount', compact('title', 'listUser'));
    }

    //TODO: 5. Duyệt đăng kí tài khoản nhà tài trợ
    public function activeUser($id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        User::where('id', $id)->update([
            'tinhtrang' => 1,
            'ngayDuyetTV' => now()
        ]);
        session()->put('message', 'Đã duyệt tài khoản đăng kí thành công');
        return redirect()->route('list_account');
    }

    //TODO: 6. Xóa tài khoản đăng kí nhà tài trợ (trường hợp đăng kí ảo)
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->put('message', 'Đã xóa tài khoản');
        return redirect()->route('list_account');
    }

    //TODO: 7. Chuyển sang trang duyệt bài đăng của nhà tài trợ
    public function listAcceptPost()
    {
        $title = "Duyệt bài đăng học bổng";
        $listHocBong = HocBong::orderBy('hocbong_id', 'desc')
            ->join('loaihocbong', 'loaihocbong.loaihocbong_id', '=', 'hocbong.loaihocbong_id')
            ->join('hocky', 'hocky.hocky_id', '=', 'hocbong.hocky_id')
            ->join('users', 'users.id', '=', 'hocbong.user_id')
            ->where('hocbong_tinhtrang', '0')
            ->get();
        return view('Admin.CTSV.User.listAcceptPost', compact('title', 'listHocBong'));
    }

    //TODO: 8. Duyệt bài đăng của nhà tài trợ
    public function activePost($hocbong_id)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        HocBong::where('hocbong_id', $hocbong_id)->update([
            'hocbong_tinhtrang' => 1,
            'hocbong_nguoiduyet' => Auth::user()->fullname,
            'hocbong_ngayduyet' => now()
        ]);
        session()->put('message', 'Đã duyệt bài đăng thành công');
        return redirect()->back();
    }

    //TODO: 9. Xem chi tiết bài đăng của nhà tài trợ
    public function detailAcceptPost($hocbong_id)
    {
        $title = 'Chi tiết học bổng';
        $loaihocbong = DB::table('loaihocbong')->orderBy('loaihocbong_id', 'asc')->get();
        $hocky = DB::table('hocky')->orderBy('hocky_id', 'asc')->get();
        $nhataitro = DB::table('users')->orderBy('id', 'asc')->get();

        $detail_hocbong = DB::table('hocbong')
            ->join('loaihocbong', 'loaihocbong.loaihocbong_id', '=', 'hocbong.loaihocbong_id')
            ->join('hocky', 'hocky.hocky_id', '=', 'hocbong.hocky_id')
            ->join('users', 'users.id', '=', 'hocbong.user_id')
            ->where('hocbong.hocbong_id', $hocbong_id)
            ->get();
        return view('Admin.CTSV.User.detailAcceptPost', compact(
            'title',
            'loaihocbong',
            'hocky',
            'detail_hocbong',
            'nhataitro'
        ));
    }

    //TODO: 10. Xóa bài đăng nếu nội dung bài đăng spam
    public function deletePost($hocbong_id)
    {
        $hocbong = HocBong::find($hocbong_id);
        $hocbong->delete();
        session()->put('message', 'Xóa bài đăng thành công');
        return redirect()->back();
    }
}
