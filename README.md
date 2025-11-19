# Timecentric

Timecentric is a modern, strict-typed, non-opinionated PHP framework paired with a Vue 3 Single Page Application (SPA). It is designed for performance and developer experience, utilizing Docker for a consistent environment.

## 🚀 Quick Start

### Prerequisites

- **Docker** & **Docker Compose**
- **Node.js** (v20+ recommended)
- **Composer**
- **PHP** (local CLI optional, but recommended for IDE support)

### Installation

1.  **Clone the repository**:
    ```bash
    git clone <repository-url>
    cd timecentric
    ```

2.  **Setup the environment**:
    Run the setup script to install dependencies, generate certificates, and build the Docker containers.
    ```bash
    npm run setup
    ```

3.  **Start the development server**:
    ```bash
    npm run dev
    ```
    This will start:
    - PHP backend (Docker: Nginx + PHP-FPM)
    - Vue frontend (Vite + Gulp watcher)

    Access the application at `https://localhost` (or configured port).

## 📚 Documentation

- [Architecture Overview](docs/ARCHITECTURE.md) - Detailed look at the backend and frontend structure.
- [Setup & Development](docs/SETUP.md) - In-depth setup instructions and workflow.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (Guacamole Framework)
- **Frontend**: Vue 3, TypeScript, Vite, Sass
- **Infrastructure**: Docker, Nginx
- **Tools**: ESLint, Prettier, PHP-CS-Fixer, PHPStan
