#!/bin/sh
echo "Burasi calisti"
if [ ! -d /private/vendor ]
then
    composer install
fi