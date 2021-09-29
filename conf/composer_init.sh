#!/bin/sh

if [ ! -f /html/composer.json]
then
	echo "/html/composer.json not found"
	exit 1
fi

if [ -d /html/vendor ]
then
	rm -rf /html/vendor
fi

if [ -f /html/composer.lock ]
then
    rm -rf /html/composer.lock
fi

cd /html

composer install
