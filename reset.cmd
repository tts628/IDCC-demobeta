@echo off
setlocal
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0reset.ps1"
set "IDCC_RESET_EXIT=%errorlevel%"
pause
exit /b %IDCC_RESET_EXIT%
