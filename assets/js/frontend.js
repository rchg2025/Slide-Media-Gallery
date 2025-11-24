/**
 * Frontend JavaScript for Slide Media Gallery
 */

(function($) {
    'use strict';
    
    var SMGFrontend = {
        
        init: function() {
            this.initLightbox();
            this.initSlider();
        },
        
        initLightbox: function() {
            // Simple lightbox functionality
            $(document).on('click', '.smg-gallery-link', function(e) {
                e.preventDefault();
                var $link = $(this);
                var imageUrl = $link.attr('href');
                var title = $link.data('title') || '';
                var description = $link.data('description') || '';
                
                // Create lightbox overlay
                var $lightbox = $('<div class="smg-lightbox"></div>');
                var $content = $('<div class="smg-lightbox-content"></div>');
                var $close = $('<span class="smg-lightbox-close">&times;</span>');
                var $img = $('<img src="' + imageUrl + '" alt="">');
                var $caption = $('<div class="smg-lightbox-caption"></div>');
                
                if (title) {
                    $caption.append('<div class="smg-lightbox-title">' + title + '</div>');
                }
                if (description) {
                    $caption.append('<div class="smg-lightbox-description">' + description + '</div>');
                }
                
                $content.append($close).append($img);
                if (title || description) {
                    $content.append($caption);
                }
                $lightbox.append($content);
                
                // Add styles
                $lightbox.css({
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                    backgroundColor: 'rgba(0,0,0,0.9)',
                    zIndex: 999999,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    padding: '20px'
                });
                
                $content.css({
                    position: 'relative',
                    maxWidth: '90%',
                    maxHeight: '90%',
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center'
                });
                
                $close.css({
                    position: 'absolute',
                    top: '-40px',
                    right: '0',
                    fontSize: '40px',
                    color: '#fff',
                    cursor: 'pointer',
                    fontWeight: 'bold',
                    zIndex: 1
                });
                
                $img.css({
                    maxWidth: '100%',
                    maxHeight: '80vh',
                    objectFit: 'contain'
                });
                
                $caption.css({
                    color: '#fff',
                    textAlign: 'center',
                    marginTop: '20px',
                    maxWidth: '600px'
                });
                
                // Append to body
                $('body').append($lightbox);
                
                // Close on click
                $lightbox.on('click', function(e) {
                    if (e.target === this || $(e.target).hasClass('smg-lightbox-close')) {
                        $lightbox.fadeOut(300, function() {
                            $lightbox.remove();
                        });
                    }
                });
                
                // Close on ESC key
                $(document).on('keydown.smglightbox', function(e) {
                    if (e.keyCode === 27) {
                        $lightbox.fadeOut(300, function() {
                            $lightbox.remove();
                        });
                        $(document).off('keydown.smglightbox');
                    }
                });
            });
        },
        
        initSlider: function() {
            // Simple slider functionality for slider layout
            $('.smg-layout-slider .smg-album-gallery').each(function() {
                var $gallery = $(this);
                var scrollAmount = 320; // width of item + gap
                
                // Add navigation buttons if not exists
                if ($gallery.siblings('.smg-slider-nav').length === 0) {
                    var $nav = $('<div class="smg-slider-nav"></div>');
                    var $prev = $('<button class="smg-slider-prev">&lsaquo;</button>');
                    var $next = $('<button class="smg-slider-next">&rsaquo;</button>');
                    
                    $nav.append($prev).append($next);
                    $gallery.parent().append($nav);
                    
                    // Style navigation
                    $nav.css({
                        display: 'flex',
                        justifyContent: 'center',
                        gap: '10px',
                        marginTop: '20px'
                    });
                    
                    $prev.add($next).css({
                        padding: '10px 20px',
                        fontSize: '24px',
                        border: '2px solid #333',
                        background: '#fff',
                        cursor: 'pointer',
                        borderRadius: '5px'
                    });
                    
                    // Navigation click handlers
                    $prev.on('click', function() {
                        $gallery.animate({
                            scrollLeft: $gallery.scrollLeft() - scrollAmount
                        }, 300);
                    });
                    
                    $next.on('click', function() {
                        $gallery.animate({
                            scrollLeft: $gallery.scrollLeft() + scrollAmount
                        }, 300);
                    });
                }
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        SMGFrontend.init();
    });
    
})(jQuery);
