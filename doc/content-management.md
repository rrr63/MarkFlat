# Content Management in MarkFlat CMS

MarkFlat CMS uses a simple yet powerful content management system based on Markdown files. This guide explains how to create and organize your content effectively.

## Content Types

MarkFlat supports two main types of content:

- **Posts**: Blog posts or articles, typically organized chronologically
- **Pages**: Static content like About, Contact, or other standalone pages

## File Structure

```
content/
├── elements/       # Static elements
│   └── home_hero.md
├── pages/          # Static pages
│   ├── about.md
│   └── contact.md
└── posts/          # Blog posts
    ├── 2025-03-15-first-post.md
    ├── 2025-03-15-first-post.md
    └── 2025-03-15-second-post.md
```

## Front Matter

All content files must start with YAML front matter, enclosed by `---`:

### For Posts

```markdown
---
title: 'My First Post'
date: '2025-03-15'
author: 'Your Name'
description: 'A brief description for SEO and previews'
tags: [programming, php]
image: '/assets/images/post-cover.jpg'  # Optional
draft: false  # Optional, defaults to false
---

Your content here...
```

### For Pages

```markdown
---
title: 'About Us'
menu_order: 1  # Order in navigation menu
show_in_menu: true  # Whether to show in main navigation
---

Page content here...
```

## Writing Content

### Markdown Support

MarkFlat supports standard Markdown syntax plus several extensions:

- Tables
- Fenced code blocks with syntax highlighting
- Task lists
- Footnotes
- Definition lists
- Automatic URL linking

### Code Blocks

```markdown
\```php
<?php
echo "Hello, World!";
\```
```

### Tables

```markdown
| Header 1 | Header 2 |
|----------|----------|
| Cell 1   | Cell 2   |
```

### Interactive Maps

MarkFlat includes special syntax for embedding maps:

```markdown
[MAP]
{
  "center": {"lat": 48.8566, "lng": 2.3522},
  "zoom": 14,
  "markers": [
    {"lat": 48.8566, "lng": 2.3522, "popup": "Paris"}
  ]
}
[/MAP]
```

## Content Organization

### Tags

Tags help organize and categorize your content:

- Use lowercase for consistency
- Separate multiple words with hyphens
- Keep tags concise and relevant
- Reuse existing tags when possible

### URL Structure

Posts and pages automatically generate clean URLs:

- Posts: `/posts/title-of-post`
- Pages: `/page-name`

## Advanced Features

### Draft Posts

Add `draft: true` to the front matter to prevent a post from being published:

```markdown
---
title: 'Work in Progress'
date: '2025-03-15'
draft: true
---
```

### View Counter

Each post automatically tracks views. The count is stored in a file-based system, maintaining the database-free philosophy.

### Search Integration

Content is automatically indexed for the built-in search feature. The search index includes:

- Title
- Description
- Content
- Tags
- Author

### Image Handling

Images can be referenced in two ways:

1. **External Images**:
```markdown
![Alt text](https://example.com/image.jpg)
```

2. **Local Images**:
```markdown
![Alt text](/assets/images/local-image.jpg)
```

Place local images in the `public/images/` directory.

## Best Practices

1. **File Naming**:
   - Use lowercase
   - Replace spaces with hyphens
   - Include the date for posts: `YYYY-MM-DD-title.md`

2. **Content Organization**:
   - Keep related content together
   - Use consistent tags
   - Maintain a clear hierarchy in pages

3. **SEO Optimization**:
   - Always include descriptions
   - Use meaningful titles
   - Add relevant tags
   - Include alt text for images

4. **Performance**:
   - Optimize images before uploading
   - Use relative links when possible
   - Keep front matter concise

## Next Steps

- Learn about the [Theming System](./theming.md)
- Explore [Maps Integration](./maps.md)
- Check out [Configuration](./configuration.md) options
