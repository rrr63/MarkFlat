---
title: 'Doc'
menu_order: 2
show_in_menu: true
---
# Doc

A lightweight, Symfony-based CMS that lets you create beautiful blogs and websites using Markdown files. No database required!

## ✨ Features

- 📝 Write content in Markdown
- 🗺️ Interactive maps with Leaflet
- 🏷️ Tag-based organization
- 📊 View counter for posts
- 🎨 Tailwind CSS styling
- 📱 Responsive design
- 🔍 Full-text search
- 📂 File-based (no database needed)
- 🚀 Fast and lightweight

## 🚀 Quick Start

### Prerequisites

- PHP 8.3+
- Composer
- Web server (Apache/Nginx)

### Installation

1. Clone the repository:
```bash
git clone https://github.com/auvernhatinternet/markflat.git
cd markflat
```

2. Install dependencies:
```bash
composer install
```

5. Configure your web server to point to the `public` directory

### Creating Content

Create your posts in the `posts` directory using Markdown files (`.md`). Each post should have a YAML front matter:

```markdown
---
title: 'My First Post'
date: '2025-03-11'
author: 'John Doe'
description: 'A brief description of your post'
tags: [programming, php]
---

Your content here in Markdown format...
```

## 🗺️ Interactive Maps

MarkFlat CMS includes support for interactive maps powered by Leaflet. You can embed maps directly in your Markdown content using a simple syntax:

### Basic Map Usage

Add a map to your Markdown content using the following syntax:

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "height": "400px",
  "width": "100%",
  "markers": [
    {"lat": 48.8566, "lng": 2.3522, "popup": "Tour Eiffel"}
  ]
}
[/MAP]
```
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "height": "400px",
  "width": "100%",
  "markers": [
    {"lat": 48.8566, "lng": 2.3522, "popup": "Tour Eiffel"}
  ]
}
[/MAP]

### Map Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `center` | Object | `{"lat": 48.8566, "lng": 2.3522}` | Map center coordinates |
| `zoom` | Number | `13` | Initial zoom level (1-19) |
| `height` | String | `"400px"` | Map container height |
| `width` | String | `"100%"` | Map container width |
| `markers` | Array | `[]` | Array of map markers |

### Marker Options

Each marker in the `markers` array can have:

| Option | Type | Description |
|--------|------|-------------|
| `lat` | Number | Marker latitude |
| `lng` | Number | Marker longitude |
| `popup` | String | Optional popup text |

### Multiple Maps

You can include multiple maps in a single Markdown file. Each map will have a unique identifier:

```markdown
## Paris Landmarks

[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "markers": [{"lat": 48.8566, "lng": 2.3522, "popup": "Tour Eiffel"}]
}
[/MAP]

## Notre-Dame Cathedral

[MAP]
{
  "center": {"lat": 48.8530, "lng": 2.3499},
  "zoom": 16,
  "markers": [{"lat": 48.8530, "lng": 2.3499, "popup": "Cathédrale Notre-Dame"}]
}
[/MAP]
```

### Styling

Maps are automatically styled to match your theme with:
- Rounded corners (`rounded-xl`)
- Shadow effects (`shadow-xl`)
- Responsive design
- Custom dimensions support

## 🔧 Configuration

The CMS can be configured through environment variables:

```env
MF_CMS_POSTS_PER_PAGE=10  # Number of posts per page
MF_CMS_PAGES_DIR=/pages
MF_CMS_POSTS_DIR=/posts
MF_CMS_SITE_NAME="CMS MarkFlat"
```

## 🎨 Themes

The CMS uses Tailwind CSS for styling. You can customize the look by:

1. Modifying the Twig templates in `templates/`
2. Adjusting the Tailwind configuration
3. Adding custom CSS in `public/css/`

## Theming System

MarkFlat CMS includes a flexible theming system that allows you to customize the appearance of your site through simple PHP configuration files.

### Using Themes

1. Set your desired theme in the `.env` file:
```env
MF_CMS_THEME=dark
```

2. Available themes are stored in `config/themes/` directory:
   - `default.php`: Light theme with a clean, professional look
   - `dark.php`: Dark theme optimized for low-light environments
   - `example.php`: Example theme showcasing advanced styling techniques

### Creating Custom Themes

1. Create a new PHP file in `config/themes/` directory (e.g., `config/themes/custom.php`)
2. Define your theme using Tailwind CSS classes:

```php
<?php
/**
 * My Custom Theme
 * Author: Your Name
 * Description: A brief description of your theme
 */

