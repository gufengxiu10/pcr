FROM phpswoole/swoole:php7.4

# 工作目录
WORKDIR /www

RUN cd /etc/apt \
    && echo "" > sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster main contrib non-free" >> sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free" >> sources.list \ 
    && echo "deb https://mirrors.ustc.edu.cn/debian/ buster-backports main contrib non-free" >> sources.list \
    && echo "deb https://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian/ buster-backports main contrib non-free" >> sources.list \
    && echo "deb-src https://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free" >> sources.list \
    && apt-get update \
    # && pecl install mcrypt \
    # && docker-php-ext-enable mcrypt \
    # && curl -sS https://getcomposer.org/installer | php \
    # && mv composer.phar /usr/local/bin/composer \
    && pecl install redis && docker-php-ext-enable redis \
    # && pecl install swoole && docker-php-ext-enable swoole \
    && apt-get install -y systemctl \
    && apt-get install -y procps \
    && apt-get install -y supervisor \
    && apt-get install -y iputils-ping \
    && apt-get install -y apt-utils \
    && apt-get install -y cron \
    && apt-get install -y vim \
    && apt-get install -y --no-install-recommends libbz2-dev \
    && docker-php-ext-install -j$(nproc) bz2 \
    && apt-get install -y --no-install-recommends libenchant-dev \
    && docker-php-ext-install -j$(nproc) enchant \
    && docker-php-ext-install -j$(nproc) bcmath calendar exif gettext sockets dba mysqli pcntl pdo_mysql shmop sysvmsg sysvsem sysvshm \
    && apt-get install -y --no-install-recommends libgmp-dev \
    && docker-php-ext-install -j$(nproc) gmp \
    && apt-get install -y --no-install-recommends libxml2-dev libtidy-dev libxslt1-dev \
    # xmlrpc
    && docker-php-ext-install -j$(nproc) soap tidy xsl \
    && apt-get install -y --no-install-recommends libzip-dev \
    && docker-php-ext-install -j$(nproc) zip \
    && apt-get install -y --no-install-recommends libsnmp-dev \
    && docker-php-ext-install -j$(nproc) snmp \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install -j$(nproc) pgsql pdo_pgsql \ 
    && apt-get install -y --no-install-recommends libpspell-dev \
    && docker-php-ext-install -j$(nproc) pspell \
    && apt-get install -y --no-install-recommends firebird-dev \
    && docker-php-ext-install -j$(nproc) pdo_firebird \
    && apt-get install -y --no-install-recommends freetds-dev \
    && docker-php-ext-configure pdo_dblib --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install -j$(nproc) pdo_dblib \
    && apt-get install -y --no-install-recommends libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install -j$(nproc) ldap \
    && apt-get install -y --no-install-recommends libc-client-dev libkrb5-dev \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install -j$(nproc) imap \
    && apt-get install -y --no-install-recommends libicu-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && apt-get install -y --no-install-recommends libmcrypt-dev \
    && docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache \
    && rm -r /var/lib/apt/lists/* 

ADD ./entrypoint.sh /usr/local/bin/

# ENTRYPOINT ["entrypoint.sh"]

# CMD ["php-fpm"]
