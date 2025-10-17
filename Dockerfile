# ---------- Stage 1 : PHP + Composer ----------
FROM php:8.2-fpm AS php-builder
WORKDIR /app

# Installer dépendances système + extensions PHP
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    curl gnupg2 ca-certificates build-essential \
    && docker-php-ext-install pdo_mysql mbstring zip exif bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Installer composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier tout le code Laravel AVANT d’installer les dépendances
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && composer dump-autoload --optimize
RUN docker-php-ext-install pdo pdo_mysql mysqli

# ---------- Stage 2 : Builder frontend ----------
FROM node:22 AS node-builder
WORKDIR /app

# Copier tout le code Laravel + vendor depuis php-builder
COPY --from=php-builder /app /app

# Installer dépendances Node
RUN npm install --silent

# Build frontend (Vite, Webpack, etc.)
RUN npm run build

# ---------- Stage 3 : Production PHP ----------
FROM php:8.2-fpm
WORKDIR /var/www/html

# Installer runtime deps
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip tini \
    && rm -rf /var/lib/apt/lists/*

# Créer utilisateur non-root
RUN useradd -G www-data,root -u 1000 -m appuser

# Copier Laravel + vendor depuis php-builder
COPY --from=php-builder /app /var/www/html

# Copier les assets frontend compilés depuis node-builder
COPY --from=node-builder /app/public/build ./public/build

# Permissions
RUN chown -R appuser:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Utilisateur non root
USER appuser

EXPOSE 9000
ENTRYPOINT ["tini", "--"]
CMD ["php-fpm"]
