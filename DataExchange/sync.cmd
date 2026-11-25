@echo off
setlocal
chcp 65001 >nul
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0sync.ps1" %*
set "IDCC_EXCHANGE_EXIT=%errorlevel%"
if not "%IDCC_EXCHANGE_EXIT%"=="0" echo Exchange did not complete. Please review the message above.
pause
exit /b %IDCC_EXCHANGE_EXIT%
