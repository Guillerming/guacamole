# PHP Coding Style Guide

This project adheres to a strict coding style enforced by `php-cs-fixer` and `phpstan`. Below are the key conventions to follow.

## 🔧 General Rules

- **Strict Types**: All PHP files **MUST** declare strict types at the very top.
  ```php
  declare(strict_types=1);
  ```
- **Indentation**: Use **4 spaces** for indentation. No tabs.
- **Line Length**: Soft limit of 120 characters.
- **Arrays**: Always use short array syntax `[]`.
  ```php
  $data = ['key' => 'value']; // Good
  $data = array('key' => 'value'); // Bad
  ```
- **Quotes**: Use **single quotes** `'` for strings unless variable interpolation is needed.

## 🏗️ Classes & Control Structures

### Braces
This project uses **K&R style** (opening brace on the **same line**) for classes, methods, and control structures. This deviates from standard PSR-12 for classes/methods.

```php
class MyClass {
    public function myMethod(): void {
        if ($condition) {
            // ...
        }
    }
}
```

### Imports
- **Unused Imports**: Must be removed.
- **Ordering**: Imports must be ordered alphabetically.
- **Global Namespace**: Classes from the global namespace must be imported or fully qualified (though importing is preferred for clarity).

## 🛡️ Type Safety

- **Typed Properties**: Use typed properties whenever possible (PHP 7.4+).
- **Return Types**: All methods and functions **MUST** specify a return type. Use `void` if nothing is returned.
- **Strict Params**: Strict comparison is enforced where applicable.

## 📝 Documentation

- **No Comments**: Avoid adding comments in the code unless absolutely necessary for complex logic. The code should be self-documenting.
- **PHPDoc**: Use PHPDoc only when type hints are insufficient (e.g., `array<string, User>`).

## 🛠️ Enforcement

Run the following commands to check and fix code style:

```bash
# Fix style issues automatically
npm run format:php

# Analyze code for static errors
npm run lint:php
```
