# Contributing to Karacraft Logman

Thank you for your interest in contributing to Karacraft Logman!

## Development Setup

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Run tests:**
   ```bash
   php vendor/bin/pest  # or phpunit for PHPUnit tests
   ```

3. **Create a feature branch:**
   ```bash
   git checkout -b feature-name
   ```

4. **Follow coding standards:**
   - Use PSR-12 coding standard
   - Keep pull request changes minimal and focused
   - Add/update tests for new functionality
   - Update documentation (README, CHANGELOG) when changing behavior

## Pull Request Checklist

Before submitting a PR, please ensure:

- [ ] Code follows PSR-12 style
- [ ] All tests pass (`php vendor/bin/pest`)
- [ ] Documentation is updated if needed
- [ ] Changelog is updated in `CHANGELOG.md` using Keep a Changelog format
- [ ] No sensitive data (API keys, passwords) is committed
- [ ] The change addresses a single concern or bug fix

## Reporting Bugs

1. Describe the expected behavior
2. Provide steps to reproduce
3. Include:
   - PHP version
   - Laravel version
   - Logman version (`composer show karacraft/logman`)
   - Full error message and stack trace (if any)
   - Relevant code snippets

## Security Issues

If you discover a security vulnerability, please email `kcraftpk@gmail.com` instead of opening an issue publicly.

## License

Karacraft Logman is licensed under the MIT License.