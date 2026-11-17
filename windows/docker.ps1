#Requires -Version 5.1
# Usage from the project root: .\windows\docker.ps1 compose ps
$ErrorActionPreference = 'Stop'
$projectRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$backendFile = Join-Path $projectRoot '.idcc-docker-backend'
$backend = if (Test-Path -LiteralPath $backendFile) { (Get-Content -LiteralPath $backendFile -Raw).Trim() } else { '' }
if ($backend -eq 'wsl') {
    & (Join-Path $PSScriptRoot 'wsl.ps1') -Action Docker -DockerArgs $args
    exit $LASTEXITCODE
}
if ($backend -ne 'desktop') { throw 'Run windows\install.cmd first to select the Docker engine.' }
$command = Get-Command docker.exe -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
$dockerExe = if ($command) { $command.Source } else { "$env:ProgramFiles\Docker\Docker\resources\bin\docker.exe" }
if (!(Test-Path -LiteralPath $dockerExe)) { $dockerExe = "$env:LOCALAPPDATA\Programs\DockerDesktop\resources\bin\docker.exe" }
Push-Location $projectRoot
try {
    & $dockerExe --host 'npipe:////./pipe/docker_engine' @args
    $code = $LASTEXITCODE
} finally { Pop-Location }
exit $code
