@echo off
setlocal
set "IDCC_GUI_SCRIPT=%~dp0setup.ps1"
powershell.exe -NoProfile -ExecutionPolicy Bypass -Command "try { $p = Start-Process powershell.exe -Verb RunAs -ArgumentList ('-NoProfile -STA -ExecutionPolicy Bypass -File ""' + $env:IDCC_GUI_SCRIPT + '"" -Tab Pages') -PassThru -Wait; exit $p.ExitCode } catch { Write-Error $_; exit 1 }"
exit /b %errorlevel%
