@echo off

SET ID=
for /f %%i in ('docker ps -f "label=subutai.builder=1" -q') do set ID=%%i
if "%1" == "id" goto id
if "%1" == "up" goto up
if "%1" == "down" goto down


if [%ID%] == [] (
    echo ERROR: Builder container not found
    goto end
)
echo Container ID = %ID%

if "%1" == "shell" goto shell
if "%1" == "composer" goto composer
if "%1" == "npm" goto npm
if "%1" == "help" goto help

goto help

:id
if [%ID%] == [] (
    echo No container id found
    goto end
)
echo %ID%
goto end

:up

if [%ID%] == [] (
    if "%2" == "build" (
        docker compose up --build -d
        goto end
    )
    docker compose up -d
    goto end
)
echo ERROR: It seems docker container already started.
goto end

:down
docker compose down
goto end

:shell
docker exec -ti %ID% sh
goto end

:composer

if "%2" == "install" (
    docker exec -ti %ID% sh /builder.sh composer install
    goto end
)

if "%2" == "require" (
    docker exec -ti %ID% sh /builder.sh composer require %3
    goto end
)

goto end

:npm

if "%2" == "install" (
    docker exec -ti %ID% sh /builder.sh npm install
    goto end
)

if "%2" == "run" (
    docker exec -ti %ID% sh /builder.sh npm run %3
    goto end
)

goto end

:help
echo Commands
echo project id -> Shows runing container id
echo project up -> Synonim of: docker compose up
echo project up build -> Synonim of: docker compose up --build
echo project down -> Synonim of: docker compose down
echo project shell -> Opens shell into container
echo project composer install -> Runs this commnad into countainer: composer install
echo project composer require [package name] ->Runs this commnad into countainer: composer require [package name]
echo project npm install -> Runs this commnad into countainer: npm install 
echo project npm run [script name] -> Runs this commnad into countainer: npm run [script name]

:end

