# Contributing to MarkFlat CMS

Thank you for your interest in contributing to MarkFlat CMS! This guide will help you understand how to contribute effectively to the project.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to the project maintainers.

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Node.js and npm
- Git

### Setting Up Development Environment

1. Fork the repository
2. Clone your fork:
```bash
git clone https://github.com/YOUR-USERNAME/markflat.git
cd markflat
```

3. Install dependencies:
```bash
composer install
npm install
```

4. Set up your development environment:
```bash
cp .env.example .env.local
```

5. Run tests to ensure everything is working:
```bash
composer test
```

## Development Workflow

### Branches

- `main`: Production-ready code
- Feature branches: `feature/your-feature-name`
- Bug fix branches: `fix/issue-description`

### Coding Standards

MarkFlat follows PSR standards and uses PHP-CS-Fixer for code style enforcement:

```bash
# Run PHP-CS-Fixer
php bin/beforePR.php

# Check coding standards
vendor/bin/phpstan analyse -l 6 tests src
```

### Testing

We use PHPUnit for testing. All new features should include tests:

```bash
# Run all tests
vendor/bin/phpunit
```

### Documentation

- Update documentation for any new features
- Keep code comments clear and helpful
- Follow PHPDoc standards for docblocks

## Making Contributions

### Pull Request Process

1. Create a new branch from `main`:
```bash
git checkout main
git pull origin main
git checkout -b feature/your-feature
```

2. Make your changes:
- Write clean, documented code
- Add tests for new features
- Update documentation as needed

3. Commit your changes:
```bash
git add .
git commit -m "feat: add new feature"
```

We follow [Conventional Commits](https://www.conventionalcommits.org/) specification:
- `feat:` New features
- `fix:` Bug fixes
- `docs:` Documentation changes
- `style:` Code style changes
- `refactor:` Code refactoring
- `test:` Adding or updating tests
- `chore:` Maintenance tasks

4. Push to your fork:
```bash
git push origin feature/your-feature
```

5. Create a Pull Request:
- Use a clear title and description
- Reference any related issues
- Include screenshots for UI changes
- Ensure all tests pass
- Request review from maintainers

### Issue Reporting

When creating an issue:

1. Use the issue template
2. Include detailed steps to reproduce
3. Provide system information
4. Add relevant logs or screenshots
5. Tag appropriately

## Development Guidelines

### Architecture

MarkFlat follows a clean architecture approach:

```
src/
├── Controller/     # HTTP request handlers
├── Service/        # Business logic
├── Post/          # Theme configuration
├── Twig/          # Theme configuration
```

### Best Practices

1. **SOLID Principles**
   - Single Responsibility
   - Open/Closed
   - Liskov Substitution
   - Interface Segregation
   - Dependency Inversion

2. **Clean Code**
   - Meaningful names
   - Small functions
   - DRY (Don't Repeat Yourself)
   - KISS (Keep It Simple, Stupid)

3. **Security**
   - Validate all input
   - Escape output
   - Follow OWASP guidelines
   - Use prepared statements

4. **Performance**
   - Optimize database queries
   - Cache when appropriate
   - Profile code performance
   - Follow Symfony best practices

## Feature Development

### Adding New Features

1. **Discussion**
   - Open an issue for discussion
   - Get feedback from maintainers
   - Plan implementation approach

2. **Implementation**
   - Follow coding standards
   - Write comprehensive tests
   - Update documentation
   - Add feature flag if needed

3. **Review**
   - Self-review your code
   - Address review comments
   - Update based on feedback

### Theme Development

When creating new themes:

1. Follow the theme structure
2. Use TailwindCSS classes
3. Ensure responsive design
4. Test across browsers

### Plugin Development

Coming soon

## Release Process

### Version Numbers

We follow [Semantic Versioning](https://semver.org/):

- MAJOR version for incompatible API changes
- MINOR version for new functionality
- PATCH version for bug fixes

### Release Checklist

1. Update CHANGELOG.md
2. Update version numbers
3. Run full test suite
4. Create release branch
5. Tag release
6. Update documentation

## Getting Help

- Join our community discussions
- Check existing issues
- Read the documentation
- Contact maintainers

## Recognition

Contributors are listed in CONTRIBUTORS.md. We appreciate:

- Code contributions
- Documentation improvements
- Bug reports
- Feature suggestions
- Community support

Thank you for contributing to MarkFlat CMS!