return [
    // Page background and text
    'body' => 'bg-gradient-to-br from-blue-50 to-purple-50 text-gray-800',
    
    // Navigation
    'nav' => 'bg-white/80 backdrop-blur-sm shadow-sm',
    'navLink' => 'text-gray-600 hover:text-blue-600 transition-colors',
    
    // Headers
    'header' => 'bg-white/50 backdrop-blur-sm',
    'headerTitle' => 'text-3xl font-bold text-blue-900',
    
    // Content containers
    'container' => 'bg-white/80 backdrop-blur-sm rounded-xl shadow-sm',
    'title' => 'text-2xl font-bold text-gray-900',
    'content' => 'prose prose-blue max-w-none',
    
    // Interactive elements
    'tag' => 'bg-blue-50 text-blue-700 hover:bg-blue-100',
    'link' => 'text-blue-600 hover:text-blue-800',
    
    // Metadata
    'date' => 'text-gray-500',
    'views' => 'text-gray-500',
    
    // Pagination
    'pagination' => 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50',
    'paginationActive' => 'bg-blue-50 border-blue-300 text-blue-700',
    
    // Map
    'map' => 'rounded-xl shadow-xl',
    'mapPopup' => 'text-gray-800',
];
```

3. Set `MF_CMS_THEME=custom` in your `.env` file

### Theme Components

Each theme must define styles for these components:

| Component | Description | Example Classes |
|-----------|-------------|-----------------|
| `body` | Main page background and text | `bg-gray-50 text-gray-800` |
| `nav` | Navigation bar styling | `bg-white shadow-md` |
| `navLink` | Navigation links | `text-gray-600 hover:text-gray-900` |
| `header` | Page header | `bg-white shadow` |
| `headerTitle` | Main page title | `text-3xl font-bold text-gray-900` |
| `container` | Content containers | `bg-white rounded-lg shadow-sm` |
| `title` | Post titles | `text-2xl font-bold text-gray-900` |
| `content` | Post content | `prose prose-gray max-w-none` |
| `tag` | Tag styling | `bg-gray-100 text-gray-700 hover:bg-gray-200` |
| `link` | Link styling | `text-blue-600 hover:text-blue-800` |
| `date` | Metadata text | `text-gray-500` |
| `views` | View counter | `text-gray-500` |
| `pagination` | Navigation | `bg-white border-gray-300 hover:bg-gray-50` |
| `paginationActive` | Active page | `bg-blue-50 border-blue-500 text-blue-600` |
| `map` | Map container | `rounded-xl shadow-xl` |
| `mapPopup` | Map popup content | `text-gray-800` |

### Advanced Theme Features

1. **Gradients and Transparency**:
```php
'body' => 'bg-gradient-to-br from-indigo-50 to-pink-50',
'nav' => 'bg-white/80 backdrop-blur-sm',
```

2. **Transitions and Hover Effects**:
```php
'link' => 'text-blue-600 hover:text-blue-800 transition-colors',
'tag' => 'bg-blue-50 hover:bg-blue-100 transition-all',
```

3. **Typography with Tailwind Prose**:
```php
'content' => 'prose prose-lg prose-blue max-w-none',
```

4. **Modern Glass Effect**:
```php
'container' => 'bg-white/80 backdrop-blur-sm border border-white/20',
```

5. **Map Styling**:
```php
'map' => 'rounded-xl shadow-xl backdrop-blur-sm',
'mapPopup' => 'text-gray-800 font-medium',
```

### Contributing Themes

To contribute a new theme:

1. Fork the repository
2. Create your theme file in `config/themes/`
3. Test your theme by setting it in `.env`
4. Submit a pull request with:
   - Your theme file
   - A screenshot of your theme
   - A brief description of the theme's style
   - Any special features or effects used

### Theme Best Practices

1. **Consistency**: Use a consistent color palette throughout your theme
2. **Accessibility**: Ensure sufficient contrast between text and background
3. **Responsiveness**: Test your theme on different screen sizes
4. **Documentation**: Add comments to explain complex styling choices
5. **Performance**: Avoid excessive use of heavy effects like shadows and blurs

## 🔍 Features in Detail

### Post Management
- Automatic post listing with pagination
- Tag-based filtering
- View counter for each post
- Markdown to HTML conversion with Tailwind CSS classes

### URL Structure
- `/` - Home page with latest posts
- `/posts/{slug}` - Individual post view
- `/tags/{tag}` - Posts filtered by tag
- `/latest/{limit}` - Latest posts feed

## 🧪 Testing

Run the test suite:

```bash
composer test
or
vendor/bin/phpunit
```

The project uses GitHub Actions for continuous integration, automatically running tests on every push and pull request.

## 📦 Directory Structure

```
markflat/
├── posts/              # Your Markdown posts
├── public/             # Web root
├── src/                # Source code
│   ├── Controller/     # Route controllers
│   ├── Post/           # Post management
│   └── Service/        # Additional services
├── templates/          # Twig templates
└── tests/              # Test suite
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with [Symfony](https://symfony.com/)
- Styled with [Tailwind CSS](https://tailwindcss.com/)
- Markdown parsing by [league/commonmark](https://commonmark.thephpleague.com/)
