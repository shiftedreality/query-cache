FROM php:7.3-cli

RUN docker-php-ext-install -j$(nproc) \
  mysqli \
  opcache \
  pdo_mysql
