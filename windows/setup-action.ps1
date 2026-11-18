#Requires -Version 5.1
[CmdletBinding()]
param([ValidateSet('Install','Plan','Apply','Diagnose','Reset')][string]$Action,[ValidateRange(1024,65535)][int]$Port=8080)
$ErrorActionPreference='Stop'
[Console]::OutputEncoding=New-Object Text.UTF8Encoding($false)
try {
    Set-Location ([IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..')))
    $env:IDCC_HTTP_PORT=[string]$Port
    switch($Action){
        Install { & (Join-Path $PSScriptRoot 'install.ps1') -Port $Port }
        Plan { & (Join-Path $PSScriptRoot 'install.ps1') -Port $Port -Plan }
        Apply { & (Join-Path $PSScriptRoot 'docker.ps1') compose up -d --force-recreate --wait --wait-timeout 180 }
        Diagnose { & (Join-Path $PSScriptRoot 'docker.ps1') compose exec -T web php docker/diagnose-setup.php }
        Reset { & (Join-Path $PSScriptRoot 'reset.ps1') -Confirmed }
    }
    if($null -ne $LASTEXITCODE){exit $LASTEXITCODE}
    exit 0
} catch {
    # Do not serialize environment variables or database settings into logs.
    Write-Error $_.Exception.Message
    exit 1
}
