# Slide Media Gallery Plugin

Plugin WordPress tạo slide album ảnh với hỗ trợ Google Drive links và nhiều kiểu hiển thị.

## Tính năng

✅ **Quản lý Album dễ dàng**
- Tạo và quản lý nhiều album ảnh
- Hỗ trợ kéo thả để sắp xếp ảnh
- Thêm tiêu đề và mô tả cho mỗi ảnh

✅ **Hỗ trợ Google Drive**
- Nhúng ảnh trực tiếp từ Google Drive
- Tự động chuyển đổi Google Drive links

✅ **4 Kiểu hiển thị**
- **Grid (Lưới)**: Hiển thị ảnh dạng lưới với nhiều tùy chọn số cột
- **Slider**: Trình chiếu slide tự động với mũi tên và dots
- **Masonry**: Lưới không đều (Pinterest style)
- **Thumbnail**: Ảnh lớn ở trên + thumbnails bên dưới

✅ **Tùy chỉnh linh hoạt**
- Điều chỉnh tốc độ chuyển slide
- Bật/tắt tự động chuyển slide
- Chọn số cột hiển thị (2-5 cột)
- Lightbox để xem ảnh phóng to

✅ **Shortcode dễ sử dụng**
- Tự động tạo shortcode cho mỗi album
- Copy shortcode chỉ với 1 click
- Ghi đè cài đặt trực tiếp trong shortcode

## Cài đặt

1. Upload thư mục `Silde Media` vào `/wp-content/plugins/`
2. Kích hoạt plugin trong WordPress Admin
3. Vào **Slide Albums** để bắt đầu tạo album

## Hướng dẫn sử dụng

### 1. Tạo Album mới

1. Vào **Slide Albums → Thêm mới**
2. Nhập tiêu đề cho album
3. Thêm ảnh vào album

### 2. Thêm ảnh

Có 3 cách thêm ảnh:

**Cách 1: Chọn từ thư viện WordPress**
- Click nút **"Chọn từ thư viện"**
- Chọn ảnh từ Media Library (có thể chọn nhiều ảnh cùng lúc)
- Click **"Thêm vào Album"**
- Ảnh sẽ tự động được thêm với URL, tiêu đề và mô tả

**Cách 2: URL ảnh trực tiếp**
- Click nút **"Thêm ảnh thủ công"**
- Nhập URL ảnh:
```
https://example.com/image.jpg
```

**Cách 3: Google Drive link**
- Click nút **"Thêm ảnh thủ công"**
- Nhập Google Drive link:
```
https://drive.google.com/file/d/FILE_ID/view
```

**Lấy Google Drive link:**
1. Upload ảnh lên Google Drive
2. Click phải → **Get link** (hoặc **Share**)
3. **QUAN TRỌNG:** Đổi quyền thành **"Anyone with the link"** và chọn **"Viewer"**
4. Copy link có dạng: `https://drive.google.com/file/d/FILE_ID/view`
5. Paste vào trường URL của plugin

**⚠️ LƯU Ý QUAN TRỌNG VỀ GOOGLE DRIVE:**
- File phải được set quyền là **"Anyone with the link"** (Public)
- Nếu file ở chế độ **"Restricted"**, ảnh sẽ KHÔNG hiển thị
- Kiểm tra lại quyền chia sẻ nếu ảnh không load được
- Plugin tự động chuyển đổi URL sang dạng tối ưu để hiển thị
- Có thể mất vài giây để Google Drive xử lý quyền truy cập mới

### 3. Cấu hình Album

**Kiểu hiển thị:**
- Grid: Lưới đều
- Slider: Trình chiếu tự động
- Masonry: Lưới không đều
- Thumbnail: Ảnh lớn + thumbnails

**Cài đặt khác:**
- Số cột (cho Grid/Masonry): 2-5 cột
- Tốc độ chuyển slide: milliseconds (1000ms = 1 giây)
- Tự động chuyển slide: Bật/Tắt

### 4. Nhúng vào trang/bài viết

