#!/bin/sh

if [ -z "${APPNAME}" ]
then
	n="$APPNAME"
else
	n="app_defautl"
fi

if [ -z "$1" ]
then
	echo "Please send a command"
	exit 1
fi

if [ "$1" = "init" ]
then	
	rm -rf /app/*	
	cp /vuedef/vue.config.js.default /app/vue.config.js
	cp /vuedef/package.json.default /app/package.json
	reg='s/anewapplicationname/'$n'/g'
	sed -i $reg /app/package.json
	ln -sf /code /app/src
	cd /app
	npm install
	exit 0
fi

if [ ! -f "/app/package-lock.json" ]
then
	echo "/app/package-lock.json first init the project"
	exit 1
fi

if [ "$1" = "watch" ]
then
	cd /app
	npx vue-cli-service build --mode development --watch
	exit 0
fi

if [ "$1" = "dev" ]
then
	cd /app
	npx vue-cli-service build --mode development
	exit 0
fi

if [ "$1" = "build" ]
then
	cd /app
	npx vue-cli-service build --mode production
	exit 0
fi



