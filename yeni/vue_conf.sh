#!/bin/sh

if [ -z "${APPNAME}" ]
then
  echo "Application name is required"
  exit 1
fi

if [ -z "$1" ]
then
	echo "Please send a command"
	exit 1
fi

if [ ! -d "/app" ]
then
	echo "/app folder not found"
	exit 1
fi

if [ 1 -f "/vuedef/vue.config.js.default" ]
then
	echo "vue.config.js.default file not dound"
	exit 1
fi

if [ "$1" = "create" ]
then
	if [ -d "/app/$APPNAME" ]
	then
		rm -rf /app/$APPNAME
	fi
	mkdir /app/$APPNAME
	cd /app
	vue create -n "$APPNAME"
	cp /vuedef/vue.config.js.default /app/$APPNAME/vue.config.js
	exit 0
fi

if [ ! -d "/app/$APPNAME" ]
then
	echo "/app/$APPNAM folder not found. Call create command"
	exit 1
fi

if [ "$1" = "watch" ]
then
	cd "/app/$APPNAME"
	npx vue-cli-service build --mode development --watch
	exit 0
fi

if [ "$1" = "dev" ]
then
	cd "/app/$APPNAME"
	npx vue-cli-service build --mode development
	exit 0
fi

if [ "$1" = "build" ]
then
	cd "/app/$APPNAME"
	npx vue-cli-service build --mode production
	exit 0
fi



