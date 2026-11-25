#Requires -Version 5.1
[CmdletBinding()]
param([ValidateSet('Enable','Disable','Status','Logs')][string]$Action='Status')
$ErrorActionPreference='Stop'
$docker=Join-Path $PSScriptRoot '..\windows\docker.ps1'
switch($Action){
  Enable {
    & $docker compose exec -T web php DataExchange/sync.php --check
    if($LASTEXITCODE -ne 0){exit $LASTEXITCODE}
    & $docker compose --profile data-exchange up -d --no-build data-exchange
  }
  Disable { & $docker compose --profile data-exchange stop data-exchange }
  Status { & $docker compose --profile data-exchange ps -a data-exchange }
  Logs { & $docker compose --profile data-exchange logs --tail 100 data-exchange }
}
exit $LASTEXITCODE
