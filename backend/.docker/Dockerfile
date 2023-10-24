FROM chatbot-goldenphp:latest

COPY . /var/www/html

WORKDIR /var/www/html

RUN enable-supervisord-program.sh app-swoole
