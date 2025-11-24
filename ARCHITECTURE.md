# Plugin Architecture

This document describes the architecture and structure of the Slide Media Gallery plugin.

## Directory Structure

```
slide-media-gallery/
├── slide-media-gallery.php          # Main plugin file
├── README.md                         # Main documentation
├── USAGE.md                          # Usage examples
├── CHANGELOG.md                      # Version history
├── .gitignore                        # Git ignore rules
├── includes/                         # PHP classes
│   ├── class-smg-post-types.php     # Custom post type & metabox
│   ├── class-smg-admin.php          # Admin assets management
│   ├── class-smg-shortcodes.php     # Frontend shortcodes
│   └── class-smg-ajax.php           # AJAX handlers
└── assets/                           # Frontend assets
    ├── css/
    │   ├── admin.css                # Admin interface styles
    │   └── frontend.css             # Frontend gallery styles
    └── js/
        ├── admin.js                 # Admin functionality
        └── frontend.js              # Frontend functionality
```

## Core Components

### 1. Main Plugin File (`slide-media-gallery.php`)

**Responsibilities:**
- Plugin initialization
- Define constants
- Load required files
- Handle activation/deactivation hooks

**Key Functions:**
- `Slide_Media_Gallery::get_instance()` - Singleton pattern
- `init()` - Initialize components
- `activate()` - Plugin activation
- `deactivate()` - Plugin deactivation

### 2. Post Types Class (`class-smg-post-types.php`)

**Responsibilities:**
- Register 'smg_album' custom post type
- Create album media metabox
- Handle drag-and-drop interface
- Save album metadata

**Key Functions:**
- `register_post_types()` - Register album post type
- `add_meta_boxes()` - Add media management metabox
- `render_album_media_metabox()` - Render admin interface
- `save_album_meta()` - Save album data
- `get_google_drive_thumbnail()` - Process Google Drive URLs

### 3. Admin Class (`class-smg-admin.php`)

**Responsibilities:**
- Enqueue admin scripts and styles
- Localize JavaScript strings
- Load WordPress media uploader

**Key Functions:**
- `enqueue_admin_scripts()` - Load admin assets

### 4. Shortcodes Class (`class-smg-shortcodes.php`)

**Responsibilities:**
- Register [smg_album] shortcode
- Render frontend gallery
- Process layout options
- Handle Google Drive image display

**Key Functions:**
- `album_shortcode()` - Process shortcode
- `enqueue_frontend_assets()` - Load frontend assets conditionally
- `get_google_drive_image_url()` - Generate Google Drive URLs

### 5. AJAX Class (`class-smg-ajax.php`)

**Responsibilities:**
- Handle AJAX requests (future expansion)

## Data Flow

### Creating an Album

```
User Input (Admin)
    ↓
WordPress Admin UI
    ↓
SMG_Post_Types::render_album_media_metabox()
    ↓
Admin.js (drag-and-drop, media picker)
    ↓
Form Submit
    ↓
SMG_Post_Types::save_album_meta()
    ↓
WordPress Database (post meta)
```

### Displaying an Album

```
Shortcode [smg_album id="123"]
    ↓
SMG_Shortcodes::album_shortcode()
    ↓
Enqueue frontend assets
    ↓
Fetch album data from database
    ↓
Process layout options
    ↓
Render HTML output
    ↓
Frontend.js (lightbox, slider)
```

## Database Schema

### Post Type: smg_album

**Standard WordPress Post Fields:**
- `ID` - Album post ID
- `post_title` - Album title
- `post_content` - Album description
- `post_status` - publish/draft/etc

**Custom Meta Fields:**
- `_smg_album_media` (serialized array)
  ```php
  array(
      array(
          'attachment_id' => 123,
          'google_drive_url' => '',
          'title' => 'Photo title',
          'description' => 'Photo description',
          'order' => 0
      ),
      // ... more items
  )
  ```

## JavaScript Architecture

### Admin (admin.js)

**Object:** `SMGAdmin`

**Key Methods:**
- `init()` - Initialize admin functionality
- `bindEvents()` - Attach event handlers
- `initSortable()` - Setup drag-and-drop
- `openMediaUploader()` - WordPress media picker
- `addMediaItem()` - Add image to list
- `updateMediaIndices()` - Update ordering

### Frontend (frontend.js)

**Object:** `SMGFrontend`

**Key Methods:**
- `init()` - Initialize frontend functionality
- `initLightbox()` - Setup lightbox viewer
- `initSlider()` - Setup slider navigation

## CSS Architecture

### Admin Styles (admin.css)

**Components:**
- Album manager container
- Toolbar
- Media list (sortable)
- Media item cards
- Drag handle
- Preview thumbnails
- Form fields
- Action buttons

### Frontend Styles (frontend.css)

**Components:**
- Album wrapper
- Album header
- Gallery layouts (grid/slider/masonry)
- Gallery items
- Captions
- Lightbox
- Responsive breakpoints

## Security Features

### Input Sanitization
- `sanitize_text_field()` - Text inputs
- `sanitize_textarea_field()` - Textareas
- `esc_url_raw()` - URLs on save
- `esc_url()` - URLs on output

### Output Escaping
- `esc_html()` - Plain text
- `esc_attr()` - HTML attributes
- `esc_url()` - URLs
- `wp_kses_post()` - HTML content

### Access Control
- `wp_verify_nonce()` - Verify form submissions
- `current_user_can()` - Check capabilities
- `DOING_AUTOSAVE` - Prevent autosave conflicts

## Extensibility

### Hooks for Developers

The plugin can be extended through WordPress hooks:

**Actions:**
- `smg_before_album_render` - Before album output
- `smg_after_album_render` - After album output
- `smg_before_save_album` - Before saving album

**Filters:**
- `smg_album_layouts` - Add custom layouts
- `smg_gallery_item_html` - Customize item output
- `smg_shortcode_atts` - Modify shortcode attributes

### Adding Custom Layouts

Example of adding a custom layout:

```php
add_filter('smg_album_layouts', function($layouts) {
    $layouts[] = 'custom';
    return $layouts;
});
```

## Performance Considerations

### Optimizations Implemented
1. **Conditional Asset Loading** - Scripts only load when shortcode is used
2. **Static Enqueue Flag** - Prevents duplicate asset loading
3. **Minimal Dependencies** - Uses WordPress core libraries
4. **CSS Grid** - Hardware-accelerated layouts
5. **Lazy Loading Ready** - Compatible with WordPress lazy loading

### Best Practices
- Keep albums under 50 images
- Use optimized images (under 500KB)
- Consider Google Drive for large galleries
- Use appropriate layout for content type

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## WordPress Compatibility

- WordPress 5.0+
- PHP 7.0+
- MySQL 5.6+ / MariaDB 10.0+

## Future Enhancements

Potential features for future versions:
- Bulk image upload
- Image editing integration
- Advanced sorting options
- Gallery templates
- Import/export functionality
- Integration with popular page builders
- Video support
- Social sharing buttons
