<!-- filepath: /home/mouayed/projects/ffp-events/docs/deployement.md -->
This is the deployment document for the FFP Event Platform (Laravel).
It uses Nginx, PHP-FPM, MySQL, and PM2 for the queue worker.

## Overview

The FFP Event Platform is a Laravel application. The deployment setup involves several key components:

*   **Nginx:** Acts as the web server, handling incoming HTTP and HTTPS requests and serving static files. It also proxies PHP requests to PHP-FPM.
*   **PHP-FPM (FastCGI Process Manager):** An alternative PHP FastCGI implementation with some additional features useful for heavy-loaded sites. It's responsible for executing the PHP code of the Laravel application.
*   **MySQL:** The relational database management system used to store the application's data.
*   **PM2:** A process manager for Node.js applications, but it can also be used to manage other types of processes, including PHP scripts. In this setup, PM2 is used to manage the Laravel queue worker, ensuring it's always running and can be easily monitored and restarted.

## Nginx Configuration

Below is the Nginx configuration used for the `registration.ffp-events.com` site. This configuration handles HTTP to HTTPS redirection, SSL termination, and serving the Laravel application via PHP-FPM.

```nginx
##
# Nginx configuration for Laravel with PHP-FPM (PHP 8.4)
##

# Server block to redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name registration.ffp-events.com;

    # Certbot's recommended HTTP->HTTPS redirect
    location / { # Updated to redirect all paths
        return 301 https://$host$request_uri;
    }

    # Allow Let's Encrypt renewals
    location ~ /\.well-known/acme-challenge/ { # Corrected path for Certbot
        allow all;
        root /var/www/html; # Default path, Certbot might use this for challenges
    }
}

# Server block to handle HTTPS traffic via PHP-FPM
server {
    server_name registration.ffp-events.com;

    # Listen on port 443 for SSL connections (IPv4 and IPv6)
    listen [::]:443 ssl http2 ipv6only=on; # Use http2
    listen 443 ssl http2;

    # Set the root directory for the Laravel application's *public* folder
    # *** UPDATED PATH ***
    root /www/ffp-events-live/app/public; # Make sure this path is correct

    # Specify index files, including index.php
    index index.php index.html index.htm;

    # SSL Certificate Configuration (Managed by Certbot - Keep these lines)
    ssl_certificate /etc/letsencrypt/live/registration.ffp-events.com/fullchain.pem; # Corrected common path
    ssl_certificate_key /etc/letsencrypt/live/registration.ffp-events.com/privkey.pem; # Corrected common path
    include /etc/letsencrypt/options-ssl-nginx.conf; # Corrected common path
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # Corrected common path

    # --- BEGIN LARAVEL/PHP-FPM CONFIGURATION ---

    # Main location block - Handles routing via Laravel's front controller
    location / {
        try_files $uri $uri/ /index.php?$query_string; # Standard Laravel try_files
    }

    # Specific location for Livewire's JavaScript asset route
    # This block might not be strictly necessary if the main location block handles it,
    # but can be kept for explicitness or specific Livewire asset handling.
    # location = /livewire/livewire.js { # More specific path for Livewire v3+
    #     alias /www/ffp-events-live/app/public/vendor/livewire/livewire.js; # Example if assets are published
    #     expires off;
    #     try_files $uri $uri/ /index.php?$query_string;
    # }

    # Location block to process PHP files via PHP-FPM
    location ~ \.php$ {
        try_files $uri =404; # Prevent accessing non-existent PHP files directly
        fastcgi_split_path_info ^(.+\.php)(/.+)$;

        # Pass the request to the PHP-FPM socket
        # ** VERIFY this path matches your PHP 8.4 FPM installation **
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;

        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params; # Include standard FastCGI parameters
    }

    # Deny access to hidden files (like .env, .git)
    location ~ /\.ht { # Corrected to target .htaccess and similar files
        deny all;
    }

    location ~ /\.env { # Specifically deny .env
        deny all;
    }

    location ~ /\.git { # Specifically deny .git
        deny all;
    }


    # Optional: Caching for static assets (adjust expiry as needed)
    location ~* \.(?:css|js|jpg|jpeg|gif|png|ico|svg|webp|woff|woff2|ttf|eot)$ {
        expires 1M;
        add_header Pragma public;
        add_header Cache-Control "public";
        access_log off; # Don't log access for static files
    }

    # --- END LARAVEL/PHP-FPM CONFIGURATION ---

    # Optional: Add Security Headers (Recommended - uncomment if desired)
    # add_header X-Frame-Options "SAMEORIGIN" always;
    # add_header X-Content-Type-Options "nosniff" always;
    # add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    # add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    # add_header Permissions-Policy "interest-cohort=()" always; # For FLoC opt-out
}
```

