FROM nginxinc/nginx-unprivileged:1.21

USER root

RUN usermod -u 1000 nginx && groupmod -g 1000 nginx
RUN chown -R nginx /etc/nginx

USER nginx

COPY .docker/templates /etc/nginx/templates

COPY public /var/www/public