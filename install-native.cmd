@echo off
setlocal
set "IDCC_SETUP_SCRIPT=%~dp0install-native.ps1"
powershell.exe -NoProfile -ExecutionPolicy Bypass -Command "try { $p = Start-Process powershell.exe -Verb RunAs -ArgumentList ('-NoProfile -ExecutionPolicy Bypass -NoExit -File ""' + $env:IDCC_SETUP_SCRIPT + '""') -PassThru -Wait; exit $p.ExitCode } catch { Write-Error $_; exit 1 }"
exit /b %errorlevel%
