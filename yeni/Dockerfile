FROM node:latest
COPY ./vue_conf.sh /vuedef/vue_conf.sh
COPY ./package.json.default /vuedef/package.json.default
COPY ./vue.config.js.default /vuedef/vue.config.js.default

RUN mkdir /code

RUN npm update -g
##RUN npm install --global yarn
RUN npm install -g @vue/cli

