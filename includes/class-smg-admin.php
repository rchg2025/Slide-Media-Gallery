<?php
/**
 * Admin functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class SMG_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        // Only load on album edit screen
        if (('post.php' === $hook || 'post-new.php' === $hook) && 'smg_album' === $post_type) {
            // Enqueue WordPress media uploader
            wp_enqueue_media();
            
            // Enqueue jQuery UI sortable
            wp_enqueue_script('jquery-ui-sortable');
            
            // Enqueue admin CSS
            wp_enqueue_style(
                'smg-admin-css',
                SMG_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                SMG_VERSION
            );
            
            // Enqueue admin JS
            wp_enqueue_script(
                'smg-admin-js',
                SMG_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                SMG_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('smg-admin-js', 'smgAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('smg_ajax_nonce'),
                'strings' => array(
                    'selectMedia' => __('Select or Upload Media', 'slide-media-gallery'),
                    'useMedia' => __('Use this media', 'slide-media-gallery'),
                    'confirmRemove' => __('Are you sure you want to remove this item?', 'slide-media-gallery'),
                ),
            ));
        }
    }
}
