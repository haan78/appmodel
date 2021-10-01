@echo off

SET ID=
for /f %%i in ('docker ps -l -f "label=subutai.builder=1" -q') do set ID=%%i
if [%ID%] == [] (
    echo No builder container found
    goto end
)

if "%1" == "composer" goto composer
if "%1" == "npm" goto npm
if "%1" == "help" goto help

goto help

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
echo project composer install
echo project composer require
echo project npm install
echo project npm run [script name]

:end

