#!/bin/bash

if [ ! -f './id_rsa' ]; then
    echo "private key required in root (id_rsa)"
    exit 1
fi

echo "Spinning up docker containers"
docker container rm -fv deploy > /dev/null 2>&1
docker run -d -it --env-file=composer.env --name deploy -v $(pwd):/var/www/repo brandaddition/php:7.3-fpm > /dev/null 2>&1

echo "Executing deployment"
docker exec -it deploy bash -c "mkdir -p ~/.ssh && cp ~/repo/id_rsa ~/.ssh/id_rsa && chmod 600 ~/.ssh/id_rsa && cd ../repo && dep deploy:unlock $1 -vvv && dep deploy $1 -vvv"

echo "Cleaning up"

echo " - removing docker container"
docker container rm -fv deploy > /dev/null 2>&1

echo " - removing artifacts"
rm -rf archive.tar.gz