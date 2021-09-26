@echo off

if not exist "%CD%\app" mkdir "%CD%\app"
for %%I in (.) do set CurrDirName=%%~nxI

set imgname=%CurrDirName%/npm
set cname=%CurrDirName%_con

if "%1" == "image" goto image
if "%1" == "bash" goto bash
if "%1" == "kill" goto kill

docker run -ti -v %CD%\app:/app -e APPNAME=%CurrDirName% -p 8080 -w / --name "%cname%" "%imgname%"1 sh /vuedef/vue_conf.sh %1

goto end

:bash
docker run -ti -v %CD%\app:/app -e APPNAME=%CurrDirName% -p 8080 -w / --name "%cname%"  "%imgname%" bash
goto end

:image
docker build -t "%imgname%" .
goto end

:kill
if not exist %CD%\app\%CurrDirName% goto end
del /s /f /q %CD%\app\%CurrDirName%
for /f %%f in ('dir /ad /b %CD%\app\%CurrDirName%\') do rd /s /q %CD%\app\%CurrDirName%\%%f
rmdir %CD%\app\%CurrDirName%
goto end


:end