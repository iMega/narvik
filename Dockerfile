FROM alpine:3.3

EXPOSE 9000

RUN apk add --update php-fpm && \
    mkdir -p /tmp/nginx/client-body && \
    rm -rf /var/cache/apk/*

COPY . /

WORKDIR /app

ENTRYPOINT ["/bin/sh", "/entry.sh"]

CMD ["php-fpm", "-F"]
