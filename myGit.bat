@ECHO OFF
setlocal
title My Git Helper
color 0A

:start
cls
echo.
echo Select a number:
echo.
echo [1] git log
echo.
echo [2] git commit
echo.
echo [3] git commit changes
echo.
echo [4] set proxy
echo.
echo [5] unset proxy
echo.
echo [6] Exit
echo.


choice /c 123456 /m "Enter your choice:"
if errorlevel 6 goto :end
if errorlevel 5 (
cls
echo.
echo Unsetting Proxy...
goto unset_proxy
)


if errorlevel 4 (
cls
echo.
echo Setting Proxy...
goto set_proxy 
)

if errorlevel 3 (
cls
echo.
echo Show Commit Changes...
goto show_chnages
)

if errorlevel 2 (
cls
echo.
echo Git Commit...
goto git_commit
)

if errorlevel 1 (
cls
echo.
echo Git Log...
goto git_log
)



:git_log
set /p count="Enter logs count: "
powershell -c git log --oneline -n"%count%"
pause
goto startloop

:git_commit
set /p commitText="Enter commit text: "
powershell -c git add .; git commit -m "%commitText%"; git push
pause
goto startloop


:show_chnages
set /p commitID="Enter commit id:"
powershell -c git show --pretty="" --name-only "%commitID%"
pause
goto startloop

:set_proxy
set /p proxyType="Enter proxy type: "
set /p proxyIP="Enter proxy ip: "
set /p proxyPort="Enter proxy port: "
powershell -c git config --global http.proxy "%proxyType%"://"%proxyIP%":"%proxyPort%"
pause
goto startloop

:unset_proxy
powershell -c git config --global --unset http.proxy
pause
goto startloop


:startover
cls
echo.
echo Restarting script...
timeout 2 >nul
goto start

:startloop
cls
echo.
choice /c ye /m "Continue Y or [E]xit?"
if errorlevel 2 goto end
if errorlevel 1 goto startover


:end
cls
echo.
echo Exiting...
timeout 2 >nul
endlocal
exit /b