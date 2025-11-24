# Slide Media Gallery

Plugin WordPress tạo slide album ảnh với hỗ trợ Google Drive links và nhiều kiểu hiển thị.

## Tính năng

### Quản lý Album dễ dàng
- ✅ Tạo và quản lý nhiều album ảnh
- ✅ Hỗ trợ kéo thả để sắp xếp ảnh
- ✅ Thêm tiêu đề và mô tả cho mỗi ảnh

### Các tính năng khác
- Hỗ trợ ảnh từ WordPress Media Library
- Hỗ trợ Google Drive links
- Nhiều kiểu hiển thị: Grid, Slider, Masonry
- Responsive design
- Lightbox tích hợp sẵn
- Shortcode đơn giản để nhúng album

## Cài đặt

1. Tải plugin và giải nén vào thư mục `/wp-content/plugins/slide-media-gallery`
2. Kích hoạt plugin trong WordPress Admin
3. Truy cập "Media Albums" để tạo album mới

## Sử dụng

### Tạo Album

1. Vào "Media Albums" > "Add New"
2. Nhập tiêu đề và mô tả cho album
3. Trong phần "Album Media":
   - Click "Add Media" để chọn ảnh từ Media Library
   - Hoặc nhập Google Drive URL trực tiếp
4. Kéo thả các ảnh để sắp xếp thứ tự
5. Thêm tiêu đề và mô tả cho từng ảnh
6. Click "Publish" để lưu album

### Hiển thị Album

Sử dụng shortcode để hiển thị album:

```
[smg_album id="123"]
```

#### Tùy chọn shortcode:

```
[smg_album id="123" layout="grid" columns="3"]
```

**Tham số:**
- `id` - ID của album (bắt buộc)
- `layout` - Kiểu hiển thị: `grid`, `slider`, `masonry` (mặc định: `grid`)
- `columns` - Số cột (1-6, mặc định: `3`)

### Ví dụ

Grid 3 cột:
```
[smg_album id="123" layout="grid" columns="3"]
```

Slider:
```
[smg_album id="123" layout="slider"]
```

Masonry:
```
[smg_album id="123" layout="masonry"]
```

## Hỗ trợ Google Drive

Để sử dụng ảnh từ Google Drive:

1. Chia sẻ file/folder với quyền "Anyone with the link can view"
2. Copy link chia sẻ
3. Paste vào trường "Google Drive URL" trong Album Media
4. Plugin sẽ tự động hiển thị ảnh

## Yêu cầu

- WordPress 5.0 trở lên
- PHP 7.0 trở lên

## License

GPL v2 or later
