<?php
/**
 * Shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

class SMG_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('smg_album', array($this, 'album_shortcode'));
        // Scripts will be enqueued conditionally when shortcode is used
    }
    
    /**
     * Enqueue frontend scripts (called when shortcode is rendered)
     */
    private function enqueue_frontend_assets() {
        static $enqueued = false;
        
        if ($enqueued) {
            return;
        }
        
        wp_enqueue_style(
            'smg-frontend-css',
            SMG_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            SMG_VERSION
        );
        
        wp_enqueue_script(
            'smg-frontend-js',
            SMG_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            SMG_VERSION,
            true
        );
        
        $enqueued = true;
    }
    
    /**
     * Album shortcode
     * Usage: [smg_album id="123" layout="grid"]
     */
    public function album_shortcode($atts) {
        // Enqueue assets only when shortcode is used
        $this->enqueue_frontend_assets();
        
        $atts = shortcode_atts(array(
            'id' => 0,
            'layout' => 'grid', // grid, slider, masonry
            'columns' => 3,
        ), $atts);
        
        $album_id = intval($atts['id']);
        if (!$album_id) {
            return '';
        }
        
        $album = get_post($album_id);
        if (!$album || $album->post_type !== 'smg_album') {
            return '';
        }
        
        $album_media = get_post_meta($album_id, '_smg_album_media', true);
        if (empty($album_media)) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="smg-album-wrapper smg-layout-<?php echo esc_attr($atts['layout']); ?>" 
             data-layout="<?php echo esc_attr($atts['layout']); ?>"
             data-columns="<?php echo esc_attr($atts['columns']); ?>">
            
            <div class="smg-album-header">
                <h2 class="smg-album-title"><?php echo esc_html($album->post_title); ?></h2>
                <?php if ($album->post_content): ?>
                    <div class="smg-album-description"><?php echo wp_kses_post($album->post_content); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="smg-album-gallery smg-columns-<?php echo esc_attr($atts['columns']); ?>">
                <?php foreach ($album_media as $media): ?>
                    <?php
                    $image_url = '';
                    $full_url = '';
                    
                    if (!empty($media['attachment_id'])) {
                        $image_url = wp_get_attachment_image_url($media['attachment_id'], 'medium');
                        $full_url = wp_get_attachment_image_url($media['attachment_id'], 'full');
                    } elseif (!empty($media['google_drive_url'])) {
                        $image_url = $this->get_google_drive_image_url($media['google_drive_url']);
                        $full_url = $image_url;
                    }
                    
                    if (!$image_url) continue;
                    ?>
                    
                    <div class="smg-gallery-item">
                        <a href="<?php echo esc_url($full_url); ?>" 
                           class="smg-gallery-link"
                           data-title="<?php echo esc_attr($media['title']); ?>"
                           data-description="<?php echo esc_attr($media['description']); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" 
                                 alt="<?php echo esc_attr($media['title']); ?>">
                            <?php if (!empty($media['title']) || !empty($media['description'])): ?>
                                <div class="smg-gallery-caption">
                                    <?php if (!empty($media['title'])): ?>
                                        <div class="smg-caption-title"><?php echo esc_html($media['title']); ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($media['description'])): ?>
                                        <div class="smg-caption-description"><?php echo esc_html($media['description']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get Google Drive image URL
     */
    private function get_google_drive_image_url($url) {
        // Sanitize the input URL first
        $url = esc_url_raw($url);
        
        // Extract file ID from Google Drive URL
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $file_id = sanitize_text_field($matches[1]);
            return esc_url("https://drive.google.com/uc?export=view&id={$file_id}");
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $file_id = sanitize_text_field($matches[1]);
            return esc_url("https://drive.google.com/uc?export=view&id={$file_id}");
        }
        return esc_url($url);
    }
}
