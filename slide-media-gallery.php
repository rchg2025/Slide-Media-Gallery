<?php
/**
 * Plugin Name: Slide Media Gallery
 * Plugin URI: https://rongcon.net/slide-media-gallery
 * Description: Tạo slide album ảnh với hỗ trợ Google Drive links và nhiều kiểu hiển thị
 * Version: 1.0.0
 * Author: Rồng Con HG & Gemini Development
 * Author URI: https://rongcon.net
 * License: GPL v2 or later
 * Text Domain: slide-media-gallery
 */

// Ngăn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa các hằng số
define('RCHG_VERSION', '1.0.0');
define('RCHG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RCHG_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RCHG_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include các file cần thiết
require_once RCHG_PLUGIN_DIR . 'includes/helper-functions.php';
require_once RCHG_PLUGIN_DIR . 'includes/class-rchg-post-type.php';
require_once RCHG_PLUGIN_DIR . 'includes/class-rchg-metabox.php';
require_once RCHG_PLUGIN_DIR . 'includes/class-rchg-shortcode.php';
require_once RCHG_PLUGIN_DIR . 'includes/class-rchg-settings.php';
require_once RCHG_PLUGIN_DIR . 'includes/class-rchg-assets.php';

// Khởi tạo plugin
class RCHG_Slide_Media_Gallery {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Hook khi kích hoạt plugin
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Hook khi hủy kích hoạt plugin
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Khởi tạo các class
        add_action('plugins_loaded', array($this, 'init_classes'));
        
        // Load text domain
        add_action('init', array($this, 'load_textdomain'));
    }
    
    public function activate() {
        // Tạo các option mặc định
        add_option('rchg_slide_speed', 3000);
        add_option('rchg_default_layout', 'grid');
        add_option('rchg_autoplay', 'yes');
        add_option('rchg_show_arrows', 'yes');
        add_option('rchg_show_dots', 'yes');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function init_classes() {
        RCHG_Post_Type::get_instance();
        RCHG_Metabox::get_instance();
        RCHG_Shortcode::get_instance();
        RCHG_Settings::get_instance();
        RCHG_Assets::get_instance();
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('slide-media-gallery', false, dirname(RCHG_PLUGIN_BASENAME) . '/languages');
    }
}

// Khởi động plugin
function rchg_slide_media_gallery() {
    return RCHG_Slide_Media_Gallery::get_instance();
}

// Khởi chạy
rchg_slide_media_gallery();
