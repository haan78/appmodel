#!/bin/sh

if [ -z "$1" ]
then
	echo "Please send a command"
	exit 1
fi

if [ "$1" = "init" ]
then
	if [ ! -f /html/package.json]
	then
		echo "/html/package.json not found"
		exit 1
	fi

	if [ -d /html/node_modules ]
	then
		rm -rf /html/node_modules
	fi

	if [ -d /html/dist ]
	then
		rm -rf /html/dist
	fi

	if [ -f /html/package-lock.json ]
	then
		rm -rf /html/package-lock.json
	fi
	
	cd /html
	npm install
	exit 0
fi

if [ ! -f "/html/package-lock.json" ]
then
	echo "/html/package-lock.json first init the project"
	exit 1
fi

if [ "$1" = "watch" ]
then
	cd /html
	npx vue-cli-service build --mode development --watch
	exit 0
fi

if [ "$1" = "dev" ]
then
	cd /html
	npx vue-cli-service build --mode development
	exit 0
fi

if [ "$1" = "build" ]
then
	cd /html
	npx vue-cli-service build --mode production
	exit 0
fi



