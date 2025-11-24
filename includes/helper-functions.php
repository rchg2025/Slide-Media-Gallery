<?php
/**
 * Helper Functions cho Slide Media Gallery
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Chuyển đổi Google Drive URL sang dạng có thể hiển thị
 * 
 * @param string $url URL gốc
 * @param string $method Phương thức chuyển đổi (thumbnail, download, view)
 * @return string URL đã chuyển đổi
 */
function rchg_convert_google_drive_url($url, $method = 'thumbnail') {
    if (strpos($url, 'drive.google.com') === false) {
        return $url;
    }
    
    // Lấy file ID từ URL
    $file_id = rchg_extract_google_drive_id($url);
    
    if (!$file_id) {
        return $url;
    }
    
    // Chuyển đổi theo method
    switch ($method) {
        case 'thumbnail':
            // Sử dụng Google Drive Thumbnail API - tốt nhất cho hình ảnh
            // sz=w2000 là kích thước tối đa (có thể điều chỉnh: w500, w1000, w2000, etc.)
            return 'https://drive.google.com/thumbnail?id=' . $file_id . '&sz=w2000';
            
        case 'download':
            // Download trực tiếp file gốc - chất lượng tốt nhất nhưng có thể chậm
            return 'https://drive.google.com/uc?export=download&id=' . $file_id;
            
        case 'view':
            // Xem file - không phải lúc nào cũng hoạt động với <img> tag
            return 'https://drive.google.com/uc?export=view&id=' . $file_id;
            
        case 'open':
            // Open API - alternative method
            return 'https://drive.google.com/open?id=' . $file_id;
            
        default:
            return 'https://drive.google.com/thumbnail?id=' . $file_id . '&sz=w2000';
    }
}

/**
 * Trích xuất File ID từ Google Drive URL
 * 
 * @param string $url Google Drive URL
 * @return string|false File ID hoặc false nếu không tìm thấy
 */
function rchg_extract_google_drive_id($url) {
    // Pattern 1: https://drive.google.com/file/d/FILE_ID/view
    if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 2: https://drive.google.com/open?id=FILE_ID
    if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
        return $matches[1];
    }
    
    // Pattern 3: Direct ID (nếu user paste ID thẳng)
    if (preg_match('/^[a-zA-Z0-9_-]{20,}$/', $url)) {
        return $url;
    }
    
    return false;
}

/**
 * Kiểm tra URL có phải là Google Drive link không
 * 
 * @param string $url URL cần kiểm tra
 * @return bool
 */
function rchg_is_google_drive_url($url) {
    return strpos($url, 'drive.google.com') !== false;
}

/**
 * Tạo nhiều phiên bản URL Google Drive để fallback
 * 
 * @param string $url Google Drive URL gốc
 * @return array Mảng các URL để thử
 */
function rchg_get_google_drive_fallback_urls($url) {
    $file_id = rchg_extract_google_drive_id($url);
    
    if (!$file_id) {
        return array($url);
    }
    
    return array(
        'thumbnail' => 'https://drive.google.com/thumbnail?id=' . $file_id . '&sz=w2000',
        'download' => 'https://drive.google.com/uc?export=download&id=' . $file_id,
        'view' => 'https://drive.google.com/uc?export=view&id=' . $file_id,
        'open' => 'https://drive.google.com/open?id=' . $file_id,
    );
}

/**
 * Kiểm tra quyền truy cập Google Drive file
 * Note: Đây chỉ là check cơ bản, không thể kiểm tra chính xác 100%
 * 
 * @param string $url Google Drive URL
 * @return array Status và message
 */
function rchg_check_google_drive_access($url) {
    $file_id = rchg_extract_google_drive_id($url);
    
    if (!$file_id) {
        return array(
            'status' => 'error',
            'message' => 'Không tìm thấy File ID trong URL'
        );
    }
    
    // Thử truy cập thumbnail API
    $test_url = 'https://drive.google.com/thumbnail?id=' . $file_id . '&sz=w100';
    $response = wp_remote_head($test_url, array('timeout' => 10));
    
    if (is_wp_error($response)) {
        return array(
            'status' => 'error',
            'message' => 'Không thể kết nối tới Google Drive: ' . $response->get_error_message()
        );
    }
    
    $status_code = wp_remote_retrieve_response_code($response);
    
    if ($status_code === 200) {
        return array(
            'status' => 'success',
            'message' => 'File có thể truy cập được'
        );
    } elseif ($status_code === 403 || $status_code === 401) {
        return array(
            'status' => 'error',
            'message' => 'File không có quyền truy cập công khai. Vui lòng đổi sang "Anyone with the link"'
        );
    } elseif ($status_code === 404) {
        return array(
            'status' => 'error',
            'message' => 'File không tồn tại hoặc đã bị xóa'
        );
    } else {
        return array(
            'status' => 'warning',
            'message' => 'Không chắc chắn về quyền truy cập (HTTP ' . $status_code . ')'
        );
    }
}

/**
 * Lấy thông tin về Google Drive file (nếu có thể)
 * 
 * @param string $file_id Google Drive File ID
 * @return array|false Thông tin file hoặc false
 */
function rchg_get_google_drive_file_info($file_id) {
    // Note: Cần Google Drive API key để lấy thông tin chi tiết
    // Đây chỉ là placeholder function
    return false;
}
