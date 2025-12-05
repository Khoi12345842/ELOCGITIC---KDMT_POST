<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách thông báo chưa đọc (cho dropdown)
     */
    public function unread()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->take(5)
            ->get();
            
        return response()->json($notifications);
    }

    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Đánh dấu tất cả đã đọc
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }

    /**
     * Trang danh sách thông báo
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
            
        $notification->delete();
        
        return redirect()->back()->with('success', 'Đã xóa thông báo');
    }
}
