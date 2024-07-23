# Utiliser l'image de base officielle Ubuntu
FROM ubuntu:20.04
ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update 
RUN apt-get install -y software-properties-common && add-apt-repository ppa:ondrej/php
# Mettre à jour les paquets et installer Apache et PHP
RUN apt-get install -y \
    apache2 \
    libapache2-mod-php \
    php-mysql \
    git \
    unzip \
    curl 
    # && docker-php-ext-configure gd --with-freetype --with-jpeg \
    # && docker-php-ext-install gd \
    # && docker-php-ext-install mbstring \
    # && docker-php-ext-install exif \
    # && docker-php-ext-install pcntl \
    # && docker-php-ext-install bcmath \
    # && docker-php-ext-install opcache \
    # && docker-php-ext-install intl \
    # && docker-php-ext-install soap \
    # && docker-php-ext-install xml \
    # && docker-php-ext-install zip \
    # && docker-php-ext-install pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt install -y php8.3-zip php8.3-xmlreader php8.3-xmlwriter php8.3-xls php8.3-gd php8.3-dom php8.3-mysqlnd php8.3-opcache php8.3-pdo php8.3-calendar php8.3-ctype php8.3-exif php8.3-ffi php8.3-fileinfo php8.3-ftp php8.3-gettext php8.3-iconv php8.3-mysqli php8.3-pdo-mysql php8.3-phar php8.3-posix php8.3-readline php8.3-shmop php8.3-sockets php8.3-sysvmsg php8.3-sysvsem php8.3-sysvshm php8.3-tokenizer 
# Copier les fichiers du projet dans le répertoire de travail de l'Apache
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances du projet
RUN composer install

# Donner les permissions appropriées aux fichiers et répertoires
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port 80
EXPOSE 80

# Lancer Apache en mode foreground
CMD ["apachectl", "-D", "FOREGROUND"]
