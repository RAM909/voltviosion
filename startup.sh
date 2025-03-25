#!/bin/bash

# Run deploy script if we're on Render (it will initialize the database)
if [ "$RENDER" = "true" ]; then
  echo "Running on Render, initializing database..."
  /var/www/html/deploy.sh
fi

# Start Apache in foreground
apache2-foreground 