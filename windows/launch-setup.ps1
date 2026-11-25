#Requires -Version 5.1
[CmdletBinding()]
param(
    [ValidateSet('Install','Database','Pages','Exchange')][string]$Tab='Install',
    [switch]$SmokeTest,
    [string]$LogDirectory
)
$ErrorActionPreference='Stop'
$script:logFile=$null
try {
    if(!$LogDirectory){$LogDirectory=Join-Path $env:LOCALAPPDATA 'IDCC-DemoBeta'}
    [void](New-Item -ItemType Directory -Path $LogDirectory -Force)
    $script:logFile=Join-Path $LogDirectory 'setup-startup.log'
    $entry=Join-Path $PSScriptRoot 'setup.ps1'
    $catalog=Join-Path ([IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))) 'common\Config\pages.json'
    if(!(Test-Path -LiteralPath $entry -PathType Leaf)){throw 'Missing windows/setup.ps1. Download and extract the complete package.'}
    if(!(Test-Path -LiteralPath $catalog -PathType Leaf)){throw 'Missing common/Config/pages.json. Download and extract the complete package.'}
    if([Threading.Thread]::CurrentThread.GetApartmentState() -ne 'STA'){throw 'STA mode is required. Launch windows/setup.cmd, or run powershell.exe -STA.'}
    $identity=[Security.Principal.WindowsIdentity]::GetCurrent()
    $principal=New-Object Security.Principal.WindowsPrincipal($identity)
    if(!$SmokeTest -and !$principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)){
        # Keep cmd.exe quoting out of the PowerShell expression. Only quote real paths here.
        $arguments='-NoProfile -STA -ExecutionPolicy Bypass -File "'+$PSCommandPath+'" -Tab '+$Tab+' -LogDirectory "'+$LogDirectory+'"'
        $exe=Join-Path $PSHOME 'powershell.exe'
        $process=Start-Process -FilePath $exe -Verb RunAs -ArgumentList $arguments -PassThru -Wait
        if($process.ExitCode -ne 0){throw 'Setup did not start or was cancelled. See setup-startup.log for details.'}
        exit 0
    }
    & $entry -Tab $Tab -SmokeTest:$SmokeTest
    exit 0
} catch {
    $message=$_.Exception.Message
    $detail=(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')+"`r`n"+$_.Exception.GetType().FullName+"`r`n"+$message+"`r`n"+$_.InvocationInfo.PositionMessage+"`r`n"
    # Startup only: no database settings, environment dump or password arguments are logged.
    if($script:logFile){
        try {[IO.File]::AppendAllText($script:logFile,$detail,(New-Object Text.UTF8Encoding($false)))}catch{}
    }
    Write-Host ''
    Write-Host 'IDCC setup could not open.' -ForegroundColor Red
    Write-Host $message -ForegroundColor Yellow
    if($script:logFile){Write-Host ('Startup log: '+$script:logFile)}
    if(!$SmokeTest -and [Environment]::UserInteractive){[void](Read-Host 'Press Enter to close this error window')}
    exit 1
}
