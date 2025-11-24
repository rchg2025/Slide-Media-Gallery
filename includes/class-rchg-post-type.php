<?php
/**
 * Đăng ký Custom Post Type cho Album
 */

if (!defined('ABSPATH')) {
    exit;
}

class RCHG_Post_Type {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('manage_rchg_album_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_rchg_album_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Albums', 'Post Type General Name', 'slide-media-gallery'),
            'singular_name'         => _x('Album', 'Post Type Singular Name', 'slide-media-gallery'),
            'menu_name'             => __('Slide Albums', 'slide-media-gallery'),
            'name_admin_bar'        => __('Album', 'slide-media-gallery'),
            'archives'              => __('Album Archives', 'slide-media-gallery'),
            'attributes'            => __('Album Attributes', 'slide-media-gallery'),
            'parent_item_colon'     => __('Parent Album:', 'slide-media-gallery'),
            'all_items'             => __('Tất cả Albums', 'slide-media-gallery'),
            'add_new_item'          => __('Thêm Album mới', 'slide-media-gallery'),
            'add_new'               => __('Thêm mới', 'slide-media-gallery'),
            'new_item'              => __('Album mới', 'slide-media-gallery'),
            'edit_item'             => __('Chỉnh sửa Album', 'slide-media-gallery'),
            'update_item'           => __('Cập nhật Album', 'slide-media-gallery'),
            'view_item'             => __('Xem Album', 'slide-media-gallery'),
            'view_items'            => __('Xem Albums', 'slide-media-gallery'),
            'search_items'          => __('Tìm kiếm Album', 'slide-media-gallery'),
        );
        
        $args = array(
            'label'                 => __('Album', 'slide-media-gallery'),
            'description'           => __('Slide Media Gallery Albums', 'slide-media-gallery'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'menu_icon'             => 'dashicons-images-alt2',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        
        register_post_type('rchg_album', $args);
    }
    
    public function set_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['rchg_shortcode'] = __('Shortcode', 'slide-media-gallery');
        $new_columns['rchg_images_count'] = __('Số ảnh', 'slide-media-gallery');
        $new_columns['rchg_layout'] = __('Kiểu hiển thị', 'slide-media-gallery');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }
    
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'rchg_shortcode':
                echo '<code>[rchg_album id="' . $post_id . '"]</code>';
                echo '<button class="button button-small rchg_copy_shortcode" data-shortcode="[rchg_album id=&quot;' . $post_id . '&quot;]" style="margin-left: 10px;">Copy</button>';
                break;
                
            case 'rchg_images_count':
                $images = get_post_meta($post_id, '_rchg_album_images', true);
                $count = !empty($images) ? count($images) : 0;
                echo $count . ' ảnh';
                break;
                
            case 'rchg_layout':
                $layout = get_post_meta($post_id, '_rchg_album_layout', true);
                if (empty($layout)) {
                    $layout = 'grid';
                }
                $layouts = array(
                    'grid' => 'Lưới',
                    'slider' => 'Slider',
                    'masonry' => 'Masonry',
                    'thumbnail' => 'Ảnh lớn + Thumbnail'
                );
                echo isset($layouts[$layout]) ? $layouts[$layout] : $layout;
                break;
        }
    }
}
