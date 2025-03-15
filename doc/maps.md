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

## Next Steps

- Learn about [Configuration](./configuration.md)
- Explore [Contributing](./contributing.md)
- Check out [Theming System](./theming.md)
