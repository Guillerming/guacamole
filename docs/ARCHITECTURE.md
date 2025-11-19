# Architecture Overview

Timecentric is built as a hybrid application, combining a robust PHP backend with a modern Vue 3 frontend.

## 🏗️ High-Level Structure

The application is divided into two main namespaces within `src/`:

1.  **`Guacamole`**: The core framework. It provides the foundational building blocks:
    - **Database**: Database connection and query builders.
    - **Router**: Request routing and dispatching.
    - **Http**: Request/Response handling.
    - **Helpers**: Utility functions.
    - **Middleware**: Request filtering and processing.

2.  **`Timecentric`**: The application layer. It contains the business logic and UI:
    - **Bootstrap.php**: Application initialization.
    - **UI**: Contains the frontend application (Vue components, Pages, Layouts).
    - **Enums/Helpers**: App-specific utilities.

## 🌐 Frontend (Vue 3 SPA)

The frontend is a Single Page Application (SPA) built with **Vue 3** and **TypeScript**.

- **Build Tool**: Vite (for fast development and bundling).
- **Styling**: SCSS (Sass).
- **Routing**: Vue Router (likely, or custom routing integration).
- **Location**: `src/Timecentric/UI`.

### Development Flow
- `npm run dev` starts the Vite dev server.
- `gulp` is used for additional asset watching/copying tasks (see `gulp/gulpfile.js`).

## 🔙 Backend (PHP)

The backend is a custom strict-typed PHP framework (`Guacamole`).

- **Entry Point**: `src/public/index.php`.
- **Autoloading**: Composer PSR-4.
- **Environment**: `phpdotenv` loads configuration from `.env`.
- **API**: The backend likely serves API endpoints consumed by the Vue frontend.

## 🐳 Infrastructure

The project runs on **Docker** using `docker-compose`.

- **Nginx**: Web server, handling requests and proxying to PHP-FPM.
- **PHP-FPM**: PHP FastCGI Process Manager.
- **Certificates**: Local SSL certificates are generated for secure development (`https://localhost`).

## 📂 Directory Structure

```
/
├── Console/        # CLI commands
├── dist/           # Compiled assets (output of build)
├── docker-compose* # Docker configuration
├── gulp/           # Gulp tasks
├── src/
│   ├── Guacamole/  # Framework Core
│   ├── Timecentric/# Application Logic & UI
│   └── public/     # Web root (index.php)
└── vendor/         # PHP Dependencies
```
