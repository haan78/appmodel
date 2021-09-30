@echo off

if not exist %CD%\html\ (
    echo %CD%\html folder not found
    goto end
)

for %%I in (.) do set CurrDirName=%%~nxI

if "%1" == "img" goto img

:img
if not exist %CD%\conf\ (
    echo %CD%\conf folder not found
    goto end
)
docker build -t %CurrDirName%_builder -f %CD%\conf\builder-dockerfile %CD%\conf
goto end

:shell
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_builder sh
goto end

:npm
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_builder sh /builder npm $2
goto end

:composer
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_builder sh /builder composer $2
goto end

:all
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_builder sh /builder all $2 $3
goto end

:help
echo Usage
echo builder shell : Opens shell in to /html folder on builder container
rem echo builder npm [options] : options can be one of this set(install, build, dev, watch)
echo builder npm install : Removes node_modules, dit and package-lock.json files and run `npm install`
echo builder npm build : Builds project with production mode
echo builder npm dev : Builds project with development mode
echo builder npm watch : Runs watch command wiht development mode
echo \n
echo builder composer install : Removes vendor and composer.lock and run `composer install`
echo builder composer require [package name] : Install a new package by composer
echo \n
echo builder all install : Runs `composer install` and `npm install` together
echo builder all install [npm commad] : Add a npm command(build, dev, watch) after insatall


:end
