# Usage Examples for Slide Media Gallery

This document provides practical examples of how to use the Slide Media Gallery plugin.

## Quick Start

### 1. Creating Your First Album

1. In WordPress admin, go to **Media Albums** > **Add New**
2. Enter a title: "Summer Vacation 2024"
3. Add description (optional)
4. Click **Add Media** button
5. Select images from Media Library or upload new ones
6. Click **Use this media**
7. For each image, you can:
   - Add a title
   - Add a description
   - Enter a Google Drive URL (alternative to Media Library)
8. Drag and drop images to reorder them
9. Click **Publish**

### 2. Displaying Album on a Page/Post

Copy the album ID from the URL (e.g., if URL is `.../post.php?post=123...`, the ID is 123)

Add shortcode to your page:
```
[smg_album id="123"]
```

## Layout Examples

### Grid Layout (Default)

**3 columns (default):**
```
[smg_album id="123"]
```

**4 columns:**
```
[smg_album id="123" columns="4"]
```

**2 columns for mobile-friendly display:**
```
[smg_album id="123" columns="2"]
```

### Slider Layout

Creates a horizontal scrolling gallery:
```
[smg_album id="123" layout="slider"]
```

### Masonry Layout

Creates a Pinterest-style layout:
```
[smg_album id="123" layout="masonry"]
```

## Google Drive Integration

### Setting Up Google Drive Images

1. Upload image to Google Drive
2. Right-click > Share > Change to "Anyone with the link"
3. Copy the share link (looks like: `https://drive.google.com/file/d/1ABC...XYZ/view`)
4. In album editor, paste into "Google Drive URL" field

### Supported URL Formats

The plugin supports these Google Drive URL formats:
- `https://drive.google.com/file/d/FILE_ID/view`
- `https://drive.google.com/open?id=FILE_ID`
- Direct file IDs

## Advanced Usage

### Multiple Albums on One Page

You can display multiple albums:
```
[smg_album id="123" columns="3"]
[smg_album id="456" layout="slider"]
```

### Album Shortcode Parameters

| Parameter | Values | Default | Description |
|-----------|--------|---------|-------------|
| `id` | number | required | Album post ID |
| `layout` | grid, slider, masonry | grid | Display layout |
| `columns` | 1-6 | 3 | Number of columns (grid layout only) |

## Tips & Best Practices

### Image Optimization
- Upload web-optimized images (under 500KB each)
- Use 1200px wide images for best results
- Consider using Google Drive for very large galleries

### Organizing Albums
- Use descriptive titles and descriptions
- Add photo titles for important context
- Use descriptions for longer stories or details
- Organize albums by event, date, or category

### Performance
- Keep albums under 50 images for best performance
- For larger galleries, consider splitting into multiple albums
- Google Drive images may load slower than local Media Library

### Accessibility
- Always add descriptive titles to images
- Use meaningful album descriptions
- Avoid relying only on images to convey information

## Common Use Cases

### Photography Portfolio
```
[smg_album id="123" layout="masonry" columns="3"]
```

### Event Gallery
```
[smg_album id="456" layout="grid" columns="4"]
```

### Product Showcase
```
[smg_album id="789" layout="slider"]
```

## Troubleshooting

### Images Not Showing
- Check if album ID is correct
- Verify images are uploaded to Media Library or Google Drive link is valid
- Ensure Google Drive sharing is set to "Anyone with the link"

### Drag and Drop Not Working
- Make sure JavaScript is enabled in browser
- Check browser console for errors
- Try refreshing the page

### Lightbox Not Opening
- Ensure jQuery is loaded on your site
- Check for JavaScript conflicts with theme/plugins
- Try disabling other gallery plugins temporarily

## Need Help?

For more information, see the main [README.md](README.md) file.
