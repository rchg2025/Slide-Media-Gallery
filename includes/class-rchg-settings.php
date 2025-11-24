<?php
/**
 * Trang cài đặt plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class RCHG_Settings {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=rchg_album',
            __('Cài đặt Slide Media Gallery', 'slide-media-gallery'),
            __('Cài đặt', 'slide-media-gallery'),
            'manage_options',
            'rchg-settings',
            array($this, 'render_settings_page')
        );
    }
    
    public function register_settings() {
        register_setting('rchg_settings_group', 'rchg_slide_speed');
        register_setting('rchg_settings_group', 'rchg_default_layout');
        register_setting('rchg_settings_group', 'rchg_autoplay');
        register_setting('rchg_settings_group', 'rchg_show_arrows');
        register_setting('rchg_settings_group', 'rchg_show_dots');
        register_setting('rchg_settings_group', 'rchg_lightbox_enabled');
        register_setting('rchg_settings_group', 'rchg_lazy_load');
    }
    
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Lưu cài đặt
        if (isset($_POST['rchg_save_settings'])) {
            check_admin_referer('rchg_settings_nonce');
            
            update_option('rchg_slide_speed', absint($_POST['rchg_slide_speed']));
            update_option('rchg_default_layout', sanitize_text_field($_POST['rchg_default_layout']));
            update_option('rchg_autoplay', isset($_POST['rchg_autoplay']) ? 'yes' : 'no');
            update_option('rchg_show_arrows', isset($_POST['rchg_show_arrows']) ? 'yes' : 'no');
            update_option('rchg_show_dots', isset($_POST['rchg_show_dots']) ? 'yes' : 'no');
            update_option('rchg_lightbox_enabled', isset($_POST['rchg_lightbox_enabled']) ? 'yes' : 'no');
            update_option('rchg_lazy_load', isset($_POST['rchg_lazy_load']) ? 'yes' : 'no');
            
            echo '<div class="notice notice-success is-dismissible"><p>Đã lưu cài đặt!</p></div>';
        }
        
        $slide_speed = get_option('rchg_slide_speed', 3000);
        $default_layout = get_option('rchg_default_layout', 'grid');
        $autoplay = get_option('rchg_autoplay', 'yes');
        $show_arrows = get_option('rchg_show_arrows', 'yes');
        $show_dots = get_option('rchg_show_dots', 'yes');
        $lightbox_enabled = get_option('rchg_lightbox_enabled', 'yes');
        $lazy_load = get_option('rchg_lazy_load', 'no');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('rchg_settings_nonce'); ?>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="rchg_slide_speed">Tốc độ chuyển slide mặc định</label>
                            </th>
                            <td>
                                <input type="number" 
                                       id="rchg_slide_speed" 
                                       name="rchg_slide_speed" 
                                       value="<?php echo esc_attr($slide_speed); ?>" 
                                       min="500" 
                                       step="100" 
                                       class="regular-text">
                                <p class="description">Đơn vị: milliseconds (1000ms = 1 giây)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="rchg_default_layout">Kiểu hiển thị mặc định</label>
                            </th>
                            <td>
                                <select id="rchg_default_layout" name="rchg_default_layout" class="regular-text">
                                    <option value="grid" <?php selected($default_layout, 'grid'); ?>>Lưới (Grid)</option>
                                    <option value="slider" <?php selected($default_layout, 'slider'); ?>>Slider</option>
                                    <option value="masonry" <?php selected($default_layout, 'masonry'); ?>>Masonry</option>
                                    <option value="thumbnail" <?php selected($default_layout, 'thumbnail'); ?>>Ảnh lớn + Thumbnail</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Tùy chọn slider</th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" 
                                               name="rchg_autoplay" 
                                               value="yes" 
                                               <?php checked($autoplay, 'yes'); ?>>
                                        Tự động chuyển slide
                                    </label>
                                    <br><br>
                                    <label>
                                        <input type="checkbox" 
                                               name="rchg_show_arrows" 
                                               value="yes" 
                                               <?php checked($show_arrows, 'yes'); ?>>
                                        Hiển thị nút mũi tên
                                    </label>
                                    <br><br>
                                    <label>
                                        <input type="checkbox" 
                                               name="rchg_show_dots" 
                                               value="yes" 
                                               <?php checked($show_dots, 'yes'); ?>>
                                        Hiển thị dấu chấm điều hướng
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Lightbox</th>
                            <td>
                                <label>
                                    <input type="checkbox" 
                                           name="rchg_lightbox_enabled" 
                                           value="yes" 
                                           <?php checked($lightbox_enabled, 'yes'); ?>>
                                    Bật lightbox khi click vào ảnh
                                </label>
                                <p class="description">Cho phép xem ảnh phóng to trong popup</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Tối ưu</th>
                            <td>
                                <label>
                                    <input type="checkbox" 
                                           name="rchg_lazy_load" 
                                           value="yes" 
                                           <?php checked($lazy_load, 'yes'); ?>>
                                    Bật lazy loading cho ảnh
                                </label>
                                <p class="description">Tải ảnh khi cần thiết để tăng tốc độ tải trang</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <p class="submit">
                    <input type="submit" 
                           name="rchg_save_settings" 
                           id="submit" 
                           class="button button-primary" 
                           value="Lưu cài đặt">
                </p>
            </form>
            
            <hr>
            
            <h2>Hướng dẫn sử dụng</h2>
            <div class="rchg_instructions">
                <h3>1. Tạo Album mới</h3>
                <p>Vào <strong>Slide Albums → Thêm mới</strong> để tạo album.</p>
                
                <h3>2. Thêm ảnh vào Album</h3>
                <p>Trong trang chỉnh sửa album, bạn có thể:</p>
                <ul>
                    <li>Nhập URL ảnh trực tiếp</li>
                    <li>Nhập link Google Drive (format: https://drive.google.com/file/d/FILE_ID/view)</li>
                    <li>Kéo thả để sắp xếp thứ tự ảnh</li>
                </ul>
                
                <h3>3. Cấu hình hiển thị</h3>
                <p>Chọn kiểu hiển thị cho album:</p>
                <ul>
                    <li><strong>Grid:</strong> Hiển thị dạng lưới</li>
                    <li><strong>Slider:</strong> Trình chiếu slide tự động</li>
                    <li><strong>Masonry:</strong> Lưới không đều (Pinterest style)</li>
                    <li><strong>Thumbnail:</strong> Ảnh lớn ở trên, thumbnails ở dưới</li>
                </ul>
                
                <h3>4. Nhúng vào trang/bài viết</h3>
                <p>Sao chép shortcode và dán vào nội dung:</p>
                <code>[rchg_album id="123"]</code>
                
                <h3>5. Tùy chỉnh shortcode</h3>
                <p>Bạn có thể ghi đè cài đặt:</p>
                <code>[rchg_album id="123" layout="slider" speed="5000" autoplay="yes"]</code>
                
                <h3>Google Drive Links</h3>
                <p>Để lấy link Google Drive:</p>
                <ol>
                    <li>Upload ảnh lên Google Drive</li>
                    <li>Click phải → Get link</li>
                    <li>Đổi quyền thành "Anyone with the link"</li>
                    <li>Copy link có dạng: https://drive.google.com/file/d/FILE_ID/view</li>
                    <li>Paste vào trường URL của plugin</li>
                </ol>
            </div>
            
            <style>
                .rchg_instructions {
                    background: #f9f9f9;
                    padding: 20px;
                    border-left: 4px solid #0073aa;
                    margin-top: 20px;
                }
                .rchg_instructions h3 {
                    margin-top: 20px;
                    margin-bottom: 10px;
                }
                .rchg_instructions ul,
                .rchg_instructions ol {
                    margin-left: 20px;
                }
                .rchg_instructions code {
                    background: #fff;
                    padding: 5px 10px;
                    border: 1px solid #ddd;
                    display: inline-block;
                    margin: 5px 0;
                }
            </style>
        </div>
        <?php
    }
}
