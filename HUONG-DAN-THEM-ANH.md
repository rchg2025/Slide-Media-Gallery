# Hướng dẫn thêm ảnh vào Album

Plugin Slide Media Gallery hỗ trợ 3 cách thêm ảnh vào album:

## 🖼️ Cách 1: Chọn từ thư viện WordPress (Khuyến nghị)

Đây là cách **dễ nhất và nhanh nhất** để thêm ảnh:

### Bước thực hiện:

1. **Mở album** cần chỉnh sửa
2. **Click nút "Chọn từ thư viện"** (màu xanh, có icon thư viện)
3. **Chọn ảnh** từ Media Library:
   - Click vào ảnh để chọn
   - Giữ **Ctrl** (Windows) hoặc **Cmd** (Mac) để chọn nhiều ảnh
   - Hoặc click **Shift** để chọn một dãy ảnh liên tiếp
4. **Click "Thêm vào Album"**
5. ✅ **Xong!** Ảnh sẽ tự động được thêm với:
   - URL ảnh
   - Tiêu đề (từ Media Library)
   - Mô tả (từ caption hoặc description)
   - Preview thumbnail

### Ưu điểm:

✅ **Nhanh chóng**: Chọn nhiều ảnh cùng lúc  
✅ **Tự động**: Tiêu đề và mô tả được điền tự động  
✅ **Preview ngay**: Thấy ảnh thumbnail ngay lập tức  
✅ **Quản lý tốt**: Tất cả ảnh được quản lý trong WordPress  
✅ **Tối ưu**: Ảnh đã được WordPress xử lý và tối ưu  

### Lưu ý:

- Ảnh phải được upload vào Media Library trước
- Có thể upload ảnh mới ngay trong popup chọn ảnh
- Định dạng hỗ trợ: JPG, PNG, GIF, WebP

---

## ✏️ Cách 2: Thêm ảnh thủ công (URL trực tiếp)

Dùng khi bạn muốn nhúng ảnh từ URL bên ngoài:

### Bước thực hiện:

1. **Click nút "Thêm ảnh thủ công"**
2. **Nhập URL ảnh** vào trường "URL ảnh hoặc Google Drive link"
3. **Nhập tiêu đề và mô tả** (tùy chọn)
4. **Click ra ngoài** để xem preview
5. **Lưu album**

### Ví dụ URL hợp lệ:

```
https://example.com/images/photo.jpg
https://cdn.example.com/image.png
https://i.imgur.com/abc123.jpg
```

### Ưu điểm:

✅ **Linh hoạt**: Dùng ảnh từ bất kỳ nguồn nào  
✅ **Không tốn dung lượng**: Ảnh không lưu trên server  

### Nhược điểm:

❌ Link hỏng nếu nguồn xóa ảnh  
❌ Phụ thuộc vào tốc độ server nguồn  

---

## 🌐 Cách 3: Google Drive link

Dùng khi bạn muốn lưu ảnh trên Google Drive:

### Bước thực hiện:

1. **Upload ảnh lên Google Drive**
2. **Click phải → Share (Chia sẻ)**
3. **⚠️ QUAN TRỌNG:** Đổi quyền thành:
   - General access: **"Anyone with the link"** (Bất kỳ ai có link)
   - Role: **"Viewer"** (Người xem)
4. **Copy link** (dạng: `https://drive.google.com/file/d/FILE_ID/view`)
5. **Click "Thêm ảnh thủ công"** trong album
6. **Paste link** vào trường URL
7. **Click ra ngoài** để xem preview
8. **Lưu album**

### Ví dụ Google Drive link:

```
https://drive.google.com/file/d/1VavlqpsHKVzPf2l5DRRLSqlTqPxL5ndX/view
```

### Ưu điểm:

✅ **Không giới hạn dung lượng** (nếu có Google Drive unlimited)  
✅ **Dễ quản lý**: Quản lý tập trung trên Google Drive  
✅ **Tự động chuyển đổi**: Plugin tự convert sang URL tối ưu  

### Nhược điểm:

❌ **Phải public**: File phải được share công khai  
❌ **Tốc độ**: Có thể load chậm hơn ảnh local  
❌ **Quyền truy cập**: Dễ bị lỗi nếu quên set quyền  

