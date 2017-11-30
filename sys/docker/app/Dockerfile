#
# Symfony common dependencies layer
#
FROM alpine:edge AS minimal_symfony

RUN echo "@edge-community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories \
 && apk add -U \
        tini \
        php7-fpm \
        php7-cli \
        php7-ctype \
        php7-curl \
        php7-dom \
        php7-iconv \
        php7-intl \
        php7-json \
        php7-mbstring \
        php7-opcache \
        php7-openssl \
        php7-phar \
        php7-posix \
        php7-session \
        php7-simplexml \
        php7-tokenizer \
        php7-xml \
 && rm -rf /var/cache/apk/* \
;

COPY files/main /

CMD ["/usr/sbin/php-fpm7", "-R", "--nodaemonize"]
ENTRYPOINT ["/sbin/tini", "--"]
EXPOSE 9000
WORKDIR /srv

#
# Project specific dependencies layer
#
FROM minimal_symfony

RUN apk add -U \
        php7-pdo \
        php7-pdo_sqlite \
        php7-redis \
 && rm -rf /var/cache/apk/* \
;
