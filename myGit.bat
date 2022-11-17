@ECHO OFF


:choice
set /P c=Choose Your Operation(1:log, 2:commit, 3:show changes, 4:set proxy, 5:unset proxy) :
if /I "%c%" EQU "1" goto :git_log
if /I "%c%" EQU "2" goto :git_commit
if /I "%c%" EQU "3" goto :show_chnages
if /I "%c%" EQU "4" goto :set_proxy
if /I "%c%" EQU "5" goto :unset_proxy
goto :choice


:git_log
set /p count="Enter logs count: "
powershell -c git log --oneline -n"%count"
REM echo "Crawling Has Started..."
pause
cls

:git_commit
set /p commitText = "Enter commit text: "
powershell -c git add .; git commit -m "%commitText%"; git push
pause
cls

:show_chnages
set /p commitID = "Enter commit id:"
powershell -c git show --pretty="" --name-only "%commitID"
pause
cls

:set_proxy
set /p proxyType = "Enter proxy type: "
set /p proxyIP = "Enter proxy ip: "
set /p proxyPort = "Enter proxy port: "
powershell -c git config --global http.proxy "%proxyType"://"%proxyIP":"%proxyPort"
pause
cls

:unset_proxy
powershell -c git config --global --unset http.proxy
pause
cls
exit
