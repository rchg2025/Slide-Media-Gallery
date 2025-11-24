/**
 * Slide Media Gallery - Admin List JavaScript
 * Copy shortcode functionality
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Copy shortcode button in admin list
        $(document).on('click', '.rchg_copy_shortcode', function(e) {
            e.preventDefault();
            var shortcode = $(this).data('shortcode');
            
            // Create temporary textarea to copy
            var $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(shortcode).select();
            document.execCommand('copy');
            $temp.remove();
            
            // Show feedback
            var $button = $(this);
            var originalText = $button.text();
            $button.text('Đã copy!').addClass('button-primary');
            
            setTimeout(function() {
                $button.text(originalText).removeClass('button-primary');
            }, 2000);
        });
    });
    
})(jQuery);
