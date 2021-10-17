FROM 831999517078.dkr.ecr.eu-west-1.amazonaws.com/adpmr:baseline

COPY ./docker/.env-build /app/.env
COPY src/ /app
RUN cd /app
RUN /usr/local/bin/composer install
RUN npm install
RUN npm run prod
RUN mkdir -p /app/public
RUN mkdir -p /app/storage
RUN mkdir -p /app/storage/logs
RUN mkdir -p /var/www/.aws/
COPY ./docker/aws_credentials-build /var/www/.aws/credential
RUN chmod -R 777 /app/public
RUN chmod -R 777 /app/storage
