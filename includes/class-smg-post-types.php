<?php
/**
 * Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

class SMG_Post_Types {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_album_meta'));
    }
    
    /**
     * Register custom post type for albums
     */
    public function register_post_types() {
        $labels = array(
            'name'                  => __('Albums', 'slide-media-gallery'),
            'singular_name'         => __('Album', 'slide-media-gallery'),
            'menu_name'             => __('Media Albums', 'slide-media-gallery'),
            'add_new'               => __('Add New', 'slide-media-gallery'),
            'add_new_item'          => __('Add New Album', 'slide-media-gallery'),
            'edit_item'             => __('Edit Album', 'slide-media-gallery'),
            'new_item'              => __('New Album', 'slide-media-gallery'),
            'view_item'             => __('View Album', 'slide-media-gallery'),
            'search_items'          => __('Search Albums', 'slide-media-gallery'),
            'not_found'             => __('No albums found', 'slide-media-gallery'),
            'not_found_in_trash'    => __('No albums found in Trash', 'slide-media-gallery'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'album'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-format-gallery',
            'supports'              => array('title', 'editor', 'thumbnail'),
        );
        
        register_post_type('smg_album', $args);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'smg_album_media',
            __('Album Media', 'slide-media-gallery'),
            array($this, 'render_album_media_metabox'),
            'smg_album',
            'normal',
            'high'
        );
    }
    
    /**
     * Render album media metabox
     */
    public function render_album_media_metabox($post) {
        wp_nonce_field('smg_save_album_meta', 'smg_album_nonce');
        
        $album_media = get_post_meta($post->ID, '_smg_album_media', true);
        if (empty($album_media)) {
            $album_media = array();
        }
        ?>
        <div id="smg-album-manager">
            <div class="smg-toolbar">
                <button type="button" class="button button-primary smg-add-media">
                    <?php _e('Add Media', 'slide-media-gallery'); ?>
                </button>
                <p class="description">
                    <?php _e('Add images from Media Library or enter Google Drive links', 'slide-media-gallery'); ?>
                </p>
            </div>
            
            <div id="smg-media-list" class="smg-media-sortable">
                <?php
                if (!empty($album_media)) {
                    foreach ($album_media as $index => $media) {
                        $this->render_media_item($index, $media);
                    }
                }
                ?>
            </div>
        </div>
        
        <script type="text/html" id="smg-media-item-template">
            <?php $this->render_media_item('{{index}}', array()); ?>
        </script>
        <?php
    }
    
    /**
     * Render individual media item
     */
    private function render_media_item($index, $media) {
        $attachment_id = isset($media['attachment_id']) ? $media['attachment_id'] : '';
        $google_drive_url = isset($media['google_drive_url']) ? $media['google_drive_url'] : '';
        $title = isset($media['title']) ? $media['title'] : '';
        $description = isset($media['description']) ? $media['description'] : '';
        $order = isset($media['order']) ? $media['order'] : $index;
        
        $thumb_url = '';
        if ($attachment_id) {
            $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
        } elseif ($google_drive_url) {
            $thumb_url = $this->get_google_drive_thumbnail($google_drive_url);
        }
        ?>
        <div class="smg-media-item" data-index="<?php echo esc_attr($index); ?>">
            <div class="smg-media-handle">
                <span class="dashicons dashicons-move"></span>
            </div>
            <div class="smg-media-preview">
                <?php if ($thumb_url): ?>
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="">
                <?php else: ?>
                    <span class="dashicons dashicons-format-image"></span>
                <?php endif; ?>
            </div>
            <div class="smg-media-details">
                <input type="hidden" 
                       name="smg_media[<?php echo esc_attr($index); ?>][attachment_id]" 
                       value="<?php echo esc_attr($attachment_id); ?>"
                       class="smg-attachment-id">
                       
                <input type="hidden" 
                       name="smg_media[<?php echo esc_attr($index); ?>][order]" 
                       value="<?php echo esc_attr($order); ?>"
                       class="smg-order">
                
                <div class="smg-field">
                    <label><?php _e('Google Drive URL', 'slide-media-gallery'); ?></label>
                    <input type="text" 
                           name="smg_media[<?php echo esc_attr($index); ?>][google_drive_url]" 
                           value="<?php echo esc_attr($google_drive_url); ?>"
                           placeholder="https://drive.google.com/..."
                           class="widefat">
                </div>
                
                <div class="smg-field">
                    <label><?php _e('Title', 'slide-media-gallery'); ?></label>
                    <input type="text" 
                           name="smg_media[<?php echo esc_attr($index); ?>][title]" 
                           value="<?php echo esc_attr($title); ?>"
                           class="widefat">
                </div>
                
                <div class="smg-field">
                    <label><?php _e('Description', 'slide-media-gallery'); ?></label>
                    <textarea name="smg_media[<?php echo esc_attr($index); ?>][description]" 
                              class="widefat" 
                              rows="3"><?php echo esc_textarea($description); ?></textarea>
                </div>
            </div>
            <div class="smg-media-actions">
                <button type="button" class="button smg-remove-media">
                    <span class="dashicons dashicons-trash"></span>
                </button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get Google Drive thumbnail URL
     */
    private function get_google_drive_thumbnail($url) {
        // Extract file ID from Google Drive URL
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $file_id = $matches[1];
            return "https://drive.google.com/thumbnail?id={$file_id}";
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $file_id = $matches[1];
            return "https://drive.google.com/thumbnail?id={$file_id}";
        }
        return '';
    }
    
    /**
     * Save album meta
     */
    public function save_album_meta($post_id) {
        // Check nonce
        if (!isset($_POST['smg_album_nonce']) || !wp_verify_nonce($_POST['smg_album_nonce'], 'smg_save_album_meta')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save media data
        if (isset($_POST['smg_media']) && is_array($_POST['smg_media'])) {
            $album_media = array();
            
            foreach ($_POST['smg_media'] as $index => $media) {
                $album_media[] = array(
                    'attachment_id' => sanitize_text_field($media['attachment_id']),
                    'google_drive_url' => esc_url_raw($media['google_drive_url']),
                    'title' => sanitize_text_field($media['title']),
                    'description' => sanitize_textarea_field($media['description']),
                    'order' => intval($media['order']),
                );
            }
            
            // Sort by order
            usort($album_media, function($a, $b) {
                return $a['order'] - $b['order'];
            });
            
            update_post_meta($post_id, '_smg_album_media', $album_media);
        } else {
            delete_post_meta($post_id, '_smg_album_media');
        }
    }
}
