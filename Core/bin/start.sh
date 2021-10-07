#!/bin/bash

echo 'Spinning up containers'
docker-compose up -d --build > /dev/null 2>&1

if cat ./src/app/etc/env.php | grep "'date' =>" > /dev/null; then 
    echo Magento already installed
else
    echo Installing Magento
    docker-compose exec --user=magento fpm magento-installer
fi