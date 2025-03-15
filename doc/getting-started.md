# Getting Started with MarkFlat CMS

This guide will help you set up and run MarkFlat CMS on your system.

## System Requirements

- PHP 8.3 or higher
- Composer
- Node.js and npm (for TailwindCSS)
- Web server (Apache/Nginx) or Symfony CLI

## Installation Methods

Clone the repository:
```bash
git clone https://github.com/auvernhatinternet/markflat.git
cd markflat
```

### Using Docker (Recommended)

The easiest way to get started is using Docker:

1. Build the image:
```bash
# Build the image
docker build -t markflat-app .

# Run the container
docker run -dit --name markflat-app -p 8080:80 -v "$PWD":/var/www/html markflat-app
```

Your site will be available at `http://localhost:8080`

### Manual Installation

1. Install PHP dependencies:
```bash
composer install
```

2. Install Node.js dependencies and build assets:
```bash
npm install
npm run build
```

3. Configure your environment:
```bash
# Copy the example environment file
cp .env.example .env

# Edit the .env file with your settings
MF_CMS_SITE_NAME="My MarkFlat Site"
MF_CMS_POSTS_PER_PAGE=10
MF_CMS_THEME=default
```

4. Set up your web server:

#### Using Symfony CLI
```bash
symfony serve
```

#### Using Apache
Configure your virtual host to point to the `public/` directory:

```apache
<VirtualHost *:80>
    ServerName markflat.local
    DocumentRoot /path/to/markflat/public
    
    <Directory /path/to/markflat/public>
        AllowOverride All
        Require all granted
        FallbackResource /index.php
    </Directory>
</VirtualHost>
```

#### Using Nginx
```nginx
server {
    listen 80;
    server_name markflat.local;
    root /path/to/markflat/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }
}
```

## Directory Structure

```
markflat/
├── assets/             # Frontend assets (CSS, JS)
├── bin/                # Custom commands
├── config/             
│   └── themes/         # Theme configurations
├── content/            
│   ├── elements/       # Static elements
│   ├── pages/          # Static pages
│   └── posts/          # Blog posts
├── public/             # Web root
├── src/                # PHP source code
├── templates/          # Twig templates
└── translations/       # Translation files
```

## First Steps After Installation

1. **Create your first post**:
   Create a new file in `content/posts/` with a `.md` extension:

```markdown
---
title: 'My First Post'
date: '2025-03-15'             
author: 'Your Name'               
views: 0                             
description: 'A brief description'      
tags: [first, blog]                 
---

Welcome to my first post!
```

2. **Customize your theme**:
   Edit `.env` to change your theme:
```bash
MF_CMS_THEME=modern-dark  # or any theme from config/themes/
```

3. **Add a static page**:
   Create a new file in `content/pages/`:
```markdown
# About

About our website...
```

## Next Steps

- Learn more about [Content Management](./content-management.md)
- Explore the [Theming System](./theming.md)
- Check out the [Maps Integration](./maps.md)
