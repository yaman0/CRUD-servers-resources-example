FROM php:7.4-apache


ENV APP_HOME /var/www/html


# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      procps \
      nano \
      git \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      cron \
      libzip-dev \
      libpq-dev \
      nodejs \
      npm \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_psql \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

RUN npm install -g yarn

# disable default site and delete all default files inside APP_HOME
RUN a2dissite 000-default.conf
RUN rm -r $APP_HOME

# create document root
RUN mkdir -p $APP_HOME/public

# change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN chown -R www-data:www-data $APP_HOME

# put apache and php config for Symfony, enable sites
COPY ./docker/symfony.conf /etc/apache2/sites-available/symfony.conf
RUN a2ensite symfony.conf
COPY ./docker/php.ini /usr/local/etc/php/php.ini

# enable apache modules
RUN a2enmod rewrite


# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
&& composer --version
ENV COMPOSER_ALLOW_SUPERUSER 1

# set working directory
WORKDIR $APP_HOME

# create composer folder for user www-data
RUN mkdir -p /var/www/.composer && chown -R www-data:www-data /var/www/.composer

USER www-data

# copy source files
COPY . $APP_HOME/

# install all PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-interaction --no-progress;
RUN yarn install && yarn encore dev
# create cached config file .env.local.php in case staging/prod environment
RUN chown www-data:www-data -R ./

EXPOSE 80