<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    // Trang hỗ trợ chính
    public function index()
    {
        return view('support.index');
    }
    
    // FAQ - Câu hỏi thường gặp
    public function faq()
    {
        $faqs = [
            [
                'category' => 'Tra cứu đơn hàng',
                'icon' => '🔍',
                'questions' => [
                    [
                        'q' => 'Làm thế nào để tra cứu đơn hàng?',
                        'a' => 'Bạn có thể tra cứu đơn hàng bằng cách nhập mã vận đơn vào ô tìm kiếm tại trang "Tra cứu". Mã vận đơn có dạng SHIP + số.'
                    ],
                    [
                        'q' => 'Tôi không nhớ mã vận đơn thì làm sao?',
                        'a' => 'Bạn có thể đăng nhập vào tài khoản để xem tất cả đơn hàng của mình, hoặc liên hệ hotline để được hỗ trợ tra cứu.'
                    ],
                ]
            ],
            [
                'category' => 'Phí vận chuyển',
                'icon' => '💰',
                'questions' => [
                    [
                        'q' => 'Phí vận chuyển được tính như thế nào?',
                        'a' => 'Phí vận chuyển = Phí cơ bản (20k) + Phí theo cân nặng (5k/kg) + Phí khoảng cách + Phí hỏa tốc (nếu có).'
                    ],
                    [
                        'q' => 'Có giảm giá khi gửi nhiều đơn không?',
                        'a' => 'Khách hàng doanh nghiệp sẽ được hưởng ưu đãi từ 10-30% khi gửi từ 50 đơn/tháng trở lên.'
                    ],
                ]
            ],
            [
                'category' => 'Thời gian giao hàng',
                'icon' => '⏱️',
                'questions' => [
                    [
                        'q' => 'Đơn hàng bao lâu sẽ được giao?',
                        'a' => 'Nội thành: 1-2 ngày. Liên tỉnh: 2-5 ngày. Hỏa tốc: Giao trong 24h.'
                    ],
                    [
                        'q' => 'Tôi có thể chọn giờ giao hàng không?',
                        'a' => 'Hiện tại chúng tôi chưa hỗ trợ chọn giờ cụ thể, nhưng bạn có thể liên hệ tài xế khi đơn đang giao.'
                    ],
                ]
            ],
            [
                'category' => 'Đơn hàng & Vấn đề',
                'icon' => '📦',
                'questions' => [
                    [
                        'q' => 'Đơn hàng bị thất lạc thì sao?',
                        'a' => 'Vui lòng liên hệ hotline ngay. Chúng tôi sẽ tra soát và bồi thường 100% nếu lỗi thuộc về chúng tôi.'
                    ],
                    [
                        'q' => 'Tôi muốn hủy đơn hàng đã tạo?',
                        'a' => 'Bạn có thể hủy đơn miễn phí nếu hàng chưa được lấy. Sau khi lấy hàng, phí hủy là 20.000đ.'
                    ],
                ]
            ],
        ];
        
        return view('support.faq', compact('faqs'));
    }
    
    // Contact - Liên hệ
    public function contact()
    {
        return view('support.contact');
    }
    
    // Submit contact form
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string|min:10',
        ]);
        
        // TODO: Gửi email hoặc lưu vào database
        
        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong 24h.');
    }
}

