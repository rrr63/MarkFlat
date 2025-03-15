# Configuration Guide for MarkFlat CMS

This guide explains how to configure MarkFlat CMS to match your needs. MarkFlat uses environment variables and configuration files to customize its behavior.

## Environment Variables

### Core Settings

```env
# Site Configuration
MF_CMS_SITE_NAME="My MarkFlat Site"
MF_CMS_POSTS_PER_PAGE=10
MF_CMS_THEME=default

# Directory Configuration
MF_CMS_PAGES_DIR=/pages
MF_CMS_POSTS_DIR=/posts
MF_CMS_ASSETS_DIR=/assets

# Feature Toggles
MF_CMS_ENABLE_SEARCH=true
MF_CMS_ENABLE_VIEW_COUNTER=true
MF_CMS_ENABLE_TAGS=true
MF_CMS_ENABLE_MAPS=true

# Cache Configuration
MF_CMS_CACHE_ENABLED=true
MF_CMS_CACHE_DIR=/var/cache/markflat
```

### Advanced Settings

```env
# Performance
MF_CMS_CACHE_TTL=3600
MF_CMS_GZIP_ENABLED=true

# Security
MF_CMS_ALLOWED_HTML_TAGS="p,a,strong,em,code,pre,h1,h2,h3,h4,h5,h6,ul,ol,li"
MF_CMS_SANITIZE_HTML=true

# Maps Configuration
MF_CMS_MAPS_DEFAULT_LAT=48.8566
MF_CMS_MAPS_DEFAULT_LNG=2.3522
MF_CMS_MAPS_DEFAULT_ZOOM=13
```

## Configuration Files

### Symfony Configuration

MarkFlat uses Symfony's configuration system. Key configuration files are located in the `config/` directory:

```
config/
├── packages/
│   ├── cache.yaml
│   ├── markdown.yaml
│   ├── routing.yaml
│   └── security.yaml
├── routes/
│   ├── annotations.yaml
│   └── routes.yaml
├── services.yaml
└── bundles.php
```

### Theme Configuration

Themes are configured in PHP files under `config/themes/`:

```php
// config/themes/custom.php
return [
    'body' => 'bg-white dark:bg-gray-900',
    'nav' => 'bg-white/80 dark:bg-gray-800/80',
    // ... other theme elements
];
```

### Content Configuration

#### Front Matter Defaults

Define default front matter in `config/content_defaults.yaml`:

```yaml
posts:
  defaults:
    layout: post
    author: "Anonymous"
    show_date: true
    
pages:
  defaults:
    layout: page
    show_in_menu: false
    menu_order: 999
```

## Advanced Configuration

### Custom Routes

Create custom routes in `config/routes/custom.yaml`:

```yaml
blog_category:
    path: /blog/category/{category}
    controller: App\Controller\BlogController::category
    
custom_feed:
    path: /feed/{type}
    controller: App\Controller\FeedController::generate
    defaults:
        type: rss
```

### Cache Configuration

Configure caching in `config/packages/cache.yaml`:

```yaml
framework:
    cache:
        app: cache.adapter.filesystem
        default_lifetime: 3600
        directory: '%kernel.cache_dir%/pools'
```

### Security Configuration

Set up security rules in `config/packages/security.yaml`:

```yaml
security:
    enable_authenticator_manager: true
    providers:
        users_in_memory: { memory: null }
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            lazy: true
```

## Performance Optimization

### Caching Strategy

1. **Content Cache**
   ```env
   MF_CMS_CACHE_ENABLED=true
   MF_CMS_CACHE_TTL=3600
   ```

2. **Asset Cache**
   ```env
   MF_CMS_ASSETS_CACHE=true
   MF_CMS_ASSETS_VERSION=1.0
   ```

3. **Search Index Cache**
   ```env
   MF_CMS_SEARCH_CACHE=true
   MF_CMS_SEARCH_UPDATE_INTERVAL=3600
   ```

### Asset Optimization

1. **CSS Optimization**
   ```env
   MF_CMS_MINIFY_CSS=true
   MF_CMS_COMBINE_CSS=true
   ```

2. **JavaScript Optimization**
   ```env
   MF_CMS_MINIFY_JS=true
   MF_CMS_COMBINE_JS=true
   ```

## Development Configuration

### Debug Mode

Enable debug mode for development:

```env
APP_ENV=dev
APP_DEBUG=true
```

### Profiler Configuration

Configure the Symfony profiler:

```yaml
# config/packages/dev/web_profiler.yaml
web_profiler:
    toolbar: true
    intercept_redirects: false
```

## Production Configuration

### Recommended Settings

```env
APP_ENV=prod
APP_DEBUG=false
MF_CMS_CACHE_ENABLED=true
MF_CMS_GZIP_ENABLED=true
MF_CMS_MINIFY_ASSETS=true
```

### Server Configuration

1. **PHP Configuration**
   ```ini
   memory_limit = 256M
   max_execution_time = 30
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

2. **Opcache Settings**
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.max_accelerated_files=20000
   opcache.validate_timestamps=0
   ```

## Best Practices

1. **Environment Variables**
   - Use `.env.local` for local overrides
   - Keep sensitive data in `.env.local`
   - Version control `.env.example`

2. **Security**
   - Regularly update dependencies
   - Use HTTPS in production
   - Configure proper file permissions

3. **Performance**
   - Enable caching in production
   - Optimize assets
   - Use appropriate TTL values

4. **Development**
   - Use development environment
   - Enable debug toolbar
   - Configure proper logging

## Troubleshooting

Common configuration issues and solutions:

1. **Cache Issues**
   - Clear cache: `php bin/console cache:clear`
   - Check permissions
   - Verify cache directory

2. **Asset Problems**
   - Run `npm run build`
   - Check asset paths
   - Verify Webpack configuration

3. **Performance Issues**
   - Enable caching
   - Optimize assets
   - Check server resources

## Next Steps

- Explore [Contributing](./contributing.md)
- Learn about [Content Management](./content-management.md)
- Check out [Theming System](./theming.md)
