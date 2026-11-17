#Requires -Version 5.1
[CmdletBinding()]
param(
    [ValidateRange(1024,65535)][int]$Port = 8080,
    [switch]$Plan,
    [switch]$NoInstall
)
$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
$projectRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..\..'))
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
if ($Plan) {
    [pscustomobject]@{ Mode='Docker (Linux containers)'; DockerCLI=$dockerExe; DockerDesktop=$desktopExe; MissingDockerAction=$(if($NoInstall){'Stop'}else{'Install Docker Desktop and WSL prerequisites'}); Project=$projectRoot; URL="http://127.0.0.1:$Port/DemoBeta/Home/" }
    return
}
foreach ($required in @('compose.yaml','docker\Dockerfile','Home\index.php','Home\vendor\autoload.php','env','docker\health.php','common\IDCC_plugin\jquery\jquery.min.js','common\language\language.php')) {
    if (!(Test-Path -LiteralPath (Join-Path $projectRoot $required))) { throw "Missing project file/folder: $required. Extract the entire repository first." }
}
$osType = Get-DockerOSType
if ($osType -eq 'windows') { throw 'Switch Docker Desktop to Linux containers, then rerun install.cmd.' }
if (!$osType -and !$desktopExe) {
    if ($NoInstall) { throw 'No running local Linux Docker engine or Docker Desktop was found. Install/start Docker Desktop first.' }
    $identity = [Security.Principal.WindowsIdentity]::GetCurrent()
    $principal = New-Object Security.Principal.WindowsPrincipal($identity)
    if (!$principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) { throw 'Run install.cmd to authorize installation as Administrator.' }
    Write-Host 'Preparing WSL 2 for Docker Desktop...'
    $restartRequired = $false
    foreach ($feature in @('Microsoft-Windows-Subsystem-Linux','VirtualMachinePlatform')) {
        $status = Get-WindowsOptionalFeature -Online -FeatureName $feature
        if ($status.State -ne 'Enabled') {
            $change = Enable-WindowsOptionalFeature -Online -FeatureName $feature -All -NoRestart
            if ($change.RestartNeeded) { $restartRequired = $true }
        }
    }
    if ($restartRequired) {
        Write-Warning 'WSL prerequisites were enabled. Restart Windows, then run install.cmd again. This script will not restart Windows automatically.'
        exit 3010
    }
    $wslVersion = $null
    try {
        $wslOutput = (& wsl.exe --version 2>$null) -join "`n"
        if ($LASTEXITCODE -eq 0 -and ($wslOutput -replace "`0",'') -match '(\d+\.\d+\.\d+(?:\.\d+)?)') { $wslVersion = [version]$Matches[1] }
    } catch { }
    if (!$wslVersion) {
        & wsl.exe --install --no-distribution --no-launch
        if ($LASTEXITCODE -eq 3010) { Write-Warning 'Restart Windows, then rerun install.cmd.'; exit 3010 }
        if ($LASTEXITCODE -ne 0) { throw 'WSL installation failed. Check virtualization/Windows requirements and rerun.' }
    }
    if (!$wslVersion -or $wslVersion -lt [version]'2.1.5') {
        & wsl.exe --update --web-download
        if ($LASTEXITCODE -ne 0) { throw 'WSL update failed. Run wsl --update manually and rerun.' }
    } else { Write-Host "WSL $wslVersion detected; skipping installation/update." }
    $cache = Join-Path $env:ProgramData 'IDCC-DemoBeta\docker-cache'
    New-Item -ItemType Directory -Path $cache -Force | Out-Null
    $installer = Join-Path $cache 'Docker Desktop Installer.exe'
    Write-Host 'Downloading Docker Desktop from Docker...'
    & curl.exe --fail --location --silent --show-error --retry 2 --proto '=https' --proto-redir '=https' --output $installer 'https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe'
    if ($LASTEXITCODE -ne 0) { throw 'Docker Desktop download failed.' }
    $signature = Get-AuthenticodeSignature -LiteralPath $installer
    if ($signature.Status -ne 'Valid' -or $signature.SignerCertificate.GetNameInfo([Security.Cryptography.X509Certificates.X509NameType]::SimpleName,$false) -notmatch '^Docker Inc\.?$') { throw 'Docker installer signature verification failed.' }
    # Keep the vendor installer interactive so the user can review its terms.
    $installed = Start-Process -FilePath $installer -ArgumentList @('install','--backend=wsl-2') -PassThru -Wait
    if ($installed.ExitCode -eq 3010) { Write-Warning 'Restart Windows, then rerun install.cmd.'; exit 3010 }
    if ($installed.ExitCode -ne 0) { throw "Docker Desktop installation failed (exit $($installed.ExitCode))." }
    $dockerExe = Find-DockerCli
    $desktopExe = Find-DockerDesktop
    if (!$dockerExe -or !$desktopExe) { throw 'Docker Desktop paths were not found after installation. Reopen PowerShell and run install.cmd again.' }
} elseif (!$osType) {
    Write-Host 'Docker Desktop detected; skipping installation.'
} else { Write-Host 'Running local Linux Docker engine detected; skipping installation.' }
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
if ($osType -ne 'linux') { throw 'Docker Linux engine is not ready. Open Docker Desktop, finish setup, ensure WSL 2/virtualization are available, and rerun install.cmd.' }
$composeFile = Join-Path $projectRoot 'compose.yaml'
$env:IDCC_HTTP_PORT = [string]$Port
# Refreshing the app never stops or removes an existing native Apache service.
# Keep SQL Server .env unchanged; generate a separate PostgreSQL credential once.
$pgEnv = Join-Path $projectRoot '.env.postgres'
if (!(Test-Path -LiteralPath $pgEnv)) {
    $bytes = New-Object byte[] 32
    $rng = [Security.Cryptography.RandomNumberGenerator]::Create()
    try { $rng.GetBytes($bytes) } finally { $rng.Dispose() }
    $password = [BitConverter]::ToString($bytes).Replace('-', '').ToLowerInvariant()
    [IO.File]::WriteAllText($pgEnv, "POSTGRES_PASSWORD=$password`n", (New-Object Text.UTF8Encoding($false)))
    Write-Host 'Created local PostgreSQL credential; existing SQL Server settings preserved.'
}
Write-Host 'Validating Compose configuration...'
Invoke-Docker -Arguments @('compose','--file',$composeFile,'config','--quiet')
Write-Host 'Building Apache/PHP and starting the app. The first build can take several minutes...'
Invoke-Docker -Arguments @('compose','--file',$composeFile,'up','--detach','--build','--wait','--wait-timeout','180')
Invoke-Docker -Arguments @('compose','--file',$composeFile,'ps')
Write-Host "Docker environment verified. Open http://127.0.0.1:$Port/DemoBeta/Home/" -ForegroundColor Green
Write-Host 'Application files persist in this project folder. env is mounted read-only and excluded from the image.'
Write-Warning 'The existing PhpSpreadsheet 1.12.0 declares PHP ^7.1. PHP 8 application compatibility still needs separate testing/update.'
