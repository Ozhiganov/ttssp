#!/usr/bin/env bash

EXT_PORT_1="80"

NGINX_PHP_NAME=`echo "docker_web"`


C_UID=`id -u`
C_GID=`id -g`
C_USER=`id -u -n`
C_GROUP=`id -g -n`

cd "$(dirname "$0")"

mkdir -p .docker/logs/nginx .docker/web
cd .docker/


> docker-compose.yml
cat <<EOF >> docker-compose.yml
version: '3'
services:
  $NGINX_PHP_NAME:
    build: ./web
    volumes:
       - ./logs/nginx:/var/log/nginx
       - ../app:/app
    ports:
      - $EXT_PORT_1:80
    restart: always

EOF


> web/conf_fixes.sh
cat <<EOF >> web/conf_fixes.sh
#!/bin/sh
sed -i 's/^fastcgi_param.*HTTPS.*/fastcgi_param  HTTPS  \$fastcgi_https if_not_empty;/' /etc/nginx/fastcgi_params
sed -i 's/^;*max_execution_time.*/max_execution_time=150/i' /etc/php7/php.ini
sed -i 's/^;*memory_limit.*/memory_limit=256M/i' /etc/php7/php.ini
sed -i 's/^;*cgi.fix_pathinfo.*/cgi.fix_pathinfo=0/i' /etc/php7/php.ini
sed -i 's/^;*short_open_tag.*/short_open_tag=on/i' /etc/php7/php.ini
sed -i 's/^;*expose_php.*/expose_php=off/i' /etc/php7/php.ini
sed -i 's:^;*date.timezone.*:date.timezone = Europe/Moscow:i' /etc/php7/php.ini
sed -i 's/^;*post_max_size.*/post_max_size = 32M/i' /etc/php7/php.ini
sed -i 's/^;*upload_max_filesize.*/upload_max_filesize = 32M/i' /etc/php7/php.ini
sed -i 's/^;*intl.default_locale.*/intl.default_locale = ru-RU/i' /etc/php7/php.ini
sed -i 's/^;*user.*/user=$C_USER/i' /etc/php7/php-fpm.d/www.conf
sed -i 's/^;*group.*/group=$C_GROUP/i' /etc/php7/php-fpm.d/www.conf
sed -i 's/^;*daemonize.*/daemonize=no/i' /etc/php7/php-fpm.conf
EOF

> web/nginx.conf
cat <<EOF >> web/nginx.conf
    pid /var/run/nginx.pid;
    user $C_USER $C_GROUP;
    daemon off;
    worker_processes  auto;
    error_log  /var/log/nginx/error.log;
    events {
        worker_connections  4096;
        multi_accept on;
        use epoll;
    }
    http {
        include       mime.types;
        default_type  application/octet-stream;
        access_log  off;
        types_hash_max_size 2048;
        server_tokens off;
        client_max_body_size 128m;
        server_name_in_redirect off;
        server_names_hash_max_size      512;
        server_names_hash_bucket_size   512;
        sendfile        on;
        tcp_nopush     on;
        tcp_nodelay on;
        keepalive_timeout  65;
        gzip                on;
        gzip_vary           on;
        gzip_comp_level     8;
        gzip_min_length     1024;
        gzip_buffers        8 64k;
        gzip_types          text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript image/svg+xml application/x-font-ttf font/opentype;
        gzip_proxied any;
        gzip_disable "MSIE [1-6]\.";
        map \$http_x_forwarded_proto \$fastcgi_https {
            default off;
            https on;
        }
        server {
            charset utf-8;
            client_max_body_size 128M;
            listen 80;
            server_name _;
            root        /app/web;
            index       index.php index.html;
            access_log  /var/log/nginx/access.log;
            error_log   /var/log/nginx/error.log;
            location / {
                try_files \$uri \$uri/ /index.php\$is_args\$args;
            }
            location ~ \.php$ {
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_read_timeout 150;
                try_files \$uri =404;
            }
            location ~ ^/assets/.*\.php\$ {
                deny all;
            }
            location ~* /\. {
                deny all;
            }
        }
    }
EOF


> web/Dockerfile
cat <<EOF >> web/Dockerfile
FROM alpine:latest

RUN (getent group $C_GID) 2>&1 > /dev/null \
    && delgroup \$(getent group $C_GID | cut -d ':' -f 1) \
    || echo "no conflicting group id to delete"

RUN addgroup -g $C_GID -S $C_GROUP && adduser -u $C_UID -S -G $C_GROUP $C_USER

RUN mkdir /app
RUN chown -R $C_USER:$C_GROUP /app

RUN apk add nginx
COPY nginx.conf /etc/nginx/nginx.conf
RUN chown -R $C_USER:$C_GROUP /var/tmp/nginx
RUN chown -R $C_USER:$C_GROUP /var/log/nginx
RUN apk add php7 php7-fpm php7-gd php7-curl \
    php7-zip php7-bz2 php7-imap php7-pgsql \
    php7-opcache php7-mbstring php7-exif \
    php7-common php7-xml php7-xmlrpc \
    php7-phar php7-ldap php7-zlib php7-imagick php7-intl \
    php7-mysqli php7-calendar php7-dom php7-ctype \
    php7-tokenizer php7-xmlwriter php7-json \
    php7-pdo_sqlite php7-pdo_mysql php7-pdo_pgsql \
    php7-pdo php7-fileinfo php7-session \
    php7-iconv php7-ftp php7-gettext \
    php7-openssl php7-posix php7-pear php7-pspell \
    php7-sysvmsg php7-sysvsem php7-sysvshm \
    php7-shmop php7-xmlreader git
RUN apk add composer
RUN apk add sox

COPY conf_fixes.sh /tmp/conf_fixes.sh
RUN chmod +x /tmp/conf_fixes.sh && /tmp/conf_fixes.sh

CMD nginx & php-fpm7
EOF

docker-compose build && docker-compose up -d


> sh_run.sh
cat <<EOF >> sh_run.sh
cd "\$(dirname "\$0")"
docker-compose exec -u $C_USER $NGINX_PHP_NAME sh
EOF
chmod a+x sh_run.sh
