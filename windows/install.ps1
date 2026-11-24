#Requires -Version 5.1
[CmdletBinding()]
param(
    [ValidateRange(1024,65535)][int]$Port = 8080,
    [switch]$Plan,
    [switch]$NoInstall
)
$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
$projectRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
function Find-DockerFile {
    param([string]$Name, [string[]]$Candidates)
    $command = Get-Command $Name -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($command) { return $command.Source }
    foreach ($candidate in $Candidates) { if (Test-Path -LiteralPath $candidate -PathType Leaf) { return $candidate } }
    return $null
}
function Find-DockerCli {
    Find-DockerFile 'docker.exe' @("$env:ProgramFiles\Docker\Docker\resources\bin\docker.exe", "$env:LOCALAPPDATA\Programs\DockerDesktop\resources\bin\docker.exe")
}
function Find-DockerDesktop {
    Find-DockerFile 'Docker Desktop.exe' @("$env:ProgramFiles\Docker\Docker\Docker Desktop.exe", "$env:LOCALAPPDATA\Programs\DockerDesktop\Docker Desktop.exe")
}
# Use the local Windows pipe, never a remote Docker context or DOCKER_HOST.
$dockerEndpoint = 'npipe:////./pipe/docker_engine'
function Invoke-Docker {
    param([string[]]$Arguments)
    & $script:dockerExe --host $script:dockerEndpoint @Arguments
    if ($LASTEXITCODE -ne 0) { throw "Docker command failed (exit $LASTEXITCODE)." }
}
function Get-DockerOSType {
    if (!$script:dockerExe) { return $null }
    try {
        $result = & $script:dockerExe --host $script:dockerEndpoint info --format '{{.OSType}}' 2>$null
        if ($LASTEXITCODE -eq 0) { return ($result -join '').Trim() }
    } catch { }
    return $null
}
if (![Environment]::Is64BitOperatingSystem -or $env:PROCESSOR_ARCHITECTURE -eq 'ARM64') { throw 'This Windows bootstrap supports x64 only.' }
$dockerExe = Find-DockerCli
$desktopExe = Find-DockerDesktop
$backendFile = Join-Path $projectRoot '.idcc-docker-backend'
$savedBackend = if (Test-Path -LiteralPath $backendFile) { (Get-Content -LiteralPath $backendFile -Raw).Trim() } else { '' }
$osType = if (!$Plan) { Get-DockerOSType } else { $null }
if ($savedBackend -eq 'wsl' -or (!$desktopExe -and !$osType -and $savedBackend -ne 'desktop')) {
    & (Join-Path $PSScriptRoot 'wsl.ps1') -Action Install -Port $Port -Plan:$Plan -NoInstall:$NoInstall
    exit $LASTEXITCODE
}
if ($Plan) {
    [pscustomobject]@{ Mode='Reuse existing Windows Docker engine'; DockerCLI=$dockerExe; DockerDesktop=$desktopExe; MissingDockerAction='Never install Docker Desktop'; Project=$projectRoot; URL="http://127.0.0.1:$Port/DemoBeta/Home/" }
    return
}
foreach ($required in @('compose.yaml','docker\Dockerfile','Home\index.php','Home\vendor\autoload.php','env','docker\health.php','common\IDCC_plugin\jquery\jquery.min.js','common\language\language.php')) {
    if (!(Test-Path -LiteralPath (Join-Path $projectRoot $required))) { throw "Missing project file/folder: $required. Extract the entire repository first." }
}
$osType = Get-DockerOSType
if ($osType -eq 'windows') { throw 'Switch Docker Desktop to Linux containers, then rerun windows\install.cmd.' }
# Existing Desktop installations stay on the same engine to preserve volumes.
if (!$osType -and !$desktopExe) { throw 'Previously selected Windows Docker engine is unavailable. Start it; no backend migration was performed.' }
if (!$osType) {
    if (!$desktopExe) { throw 'Start your local Linux Docker engine and rerun.' }
    Write-Host 'Starting Docker Desktop. Complete any first-run prompts in its window.'
    Start-Process -FilePath $desktopExe -WindowStyle Hidden | Out-Null
    for ($attempt=0; $attempt -lt 90; $attempt++) {
        $osType = Get-DockerOSType
        if ($osType) { break }
        if ($attempt -and $attempt % 15 -eq 0) { Write-Host 'Waiting for Docker Desktop; check its setup/virtualization messages if needed...' }
        Start-Sleep -Seconds 2
    }
}
if ($osType -ne 'linux') { throw 'Docker Linux engine is not ready. Open Docker Desktop, finish setup, ensure WSL 2/virtualization are available, and rerun windows\install.cmd.' }
$composeFile = Join-Path $projectRoot 'compose.yaml'
$env:IDCC_HTTP_PORT = [string]$Port
# Refreshing the app never stops or removes an existing native Apache service.
# Preserve SQL Server credentials during profile upgrade; generate PostgreSQL credentials only once.
$pgEnv = Join-Path $projectRoot '.env.postgres'
if (!(Test-Path -LiteralPath $pgEnv)) {
    $bytes = New-Object byte[] 32
    $rng = [Security.Cryptography.RandomNumberGenerator]::Create()
    try { $rng.GetBytes($bytes) } finally { $rng.Dispose() }
    $password = [BitConverter]::ToString($bytes).Replace('-', '').ToLowerInvariant()
    [IO.File]::WriteAllText($pgEnv, "POSTGRES_PASSWORD=$password`n", (New-Object Text.UTF8Encoding($false)))
    Write-Host 'Created local PostgreSQL credential; existing SQL Server settings preserved.'
}
Import-Module (Join-Path $PSScriptRoot 'ConnectionProfiles.psm1') -Force
Initialize-IdccConnectionProfiles $projectRoot
Write-Host 'Validating Compose configuration...'
Invoke-Docker -Arguments @('compose','--file',$composeFile,'config','--quiet')
Write-Host 'Building Apache/PHP and starting the app. The first build can take several minutes...'
Invoke-Docker -Arguments @('compose','--file',$composeFile,'up','--detach','--build','--wait','--wait-timeout','180')
Invoke-Docker -Arguments @('compose','--file',$composeFile,'ps')
Write-Host "Docker environment verified. Open http://127.0.0.1:$Port/DemoBeta/Home/" -ForegroundColor Green
Write-Host 'Application files persist in this project folder. env is mounted read-only and excluded from the image.'
Write-Warning 'The existing PhpSpreadsheet 1.12.0 declares PHP ^7.1. PHP 8 application compatibility still needs separate testing/update.'

[IO.File]::WriteAllText($backendFile, 'desktop', (New-Object Text.UTF8Encoding($false)))
