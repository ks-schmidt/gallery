FROM quay.io/hellofresh/php70

ENV APP_DIR /server/http

ADD ./docker/nginx/default.conf /etc/nginx/sites-available/default.conf
RUN cd /etc/nginx/sites-enabled \
    && rm default \
    && ln -s ../sites-available/default.conf default

RUN sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /etc/php/7.0/fpm/pool.d/www.conf

WORKDIR $APP_DIR
