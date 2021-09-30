@echo off

SET ID=
for /f %%i in ('docker ps -l -f "label=subutai.builder=1" -q') do set ID=%%i
if [%ID%] == [] (
    echo No builder container found
    goto end
)

if ["%1"] == "composer" goto composer
if ["%1"] == "npm" goto npm
if ["%1"] == "all" goto all

goto help

:composer

if ["%2"] == "install" (
    docker exec -ti %ID% sh /builder.sh composer install
    goto end
)

if ["%2"] == ""

goto end

:npm
goto end

:all
goto end

:help
goto end

:end

