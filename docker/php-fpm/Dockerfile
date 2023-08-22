FROM php:8.3.0beta2-fpm
WORKDIR "/app"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN apt-get update
RUN apt-get install -y libzip-dev zip
RUN install-php-extensions gd zip intl pgsql pdo_pgsql redis
RUN install-php-extensions @composer
RUN pecl install xdebug-3.2.0RC1 && docker-php-ext-enable xdebug
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
