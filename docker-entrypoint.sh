#!/bin/sh
set -e

# Run migrations
echo "🚀 Running database migrations..."
php guacamole migrations init
php guacamole migrations roll

# Execute the main command (e.g., php-fpm)
echo "✅ Starting PHP-FPM..."
exec "$@"
