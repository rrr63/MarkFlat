# Maps Integration in MarkFlat CMS

MarkFlat CMS includes a powerful maps integration powered by Leaflet.js, allowing you to embed interactive maps directly in your Markdown content.

## Basic Usage

Add a map to your Markdown content using the following syntax:

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "height": "400px",
  "markers": [
    {"lat": 48.8566, "lng": 2.3522, "popup": "Paris"}
  ]
}
[/MAP]
```

## Map Configuration Options

### Basic Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `center` | Object | `{"lat": 48.8566, "lng": 2.3522}` | Map center coordinates |
| `zoom` | Number | `13` | Initial zoom level (1-19) |
| `height` | String | `"400px"` | Map container height |
| `width` | String | `"100%"` | Map container width |
| `markers` | Array | `[]` | Array of map markers |

### Advanced Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `maxZoom` | Number | `19` | Maximum zoom level |
| `minZoom` | Number | `1` | Minimum zoom level |
| `zoomControl` | Boolean | `true` | Show zoom controls |
| `scrollWheelZoom` | Boolean | `true` | Enable zoom with mouse wheel |
| `dragging` | Boolean | `true` | Enable map dragging |

## Marker Configuration

Each marker in the `markers` array can have these properties:

```json
{
  "lat": 48.8566,
  "lng": 2.3522,
  "popup": "Marker popup text",
  "title": "Hover tooltip text",
  "icon": "default",  // or "custom"
  "customIcon": {     // Only if icon: "custom"
    "iconUrl": "/path/to/icon.png",
    "iconSize": [25, 41],
    "iconAnchor": [12, 41],
    "popupAnchor": [1, -34]
  }
}
```

## Examples

### Basic Map with Single Marker

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "markers": [
    {
      "lat": 48.8566,
      "lng": 2.3522,
      "popup": "Welcome to Paris!"
    }
  ]
}
[/MAP]
```

### Multiple Markers

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 13,
  "markers": [
    {
      "lat": 48.8566,
      "lng": 2.3522,
      "popup": "Eiffel Tower"
    },
    {
      "lat": 48.8530,
      "lng": 2.3499,
      "popup": "Notre-Dame Cathedral"
    }
  ]
}
[/MAP]
```

### Custom Styling

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "height": "600px",
  "width": "800px",
  "markers": [
    {
      "lat": 48.8566,
      "lng": 2.3522,
      "popup": "Custom styled marker",
      "icon": "custom",
      "customIcon": {
        "iconUrl": "/images/custom-marker.png",
        "iconSize": [25, 41],
        "iconAnchor": [12, 41],
        "popupAnchor": [1, -34]
      }
    }
  ]
}
[/MAP]
```

## Advanced Features

### Custom Controls

Add custom controls to your map:

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "controls": {
    "scale": true,
    "fullscreen": true,
    "layers": true
  }
}
[/MAP]
```

### Multiple Map Layers

Use different map layers:

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "layers": [
    {
      "name": "Streets",
      "url": "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      "attribution": "© OpenStreetMap contributors"
    },
    {
      "name": "Satellite",
      "url": "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
      "attribution": "© Esri"
    }
  ]
}
[/MAP]
```

## Styling Integration

Maps automatically inherit your theme's styling:

- Rounded corners (`rounded-xl`)
- Shadow effects (`shadow-xl`)
- Responsive container
- Theme-aware controls

## Best Practices

1. **Performance**
   - Don't load too many markers at once
   - Use appropriate zoom levels
   - Optimize custom marker images

2. **Accessibility**
   - Provide meaningful popup content
   - Use descriptive titles
   - Ensure keyboard navigation works

3. **Mobile Responsiveness**
   - Test on different screen sizes
   - Use responsive dimensions
   - Enable touch interactions

4. **Content Organization**
   - Group related markers
   - Use consistent popup styling
   - Maintain clean JSON structure

## Troubleshooting

Common issues and solutions:

1. **Map not displaying**
   - Check coordinates format
   - Verify JSON syntax
   - Ensure Leaflet.js is loaded

2. **Custom markers not showing**
   - Verify image path
   - Check icon dimensions
   - Confirm file permissions

3. **Performance issues**
   - Reduce number of markers
   - Optimize marker images
   - Use appropriate zoom levels

## Next Steps

- Learn about [Configuration](./configuration.md)
- Explore [Contributing](./contributing.md)
- Check out [Theming System](./theming.md)
