<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostOfficeController extends Controller
{
    /**
     * Hiển thị trang tìm kiếm bưu cục
     */
    public function index(Request $request)
    {
        $city = $request->input('city');
        $district = $request->input('district');
        
        $postOffices = $this->getPostOffices($city, $district);
        $cities = $this->getCities();
        
        return view('post-offices.index', compact('postOffices', 'cities', 'city', 'district'));
    }

    /**
     * Danh sách bưu cục (fake data)
     */
    private function getPostOffices($city = null, $district = null)
    {
        $allPostOffices = [
            // Hà Nội
            ['name' => 'Bưu cục Hoàn Kiếm', 'city' => 'Hà Nội', 'district' => 'Hoàn Kiếm', 'address' => '75 Đinh Tiên Hoàng', 'phone' => '024-3942-3088', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Đống Đa', 'city' => 'Hà Nội', 'district' => 'Đống Đa', 'address' => '102 Láng Hạ', 'phone' => '024-3514-5678', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Cầu Giấy', 'city' => 'Hà Nội', 'district' => 'Cầu Giấy', 'address' => '45 Trần Thái Tông', 'phone' => '024-3755-1234', 'hours' => '7:30 - 20:30', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS']],
            ['name' => 'Bưu cục Hai Bà Trưng', 'city' => 'Hà Nội', 'district' => 'Hai Bà Trưng', 'address' => '123 Bạch Mai', 'phone' => '024-3869-7777', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'Chuyển phát nhanh', 'Thu hộ COD']],
            ['name' => 'Bưu cục Thanh Xuân', 'city' => 'Hà Nội', 'district' => 'Thanh Xuân', 'address' => '89 Nguyễn Trãi', 'phone' => '024-3858-9999', 'hours' => '7:00 - 20:00', 'services' => ['Gửi hàng', 'Nhận hàng']],
            ['name' => 'Bưu cục Tây Hồ', 'city' => 'Hà Nội', 'district' => 'Tây Hồ', 'address' => '234 Âu Cơ', 'phone' => '024-3718-2222', 'hours' => '7:30 - 20:30', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS']],
            
            // TP.HCM
            ['name' => 'Bưu cục Quận 1', 'city' => 'TP.HCM', 'district' => 'Quận 1', 'address' => '2 Công xã Paris', 'phone' => '028-3829-5555', 'hours' => '7:00 - 22:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS', 'Chuyển phát nhanh', 'Thu hộ COD']],
            ['name' => 'Bưu cục Quận 3', 'city' => 'TP.HCM', 'district' => 'Quận 3', 'address' => '56 Võ Văn Tần', 'phone' => '028-3930-4444', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Tân Bình', 'city' => 'TP.HCM', 'district' => 'Tân Bình', 'address' => '789 Cộng Hòa', 'phone' => '028-3844-6666', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS']],
            ['name' => 'Bưu cục Bình Thạnh', 'city' => 'TP.HCM', 'district' => 'Bình Thạnh', 'address' => '321 Xô Viết Nghệ Tĩnh', 'phone' => '028-3512-3333', 'hours' => '7:30 - 20:30', 'services' => ['Gửi hàng', 'Nhận hàng']],
            ['name' => 'Bưu cục Thủ Đức', 'city' => 'TP.HCM', 'district' => 'Thủ Đức', 'address' => '456 Võ Văn Ngân', 'phone' => '028-3725-8888', 'hours' => '7:00 - 20:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Quận 7', 'city' => 'TP.HCM', 'district' => 'Quận 7', 'address' => '123 Nguyễn Văn Linh', 'phone' => '028-3771-9999', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS', 'Thu hộ COD']],
            
            // Đà Nẵng
            ['name' => 'Bưu cục Hải Châu', 'city' => 'Đà Nẵng', 'district' => 'Hải Châu', 'address' => '45 Bạch Đằng', 'phone' => '0236-3822-111', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Sơn Trà', 'city' => 'Đà Nẵng', 'district' => 'Sơn Trà', 'address' => '234 Võ Nguyên Giáp', 'phone' => '0236-3888-222', 'hours' => '7:00 - 20:00', 'services' => ['Gửi hàng', 'Nhận hàng']],
            ['name' => 'Bưu cục Thanh Khê', 'city' => 'Đà Nẵng', 'district' => 'Thanh Khê', 'address' => '789 Điện Biên Phủ', 'phone' => '0236-3654-333', 'hours' => '7:30 - 20:30', 'services' => ['Gửi hàng', 'Nhận hàng', 'Chuyển phát nhanh']],
            
            // Hải Phòng
            ['name' => 'Bưu cục Hồng Bàng', 'city' => 'Hải Phòng', 'district' => 'Hồng Bàng', 'address' => '12 Điện Biên Phủ', 'phone' => '0225-3822-444', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS']],
            ['name' => 'Bưu cục Lê Chân', 'city' => 'Hải Phòng', 'district' => 'Lê Chân', 'address' => '56 Lê Thánh Tông', 'phone' => '0225-3745-555', 'hours' => '7:00 - 20:00', 'services' => ['Gửi hàng', 'Nhận hàng']],
            
            // Cần Thơ
            ['name' => 'Bưu cục Ninh Kiều', 'city' => 'Cần Thơ', 'district' => 'Ninh Kiều', 'address' => '78 Trần Hưng Đạo', 'phone' => '0292-3821-666', 'hours' => '7:00 - 21:00', 'services' => ['Gửi hàng', 'Nhận hàng', 'EMS', 'Chuyển phát nhanh']],
            ['name' => 'Bưu cục Cái Răng', 'city' => 'Cần Thơ', 'district' => 'Cái Răng', 'address' => '234 Mậu Thân', 'phone' => '0292-3875-777', 'hours' => '7:00 - 20:00', 'services' => ['Gửi hàng', 'Nhận hàng']],
        ];

        // Filter by city and district
        $filtered = collect($allPostOffices);
        
        if ($city) {
            $filtered = $filtered->filter(fn($po) => $po['city'] === $city);
        }
        
        if ($district) {
            $filtered = $filtered->filter(fn($po) => $po['district'] === $district);
        }
        
        return $filtered->values()->all();
    }

    /**
     * Danh sách thành phố
     */
    private function getCities()
    {
        return [
            'Hà Nội' => ['Hoàn Kiếm', 'Đống Đa', 'Cầu Giấy', 'Hai Bà Trưng', 'Thanh Xuân', 'Tây Hồ', 'Ba Đình', 'Long Biên'],
            'TP.HCM' => ['Quận 1', 'Quận 3', 'Tân Bình', 'Bình Thạnh', 'Thủ Đức', 'Quận 7', 'Quận 10', 'Phú Nhuận'],
            'Đà Nẵng' => ['Hải Châu', 'Sơn Trà', 'Thanh Khê', 'Ngũ Hành Sơn', 'Liên Chiểu'],
            'Hải Phòng' => ['Hồng Bàng', 'Lê Chân', 'Ngô Quyền', 'Kiến An'],
            'Cần Thơ' => ['Ninh Kiều', 'Cái Răng', 'Bình Thủy', 'Ô Môn'],
        ];
    }
}
