#Requires -Version 5.1
[CmdletBinding()]
param(
    [ValidateRange(1024,65535)][int]$Port = 8080,
    [switch]$Plan,
    [switch]$NoInstall
)
$ErrorActionPreference = 'Stop'
& (Join-Path $PSScriptRoot 'scripts\windows\install.ps1') @PSBoundParameters
if ($LASTEXITCODE -eq 3010) { exit 3010 }
