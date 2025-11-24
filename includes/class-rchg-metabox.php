<?php
/**
 * Metabox cho Album
 */

if (!defined('ABSPATH')) {
    exit;
}

class RCHG_Metabox {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_metaboxes'));
        add_action('save_post_rchg_album', array($this, 'save_metabox'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_metaboxes() {
        add_meta_box(
            'rchg_album_images',
            __('Quản lý ảnh Album', 'slide-media-gallery'),
            array($this, 'render_images_metabox'),
            'rchg_album',
            'normal',
            'high'
        );
        
        add_meta_box(
            'rchg_album_settings',
            __('Cài đặt Album', 'slide-media-gallery'),
            array($this, 'render_settings_metabox'),
            'rchg_album',
            'side',
            'default'
        );
        
        add_meta_box(
            'rchg_album_shortcode',
            __('Shortcode', 'slide-media-gallery'),
            array($this, 'render_shortcode_metabox'),
            'rchg_album',
            'side',
            'default'
        );
    }
    
    public function render_images_metabox($post) {
        wp_nonce_field('rchg_album_nonce', 'rchg_album_nonce_field');
        
        $images = get_post_meta($post->ID, '_rchg_album_images', true);
        if (empty($images)) {
            $images = array();
        }
        ?>
        <div class="rchg_album_images_container">
            <div class="rchg_images_list" id="rchg_images_list">
                <?php
                if (!empty($images)) {
                    foreach ($images as $index => $image) {
                        $this->render_image_item($index, $image);
                    }
                }
                ?>
            </div>
            
            <div class="rchg_add_image_section">
                <div style="margin-bottom: 15px;">
                    <button type="button" class="button button-primary button-large" id="rchg_add_media_btn" style="margin-right: 10px;">
                        <span class="dashicons dashicons-admin-media"></span> Chọn từ thư viện
                    </button>
                    <button type="button" class="button button-secondary button-large" id="rchg_add_image_btn">
                        <span class="dashicons dashicons-plus-alt"></span> Thêm ảnh thủ công
                    </button>
                </div>
                <p class="description" style="text-align: left; max-width: 600px; margin: 0 auto;">
                    <strong>📚 Chọn từ thư viện:</strong> Chọn ảnh từ Media Library của WordPress (có thể chọn nhiều ảnh cùng lúc).<br>
                    <strong>✏️ Thêm ảnh thủ công:</strong> Nhập link Google Drive hoặc URL ảnh trực tiếp.<br>
                    <strong>💡 Mẹo:</strong> Có thể kéo thả để sắp xếp lại thứ tự ảnh sau khi thêm.<br>
                    <em>Định dạng Google Drive: https://drive.google.com/file/d/FILE_ID/view</em>
                </p>
            </div>
        </div>
        
        <style>
            .rchg_album_images_container {
                padding: 10px 0;
            }
            .rchg_images_list {
                margin-bottom: 20px;
            }
            .rchg_image_item {
                background: #f9f9f9;
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 10px;
                border-radius: 4px;
                position: relative;
            }
            .rchg_image_item:hover {
                background: #f0f0f0;
            }
            .rchg_image_preview {
                display: flex;
                gap: 15px;
                align-items: flex-start;
            }
            .rchg_image_preview img {
                max-width: 150px;
                max-height: 150px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .rchg_image_fields {
                flex: 1;
            }
            .rchg_image_fields input[type="text"],
            .rchg_image_fields textarea {
                width: 100%;
                margin-bottom: 10px;
            }
            .rchg_image_actions {
                position: absolute;
                top: 10px;
                right: 10px;
            }
            .rchg_image_actions button {
                margin-left: 5px;
            }
            .rchg_add_image_section {
                padding: 20px;
                background: #f9f9f9;
                border: 2px dashed #ddd;
                text-align: center;
                border-radius: 4px;
            }
            .rchg_add_image_section button {
                margin: 0 5px 10px 5px;
            }
            .rchg_sortable_handle {
                cursor: move;
                display: inline-block;
                padding: 5px 10px;
                background: #ddd;
                border-radius: 3px;
                margin-bottom: 10px;
            }
        </style>
        <?php
    }
    
    private function render_image_item($index, $image) {
        $url = isset($image['url']) ? $image['url'] : '';
        $title = isset($image['title']) ? $image['title'] : '';
        $description = isset($image['description']) ? $image['description'] : '';
        $display_url = $this->convert_google_drive_url($url);
        ?>
        <div class="rchg_image_item" data-index="<?php echo $index; ?>">
            <span class="rchg_sortable_handle">
                <span class="dashicons dashicons-menu"></span> Kéo để sắp xếp
            </span>
            
            <div class="rchg_image_actions">
                <button type="button" class="button button-small rchg_remove_image" title="Xóa ảnh">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
            
            <div class="rchg_image_preview">
                <?php if (!empty($display_url)): ?>
                    <img src="<?php echo esc_url($display_url); ?>" alt="">
                <?php endif; ?>
                
                <div class="rchg_image_fields">
                    <input type="text" 
                           name="rchg_album_images[<?php echo $index; ?>][url]" 
                           value="<?php echo esc_attr($url); ?>" 
                           placeholder="URL ảnh hoặc Google Drive link"
                           class="rchg_image_url">
                    
                    <input type="text" 
                           name="rchg_album_images[<?php echo $index; ?>][title]" 
                           value="<?php echo esc_attr($title); ?>" 
                           placeholder="Tiêu đề ảnh (tùy chọn)">
                    
                    <textarea name="rchg_album_images[<?php echo $index; ?>][description]" 
                              rows="2" 
                              placeholder="Mô tả ảnh (tùy chọn)"><?php echo esc_textarea($description); ?></textarea>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_settings_metabox($post) {
        $layout = get_post_meta($post->ID, '_rchg_album_layout', true);
        $slide_speed = get_post_meta($post->ID, '_rchg_album_slide_speed', true);
        $autoplay = get_post_meta($post->ID, '_rchg_album_autoplay', true);
        $columns = get_post_meta($post->ID, '_rchg_album_columns', true);
        
        if (empty($layout)) $layout = 'grid';
        if (empty($slide_speed)) $slide_speed = get_option('rchg_slide_speed', 3000);
        if (empty($autoplay)) $autoplay = 'yes';
        if (empty($columns)) $columns = '3';
        ?>
        <div class="rchg_settings_fields">
            <p>
                <label><strong>Kiểu hiển thị:</strong></label><br>
                <select name="rchg_album_layout" style="width: 100%;">
                    <option value="grid" <?php selected($layout, 'grid'); ?>>Lưới (Grid)</option>
                    <option value="slider" <?php selected($layout, 'slider'); ?>>Slider</option>
                    <option value="masonry" <?php selected($layout, 'masonry'); ?>>Masonry</option>
                    <option value="thumbnail" <?php selected($layout, 'thumbnail'); ?>>Ảnh lớn + Thumbnail</option>
                </select>
            </p>
            
            <p>
                <label><strong>Số cột (Grid/Masonry):</strong></label><br>
                <select name="rchg_album_columns" style="width: 100%;">
                    <option value="2" <?php selected($columns, '2'); ?>>2 cột</option>
                    <option value="3" <?php selected($columns, '3'); ?>>3 cột</option>
                    <option value="4" <?php selected($columns, '4'); ?>>4 cột</option>
                    <option value="5" <?php selected($columns, '5'); ?>>5 cột</option>
                </select>
            </p>
            
            <p>
                <label><strong>Tốc độ chuyển slide (ms):</strong></label><br>
                <input type="number" 
                       name="rchg_album_slide_speed" 
                       value="<?php echo esc_attr($slide_speed); ?>" 
                       min="500" 
                       step="100"
                       style="width: 100%;">
                <small>1000ms = 1 giây</small>
            </p>
            
            <p>
                <label>
                    <input type="checkbox" 
                           name="rchg_album_autoplay" 
                           value="yes" 
                           <?php checked($autoplay, 'yes'); ?>>
                    <strong>Tự động chuyển slide</strong>
                </label>
            </p>
            
            <p class="description" style="margin-top: 15px; padding: 10px; background: #f0f8ff; border-left: 3px solid #0073aa;">
                <strong>💡 Lưu ý:</strong> Slider sẽ tự động điều chỉnh chiều cao theo kích thước ảnh để hiển thị toàn bộ nội dung (không cắt ảnh).
            </p>
        </div>
        <?php
    }
    
    public function render_shortcode_metabox($post) {
        if ($post->post_status === 'publish' || $post->post_status === 'draft') {
            $shortcode = '[rchg_album id="' . $post->ID . '"]';
            ?>
            <div class="rchg_shortcode_display">
                <p><strong>Sao chép mã shortcode này:</strong></p>
                <input type="text" 
                       value="<?php echo esc_attr($shortcode); ?>" 
                       readonly 
                       style="width: 100%;" 
                       onclick="this.select();">
                <button type="button" 
                        class="button button-secondary" 
                        onclick="navigator.clipboard.writeText('<?php echo esc_js($shortcode); ?>'); alert('Đã sao chép!');"
                        style="width: 100%; margin-top: 10px;">
                    Copy Shortcode
                </button>
                <p class="description">
                    Dán shortcode này vào bài viết hoặc trang để hiển thị album.
                </p>
            </div>
            <?php
        } else {
            echo '<p>Lưu album để tạo shortcode.</p>';
        }
    }
    
    public function save_metabox($post_id, $post) {
        // Kiểm tra nonce
        if (!isset($_POST['rchg_album_nonce_field']) || !wp_verify_nonce($_POST['rchg_album_nonce_field'], 'rchg_album_nonce')) {
            return;
        }
        
        // Kiểm tra autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Kiểm tra quyền
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Lưu ảnh
        if (isset($_POST['rchg_album_images'])) {
            $images = array_values($_POST['rchg_album_images']); // Reset index
            update_post_meta($post_id, '_rchg_album_images', $images);
        } else {
            delete_post_meta($post_id, '_rchg_album_images');
        }
        
        // Lưu cài đặt
        if (isset($_POST['rchg_album_layout'])) {
            update_post_meta($post_id, '_rchg_album_layout', sanitize_text_field($_POST['rchg_album_layout']));
        }
        
        if (isset($_POST['rchg_album_slide_speed'])) {
            update_post_meta($post_id, '_rchg_album_slide_speed', absint($_POST['rchg_album_slide_speed']));
        }
        
        if (isset($_POST['rchg_album_columns'])) {
            update_post_meta($post_id, '_rchg_album_columns', sanitize_text_field($_POST['rchg_album_columns']));
        }
        
        $autoplay = isset($_POST['rchg_album_autoplay']) ? 'yes' : 'no';
        update_post_meta($post_id, '_rchg_album_autoplay', $autoplay);
    }
    
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ('rchg_album' !== $post_type) {
            return;
        }
        
        wp_enqueue_script('jquery-ui-sortable');
    }
    
    private function convert_google_drive_url($url) {
        // Chuyển đổi Google Drive URL sang dạng có thể hiển thị trực tiếp
        if (strpos($url, 'drive.google.com') !== false) {
            // Lấy file ID từ URL
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches);
            if (!isset($matches[1])) {
                preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches);
            }
            
            if (isset($matches[1])) {
                $file_id = $matches[1];
                // Sử dụng thumbnail API của Google Drive cho preview tốt hơn
                // hoặc dùng uc?export=download cho file gốc
                return 'https://drive.google.com/thumbnail?id=' . $file_id . '&sz=w2000';
                // Alternative: return 'https://drive.google.com/uc?export=download&id=' . $file_id;
            }
        }
        return $url;
    }
}
