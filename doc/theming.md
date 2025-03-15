# Theming System in MarkFlat CMS

MarkFlat CMS features a flexible theming system powered by TailwindCSS. This guide explains how to use and customize themes.

## Theme Structure

Themes are defined in PHP configuration files located in the `config/themes/` directory. Each theme file returns an array of TailwindCSS classes for different UI components.

### Default Theme Structure

```php
<?php
// config/themes/default.php

return [
    // Page background and text
    'body' => 'bg-white text-gray-800',
    
    // Navigation
    'nav' => 'bg-white/80 backdrop-blur-sm shadow-sm',
    'navLink' => 'text-gray-600 hover:text-blue-600',
    
    // Headers
    'header' => 'bg-white/50',
    'headerTitle' => 'text-3xl font-bold text-gray-900',
    
    // Content
    'content' => 'prose prose-lg max-w-none',
    'contentLink' => 'text-blue-600 hover:text-blue-800',
    
    // Cards
    'card' => 'bg-white rounded-xl shadow-md',
    'cardHeader' => 'p-4 border-b',
    'cardContent' => 'p-4',
    
    // Buttons
    'button' => 'px-4 py-2 rounded-lg',
    'buttonPrimary' => 'bg-blue-600 text-white hover:bg-blue-700',
    'buttonSecondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300',
];
```

## Available Themes

MarkFlat comes with several pre-built themes:

- `default.php`: Light theme with a clean, professional look
- `dark.php`: Dark theme optimized for low-light environments
- `modern-dark.php`: Modern dark theme with gradient accents
- `sunrise.php`: Light theme with warm colors

## Theme Selection

Set your desired theme in the `.env` file:

```env
MF_CMS_THEME=dark
```

## Creating Custom Themes

1. Create a new PHP file in `config/themes/` (e.g., `custom.php`)
2. Define your theme using TailwindCSS classes:

```php
<?php
// config/themes/custom.php

return [
    'body' => 'bg-gradient-to-br from-blue-50 to-purple-50 text-gray-800',
    'nav' => 'bg-white/80 backdrop-blur-sm border-b border-gray-200',
    // ... define other elements
];
```

### Theme Components

Here's a complete list of themeable components:

#### Layout Components
- `body`: Main page background and text
- `container`: Main content container
- `wrapper`: Inner content wrapper

#### Navigation
- `nav`: Main navigation bar
- `navLink`: Navigation links
- `navLinkActive`: Active navigation link
- `navDropdown`: Dropdown menu
- `navDropdownItem`: Dropdown menu items

#### Headers
- `header`: Page header section
- `headerTitle`: Main title
- `headerSubtitle`: Subtitle text

#### Content
- `content`: Main content area
- `contentLink`: Links within content
- `contentHeading`: Content headings
- `contentList`: Lists in content
- `contentCode`: Code blocks
- `contentQuote`: Blockquotes

#### Cards
- `card`: Card container
- `cardHeader`: Card header
- `cardContent`: Card content area
- `cardFooter`: Card footer
- `cardTitle`: Card title
- `cardMeta`: Card metadata

#### Buttons
- `button`: Base button style
- `buttonPrimary`: Primary action button
- `buttonSecondary`: Secondary action button
- `buttonDanger`: Danger/delete button

#### Forms
- `input`: Text input fields
- `select`: Select dropdowns
- `checkbox`: Checkbox inputs
- `radio`: Radio buttons
- `label`: Form labels
- `formGroup`: Form field groups

#### Special Elements
- `tag`: Tag elements
- `badge`: Badge elements
- `alert`: Alert messages
- `alertSuccess`: Success alerts
- `alertError`: Error alerts
- `alertWarning`: Warning alerts

## Using TailwindCSS Features

### Responsive Design

Use Tailwind's responsive prefixes:

```php
'card' => 'w-full md:w-1/2 lg:w-1/3 p-4'
```

### Dark Mode

Support both light and dark modes:

```php
'body' => 'bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200'
```

### Hover and Focus States

Add interactive states:

```php
'button' => 'bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500'
```

## Theme Best Practices

1. **Consistency**
   - Use a consistent color palette
   - Maintain spacing rhythm
   - Keep interactive elements consistent

2. **Accessibility**
   - Ensure sufficient color contrast
   - Use semantic HTML elements
   - Support keyboard navigation

3. **Performance**
   - Use TailwindCSS's purge feature
   - Avoid excessive use of custom properties
   - Keep themes modular and reusable

4. **Maintainability**
   - Document color schemes and variables
   - Group related styles together
   - Use meaningful component names

## Advanced Theming

### Custom Colors

Define custom colors in `tailwind.config.js`:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        'brand': {
          50: '#f0f9ff',
          // ... other shades
          900: '#0c4a6e',
        },
      },
    },
  },
}
```

### Custom Components

Create reusable component classes in your theme:

```php
'customCard' => 'rounded-xl shadow-lg bg-white dark:bg-gray-800 p-6'
```

## Next Steps

- Explore [Maps Integration](./maps.md)
- Learn about [Configuration](./configuration.md)
- Check out [Contributing](./contributing.md) guidelines
