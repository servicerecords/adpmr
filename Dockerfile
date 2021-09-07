FROM codesure/srrdigital:baseline

COPY ./docker/.env-build /app/.env
COPY src/ /app
RUN cd /app
RUN /usr/local/bin/composer install
RUN npm install
RUN npm run prod
RUN mkdir -p /app/public
RUN mkdir -p /app/storage
RUN mkdir -p /app/storage/logs
RUN chmod -R 777 /app/public
RUN chmod -R 777 /app/storage
