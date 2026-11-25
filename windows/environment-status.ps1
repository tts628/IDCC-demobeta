#Requires -Version 5.1
# Read-only status probe. Never installs, starts or recreates project services.
$ErrorActionPreference='Stop'
[Console]::OutputEncoding=New-Object Text.UTF8Encoding($false)
$root=[IO.Path]::GetFullPath([IO.Path]::Combine($PSScriptRoot,'..'))
$result=[ordered]@{stage='environment_status';backend='not_configured';docker='unknown';version='';image='unknown';web='unknown';postgres='unknown'}
function Emit-Status { $result | ConvertTo-Json -Compress; exit 0 }
function Service-State($record){
    if(!$record){return 'missing'}
    if($record.State -ne 'running'){return 'stopped'}
    if($record.Status -match '\(unhealthy\)'){return 'unhealthy'}
    if($record.Status -match '\(healthy\)'){return 'healthy'}
    if($record.Status -match 'health: starting'){return 'starting'}
    return 'running'
}
try{
    $marker=[IO.Path]::Combine($root,'.idcc-docker-backend')
    if([IO.File]::Exists($marker)){$result.backend=[IO.File]::ReadAllText($marker).Trim()}
    if($result.backend -notin @('wsl','desktop')){$result.backend='not_configured';$result.docker='not_configured';Emit-Status}
    $docker=[IO.Path]::Combine($PSScriptRoot,'docker.ps1')
    $version=& $docker info --format '{{.ServerVersion}}' 2>$null
    if($LASTEXITCODE -ne 0){$result.docker='unavailable';Emit-Status}
    $result.docker='ready';$result.version=([string]($version -join '')).Trim()
    $image=& $docker image ls --filter 'reference=idcc-demobata-web:local' --format '{{.ID}}' 2>$null
    if($LASTEXITCODE -eq 0){$result.image=if(($image -join '').Trim()){'ready'}else{'missing'}}
    $raw=& $docker ps -a --filter 'label=com.docker.compose.project=idcc-demobata' --format '{{json .}}' 2>$null
    if($LASTEXITCODE -ne 0){Emit-Status}
    $records=@(foreach($line in $raw){if($line.Trim()){$line|ConvertFrom-Json}})
    foreach($service in @('web','postgres')){
        $record=@($records | Where-Object {$_.Labels -match ('(?:^|,)com\.docker\.compose\.service='+$service+'(?:,|$)')})
        if($record.Count -gt 1){$result[$service]='unknown'}else{$result[$service]=Service-State ($record|Select-Object -First 1)}
    }
}catch{if($result.docker -eq 'unknown'){$result.docker='unavailable'}}
Emit-Status
