/**
 * Slide Media Gallery - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Khởi tạo các slider
        initSliders();
        
        // Khởi tạo thumbnail galleries
        initThumbnailGalleries();
        
        // Khởi tạo lightbox
        if (rchgSettings.lightboxEnabled === 'yes') {
            initLightbox();
        }
        
    });
    
    /**
     * Khởi tạo Slider Layout
     */
    function initSliders() {
        $('.rchg_layout_slider').each(function() {
            var $wrapper = $(this);
            var $container = $wrapper.find('.rchg_slider_container');
            var $track = $wrapper.find('.rchg_slider_track');
            var $slides = $wrapper.find('.rchg_slide');
            var $dots = $wrapper.find('.rchg_dot');
            var currentIndex = 0;
            var slideCount = $slides.length;
            var speed = parseInt($wrapper.data('speed')) || 3000;
            var autoplay = $wrapper.data('autoplay') === 'yes';
            var autoplayTimer;
            
            if (slideCount <= 1) {
                $wrapper.find('.rchg_slider_arrow, .rchg_slider_dots').hide();
                return;
            }
            
            // Điều chỉnh chiều cao container theo ảnh cao nhất
            function adjustContainerHeight() {
                var maxHeight = 0;
                $slides.find('img').each(function() {
                    if (this.complete) {
                        var imgHeight = $(this).height();
                        if (imgHeight > maxHeight) {
                            maxHeight = imgHeight;
                        }
                    }
                });
                
                if (maxHeight > 0) {
                    $container.css('min-height', maxHeight + 'px');
                }
            }
            
            // Load tất cả ảnh trước rồi điều chỉnh chiều cao
            var imagesLoaded = 0;
            var totalImages = $slides.find('img').length;
            
            $slides.find('img').each(function() {
                var $img = $(this);
                if (this.complete) {
                    imagesLoaded++;
                } else {
                    $img.on('load', function() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            adjustContainerHeight();
                        }
                    });
                }
            });
            
            if (imagesLoaded === totalImages) {
                adjustContainerHeight();
            }
            
            // Điều chỉnh lại khi resize window
            $(window).on('resize', function() {
                adjustContainerHeight();
            });
            
            // Chuyển slide
            function goToSlide(index) {
                if (index < 0) {
                    index = slideCount - 1;
                } else if (index >= slideCount) {
                    index = 0;
                }
                
                currentIndex = index;
                var offset = -100 * currentIndex;
                $track.css('transform', 'translateX(' + offset + '%)');
                
                // Update dots
                $dots.removeClass('rchg_active');
                $dots.eq(currentIndex).addClass('rchg_active');
            }
            
            // Next slide
            $wrapper.find('.rchg_arrow_next').on('click', function() {
                goToSlide(currentIndex + 1);
                resetAutoplay();
            });
            
            // Previous slide
            $wrapper.find('.rchg_arrow_prev').on('click', function() {
                goToSlide(currentIndex - 1);
                resetAutoplay();
            });
            
            // Dots navigation
            $dots.on('click', function() {
                var index = $(this).data('index');
                goToSlide(index);
                resetAutoplay();
            });
            
            // Autoplay
            function startAutoplay() {
                if (autoplay) {
                    autoplayTimer = setInterval(function() {
                        goToSlide(currentIndex + 1);
                    }, speed);
                }
            }
            
            function resetAutoplay() {
                clearInterval(autoplayTimer);
                startAutoplay();
            }
            
            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($wrapper.is(':hover')) {
                    if (e.keyCode === 37) { // Left arrow
                        goToSlide(currentIndex - 1);
                        resetAutoplay();
                    } else if (e.keyCode === 39) { // Right arrow
                        goToSlide(currentIndex + 1);
                        resetAutoplay();
                    }
                }
            });
            
            // Touch swipe support
            var touchStartX = 0;
            var touchEndX = 0;
            
            $container.on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });
            
            $container.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                handleSwipe();
            });
            
            function handleSwipe() {
                if (touchEndX < touchStartX - 50) {
                    goToSlide(currentIndex + 1);
                    resetAutoplay();
                }
                if (touchEndX > touchStartX + 50) {
                    goToSlide(currentIndex - 1);
                    resetAutoplay();
                }
            }
            
            // Start autoplay
            startAutoplay();
        });
    }
    
    /**
     * Khởi tạo Thumbnail Gallery Layout
     */
    function initThumbnailGalleries() {
        $('.rchg_layout_thumbnail').each(function() {
            var $wrapper = $(this);
            var $mainContainer = $wrapper.find('.rchg_thumbnail_main');
            var $mainImages = $wrapper.find('.rchg_main_image');
            var $thumbnails = $wrapper.find('.rchg_thumbnail_item');
            var currentIndex = 0;
            var imageCount = $mainImages.length;
            var speed = parseInt($wrapper.data('speed')) || 3000;
            var autoplay = $wrapper.data('autoplay') === 'yes';
            var autoplayTimer;
            
            if (imageCount <= 1) {
                $wrapper.find('.rchg_thumb_arrow').hide();
                return;
            }
            
            // Điều chỉnh chiều cao container theo ảnh cao nhất
            function adjustMainContainerHeight() {
                var maxHeight = 0;
                $mainImages.find('img').each(function() {
                    if (this.complete) {
                        var imgHeight = $(this).height();
                        if (imgHeight > maxHeight) {
                            maxHeight = imgHeight;
                        }
                    }
                });
                
                if (maxHeight > 0) {
                    $mainContainer.css('min-height', maxHeight + 'px');
                }
            }
            
            // Load tất cả ảnh trước
            var imagesLoaded = 0;
            var totalImages = $mainImages.find('img').length;
            
            $mainImages.find('img').each(function() {
                var $img = $(this);
                if (this.complete) {
                    imagesLoaded++;
                } else {
                    $img.on('load', function() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            adjustMainContainerHeight();
                        }
                    });
                }
            });
            
            if (imagesLoaded === totalImages) {
                adjustMainContainerHeight();
            }
            
            // Điều chỉnh lại khi resize
            $(window).on('resize', function() {
                adjustMainContainerHeight();
            });
            
            // Chuyển ảnh
            function goToImage(index) {
                if (index < 0) {
                    index = imageCount - 1;
                } else if (index >= imageCount) {
                    index = 0;
                }
                
                currentIndex = index;
                
                // Update main image
                $mainImages.removeClass('rchg_active');
                $mainImages.eq(currentIndex).addClass('rchg_active');
                
                // Update thumbnails
                $thumbnails.removeClass('rchg_active');
                $thumbnails.eq(currentIndex).addClass('rchg_active');
                
                // Scroll thumbnail into view
                var $activeThumb = $thumbnails.eq(currentIndex);
                var thumbList = $wrapper.find('.rchg_thumbnail_list')[0];
                if (thumbList && $activeThumb.length) {
                    var scrollLeft = $activeThumb[0].offsetLeft - (thumbList.offsetWidth / 2) + ($activeThumb.width() / 2);
                    $(thumbList).animate({ scrollLeft: scrollLeft }, 300);
                }
            }
            
            // Arrow navigation
            $wrapper.find('.rchg_arrow_next').on('click', function() {
                goToImage(currentIndex + 1);
                resetAutoplay();
            });
            
            $wrapper.find('.rchg_arrow_prev').on('click', function() {
                goToImage(currentIndex - 1);
                resetAutoplay();
            });
            
            // Thumbnail click
            $thumbnails.on('click', function() {
                var index = $(this).data('index');
                goToImage(index);
                resetAutoplay();
            });
            
            // Autoplay
            function startAutoplay() {
                if (autoplay) {
                    autoplayTimer = setInterval(function() {
                        goToImage(currentIndex + 1);
                    }, speed);
                }
            }
            
            function resetAutoplay() {
                clearInterval(autoplayTimer);
                startAutoplay();
            }
            
            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($wrapper.is(':hover')) {
                    if (e.keyCode === 37) { // Left arrow
                        goToImage(currentIndex - 1);
                        resetAutoplay();
                    } else if (e.keyCode === 39) { // Right arrow
                        goToImage(currentIndex + 1);
                        resetAutoplay();
                    }
                }
            });
            
            // Start autoplay
            startAutoplay();
        });
    }
    
    /**
     * Khởi tạo Lightbox
     */
    function initLightbox() {
        // Tạo lightbox HTML
        if ($('#rchg_lightbox').length === 0) {
            var lightboxHTML = `
                <div id="rchg_lightbox" class="rchg_lightbox_overlay">
                    <button class="rchg_lightbox_close" aria-label="Close">×</button>
                    <button class="rchg_lightbox_arrow rchg_lightbox_arrow_prev" aria-label="Previous">‹</button>
                    <button class="rchg_lightbox_arrow rchg_lightbox_arrow_next" aria-label="Next">›</button>
                    <div class="rchg_lightbox_counter"></div>
                    <div class="rchg_lightbox_content">
                        <img src="" alt="" class="rchg_lightbox_image">
                    </div>
                    <div class="rchg_lightbox_caption">
                        <div class="rchg_lightbox_title"></div>
                    </div>
                </div>
            `;
            $('body').append(lightboxHTML);
        }
        
        var $lightbox = $('#rchg_lightbox');
        var $image = $lightbox.find('.rchg_lightbox_image');
        var $title = $lightbox.find('.rchg_lightbox_title');
        var $counter = $lightbox.find('.rchg_lightbox_counter');
        var currentGallery = [];
        var currentIndex = 0;
        
        // Open lightbox
        $(document).on('click', '.rchg_lightbox', function(e) {
            e.preventDefault();
            
            var $clicked = $(this);
            var galleryId = $clicked.data('lightbox');
            
            // Build gallery array
            currentGallery = [];
            $('[data-lightbox="' + galleryId + '"]').each(function() {
                currentGallery.push({
                    src: $(this).attr('href'),
                    title: $(this).data('title') || ''
                });
            });
            
            // Find current index
            currentIndex = currentGallery.findIndex(function(item) {
                return item.src === $clicked.attr('href');
            });
            
            showLightboxImage(currentIndex);
            $lightbox.addClass('rchg_active');
            $('body').css('overflow', 'hidden');
        });
        
        // Show image
        function showLightboxImage(index) {
            if (index < 0) index = currentGallery.length - 1;
            if (index >= currentGallery.length) index = 0;
            
            currentIndex = index;
            var item = currentGallery[currentIndex];
            
            $image.attr('src', item.src).attr('alt', item.title);
            $title.text(item.title);
            $counter.text((currentIndex + 1) + ' / ' + currentGallery.length);
            
            // Hide arrows if only one image
            if (currentGallery.length <= 1) {
                $lightbox.find('.rchg_lightbox_arrow').hide();
            } else {
                $lightbox.find('.rchg_lightbox_arrow').show();
            }
        }
        
        // Close lightbox
        $lightbox.find('.rchg_lightbox_close').on('click', function() {
            $lightbox.removeClass('rchg_active');
            $('body').css('overflow', '');
        });
        
        // Click outside image
        $lightbox.on('click', function(e) {
            if ($(e.target).is($lightbox)) {
                $lightbox.removeClass('rchg_active');
                $('body').css('overflow', '');
            }
        });
        
        // Navigation
        $lightbox.find('.rchg_lightbox_arrow_next').on('click', function() {
            showLightboxImage(currentIndex + 1);
        });
        
        $lightbox.find('.rchg_lightbox_arrow_prev').on('click', function() {
            showLightboxImage(currentIndex - 1);
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if ($lightbox.hasClass('rchg_active')) {
                if (e.keyCode === 27) { // ESC
                    $lightbox.removeClass('rchg_active');
                    $('body').css('overflow', '');
                } else if (e.keyCode === 37) { // Left arrow
                    showLightboxImage(currentIndex - 1);
                } else if (e.keyCode === 39) { // Right arrow
                    showLightboxImage(currentIndex + 1);
                }
            }
        });
    }
    
    /**
     * Lazy loading (nếu được bật)
     */
    if (rchgSettings.lazyLoad === 'yes' && 'IntersectionObserver' in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        $('.rchg_album_wrapper img[data-src]').each(function() {
            imageObserver.observe(this);
        });
    }
    
})(jQuery);