### Explanation of Nginx Configuration:

1.  **HTTP to HTTPS Redirection Server Block:**
    *   `listen 80;` and `listen [::]:80;`: Listen on port 80 for IPv4 and IPv6 HTTP traffic.
    *   `server_name registration.ffp-events.com;`: Specifies the domain this block applies to.
    *   `location / { return 301 https://$host$request_uri; }`: Redirects all incoming HTTP requests to their HTTPS equivalent using a permanent (301) redirect.
    *   `location ~ /\.well-known/acme-challenge/`: This location is crucial for Let's Encrypt (Certbot) to perform domain validation for SSL certificate issuance and renewal. It allows access to the ACME challenge files.
        *   `root /var/www/html;`: Specifies the root directory where Certbot places challenge files. This path might need adjustment based on your Certbot setup.

2.  **HTTPS Server Block:**
    *   `server_name registration.ffp-events.com;`: The domain for this HTTPS server block.
    *   `listen [::]:443 ssl http2 ipv6only=on;` and `listen 443 ssl http2;`: Listen on port 443 for IPv4 and IPv6 HTTPS traffic, enabling SSL and HTTP/2.
    *   `root /www/ffp-events-live/app/public;`: **Crucial:** This sets the document root to the `public` directory of your Laravel application. All web requests are served relative to this directory. Ensure this path is correct for your server.
    *   `index index.php index.html index.htm;`: Defines the default files to serve if a directory is requested. `index.php` is the entry point for Laravel.
    *   **SSL Configuration:**
        *   `ssl_certificate ...fullchain.pem;` and `ssl_certificate_key ...privkey.pem;`: Paths to your SSL certificate and private key files, typically managed by Certbot and located in `/etc/letsencrypt/live/yourdomain/`.
        *   `include /etc/letsencrypt/options-ssl-nginx.conf;`: Includes recommended SSL settings from Certbot for better security.
        *   `ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;`: Path to Diffie-Hellman parameters file, enhancing SSL security.
    *   **Laravel/PHP-FPM Configuration:**
        *   `location / { try_files $uri $uri/ /index.php?$query_string; }`: This is the core location block for Laravel. It attempts to serve a file (`$uri`) or directory (`$uri/`) directly if it exists. If not, it passes the request to `index.php` (Laravel's front controller) with the original query string.
        *   `location ~ \.php$ { ... }`: This block handles requests for PHP files.
            *   `try_files $uri =404;`: Ensures that Nginx doesn't try to execute non-existent PHP files.
            *   `fastcgi_split_path_info ^(.+\.php)(/.+)$;`: Splits the request URI into the script filename and path info.
            *   `fastcgi_pass unix:/run/php/php8.4-fpm.sock;`: **Crucial:** This directive proxies the PHP request to the PHP-FPM service listening on the specified Unix socket. Ensure this path matches your PHP-FPM version and configuration (e.g., `/run/php/php8.3-fpm.sock` for PHP 8.3).
            *   `fastcgi_index index.php;`: Specifies `index.php` as the default script if the URI is a directory.
            *   `fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;`: Sets the `SCRIPT_FILENAME` FastCGI parameter, which PHP needs to know which file to execute.
            *   `include fastcgi_params;`: Includes other standard FastCGI parameters.
    *   **Security Rules:**
        *   `location ~ /\.ht { deny all; }`, `location ~ /\.env { deny all; }`, `location ~ /\.git { deny all; }`: These blocks deny access to sensitive files and directories like `.htaccess`, `.env` (which contains application secrets), and `.git` (version control data).
    *   **Static Assets Caching:**
        *   `location ~* \.(?:css|js|...)$ { ... }`: This block matches common static file extensions.
            *   `expires 1M;`: Sets browser cache expiry to 1 month for these assets.
            *   `add_header Pragma public;` and `add_header Cache-Control "public";`: Sets caching headers.
            *   `access_log off;`: Disables access logging for static files to reduce log noise.
    *   **Optional Security Headers:**
        *   The commented-out `add_header` lines provide additional security headers like `X-Frame-Options`, `X-Content-Type-Options`, etc. It's generally recommended to uncomment and use these.

## PHP-FPM

PHP-FPM runs as a separate service. Nginx communicates with it via a Unix socket (e.g., `/run/php/php8.4-fpm.sock`) or a TCP port. Ensure that the PHP-FPM service is running and configured to use the same socket/port specified in the Nginx `fastcgi_pass` directive. You'll also need the necessary PHP extensions for Laravel (e.g., `php-mysql`, `php-xml`, `php-mbstring`, `php-curl`, etc.).

## MySQL

The Laravel application connects to a MySQL database. The database connection details (host, database name, username, password) are configured in the `.env` file of the Laravel application. Ensure the MySQL server is running and accessible by the application server, and the specified user has the necessary permissions on the database.

## PM2 for Queue Worker

Laravel's queue system allows deferring time-consuming tasks (like sending emails) to a background process, improving application response times. PM2 is used to manage this queue worker.

1.  **Installation:** If not already installed, install PM2 globally using npm:
    ```bash
    sudo npm install pm2 -g
    ```

2.  **Starting the Queue Worker with PM2:**
    Navigate to your Laravel project directory and start the queue worker:
    ```bash
    pm2 start "php artisan queue:work --tries=3 --timeout=90" --name "ffp-events-queue"
    ```
    *   `php artisan queue:work`: The Laravel command to start a queue worker.
    *   `--tries=3`: The worker will attempt a job 3 times before marking it as failed.
    *   `--timeout=90`: The worker will timeout after 90 seconds if a job doesn't complete.
    *   `--name "ffp-events-queue"`: Assigns a name to the process in PM2 for easier management.

3.  **Ensuring PM2 Starts on Boot:**
    To make sure your PM2 processes (including the queue worker) restart automatically after a server reboot:
    ```bash
    pm2 startup
    ```
    This command will output another command that you need to run with superuser privileges.

4.  **Saving PM2 Process List:**
    After starting your processes, save the current list so PM2 can resurrect them on startup:
    ```bash
    pm2 save
    ```

5.  **Monitoring and Managing with PM2:**
    *   List all processes: `pm2 list`
    *   Monitor logs: `pm2 logs ffp-events-queue` or `pm2 logs` for all
    *   Restart a process: `pm2 restart ffp-events-queue`
    *   Stop a process: `pm2 stop ffp-events-queue`
    *   Delete a process: `pm2 delete ffp-events-queue`

## Deployment Steps (General Outline)

1.  **Provision Server:** Set up a server (e.g., Ubuntu) with Nginx, PHP (correct version with FPM), MySQL, Composer, Node.js, and npm.
2.  **Configure Firewall:** Open necessary ports (e.g., 80 for HTTP, 443 for HTTPS, 22 for SSH).
3.  **Clone Repository:** Clone your Laravel application from your Git repository to a directory like `/www/ffp-events-live`.
4.  **Install Dependencies:**
    *   `composer install --optimize-autoloader --no-dev` (for production)
    *   `npm install && npm run build` (if you have frontend assets to compile)
5.  **Configure `.env` File:** Create a `.env` file from `.env.example` and fill in your production database credentials, app URL, mail settings, etc.
6.  **Generate Application Key:** `php artisan key:generate`
7.  **Run Migrations & Seeders (if applicable):** `php artisan migrate --force`
8.  **Optimize Laravel:**
    *   `php artisan config:cache`
    *   `php artisan route:cache`
    *   `php artisan view:cache` (if you cache views)
9.  **Set Up Nginx:**
    *   Create the Nginx site configuration file (e.g., `/etc/nginx/sites-available/registration.ffp-events.com`) with the content provided above.
    *   Create a symbolic link to `sites-enabled`: `sudo ln -s /etc/nginx/sites-available/registration.ffp-events.com /etc/nginx/sites-enabled/`
    *   Test Nginx configuration: `sudo nginx -t`
    *   Reload Nginx: `sudo systemctl reload nginx`
10. **Set Up SSL (Certbot):**
    *   Install Certbot and the Nginx plugin.
    *   Run Certbot to obtain and install SSL certificates: `sudo certbot --nginx -d registration.ffp-events.com`
    *   Certbot should automatically update your Nginx configuration for SSL. Verify the paths in the Nginx config.
11. **Set Up Queue Worker with PM2:** Follow the PM2 steps outlined above.
12. **Set Permissions:** Ensure the web server user (often `www-data`) has the necessary write permissions for `storage` and `bootstrap/cache` directories.
    ```bash
    sudo chown -R www-data:www-data /www/ffp-events-live/storage
    sudo chown -R www-data:www-data /www/ffp-events-live/bootstrap/cache
    sudo chmod -R 775 /www/ffp-events-live/storage
    sudo chmod -R 775 /www/ffp-events-live/bootstrap/cache
    ```
13. **Test Application:** Access `https://registration.ffp-events.com` in your browser.
