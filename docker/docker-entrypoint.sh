#!/bin/sh
/etc/init.d/php7.3-fpm start && chmod 777 /run/php/php*.sock && nginx -g "daemon off;"
