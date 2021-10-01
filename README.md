# SUBUTAI

## Project setup
### start docker containers
```
docker compose up
```

### Install project webpack and node modules
```
project npm install
or
docker exec -ti [container id] sh /builder.js npm install
```

## Use composer and npm via container

### Install project composer dependicies
```
project composer install
or
docker exec -ti [container id] sh /builder.js composer install
```
### Add composer dependicie in to project
```
project composer requier [package name]
or
docker exec -ti [container id] sh /builder.js composer requier [package name]
```

### Run npm script into container
```
project npm run [script name]
or
docker exec -ti [container id] sh /builder.js npm run [script name]
```