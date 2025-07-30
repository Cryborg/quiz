#!/usr/bin/env bash

# Laravel Sail wrapper script
if [ $# -gt 0 ]; then
    # If we have arguments, pass them to sail
    exec ./vendor/bin/sail "$@"
else
    # If no arguments, show help
    echo "Laravel Sail - Docker wrapper for Laravel"
    echo ""
    echo "Usage: ./sail [command]"
    echo ""
    echo "Common commands:"
    echo "  up              Start all services"
    echo "  up -d           Start all services in background"
    echo "  down            Stop all services"
    echo "  artisan         Run artisan commands"
    echo "  composer        Run composer commands"
    echo "  npm             Run npm commands"
    echo "  shell           Open bash shell in app container"
    echo "  mysql           Open MySQL shell"
    echo ""
    echo "Examples:"
    echo "  ./sail up -d"
    echo "  ./sail artisan migrate"
    echo "  ./sail composer install"
    echo "  ./sail npm run dev"
fi