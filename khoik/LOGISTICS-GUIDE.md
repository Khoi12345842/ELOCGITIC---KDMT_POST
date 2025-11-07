# 🚚 Hệ thống Logistics - Fake Data Demo

## ✅ Đã hoàn thành

Tôi đã tạo một hệ thống logistics hoàn chỉnh với **fake data ngẫu nhiên** cho phần khách hàng. Hệ thống bao gồm:

### 📦 Database Schema

1. **orders** - Đơn hàng
   - Mã đơn hàng (ORD20231107XXXX)
   - Thông tin người gửi/nhận
   - Thông tin hàng hóa (trọng lượng, COD, phí ship)
   - Trạng thái đơn hàng (8 trạng thái)

2. **shipments** - Vận đơn
   - Mã vận đơn (SHIPYYYYMMDDXXXXX)
   - Vị trí hiện tại
   - Thông tin tài xế
   - Tọa độ GPS (fake)
   - Thời gian giao hàng dự kiến

3. **shipment_histories** - Lịch sử vận chuyển
   - Timeline di chuyển của từng vận đơn
   - Vị trí, trạng thái, thời gian

---

## 🎯 Tính năng đã làm

### ✨ Giao diện khách hàng:

1. **Trang danh sách đơn hàng** (`/orders`)
   - Hiển thị tất cả đơn hàng với pagination
   - Filter theo trạng thái
   - Xem mã vận đơn

2. **Trang chi tiết đơn hàng** (`/orders/{id}`)
   - Thông tin đầy đủ người gửi/nhận
   - Thông tin hàng hóa
   - Timeline vận chuyển chi tiết
   - Thông tin tài xế (nếu có)

3. **Trang tra cứu vận đơn** (`/track`)
   - Nhập mã vận đơn để tracking
   - Hiển thị lịch sử di chuyển real-time (fake)
   - Vị trí hiện tại, tài xế, xe

---

## 🚀 Cách sử dụng

### 1. Truy cập hệ thống

Server đang chạy tại: **http://127.0.0.1:8000**

### 2. Các routes:

- **Danh sách đơn hàng**: http://127.0.0.1:8000/orders
- **Tra cứu vận đơn**: http://127.0.0.1:8000/track

### 3. Fake data đã tạo:

- ✅ **50 đơn hàng** ngẫu nhiên
- ✅ **50 vận đơn** tương ứng
- ✅ **3-7 lịch sử** mỗi vận đơn
- ✅ Tên, địa chỉ, SĐT ngẫu nhiên (tiếng Việt)
- ✅ Trạng thái đa dạng (pending → delivered)
- ✅ Tọa độ GPS ngẫu nhiên (trong Việt Nam)
- ✅ Thông tin tài xế ngẫu nhiên

---

## 🔄 Làm mới data

Nếu muốn tạo lại data ngẫu nhiên mới:

```bash
cd "d:\ELOGICTIC KDMT\khoik"
php artisan migrate:fresh --seed
```

Lệnh này sẽ:
1. Xóa toàn bộ database
2. Tạo lại bảng
3. Tự động fake 50 đơn hàng mới

---

## 🎨 Giao diện

- **Tailwind CSS** - Responsive, đẹp
- **Timeline view** - Hiển thị lịch sử vận chuyển
- **Color-coded status** - Mỗi trạng thái có màu riêng
- **Mobile friendly** - Tự động responsive

---

## 📊 Data được fake:

### Thông tin ngẫu nhiên:
- ✅ Tên người Việt
- ✅ Số điện thoại (09XXXXXXXX)
- ✅ Địa chỉ các tỉnh thành
- ✅ Biển số xe (XX-XXXX XX)
- ✅ Khối lượng (0.5-50kg)
- ✅ Phí ship (20,000-100,000 VNĐ)
- ✅ COD (0-5,000,000 VNĐ)

### Trạng thái đơn hàng:
1. 🟡 Chờ xử lý
2. 🔵 Đã xác nhận
3. 🟡 Đã lấy hàng
4. 🟣 Đang vận chuyển
5. 🟠 Đang giao hàng
6. 🟢 Đã giao hàng
7. 🔴 Đã hủy
8. 🟤 Hoàn trả

---

## 💡 Mở rộng sau này

Khi cần làm phần admin thật:

1. **Admin Dashboard**: Quản lý đơn hàng
2. **Update Status**: Cập nhật trạng thái real-time
3. **Assign Driver**: Gán tài xế cho đơn hàng
4. **Real GPS Tracking**: Tích hợp Google Maps
5. **Notifications**: Thông báo cho khách hàng
6. **Print Label**: In tem vận đơn

---

## 🔧 Các lệnh hữu ích

```bash
# Chạy server
php artisan serve

# Tạo data mới
php artisan migrate:fresh --seed

# Xem routes
php artisan route:list

# Xem database
php artisan tinker
>>> \App\Models\Order::count()
>>> \App\Models\Shipment::first()
```

---

## 📝 Notes

- Data hoàn toàn **FAKE** để demo
- Có thể dùng cho presentation/testing
- Không cần phần backend admin vẫn chạy được
- Khách hàng chỉ **XEM** không **TẠO MỚI** đơn

Chúc bạn demo thành công! 🎉




còn thiếu hoặc cần sửa : liên kết shop doanh nghiệp , tên shop ,giao diện các đơn hàng , giao diện chưa đăng nhập cần hoàn thiện 