from php:7.4-fpm

run docker-php-ext-install -j$(nproc) mysqli pdo_mysql \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/' "$PHP_INI_DIR/php.ini"
