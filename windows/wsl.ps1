#Requires -Version 5.1
[CmdletBinding()]
param(
    [ValidateSet('Install','Reset','Docker')][string]$Action = 'Install',
    [ValidateRange(1024,65535)][int]$Port = 8080,
    [switch]$Plan,
    [switch]$NoInstall,
    [switch]$Confirmed,
    [string[]]$DockerArgs = @()
)
$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
$projectRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$distro = 'Ubuntu-24.04'
if ($Plan) {
    [pscustomobject]@{ Backend='WSL 2 + Ubuntu-24.04 + Docker Engine (no Docker Desktop)'; Action=$Action; Project=$projectRoot; InstallMissing=(!$NoInstall); URL="http://127.0.0.1:$Port/DemoBeta/Home/" }
    exit 0
}
function Assert-Administrator {
    $principal = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
    if (!$principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) { throw 'Run windows\install.cmd as the same Windows user to enable WSL prerequisites.' }
}
function Invoke-Wsl {
    param([string[]]$WslArgs)
    & wsl.exe @WslArgs
    if ($LASTEXITCODE -eq 3010) { Write-Warning 'Restart Windows, then rerun windows\install.cmd.'; exit 3010 }
    if ($LASTEXITCODE -ne 0) { throw "WSL command failed (exit $LASTEXITCODE). See docs/WINDOWS-SETUP.md." }
}
if ($Action -eq 'Install' -and !$NoInstall) {
    Assert-Administrator
    $restart = $false
    foreach ($feature in @('Microsoft-Windows-Subsystem-Linux','VirtualMachinePlatform')) {
        if ((Get-WindowsOptionalFeature -Online -FeatureName $feature).State -ne 'Enabled') {
            $result = Enable-WindowsOptionalFeature -Online -FeatureName $feature -All -NoRestart
            if ($result.RestartNeeded) { $restart = $true }
        }
    }
    if ($restart) { Write-Warning 'WSL features enabled. Restart Windows and rerun windows\install.cmd.'; exit 3010 }
    $versionText = ''
    try { $versionText = ((& wsl.exe --version 2>$null) -join "`n") -replace "`0",'' } catch { }
    if ($versionText -notmatch '(\d+\.\d+\.\d+)') {
        Invoke-Wsl -WslArgs @('--install','--no-distribution','--no-launch')
        Invoke-Wsl -WslArgs @('--update','--web-download')
    } elseif ([version]$Matches[1] -lt [version]'2.1.5') {
        Invoke-Wsl -WslArgs @('--update','--web-download')
    }
}
if (!(Get-Command wsl.exe -ErrorAction SilentlyContinue)) { throw 'WSL is missing. Run windows\install.cmd first.' }
$names = @((& wsl.exe --list --quiet) | ForEach-Object { ($_ -replace "`0",'').Trim() })
if ($LASTEXITCODE -ne 0) { throw 'Cannot list WSL distributions. Finish WSL setup and rerun.' }
if ($names -notcontains $distro) {
    if ($Action -ne 'Install' -or $NoInstall) { throw 'Ubuntu-24.04 is not installed. Nothing was removed. Run windows\install.cmd.' }
    Invoke-Wsl -WslArgs @('--install','--distribution',$distro,'--no-launch','--web-download')
}
$base = @('--distribution',$distro,'--user','root')
$kernel = (& wsl.exe @base --exec uname -r) -join ''
if ($LASTEXITCODE -ne 0) { throw 'Ubuntu first-run setup is incomplete. Open Ubuntu-24.04 once, complete its prompts, then rerun windows\install.cmd.' }
if ($kernel -notmatch 'WSL2') { throw 'Ubuntu-24.04 must use WSL 2. Back up that distribution, then run: wsl --set-version Ubuntu-24.04 2' }
$base += @('--cd',$projectRoot,'--exec')
if ($Action -eq 'Install') {
    # Ubuntu 24.04 WSL enables systemd by default. Do not overwrite an existing wsl.conf.
    & wsl.exe @base test -d /run/systemd/system
    if ($LASTEXITCODE -ne 0) { throw 'Enable systemd in Ubuntu /etc/wsl.conf ([boot] systemd=true), restart that distribution, and retry. See docs/WINDOWS-SETUP.md.' }
    # A persistent WSL client prevents idle shutdown. flock permits only one keeper.
    Start-Process -FilePath (Get-Command wsl.exe).Source -WindowStyle Hidden -ArgumentList @('--distribution',$distro,'--user','root','--cd',('"'+$projectRoot+'"'),'--exec','bash','ubuntu/wsl-keepalive.sh') | Out-Null
    $installArgs = @('bash','ubuntu/install.sh','--port',[string]$Port)
    if ($NoInstall) { $installArgs += '--no-install' }
    Invoke-Wsl -WslArgs ($base + $installArgs)
    [IO.File]::WriteAllText((Join-Path $projectRoot '.idcc-docker-backend'), 'wsl', (New-Object Text.UTF8Encoding($false)))
    Write-Host 'WSL Docker environment ready. No Docker Desktop was installed.' -ForegroundColor Green
} elseif ($Action -eq 'Reset') {
    $resetArgs = @('bash','ubuntu/reset.sh')
    if ($Confirmed) { $resetArgs += '--confirmed' }
    Invoke-Wsl -WslArgs ($base + $resetArgs)
} else {
    # Explicit local socket; never use Docker Desktop integration or a remote context.
    Invoke-Wsl -WslArgs ($base + @('env','-u','DOCKER_HOST','-u','DOCKER_CONTEXT','docker','--host','unix:///var/run/docker.sock') + $DockerArgs)
}
exit 0
