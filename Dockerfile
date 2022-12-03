FROM enotvtapke/personal:nginx-fpm
RUN apk add php8-tokenizer php8-dom php8-xmlwriter php8-xml php8-simplexml
COPY . /var/www/php
WORKDIR /var/www/php
RUN composer update
RUN composer install --no-dev