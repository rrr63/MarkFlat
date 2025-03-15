# 🌟 MarkFlat CMS

[![PHP Tests](https://github.com/auvernhatinternet/markflat/actions/workflows/php.yml/badge.svg)](https://github.com/auvernhatinternet/markflat/actions/workflows/php.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://www.php.net)
[![Symfony Version](https://img.shields.io/badge/Symfony-7.0%2B-purple.svg)](https://symfony.com)

> 🚀 A modern, file-based CMS built with Symfony and TailwindCSS. Create beautiful websites without the complexity of a database!

## 🎯 Why MarkFlat?

MarkFlat is designed for developers and content creators who want a **simple**, **fast**, and **modern** CMS without the overhead of a database. Write your content in Markdown, style it with TailwindCSS, and deploy it anywhere!

### ✨ Key Features

- 📝 **File-based**: No database required - all content lives in Markdown files
- 🎨 **Modern Stack**: Built with Symfony 7+ and TailwindCSS
- 🗺️ **Interactive Maps**: Built-in Leaflet.js integration
- 🎯 **Simple & Fast**: Lightweight and blazing fast by design
- 🔍 **Full-text Search**: Find content instantly
- 📱 **Responsive**: Beautiful on all devices
- 🌙 **Dark Mode**: Built-in dark mode support
- 🏷️ **Tag System**: Organize content effortlessly

## 🚀 Quick Start

```bash
# Clone the repository
git clone https://github.com/auvernhatinternet/markflat.git
cd markflat

# Using Docker (recommended)
docker build -t markflat-app .
docker run -dit --name markflat-app -p 8080:80 -v "$PWD":/var/www/html markflat-app

# Or manual installation
composer install
npm install
npm run build
```

## 📖 Documentation

Detailed documentation is available in the [/doc](./doc) directory:

- 📚 [Introduction](./doc/introduction.md)
- 🏁 [Getting Started](./doc/getting-started.md)
- 📝 [Content Management](./doc/content-management.md)
- 🎨 [Theming System](./doc/theming.md)
- 🗺️ [Maps Integration](./doc/maps.md)
- ⚙️ [Configuration](./doc/configuration.md)
- 🤝 [Contributing](./doc/contributing.md)

## 🌟 Showcase

![MarkFlat Screenshot](https://raw.githubusercontent.com/auvernhatinternet/markflat/main/doc/assets/screenshot.png)

## 🤝 Contributing

We love your input! Check out our [Contributing Guide](./doc/contributing.md) to get started.

- 🐛 Report bugs by [opening an issue](https://github.com/auvernhatinternet/markflat/issues/new)
- 💡 Propose new features
- 📝 Improve documentation
- 🔧 Submit pull requests

## 💖 Support

- ⭐ Star this repo
- 📢 Share with your friends

## 📄 License

MarkFlat is open-source software licensed under the MIT license.

## 🙏 Acknowledgments

- Built with [Symfony](https://symfony.com)
- Styled with [TailwindCSS](https://tailwindcss.com)
- Maps powered by [Leaflet](https://leafletjs.com)
- Icons by [Heroicons](https://heroicons.com)

---

<p align="center">
  Made with ❤️ by the MarkFlat team
</p>
