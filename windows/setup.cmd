@echo off
setlocal
set "IDCC_POWERSHELL=%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe"
if not exist "%IDCC_POWERSHELL%" (
  echo Windows PowerShell was not found.
  pause
  exit /b 1
)
"%IDCC_POWERSHELL%" -NoProfile -STA -ExecutionPolicy Bypass -File "%~dp0launch-setup.ps1" -Tab Install
set "IDCC_SETUP_EXIT=%errorlevel%"
if not "%IDCC_SETUP_EXIT%"=="0" (
  echo.
  echo Setup failed. Please copy the error shown above.
  pause
)
exit /b %IDCC_SETUP_EXIT%
