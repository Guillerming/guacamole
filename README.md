# Guacamole

Guacamole is a modern, containerized PHP8.4 + Vue|React framework.

It is built with PHP 8.4 (FPM), Nginx, and PostgreSQL, and it can integrate a TypeScript-based SPA (React or Vue).

The core idea behind this approach is to offer a stable as muchas non-opinionated way to do stuff while enforcing strict types across different languages.

## Features

*   PHP 8.4 (FPM) backend
*   Nginx as web server
*   PostgreSQL database
*   Composer for dependency management
*   Static analysis with PHPStan (max level)
*   Code formatting with PHP CS Fixer (strict rules)
*   Pre-commit hook for linting and formatting
*   Dockerized development environment
*   Environment variable management with Dotenv

## Getting Started

### Prerequisites

*   Docker & Docker Compose
*   Composer (locally for development tools)
*   Git

### Setup

1.  Clone the repository
2.  Copy the environment example file and edit as needed:
```
cp .env.example .env
# Edit .env with your preferred settings
```
3.  Install dependencies and initialize the project (this will also install git hooks and start the containers):
```
npm run initial
```


### Usage

*   Run `npm run dev` at the project root dir
*   Access the app at: https://localhost:8443

### Git Hooks

A pre-commit hook is automatically installed to enforce code style and static analysis before every commit. If the checks fail, the commit will be aborted.

### SPA Integration

You can add a TypeScript-based SPA (React, Vue) inside `src/App/UI` or another directory. Configure Nginx and PHP to serve the SPA for specific routes as needed.

## License

MIT