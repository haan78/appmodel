#!/bin/sh

if [ -z "$1" ]
then
	echo "Please send a command"
	exit 1
fi

if [ "$1" = "init" ]
then
	if [ ! -f /app/package.json]
	then
		echo "/app/package.json not found please assign with docker volume"
		exit 1
	fi

	if [ -d /app/node_modules ]
	then
		rm -rf /app/node_modules
	fi

	if [ -d /app/dist ]
	then
		rm -rf /app/dist
	fi

	if [ -f /app/package-lock.json ]
	then
		rm -rf /app/package-lock.json
	fi
	
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



