#Requires -Version 5.1
[CmdletBinding()]
param([switch]$Plan)
$ErrorActionPreference = 'Stop'
& (Join-Path $PSScriptRoot 'scripts\windows\reset.ps1') @PSBoundParameters
