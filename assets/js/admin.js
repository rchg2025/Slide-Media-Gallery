/**
 * Admin JavaScript for Slide Media Gallery
 */

(function($) {
    'use strict';
    
    var SMGAdmin = {
        mediaFrame: null,
        mediaIndex: 0,
        
        init: function() {
            this.bindEvents();
            this.initSortable();
            this.updateMediaIndices();
        },
        
        bindEvents: function() {
            var self = this;
            
            // Add media button
            $(document).on('click', '.smg-add-media', function(e) {
                e.preventDefault();
                self.openMediaUploader();
            });
            
            // Remove media button
            $(document).on('click', '.smg-remove-media', function(e) {
                e.preventDefault();
                if (confirm(smgAdmin.strings.confirmRemove)) {
                    $(this).closest('.smg-media-item').fadeOut(300, function() {
                        $(this).remove();
                        self.updateMediaIndices();
                    });
                }
            });
        },
        
        initSortable: function() {
            var self = this;
            
            $('#smg-media-list').sortable({
                handle: '.smg-media-handle',
                placeholder: 'ui-sortable-placeholder',
                cursor: 'move',
                opacity: 0.8,
                tolerance: 'pointer',
                update: function(event, ui) {
                    self.updateMediaIndices();
                }
            });
        },
        
        openMediaUploader: function() {
            var self = this;
            
            // Create media frame if it doesn't exist
            if (this.mediaFrame) {
                this.mediaFrame.open();
                return;
            }
            
            // Create the media frame
            this.mediaFrame = wp.media({
                title: smgAdmin.strings.selectMedia,
                button: {
                    text: smgAdmin.strings.useMedia
                },
                multiple: true
            });
            
            // When media is selected
            this.mediaFrame.on('select', function() {
                var selection = self.mediaFrame.state().get('selection');
                
                selection.each(function(attachment) {
                    attachment = attachment.toJSON();
                    self.addMediaItem({
                        attachment_id: attachment.id,
                        title: attachment.title || '',
                        description: attachment.description || '',
                        google_drive_url: '',
                        thumb_url: attachment.sizes && attachment.sizes.thumbnail ? 
                                   attachment.sizes.thumbnail.url : attachment.url
                    });
                });
                
                self.updateMediaIndices();
            });
            
            // Open the media frame
            this.mediaFrame.open();
        },
        
        addMediaItem: function(data) {
            var template = $('#smg-media-item-template').html();
            var $list = $('#smg-media-list');
            
            // Get next index
            var index = this.getNextIndex();
            
            // Replace template placeholders
            template = template.replace(/\{\{index\}\}/g, index);
            
            var $item = $(template);
            
            // Set data
            $item.find('.smg-attachment-id').val(data.attachment_id || '');
            $item.find('input[name*="[google_drive_url]"]').val(data.google_drive_url || '');
            $item.find('input[name*="[title]"]').val(data.title || '');
            $item.find('textarea[name*="[description]"]').val(data.description || '');
            
            // Set thumbnail
            if (data.thumb_url) {
                $item.find('.smg-media-preview').html('<img src="' + data.thumb_url + '" alt="">');
            }
            
            // Add to list
            $list.append($item);
            
            // Scroll to new item
            $item[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        },
        
        getNextIndex: function() {
            var maxIndex = 0;
            $('#smg-media-list .smg-media-item').each(function() {
                var index = parseInt($(this).data('index')) || 0;
                if (index > maxIndex) {
                    maxIndex = index;
                }
            });
            return maxIndex + 1;
        },
        
        updateMediaIndices: function() {
            $('#smg-media-list .smg-media-item').each(function(index) {
                var $item = $(this);
                var currentIndex = $item.data('index');
                var newIndex = index;
                
                // Update order hidden field
                $item.find('.smg-order').val(newIndex);
                
                // Update name attributes to reflect new index
                $item.find('input, textarea').each(function() {
                    var $field = $(this);
                    var name = $field.attr('name');
                    if (name) {
                        // Replace old index with new index in name
                        var newName = name.replace(/\[(\d+)\]/, '[' + currentIndex + ']');
                        $field.attr('name', newName);
                    }
                });
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        SMGAdmin.init();
    });
    
})(jQuery);
