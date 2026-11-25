#Requires -Version 5.1
[CmdletBinding()]
param([ValidateSet('Install','Plan','Apply','Diagnose','Reset','SyncCheck','SyncEnable','SyncDisable','SyncInspect','EnvironmentInspect')][string]$Action,[ValidateRange(1024,65535)][int]$Port=8080)
$ErrorActionPreference='Stop'
[Console]::OutputEncoding=New-Object Text.UTF8Encoding($false)
try {
    Set-Location ([IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..')))
    $env:IDCC_HTTP_PORT=[string]$Port
    switch($Action){
        EnvironmentInspect { & ([IO.Path]::Combine($PSScriptRoot,'environment-status.ps1')) }
        Install { & (Join-Path $PSScriptRoot 'install.ps1') -Port $Port }
        Plan { & (Join-Path $PSScriptRoot 'install.ps1') -Port $Port -Plan }
        Apply { & (Join-Path $PSScriptRoot 'docker.ps1') compose up -d --force-recreate --wait --wait-timeout 180 }
        Diagnose { & (Join-Path $PSScriptRoot 'docker.ps1') compose exec -T web php docker/diagnose-setup.php }
        SyncCheck { & (Join-Path $PSScriptRoot 'docker.ps1') compose exec -T web php DataExchange/sync.php --check }
        SyncEnable { & (Join-Path $PSScriptRoot '..\DataExchange\schedule.ps1') -Action Enable }
        SyncDisable { & (Join-Path $PSScriptRoot '..\DataExchange\schedule.ps1') -Action Disable }
        SyncInspect {
            $docker=Join-Path $PSScriptRoot 'docker.ps1'
            $raw=& $docker compose --profile data-exchange ps -a --format json data-exchange
            if($LASTEXITCODE -ne 0){exit $LASTEXITCODE}
            $records=@(foreach($line in $raw){if($line.Trim()){ $line | ConvertFrom-Json }})
            $state=if(!$records.Count){'missing'}elseif(@($records | Where-Object {$_.State -eq 'running'}).Count){'running'}elseif(@($records | Where-Object {$_.State -in @('exited','created')}).Count){'stopped'}else{'unready'}
            Write-Output ('IDCC_SYNC_STATUS:'+$state)
            if($records.Count){ & $docker compose --profile data-exchange logs --no-color --tail 100 data-exchange }
        }
        Reset { & (Join-Path $PSScriptRoot 'reset.ps1') -Confirmed }
    }
    if($null -ne $LASTEXITCODE){exit $LASTEXITCODE}
    exit 0
} catch {
    # Do not serialize environment variables or database settings into logs.
    Write-Error $_.Exception.Message
    exit 1
}
