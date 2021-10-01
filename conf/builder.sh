#!/bin/sh

error()
{
    echo $1
    exit 1
}

cleardir()
{
    if [ "$1" = "composer" ]
    then

        rm -rf /html/vendor
        rm -rf /html/composer.lock

    elif [ "$1" = "npm" ]
    then

        rm -rf /html/node_modules
        rm -rf /html/dist
        rm -rf /html/package-lock.json

    fi
}

if [ ! -d /html ]
then
    error "/html folder not found"
fi

cd /html

if [ "$1" = "composer" ]
then
    
    if [ ! -f /html/composer.json ]
    then
	    error /html/composer.json not found	    
    fi

    if [ "$2" = "install" ]
    then
        cleardir composer
        composer install
    elif [ "$2" = "require" ]
    then
        composer require $3
    else
        error "Unknown command : builder composer [$2]"
    fi
elif [ "$1" = "npm" ]
then    
    if [ ! -f /html/package.json ]
	then
		error "/html/package.json not found"
	fi

    if [ "$2" = "install" ]
    then
        cleardir npm
	    npm install
    elif [ "$2" = "build" ]
    then
        npx vue-cli-service build --mode production
    elif [ "$2" = "dev" ]
    then
        npx vue-cli-service build --mode development
    elif [ "$2" = "watch" ]
    then
        npx vue-cli-service build --mode development --watch
    elif [ "$2" = "run" ]
    then
        npm run $3
    else
        error "Unknown command : builder npm [$2]"
    fi
else
    error "Unknown command : builder [$2]"
fi

exit 0