#!/bin/bash

# Exit on error
set -e

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL to be ready..."
sleep 10

# Initialize the database if needed
echo "Initializing the database..."
PGPASSWORD=$DB_PASS psql -h $DB_HOST -U $DB_USER -d $DB_NAME -f /var/www/html/shop/shop_db_pg.sql || true

# Exit successfully
echo "Deployment completed successfully!"
exit 0 