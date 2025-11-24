<?php
/**
 * Quản lý CSS và JavaScript
 */

if (!defined('ABSPATH')) {
    exit;
}

class RCHG_Assets {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_footer', array($this, 'add_admin_scripts'));
    }
    
    public function enqueue_frontend_assets() {
        // CSS
        wp_enqueue_style(
            'rchg-frontend-style',
            RCHG_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            RCHG_VERSION
        );
        
        // JavaScript
        wp_enqueue_script(
            'rchg-frontend-script',
            RCHG_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            RCHG_VERSION,
            true
        );
        
        // Lightbox (Simple Lightbox library)
        wp_enqueue_style(
            'rchg-lightbox-style',
            RCHG_PLUGIN_URL . 'assets/css/lightbox.css',
            array(),
            RCHG_VERSION
        );
        
        wp_enqueue_script(
            'rchg-lightbox-script',
            RCHG_PLUGIN_URL . 'assets/js/lightbox.js',
            array('jquery'),
            RCHG_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('rchg-frontend-script', 'rchgSettings', array(
            'lightboxEnabled' => get_option('rchg_lightbox_enabled', 'yes'),
            'lazyLoad' => get_option('rchg_lazy_load', 'no'),
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        global $post_type;
        
        // Chỉ load trên trang album
        if ('rchg_album' === $post_type || $hook === 'rchg_album_page_rchg-settings') {
            // Enqueue WordPress media library
            wp_enqueue_media();
            
            wp_enqueue_style(
                'rchg-admin-style',
                RCHG_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                RCHG_VERSION
            );
            
            wp_enqueue_script(
                'rchg-admin-script',
                RCHG_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                RCHG_VERSION,
                true
            );
        }
        
        // Load trên tất cả trang admin (cho copy shortcode button)
        if (in_array($hook, array('edit.php'))) {
            wp_enqueue_script(
                'rchg-admin-list',
                RCHG_PLUGIN_URL . 'assets/js/admin-list.js',
                array('jquery'),
                RCHG_VERSION,
                true
            );
        }
    }
    
    public function add_admin_scripts() {
        global $post_type;
        
        if ('rchg_album' !== $post_type) {
            return;
        }
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Template cho image item
            var imageItemTemplate = function(index) {
                return `
                <div class="rchg_image_item" data-index="${index}">
                    <span class="rchg_sortable_handle">
                        <span class="dashicons dashicons-menu"></span> Kéo để sắp xếp
                    </span>
                    
                    <div class="rchg_image_actions">
                        <button type="button" class="button button-small rchg_remove_image" title="Xóa ảnh">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                    
                    <div class="rchg_image_preview">
                        <div class="rchg_image_fields">
                            <input type="text" 
                                   name="rchg_album_images[${index}][url]" 
                                   value="" 
                                   placeholder="URL ảnh hoặc Google Drive link"
                                   class="rchg_image_url">
                            
                            <input type="text" 
                                   name="rchg_album_images[${index}][title]" 
                                   value="" 
                                   placeholder="Tiêu đề ảnh (tùy chọn)">
                            
                            <textarea name="rchg_album_images[${index}][description]" 
                                      rows="2" 
                                      placeholder="Mô tả ảnh (tùy chọn)"></textarea>
                        </div>
                    </div>
                </div>
                `;
            };
            
            // Thêm ảnh mới (thủ công)
            var imageIndex = $('.rchg_image_item').length;
            $('#rchg_add_image_btn').on('click', function() {
                $('#rchg_images_list').append(imageItemTemplate(imageIndex));
                imageIndex++;
            });
            
            // Thêm ảnh từ Media Library
            var mediaFrame;
            $('#rchg_add_media_btn').on('click', function(e) {
                e.preventDefault();
                
                // Nếu media frame đã tồn tại, mở lại
                if (mediaFrame) {
                    mediaFrame.open();
                    return;
                }
                
                // Tạo media frame mới
                mediaFrame = wp.media({
                    title: 'Chọn ảnh cho Album',
                    button: {
                        text: 'Thêm vào Album'
                    },
                    multiple: true  // Cho phép chọn nhiều ảnh
                });
                
                // Khi chọn ảnh xong
                mediaFrame.on('select', function() {
                    var attachments = mediaFrame.state().get('selection').toJSON();
                    var count = attachments.length;
                    
                    // Hiển thị loading
                    var $loadingMsg = $('<div class="notice notice-info" style="margin: 10px 0; padding: 10px;"><p>Đang thêm ' + count + ' ảnh...</p></div>');
                    $('.rchg_add_image_section').before($loadingMsg);
                    
                    // Thêm từng ảnh được chọn
                    attachments.forEach(function(attachment) {
                        // Tạo item mới
                        $('#rchg_images_list').append(imageItemTemplate(imageIndex));
                        
                        var $newItem = $('.rchg_image_item[data-index="' + imageIndex + '"]');
                        
                        // Set URL
                        $newItem.find('.rchg_image_url').val(attachment.url);
                        
                        // Set title từ attachment
                        if (attachment.title) {
                            $newItem.find('input[name*="[title]"]').val(attachment.title);
                        }
                        
                        // Set description từ caption hoặc description
                        if (attachment.caption) {
                            $newItem.find('textarea[name*="[description]"]').val(attachment.caption);
                        } else if (attachment.description) {
                            $newItem.find('textarea[name*="[description]"]').val(attachment.description);
                        }
                        
                        // Add preview image
                        var imgUrl = attachment.sizes && attachment.sizes.thumbnail 
                            ? attachment.sizes.thumbnail.url 
                            : attachment.url;
                        $newItem.find('.rchg_image_preview').prepend('<img src="' + imgUrl + '" alt="">');
                        
                        imageIndex++;
                    });
                    
                    // Xóa loading và hiển thị success message
                    $loadingMsg.remove();
                    var $successMsg = $('<div class="notice notice-success is-dismissible" style="margin: 10px 0; padding: 10px;"><p>✅ Đã thêm ' + count + ' ảnh thành công!</p></div>');
                    $('.rchg_add_image_section').before($successMsg);
                    
                    // Tự động ẩn success message sau 3 giây
                    setTimeout(function() {
                        $successMsg.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 3000);
                });
                
                // Mở media frame
                mediaFrame.open();
            });
            
            // Xóa ảnh
            $(document).on('click', '.rchg_remove_image', function() {
                if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
                    $(this).closest('.rchg_image_item').remove();
                    reindexImages();
                }
            });
            
            // Sortable
            $('#rchg_images_list').sortable({
                handle: '.rchg_sortable_handle',
                update: function(event, ui) {
                    reindexImages();
                }
            });
            
            // Reindex sau khi sort hoặc xóa
            function reindexImages() {
                $('.rchg_image_item').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('input, textarea').each(function() {
                        var name = $(this).attr('name');
                        if (name) {
                            var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', newName);
                        }
                    });
                });
            }
            
            // Preview ảnh khi nhập URL
            $(document).on('blur', '.rchg_image_url', function() {
                var url = $(this).val();
                var $item = $(this).closest('.rchg_image_item');
                var $preview = $item.find('.rchg_image_preview');
                
                if (url) {
                    // Convert Google Drive URL
                    var displayUrl = url;
                    if (url.indexOf('drive.google.com') !== -1) {
                        var matches = url.match(/\/d\/([a-zA-Z0-9_-]+)/);
                        if (!matches) {
                            matches = url.match(/id=([a-zA-Z0-9_-]+)/);
                        }
                        if (matches && matches[1]) {
                            // Sử dụng thumbnail API của Google Drive
                            displayUrl = 'https://drive.google.com/thumbnail?id=' + matches[1] + '&sz=w2000';
                        }
                    }
                    
                    // Remove existing image
                    $preview.find('img').remove();
                    
                    // Add new image with error handling
                    var $img = $('<img>').attr('src', displayUrl);
                    $img.on('error', function() {
                        // Nếu thumbnail API không hoạt động, thử dùng uc?export=download
                        if (displayUrl.indexOf('thumbnail?') !== -1) {
                            var fileId = matches[1];
                            $(this).attr('src', 'https://drive.google.com/uc?export=download&id=' + fileId);
                        } else {
                            // Hiển thị thông báo lỗi
                            $(this).replaceWith('<div style="padding: 20px; background: #f5f5f5; text-align: center;">⚠️ Không thể tải ảnh. Kiểm tra quyền chia sẻ Google Drive.</div>');
                        }
                    });
                    $img.prependTo($preview);
                }
            });
        });
        </script>
        <?php
    }
}
