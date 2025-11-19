# Setup & Development Guide

## 📋 Prerequisites

Ensure you have the following installed on your machine:

- **Docker Desktop** (or Docker Engine + Compose)
- **Node.js** (v20 or higher)
- **npm** (usually comes with Node.js)
- **Composer** (PHP Dependency Manager)

## 🛠️ Initial Setup

1.  **Install Dependencies**:
    Run the setup script. This handles both PHP (Composer) and Node (npm) dependencies, generates SSL certificates, and builds the Docker containers.
    ```bash
    npm run setup
    ```
    *Note: This script runs `./generate-certs.sh` which may require `mkcert` or similar tools if not using the provided script's logic. Check the script if you encounter certificate issues.*

2.  **Environment Configuration**:
    The setup process should handle `.env` creation. If not, copy `.env.example` to `.env`:
    ```bash
    cp .env.example .env
    ```

## 🏃‍♂️ Running the Application

### Development Mode

To start the full development environment (PHP Backend + Vue Frontend):

```bash
npm run dev
```

This command runs two processes in parallel:
1.  `npm run dev:php`: Starts Docker containers (`timecentric-php`, `timecentric-nginx`).
2.  `npm run dev:spa`: Starts the Gulp watcher and Vite server.

Access the application at: **https://localhost**

### Docker Management

- **Stop containers**:
    ```bash
    docker-compose -f docker-compose.dev.yml down
    ```
- **View Logs**:
    ```bash
    npm run logs:php
    npm run logs:nginx
    ```

## 🧪 Code Quality Tools

The project includes several tools to maintain code quality.

- **Linting**:
    ```bash
    npm run lint      # Lints both PHP and JS/TS
    npm run lint:php  # PHPStan
    npm run lint:js   # ESLint
    ```

- **Formatting**:
    ```bash
    npm run format      # Formats both PHP and JS/TS
    npm run format:php  # PHP-CS-Fixer
    npm run format:js   # Prettier
    ```

## 📦 Building for Production

To build the frontend assets:

```bash
npm run build:spa
```

To run the production Docker environment:

```bash
docker-compose -f docker-compose.prod.yml up -d
```
