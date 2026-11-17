FROM php:8.3-apache-bookworm

ARG SQLSRV_VERSION=5.13.3
ENV TZ=Asia/Taipei

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends ca-certificates curl gnupg \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev libonig-dev \
        unixodbc-dev; \
    curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg; \
    echo 'deb [arch=amd64,arm64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main' \
        > /etc/apt/sources.list.d/microsoft-prod.list; \
    apt-get update; \
    ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" gd mbstring zip mysqli pdo_mysql opcache; \
    pecl install "sqlsrv-${SQLSRV_VERSION}" "pdo_sqlsrv-${SQLSRV_VERSION}"; \
    docker-php-ext-enable sqlsrv pdo_sqlsrv; \
    a2enmod rewrite; \
    cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    rm -rf /var/lib/apt/lists/* /tmp/pear

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-idcc.ini
COPY docker/apache.conf /etc/apache2/conf-available/idcc.conf
COPY docker/health.php docker/healthcheck.php /opt/idcc/
COPY --chown=www-data:www-data . /var/www/html/DemoBeta/

RUN a2enconf idcc \
    && mkdir -p /var/www/html/DemoBeta/env \
    && test -f /var/www/html/DemoBeta/common/IDCC_plugin/jquery/jquery.min.js \
    && ln -s /var/www/html/DemoBeta/common /var/www/html/common \
    && apache2ctl -t \
    && php -r '$required=["curl","openssl","ctype","dom","fileinfo","gd","iconv","mbstring","SimpleXML","xml","xmlreader","xmlwriter","zip","mysqli","pdo_mysql","sqlsrv","pdo_sqlsrv"]; $missing=array_diff($required,get_loaded_extensions()); if ($missing) {fwrite(STDERR,implode(",",$missing)); exit(1);}'

WORKDIR /var/www/html/DemoBeta
HEALTHCHECK --interval=15s --timeout=5s --start-period=30s --retries=5 \
    CMD ["php", "/opt/idcc/healthcheck.php"]
