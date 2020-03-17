FROM centos:7

RUN yum -y update && \
    yum -y install epel-release && \
    yum install -y initscripts && \
    yum -y install nginx \
    yum -y install https://$(rpm -E '%{?centos:centos}%{!?centos:rhel}%{rhel}').iuscommunity.org/ius-release.rpm && \
    yum -y install php72u-fpm-nginx \
        php72u-xml \
        php72u-soap \
        php72u-cli \
        php72u-json \
        php72u-mbstring \
        php72u-gd \
        php72u-pdo \
        php72u-mysqlnd \
        php72u-mcrypt \
        php72u-process \
        php72u-pecl-redis \
        supervisor \
	    certbot \
	    crontabs \
	    ntp \
	    vim \
	    libsodium \
	    php72u-gmp \
	    rsync \
	    zip

COPY ./docker/default.conf /etc/nginx/conf.d/
COPY ./docker/nginx.conf /etc/nginx/
#COPY ./docker/ssl.conf /etc/nginx/conf.d/
COPY ./docker/www.conf /etc/php-fpm.d/www.conf
COPY ./docker/php.ini /etc/php.ini
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80 443

# comment out PAM
RUN sed -i -e '/pam_loginuid.so/s/^/#/' /etc/pam.d/crond

COPY ./docker/laravel_cron /etc/cron.d/laravel_cron
RUN chmod 0644 /etc/cron.d/laravel_cron
RUN crontab /etc/cron.d/laravel_cron

RUN yes | cp /usr/share/zoneinfo/Asia/Tehran /etc/localtime

#CMD ["nginx", "-g", "daemon off;"]
CMD ["/usr/bin/supervisord", "-n"]
WORKDIR /usr/share/nginx/html/home

