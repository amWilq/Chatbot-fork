FROM chatbot-goldenphp:latest

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer update
RUN composer install
