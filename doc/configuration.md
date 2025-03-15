# Configuration Guide for MarkFlat CMS

This guide explains how to configure MarkFlat CMS to match your needs. MarkFlat uses environment variables and configuration files to customize its behavior.

## Configuration Files

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


## Next Steps

- Explore [Contributing](./contributing.md)
- Learn about [Content Management](./content-management.md)
- Check out [Theming System](./theming.md)
