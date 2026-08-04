FROM php:8.3-apache

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libssl-dev \
    pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Activation du module Apache rewrite
RUN a2enmod rewrite

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www/html

# Copie du projet dans le conteneur
COPY . /var/www/html

# Installation des dépendances PHP
RUN composer install --no-interaction --prefer-dist

EXPOSE 80