---

## 🎯 So sánh các phương pháp

| Tính năng | Thư viện WP | URL trực tiếp | Google Drive |
|-----------|-------------|---------------|--------------|
| **Tốc độ** | ⚡⚡⚡ Nhanh nhất | ⚡⚡ Trung bình | ⚡ Chậm |
| **Độ tin cậy** | ✅✅✅ Cao nhất | ⚠️ Phụ thuộc nguồn | ⚠️ Phải public |
| **Dung lượng** | 💾 Tốn hosting | 💾 Không tốn | 💾 Không tốn |
| **Quản lý** | ✅ Dễ nhất | ⚠️ Khó | ✅ Dễ |
| **SEO** | ✅ Tốt | ✅ Tốt | ⚠️ Trung bình |
| **Chọn nhiều ảnh** | ✅ Có | ❌ Không | ❌ Không |

---

## 💡 Khuyến nghị

### Dùng **Thư viện WordPress** khi:
- ✅ Bạn có hosting đủ dung lượng
- ✅ Muốn tốc độ tải nhanh nhất
- ✅ Cần thêm nhiều ảnh nhanh chóng
- ✅ Muốn quản lý ảnh tập trung trong WordPress

### Dùng **URL trực tiếp** khi:
- ✅ Ảnh đã có trên CDN/server khác
- ✅ Muốn tiết kiệm dung lượng hosting
- ✅ Nguồn ảnh đáng tin cậy

### Dùng **Google Drive** khi:
- ✅ Có Google Drive unlimited/dung lượng lớn
- ✅ Hosting bị giới hạn dung lượng
- ✅ Muốn quản lý ảnh trên cloud
- ⚠️ Chấp nhận tốc độ load chậm hơn

---

## 🔧 Các tính năng khác

### Sắp xếp lại ảnh
- **Kéo thả** icon "☰" để thay đổi thứ tự
- Thứ tự sẽ được giữ nguyên khi hiển thị

### Chỉnh sửa thông tin ảnh
- **Tiêu đề**: Hiển thị trên caption
- **Mô tả**: Hiển thị dưới tiêu đề (tùy layout)
- **URL**: Có thể thay đổi bất kỳ lúc nào

### Xóa ảnh
- Click nút **Trash** (thùng rác) ở góc phải
- Xác nhận xóa
- Ảnh sẽ bị xóa khỏi album (không xóa khỏi Media Library)

### Preview trước khi lưu
- Ảnh từ thư viện: Preview ngay lập tức
- URL thủ công: Preview sau khi blur khỏi trường nhập
- Google Drive: Preview sau vài giây (cần convert URL)

---

## ❓ Troubleshooting

### ❌ Không thấy preview ảnh

**Nguyên nhân:**
- URL sai hoặc không truy cập được
- Google Drive chưa public
- Định dạng file không hỗ trợ

**Giải pháp:**
1. Kiểm tra URL trong trình duyệt ẩn danh
2. Đổi quyền Google Drive sang public
3. Đảm bảo file là ảnh (JPG, PNG, GIF, WebP)

### ❌ Nút "Chọn từ thư viện" không hoạt động

**Nguyên nhân:**
- JavaScript bị lỗi
- Conflict với plugin khác

**Giải pháp:**
1. Clear cache trình duyệt
2. Tắt các plugin khác để test
3. Check Console (F12) xem có lỗi không

### ❌ Ảnh load chậm

**Nguyên nhân:**
- Dùng Google Drive hoặc URL ngoài
- File ảnh quá lớn

**Giải pháp:**
1. Dùng thư viện WordPress thay vì Google Drive
2. Nén ảnh trước khi upload
3. Dùng CDN nếu có thể

---

## 📞 Cần hỗ trợ?

Nếu gặp vấn đề, hãy cung cấp:
- Screenshot màn hình
- URL ảnh đang dùng (nếu có)
- Thông báo lỗi trong Console (F12)
- Phương pháp thêm ảnh đang dùng

---

**Tip cuối:** Nên dùng **Thư viện WordPress** cho trải nghiệm tốt nhất! 🎉
