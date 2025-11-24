# Hướng dẫn khắc phục lỗi Google Drive không hiển thị ảnh

## Vấn đề: Ảnh từ Google Drive không hiển thị

Nếu bạn đã nhúng link Google Drive nhưng ảnh không hiển thị, hãy kiểm tra các bước sau:

### ✅ 1. Kiểm tra quyền chia sẻ (QUAN TRỌNG NHẤT)

**Đây là nguyên nhân phổ biến nhất!**

1. Mở Google Drive
2. Tìm file ảnh của bạn
3. Click phải → **Share** (Chia sẻ) hoặc **Get link**
4. Trong phần "General access":
   - Phải chọn: **"Anyone with the link"** (Bất kỳ ai có link)
   - KHÔNG được để: **"Restricted"** (Giới hạn)
5. Trong phần role: Chọn **"Viewer"** (Người xem)
6. Click **Copy link** và **Done**

**Kiểm tra:** URL phải có dạng:
```
https://drive.google.com/file/d/1VavlqpsHKVzPf2l5DRRLSqlTqPxL5ndX/view
```

### ✅ 2. Định dạng URL đúng

Plugin hỗ trợ các format sau:

**Format 1 (Khuyến nghị):**
```
https://drive.google.com/file/d/FILE_ID/view
```

**Format 2:**
```
https://drive.google.com/open?id=FILE_ID
```

**Format 3 (Chỉ ID):**
```
FILE_ID
```

**Ví dụ URL hợp lệ:**
```
https://drive.google.com/file/d/1VavlqpsHKVzPf2l5DRRLSqlTqPxL5ndX/view
```

### ✅ 3. Cách plugin chuyển đổi URL

Plugin tự động chuyển Google Drive URL thành:
```
https://drive.google.com/thumbnail?id=FILE_ID&sz=w2000
```

Đây là **Google Drive Thumbnail API** - phương thức tốt nhất để hiển thị ảnh.

### ✅ 4. Kiểm tra file có tồn tại không

1. Copy link Google Drive
2. Mở trình duyệt ẩn danh (Incognito/Private mode)
3. Paste link vào thanh địa chỉ
4. Nếu bạn thấy ảnh → Link OK
5. Nếu bạn thấy "Need permission" → Quyền chưa đúng
6. Nếu bạn thấy "File not found" → File đã bị xóa

### ✅ 5. Thời gian xử lý

Sau khi đổi quyền chia sẻ:
- Google Drive cần **5-30 giây** để cập nhật quyền
- Đợi một chút rồi refresh trang
- Clear cache trình duyệt nếu vẫn không thấy

### ✅ 6. Kiểm tra loại file

Plugin hỗ trợ các định dạng ảnh:
- ✅ JPG/JPEG
- ✅ PNG
- ✅ GIF
- ✅ WebP
- ❌ KHÔNG hỗ trợ: PDF, Video, Documents

### ✅ 7. Giới hạn kích thước

Google Drive Thumbnail API giới hạn:
- Kích thước tối đa: **2000px** (plugin đang dùng)
- File quá lớn có thể load chậm
- Khuyến nghị: Ảnh dưới 5MB

### ✅ 8. Kiểm tra trong Admin WordPress

1. Vào **Slide Albums** → Chỉnh sửa album
2. Nhập URL Google Drive vào trường
3. Click ra ngoài (blur) để xem preview
4. Nếu preview không hiển thị → Có lỗi với link

### ✅ 9. Test với URL khác nhau

Plugin tự động thử nhiều format:

**Method 1: Thumbnail API (Mặc định)**
```
https://drive.google.com/thumbnail?id=FILE_ID&sz=w2000
```

**Method 2: Download API (Nếu method 1 fail)**
```
https://drive.google.com/uc?export=download&id=FILE_ID
```

**Method 3: View API (Fallback)**
```
https://drive.google.com/uc?export=view&id=FILE_ID
```

### ✅ 10. Các lỗi thường gặp

#### Lỗi: "Ảnh không tải được"
**Nguyên nhân:** Quyền chia sẻ chưa đúng  
**Giải pháp:** Đổi sang "Anyone with the link"

#### Lỗi: "403 Forbidden"
**Nguyên nhân:** File bị giới hạn quyền truy cập  
**Giải pháp:** Kiểm tra lại General Access settings

#### Lỗi: "404 Not Found"
**Nguyên nhân:** File không tồn tại hoặc ID sai  
**Giải pháp:** Kiểm tra lại link

#### Lỗi: Ảnh load chậm
**Nguyên nhân:** File quá lớn hoặc mạng chậm  
**Giải pháp:** Nén ảnh trước khi upload

### ✅ 11. Cách test quyền truy cập

**Test bằng trình duyệt ẩn danh:**

1. Mở Chrome/Firefox ở chế độ Incognito
2. Paste URL này vào thanh địa chỉ:
```
https://drive.google.com/thumbnail?id=FILE_ID&sz=w500
```
(Thay FILE_ID bằng ID thực tế)

3. Nếu thấy ảnh → OK ✅
4. Nếu báo lỗi → Quyền chưa đúng ❌

### ✅ 12. Alternative: Dùng Google Photos

Nếu Google Drive vẫn không hoạt động, bạn có thể:

1. Upload ảnh lên **Google Photos**
2. Get link từ Google Photos
3. Hoặc dùng các dịch vụ khác: Imgur, Cloudinary, etc.

### ✅ 13. Kiểm tra Console lỗi

1. Mở trang web có album
2. Press F12 để mở Developer Tools
3. Vào tab **Console**
4. Xem có lỗi liên quan đến Google Drive không
5. Thường sẽ thấy lỗi CORS hoặc 403 nếu quyền chưa đúng

### ✅ 14. Clear Cache

Sau khi sửa quyền:
1. Clear cache WordPress (nếu dùng cache plugin)
2. Clear cache trình duyệt (Ctrl+Shift+Del)
3. Thử trên trình duyệt khác
4. Thử trên thiết bị khác

### 🔧 Debug Mode

Nếu vẫn không hoạt động, bật debug:

1. Mở file `wp-config.php`
2. Thêm:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```
3. Kiểm tra file `/wp-content/debug.log` để xem lỗi

### 📞 Hỗ trợ

Nếu đã thử tất cả các bước trên mà vẫn không được:

1. Kiểm tra file ID có đúng không
2. Thử với file ảnh khác
3. Kiểm tra xem Google Drive có bị giới hạn bởi tổ chức/công ty không
4. Liên hệ support với thông tin:
   - URL Google Drive đầy đủ
   - Screenshot lỗi trong Console (F12)
   - Screenshot quyền chia sẻ trong Google Drive

### ✅ Checklist nhanh

- [ ] Quyền chia sẻ: "Anyone with the link"
- [ ] Role: "Viewer"
- [ ] URL format đúng: `/file/d/FILE_ID/view`
- [ ] Test trong trình duyệt ẩn danh
- [ ] Đợi 30 giây sau khi đổi quyền
- [ ] Clear cache
- [ ] Kiểm tra Console (F12) có lỗi không
- [ ] File là ảnh (JPG/PNG/GIF)
- [ ] Kích thước file hợp lý (<5MB)

---

**Lưu ý cuối:** 99% trường hợp ảnh không hiển thị là do **quyền chia sẻ chưa đúng**. Hãy kiểm tra kỹ phần này trước!
