<?php
/**
 * Plugin Name: Slide Media Gallery
 * Plugin URI: https://github.com/rchg2025/Slide-Media-Gallery
 * Description: Create beautiful image gallery albums with drag-and-drop ordering, Google Drive support, and multiple display layouts. Manage albums easily with photo titles and descriptions.
 * Version: 1.0.0
 * Author: rchg2025
 * Text Domain: slide-media-gallery
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SMG_VERSION', '1.0.0');
define('SMG_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMG_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SMG_PLUGIN_FILE', __FILE__);

// Include required files
require_once SMG_PLUGIN_DIR . 'includes/class-smg-post-types.php';
require_once SMG_PLUGIN_DIR . 'includes/class-smg-admin.php';
require_once SMG_PLUGIN_DIR . 'includes/class-smg-shortcodes.php';
require_once SMG_PLUGIN_DIR . 'includes/class-smg-ajax.php';

/**
 * Main plugin class
 */
class Slide_Media_Gallery {
    
    /**
     * Single instance
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('slide-media-gallery', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize components
        SMG_Post_Types::get_instance();
        SMG_Admin::get_instance();
        SMG_Shortcodes::get_instance();
        SMG_Ajax::get_instance();
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Register post types
        SMG_Post_Types::get_instance()->register_post_types();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize plugin
Slide_Media_Gallery::get_instance();
