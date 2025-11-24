<?php
/**
 * Xử lý Shortcode
 */

if (!defined('ABSPATH')) {
    exit;
}

class RCHG_Shortcode {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('rchg_album', array($this, 'render_album_shortcode'));
    }
    
    public function render_album_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'layout' => '',
            'columns' => '',
            'autoplay' => '',
            'speed' => ''
        ), $atts, 'rchg_album');
        
        $album_id = intval($atts['id']);
        
        if (!$album_id || get_post_type($album_id) !== 'rchg_album') {
            return '<p>Album không tồn tại.</p>';
        }
        
        // Lấy dữ liệu album
        $images = get_post_meta($album_id, '_rchg_album_images', true);
        
        if (empty($images)) {
            return '<p>Album chưa có ảnh nào.</p>';
        }
        
        // Lấy cài đặt
        $layout = !empty($atts['layout']) ? $atts['layout'] : get_post_meta($album_id, '_rchg_album_layout', true);
        $columns = !empty($atts['columns']) ? $atts['columns'] : get_post_meta($album_id, '_rchg_album_columns', true);
        $speed = !empty($atts['speed']) ? $atts['speed'] : get_post_meta($album_id, '_rchg_album_slide_speed', true);
        $autoplay = !empty($atts['autoplay']) ? $atts['autoplay'] : get_post_meta($album_id, '_rchg_album_autoplay', true);
        
        // Giá trị mặc định
        if (empty($layout)) $layout = 'grid';
        if (empty($columns)) $columns = '3';
        if (empty($speed)) $speed = 3000;
        if (empty($autoplay)) $autoplay = 'yes';
        
        // Render theo layout
        $output = '';
        
        switch ($layout) {
            case 'slider':
                $output = $this->render_slider_layout($album_id, $images, $speed, $autoplay);
                break;
            case 'masonry':
                $output = $this->render_masonry_layout($album_id, $images, $columns);
                break;
            case 'thumbnail':
                $output = $this->render_thumbnail_layout($album_id, $images, $speed, $autoplay);
                break;
            case 'grid':
            default:
                $output = $this->render_grid_layout($album_id, $images, $columns);
                break;
        }
        
        return $output;
    }
    
    private function render_grid_layout($album_id, $images, $columns) {
        $output = '<div class="rchg_album_wrapper rchg_layout_grid" data-album-id="' . $album_id . '" data-columns="' . $columns . '">';
        $output .= '<div class="rchg_grid_container rchg_columns_' . $columns . '">';
        
        foreach ($images as $index => $image) {
            $url = $this->convert_google_drive_url($image['url']);
            $title = isset($image['title']) ? esc_attr($image['title']) : '';
            $description = isset($image['description']) ? esc_html($image['description']) : '';
            
            $output .= '<div class="rchg_grid_item" data-index="' . $index . '">';
            $output .= '<a href="' . esc_url($url) . '" class="rchg_lightbox" data-lightbox="rchg_album_' . $album_id . '" data-title="' . $title . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . $title . '">';
            $output .= '<div class="rchg_overlay">';
            $output .= '<span class="rchg_zoom_icon"><i class="dashicons dashicons-search"></i></span>';
            $output .= '</div>';
            $output .= '</a>';
            
            if ($title || $description) {
                $output .= '<div class="rchg_caption">';
                if ($title) $output .= '<h4 class="rchg_title">' . esc_html($title) . '</h4>';
                if ($description) $output .= '<p class="rchg_description">' . $description . '</p>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    private function render_slider_layout($album_id, $images, $speed, $autoplay) {
        $output = '<div class="rchg_album_wrapper rchg_layout_slider" data-album-id="' . $album_id . '" data-speed="' . $speed . '" data-autoplay="' . $autoplay . '">';
        $output .= '<div class="rchg_slider_container">';
        $output .= '<div class="rchg_slider_track">';
        
        foreach ($images as $index => $image) {
            $url = $this->convert_google_drive_url($image['url']);
            $title = isset($image['title']) ? esc_html($image['title']) : '';
            $description = isset($image['description']) ? esc_html($image['description']) : '';
            
            $output .= '<div class="rchg_slide" data-index="' . $index . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . $title . '">';
            
            if ($title || $description) {
                $output .= '<div class="rchg_slide_caption">';
                if ($title) $output .= '<h3 class="rchg_slide_title">' . $title . '</h3>';
                if ($description) $output .= '<p class="rchg_slide_description">' . $description . '</p>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        // Navigation arrows
        $output .= '<button class="rchg_slider_arrow rchg_arrow_prev" aria-label="Previous">‹</button>';
        $output .= '<button class="rchg_slider_arrow rchg_arrow_next" aria-label="Next">›</button>';
        
        // Dots
        $output .= '<div class="rchg_slider_dots">';
        foreach ($images as $index => $image) {
            $output .= '<button class="rchg_dot' . ($index === 0 ? ' rchg_active' : '') . '" data-index="' . $index . '"></button>';
        }
        $output .= '</div>';
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    private function render_masonry_layout($album_id, $images, $columns) {
        $output = '<div class="rchg_album_wrapper rchg_layout_masonry" data-album-id="' . $album_id . '" data-columns="' . $columns . '">';
        $output .= '<div class="rchg_masonry_container rchg_columns_' . $columns . '">';
        
        foreach ($images as $index => $image) {
            $url = $this->convert_google_drive_url($image['url']);
            $title = isset($image['title']) ? esc_attr($image['title']) : '';
            $description = isset($image['description']) ? esc_html($image['description']) : '';
            
            $output .= '<div class="rchg_masonry_item" data-index="' . $index . '">';
            $output .= '<a href="' . esc_url($url) . '" class="rchg_lightbox" data-lightbox="rchg_album_' . $album_id . '" data-title="' . $title . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . $title . '">';
            $output .= '<div class="rchg_overlay">';
            $output .= '<span class="rchg_zoom_icon"><i class="dashicons dashicons-search"></i></span>';
            $output .= '</div>';
            $output .= '</a>';
            
            if ($title || $description) {
                $output .= '<div class="rchg_caption">';
                if ($title) $output .= '<h4 class="rchg_title">' . esc_html($title) . '</h4>';
                if ($description) $output .= '<p class="rchg_description">' . $description . '</p>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    private function render_thumbnail_layout($album_id, $images, $speed, $autoplay) {
        $output = '<div class="rchg_album_wrapper rchg_layout_thumbnail" data-album-id="' . $album_id . '" data-speed="' . $speed . '" data-autoplay="' . $autoplay . '">';
        
        // Main image display
        $output .= '<div class="rchg_thumbnail_main">';
        
        foreach ($images as $index => $image) {
            $url = $this->convert_google_drive_url($image['url']);
            $title = isset($image['title']) ? esc_html($image['title']) : '';
            $description = isset($image['description']) ? esc_html($image['description']) : '';
            $active = $index === 0 ? ' rchg_active' : '';
            
            $output .= '<div class="rchg_main_image' . $active . '" data-index="' . $index . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . $title . '">';
            
            if ($title || $description) {
                $output .= '<div class="rchg_main_caption">';
                if ($title) $output .= '<h3 class="rchg_main_title">' . $title . '</h3>';
                if ($description) $output .= '<p class="rchg_main_description">' . $description . '</p>';
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        // Navigation arrows
        $output .= '<button class="rchg_thumb_arrow rchg_arrow_prev" aria-label="Previous">‹</button>';
        $output .= '<button class="rchg_thumb_arrow rchg_arrow_next" aria-label="Next">›</button>';
        
        $output .= '</div>';
        
        // Thumbnails
        $output .= '<div class="rchg_thumbnail_list">';
        
        foreach ($images as $index => $image) {
            $url = $this->convert_google_drive_url($image['url']);
            $title = isset($image['title']) ? esc_attr($image['title']) : '';
            $active = $index === 0 ? ' rchg_active' : '';
            
            $output .= '<div class="rchg_thumbnail_item' . $active . '" data-index="' . $index . '">';
            $output .= '<img src="' . esc_url($url) . '" alt="' . $title . '">';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        $output .= '</div>';
        
        return $output;
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
