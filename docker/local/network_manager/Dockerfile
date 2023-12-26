FROM alpine:3.18.0

RUN apk update \
    && apk add openssh bash supervisor

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY monolith-ssh-tunnel /usr/local/bin/monolith-ssh-tunnel

RUN chmod +x /usr/local/bin/start-container /usr/local/bin/monolith-ssh-tunnel

EXPOSE 3306

ENTRYPOINT ["start-container"]
