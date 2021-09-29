@echo off

if not exist %CD%\html\ (
    echo %CD%\html folder not found
    goto end
)

for %%I in (.) do set CurrDirName=%%~nxI

if [%1] == [] goto help
if [%2] == [] goto help

if "%1" == "npm" goto npm
if "%1" == "composer" goto composer
if "%1" == "doit" goto doitall

:npm

if "%2" == "img" goto npmimg
if "%2" == "bash" goto npmbash
if "%2" == "init" goto npminit
if "%2" == "dev" goto npmidev
if "%2" == "build" goto npmbuild
if "%2" == "watch" goto npmwatch
goto help

:npmimg
docker build -t %CurrDirName%_npm_runner -f %CD%\conf\npm-runner-dockerfile .
goto end

:npmbash
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_npm_runner bash
goto end

:npminit
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh init
goto end

:npmidev
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh dev
goto end

:npmbuild
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh build
goto end

:npmwatch
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh watch
goto end

:composer

if "%2" == "img" goto comimg
if "%2" == "bash" goto combash
if "%2" == "init" goto cominit
goto help

:comimg
docker build -t %CurrDirName%_composer_runner -f %CD%\conf\php-runner-dockerfile .
goto end

:combash
docker run -ti -p 8080 -v %CD%\html:/html -w /html --rm %CurrDirName%_composer_runner sh
goto end

:cominit
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_composer_runner sh /composer_init.sh
goto end

:doitall
docker build -t %CurrDirName%_composer_runner -f %CD%\conf\php-runner-dockerfile .
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_composer_runner sh /composer_init.sh
docker build -t %CurrDirName%_npm_runner -f %CD%\conf\npm-runner-dockerfile .
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh init
docker run -ti -v %CD%\html:/html --rm %CurrDirName%_npm_runner sh /vue_conf.sh dev
docker compose up -d
goto end

:help
echo runner npm [img|bash|init|dev|build|watch]
echo OR
echo runner composer [img|bash|init]
echo OR
echo runner doitall

:end