**Shortcode cơ bản:**
```
[rchg_album id="123"]
```

**Shortcode với tùy chỉnh:**
```
[rchg_album id="123" layout="slider" speed="5000" columns="4" autoplay="yes"]
```

**Tham số shortcode:**
- `id`: ID của album (bắt buộc)
- `layout`: grid | slider | masonry | thumbnail
- `columns`: 2 | 3 | 4 | 5
- `speed`: Tốc độ chuyển slide (ms)
- `autoplay`: yes | no

## CSS Classes và IDs

Tất cả class và ID bắt đầu với prefix `rchg_`:

### Wrapper Classes
- `.rchg_album_wrapper` - Container chính
- `.rchg_layout_grid` - Grid layout
- `.rchg_layout_slider` - Slider layout
- `.rchg_layout_masonry` - Masonry layout
- `.rchg_layout_thumbnail` - Thumbnail layout

### Grid Layout
- `.rchg_grid_container` - Grid container
- `.rchg_grid_item` - Grid item
- `.rchg_columns_2`, `.rchg_columns_3`, etc. - Số cột

### Slider Layout
- `.rchg_slider_container` - Slider container
- `.rchg_slider_track` - Slider track
- `.rchg_slide` - Mỗi slide
- `.rchg_slider_arrow` - Nút mũi tên
- `.rchg_arrow_prev`, `.rchg_arrow_next` - Mũi tên trái/phải
- `.rchg_slider_dots` - Container dots
- `.rchg_dot` - Mỗi dot

### Masonry Layout
- `.rchg_masonry_container` - Masonry container
- `.rchg_masonry_item` - Masonry item

### Thumbnail Layout
- `.rchg_thumbnail_main` - Ảnh chính
- `.rchg_main_image` - Main image wrapper
- `.rchg_thumbnail_list` - Danh sách thumbnails
- `.rchg_thumbnail_item` - Mỗi thumbnail

### Common Elements
- `.rchg_overlay` - Overlay khi hover
- `.rchg_caption` - Caption container
- `.rchg_title` - Tiêu đề ảnh
- `.rchg_description` - Mô tả ảnh
- `.rchg_active` - Trạng thái active

### Lightbox
- `.rchg_lightbox_overlay` - Lightbox overlay
- `.rchg_lightbox_content` - Lightbox content
- `.rchg_lightbox_image` - Ảnh trong lightbox
- `.rchg_lightbox_close` - Nút đóng
- `.rchg_lightbox_arrow` - Mũi tên lightbox

## Tùy chỉnh CSS

Bạn có thể tùy chỉnh giao diện bằng cách thêm CSS vào theme:

```css
/* Thay đổi màu overlay */
.rchg_overlay {
    background: rgba(0, 100, 200, 0.7) !important;
}

/* Thay đổi kích thước ảnh Grid */
.rchg_grid_item img {
    height: 300px !important;
}

/* Tùy chỉnh nút mũi tên */
.rchg_slider_arrow {
    background: #ff6b6b !important;
    color: white !important;
}
```

## Cài đặt toàn cục

Vào **Slide Albums → Cài đặt** để cấu hình:

- Tốc độ chuyển slide mặc định
- Kiểu hiển thị mặc định
- Bật/tắt tự động chuyển slide
- Hiển thị nút mũi tên
- Hiển thị dots
- Bật/tắt lightbox
- Lazy loading

## Yêu cầu hệ thống

- WordPress 5.0 trở lên
- PHP 7.0 trở lên
- jQuery (có sẵn trong WordPress)

## Support

Nếu bạn gặp vấn đề hoặc có câu hỏi, vui lòng liên hệ qua:
- Email: support@example.com
- Website: https://example.com

## Changelog

### Version 1.0.0
- Phiên bản đầu tiên
- 4 kiểu hiển thị: Grid, Slider, Masonry, Thumbnail
- Hỗ trợ Google Drive links
- Lightbox tích hợp
- Shortcode linh hoạt
- Trang cài đặt

## License

GPL v2 or later

## Credits

Developed by Your Name
