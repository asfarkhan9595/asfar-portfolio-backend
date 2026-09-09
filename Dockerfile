FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Install Node dependencies and build Vite assets
RUN npm install
RUN npm run build

# Laravel / Nginx configuration
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Run migrations, then start Laravel/Nginx
CMD ["sh", "-c", "php artisan migrate --force && /start.sh"]