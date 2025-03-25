# VoltVision Website

A website for VoltVision with e-commerce functionality.

## Deployment on Render

This project is set up to be easily deployed on Render.com using the included render.yaml file.

### Prerequisites

1. A Render.com account (free tier works fine)
2. A GitHub/GitLab/Bitbucket repository with this code

### Deployment Steps

1. Push your code to a Git repository
2. On Render dashboard, click "New" and select "Blueprint"
3. Connect to your Git repository and select the repo with this code
4. Render will automatically:

   - Create a PostgreSQL database
   - Build and deploy your web service using the Dockerfile
   - Set up environment variables for the database connection

5. When the deployment is complete, you'll get a URL for your live website

### Manual Database Setup

If the automatic database setup fails, you can manually set up the database:

1. Visit `https://your-render-url.com/shop/update_schema.php` to create the database tables
2. This will create all necessary tables in the correct PostgreSQL format

### Troubleshooting

If you encounter SQL errors:

1. Check the error message - it likely relates to the difference between MySQL (local) and PostgreSQL (on Render)
2. Common issues include:
   - Table name quoting: MySQL uses backticks (`), PostgreSQL uses double quotes (")
   - LIMIT syntax: MySQL uses `LIMIT x, y`, PostgreSQL uses `LIMIT y OFFSET x`
   - Date/time functions: MySQL and PostgreSQL have different function names

The codebase includes automatic conversion between MySQL and PostgreSQL syntax, but some complex queries might need manual adjustment.

## Local Development

For local development, you'll need:

1. PHP 8.x
2. MySQL or PostgreSQL
3. Apache/Nginx web server

Configure your database connection in `shop/components/connect.php`.
