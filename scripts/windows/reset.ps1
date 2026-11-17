#Requires -Version 5.1
[CmdletBinding()]
param([switch]$Plan)
$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
$projectRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..'))
Write-Host "Project: idcc-demobata ($projectRoot)"
Write-Host 'REMOVE: project containers and network; idcc-demobata-web:local image.'
Write-Host 'KEEP: all PostgreSQL volumes/data, .env, .env.postgres, source files, Docker/WSL, other projects.'
Write-Host 'The app will stop. Run install.cmd afterwards to rebuild.'
if ($Plan) { return }
$command = Get-Command docker.exe -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
$dockerExe = if ($command) { $command.Source } else { $null }
if (!$dockerExe) {
    foreach ($candidate in @("$env:ProgramFiles\Docker\Docker\resources\bin\docker.exe", "$env:LOCALAPPDATA\Programs\DockerDesktop\resources\bin\docker.exe")) {
        if (Test-Path -LiteralPath $candidate -PathType Leaf) { $dockerExe = $candidate; break }
    }
}
if (!$dockerExe) { throw 'Docker CLI not found. Nothing was removed.' }
function Invoke-LocalDocker {
    param([string[]]$DockerArgs)
    & $script:dockerExe --host 'npipe:////./pipe/docker_engine' @DockerArgs
    if ($LASTEXITCODE -ne 0) { throw "Docker failed (exit $LASTEXITCODE). Start Docker Desktop with Linux containers and retry." }
}
Invoke-LocalDocker -DockerArgs @('info','--format','{{.OSType}}')
Invoke-LocalDocker -DockerArgs @('ps','-a','--filter','label=com.docker.compose.project=idcc-demobata','--format','table {{.Names}}\t{{.Status}}')
if ((Read-Host 'Type RESET to remove the listed project environment') -cne 'RESET') { Write-Host 'Cancelled. Nothing was removed.'; return }
Invoke-LocalDocker -DockerArgs @('compose','--project-name','idcc-demobata','--project-directory',$projectRoot,'--env-file',(Join-Path $projectRoot 'docker\reset.env'),'--file',(Join-Path $projectRoot 'docker\reset.compose.yaml'),'down','--remove-orphans','--timeout','30')
# Never force image removal: another container may still need this image.
$imageIds = @(Invoke-LocalDocker -DockerArgs @('image','ls','--quiet','--filter','reference=idcc-demobata-web:local'))
if ($imageIds.Count -gt 0) { Invoke-LocalDocker -DockerArgs @('image','rm','idcc-demobata-web:local') }
Write-Host 'Reset complete. PostgreSQL data and credentials were kept. Run install.cmd to rebuild.' -ForegroundColor Green
