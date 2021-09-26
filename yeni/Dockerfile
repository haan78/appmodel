FROM node:latest
RUN mkdir /vudef
COPY ./vue_conf.sh /vuedef/vue_conf.sh
COPY ./vue.config.js.default /vuedef/vue.config.js.default

WORKDIR /html

RUN npm update -g
##RUN npm install --global yarn
RUN npm install -g @vue/cli

