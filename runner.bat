@echo off

if not exist %CD%\src\ (
    echo %CD%\src folder not found
    goto end
)

if not exist "%CD%\app" mkdir "%CD%\app"


for %%I in (.) do set CurrDirName=%%~nxI

if [%1] == [] goto help
if [%2] == [] goto help

if "%2" == "image" goto image
if "%2" == "bash" goto bash
if "%2" == "kill" goto kill


docker run -ti -v %CD%\html:/app -e APPNAME=%CurrDirName% --rm %1 sh /vue_conf.sh %2
goto end

:bash
docker run -ti -e APPNAME=%CurrDirName% -p 8080 -w /app --rm %1 bash
goto end

:image
rem docker build -t %2 - < %CD%\conf\npm-runner-dockerfile
docker build -t %1 -f %CD%\conf\npm-runner-dockerfile .
goto end

:kill
del /s /f /q %CD%\app\
for /f %%f in ('dir /ad /b %CD%\app\') do rd /s /q %CD%\app\%%f
rmdir %CD%\app\
goto end

:help
echo runner [image name] [command]

:end