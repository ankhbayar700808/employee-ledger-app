FROM php:8.2-fpm

# MySQL-тэй холбогдоход шаардлагатай PDO өргөтгөлийг суулгах
RUN docker-php-ext-install pdo pdo_mysql

# Ажлын хавтсыг зааж өгөх
WORKDIR /var/www/html
