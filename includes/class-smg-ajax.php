<?php
/**
 * AJAX handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

class SMG_Ajax {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Add AJAX actions if needed in the future
    }
}
