#Requires -Version 5.1
[CmdletBinding()]
param(
    [string]$InstallRoot = (Join-Path $env:ProgramData 'IDCC-DemoBeta'),
    [ValidateRange(1024,65535)][int]$Port = 8080,
    [string]$ApacheExe,
    [string]$PhpExe,
    [switch]$Plan
)
$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
Import-Module (Join-Path $PSScriptRoot 'setup\WindowsSetup.psm1') -Force
function Invoke-Native {
    param([string]$File, [string[]]$Arguments)
    & $File @Arguments
    if ($LASTEXITCODE -ne 0) { throw "Command failed (exit $LASTEXITCODE): $File" }
}
function Install-MicrosoftPackage {
    param([string]$Uri, [string]$File, [string]$Arguments)
    & curl.exe --fail --location --silent --show-error --proto '=https' --proto-redir '=https' --output $File $Uri
    if ($LASTEXITCODE -ne 0) { throw "Download failed: $Uri" }
    $signature = Get-AuthenticodeSignature -LiteralPath $File
    if ($signature.Status -ne 'Valid' -or $signature.SignerCertificate.Subject -notmatch 'O=Microsoft Corporation') {
        throw "Microsoft signature verification failed: $File"
    }
    if ($File.EndsWith('.msi')) {
        $process = Start-Process msiexec.exe -ArgumentList "/i `"$File`" $Arguments" -WindowStyle Hidden -Wait -PassThru
    } else {
        $process = Start-Process -FilePath $File -ArgumentList $Arguments -WindowStyle Hidden -Wait -PassThru
    }
    if ($process.ExitCode -notin @(0,1638,3010)) { throw "Installer failed: $File (exit $($process.ExitCode))" }
    if ($process.ExitCode -eq 3010) { Write-Warning 'Windows restart is required by a prerequisite installer.' }
}
if (![Environment]::Is64BitOperatingSystem -or $env:PROCESSOR_ARCHITECTURE -eq 'ARM64') { throw 'This installer supports Windows x64 only.' }
$InstallRoot = [IO.Path]::GetFullPath($InstallRoot)
$appRoot = [IO.Path]::GetFullPath($PSScriptRoot)
foreach ($path in @($InstallRoot,$appRoot)) { ConvertTo-ApachePath $path | Out-Null }
$managedPhp = Join-Path $InstallRoot 'php'
$managedApache = Join-Path $InstallRoot 'apache\Apache24'
$stateFile = Join-Path $InstallRoot 'state.json'
$saved = $null
if (Test-Path -LiteralPath $stateFile) { $saved = Get-Content -LiteralPath $stateFile -Raw | ConvertFrom-Json }
if (!$PhpExe -and $saved) { $PhpExe = $saved.PhpExe }
if (!$ApacheExe -and $saved) { $ApacheExe = $saved.ApacheExe }
if (!$PhpExe) {
    $PhpExe = Find-Executable php.exe @((Join-Path $managedPhp 'php.exe'),'C:\php\php.exe','C:\xampp\php\php.exe','C:\wamp64\bin\php\php*\php.exe','C:\laragon\bin\php\php*\php.exe')
}
if (!$ApacheExe) {
    $ApacheExe = Find-Executable httpd.exe @((Join-Path $managedApache 'bin\httpd.exe'),'C:\Apache24\bin\httpd.exe','C:\xampp\apache\bin\httpd.exe','C:\wamp64\bin\apache\apache*\bin\httpd.exe','C:\laragon\bin\apache\httpd*\bin\httpd.exe')
    if (!$ApacheExe) {
        $apacheServices = @()
        try { $apacheServices = @(Get-CimInstance Win32_Service -Filter "PathName LIKE '%httpd.exe%'" -ErrorAction Stop) } catch { Write-Verbose 'Service discovery is unavailable; using executable path discovery.' }
        foreach ($service in $apacheServices) {
            if ($service.PathName -match '^\s*"([^"]*httpd\.exe)"|^\s*(.*?httpd\.exe)') {
                $candidate = if ($Matches[1]) { $Matches[1] } else { $Matches[2] }
                if (Test-Path -LiteralPath $candidate) { $ApacheExe = $candidate; break }
            }
        }
    }
}
if ($Plan) {
    [pscustomobject]@{ Project=$appRoot; InstallRoot=$InstallRoot; Apache=$(if($ApacheExe){"Reuse: $ApacheExe"}else{'Install Apache 2.4.71 x64'}); PHP=$(if($PhpExe){"Reuse: $PhpExe"}else{'Install PHP 8.3.36 TS x64'}); URL="http://127.0.0.1:$Port/DemoBeta/Home/" }
    return
}
$identity = [Security.Principal.WindowsIdentity]::GetCurrent()
if (!(New-Object Security.Principal.WindowsPrincipal($identity)).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
    throw 'Run install.cmd, or run PowerShell as Administrator.'
}
foreach ($required in @('Home\index.php','Home\vendor\autoload.php','env')) {
    if (!(Test-Path -LiteralPath (Join-Path $appRoot $required))) { throw "Missing project file/folder: $required. Extract the entire repository first." }
}
foreach ($explicit in @($ApacheExe,$PhpExe)) { if ($explicit -and !(Test-Path -LiteralPath $explicit -PathType Leaf)) { throw "Executable not found: $explicit" } }
$existingService = Get-Service -Name 'IDCC-DemoBeta' -ErrorAction SilentlyContinue
if ($existingService -and !$saved) { throw 'A service named IDCC-DemoBeta already exists but is not managed by this installer.' }
if ($existingService -and $saved.ApacheExe -ne $ApacheExe) { throw 'The existing IDCC service uses another Apache executable. Keep the saved Apache path.' }
New-Item -ItemType Directory -Path $InstallRoot,(Join-Path $InstallRoot 'cache'),(Join-Path $InstallRoot 'logs'),(Join-Path $InstallRoot 'www') -Force | Out-Null
$cache = Join-Path $InstallRoot 'cache'
# A current VC runtime is needed by the pinned VS18 Apache build.
$vc = Get-ItemProperty 'HKLM:\SOFTWARE\Microsoft\VisualStudio\14.0\VC\Runtimes\x64' -ErrorAction SilentlyContinue
if (!$vc -or !$vc.Installed -or ([version]($vc.Version -replace '^v','')) -lt [version]'14.52.37029') {
    Install-MicrosoftPackage 'https://aka.ms/vc14/vc_redist.x64.exe' (Join-Path $cache 'vc_redist.x64.exe') '/install /quiet /norestart'
} else { Write-Host 'Visual C++ runtime detected; skipping installation.' }
if (!$ApacheExe) {
    $zip = Join-Path $cache 'apache-2.4.71.zip'
    Get-VerifiedDownload 'https://www.apachelounge.com/download/VS18/binaries/httpd-2.4.71-261112-Win64-VS18.zip' $zip '44961A929DFF16B767B3ADBCEA55760A942A00239B2891DC55806BE34029E1FC'
    Expand-Archive -LiteralPath $zip -DestinationPath (Join-Path $InstallRoot 'apache') -Force
    $ApacheExe = Join-Path $managedApache 'bin\httpd.exe'
} else { Write-Host "Apache detected; skipping installation: $ApacheExe" }
$ownPhp = !$PhpExe -or $PhpExe -eq (Join-Path $managedPhp 'php.exe')
if (!$PhpExe) {
    $zip = Join-Path $cache 'php-8.3.36.zip'
    Get-VerifiedDownload 'https://downloads.php.net/~windows/releases/php-8.3.36-1-Win32-vs16-x64.zip' $zip '126DD3CC8C19DF5085B9420B2BC74ED591DE3D98528858C52FC42739B7BBA92E'
    Expand-Archive -LiteralPath $zip -DestinationPath $managedPhp -Force
    $PhpExe = Join-Path $managedPhp 'php.exe'
} else { Write-Host "PHP detected; skipping installation: $PhpExe" }
$phpRoot = Split-Path $PhpExe
$apacheRoot = Split-Path (Split-Path $ApacheExe)
$phpFacts = & $PhpExe -n -r 'echo json_encode(["version"=>PHP_VERSION,"bits"=>PHP_INT_SIZE*8,"ts"=>PHP_ZTS]);'
if ($LASTEXITCODE -ne 0) { throw 'Existing PHP could not start. Check its Visual C++ runtime.' }
$facts = $phpFacts | ConvertFrom-Json
if ($facts.bits -ne 64 -or !$facts.ts -or $facts.version -notmatch '^8\.' -or !(Test-Path (Join-Path $phpRoot 'php8apache2_4.dll'))) {
    throw 'Existing PHP must be x64, PHP 8, Thread Safe, with php8apache2_4.dll. Existing installation was preserved. Supply -PhpExe to a compatible installation.'
}
if (!(Get-OdbcDriver -Name 'ODBC Driver 18 for SQL Server' -Platform '64-bit' -ErrorAction SilentlyContinue) -and !(Get-OdbcDriver -Name 'ODBC Driver 17 for SQL Server' -Platform '64-bit' -ErrorAction SilentlyContinue)) {
    Install-MicrosoftPackage 'https://go.microsoft.com/fwlink/?linkid=2378279' (Join-Path $cache 'msodbcsql.msi') '/qn /norestart IACCEPTMSODBCSQLLICENSETERMS=YES'
} else { Write-Host 'SQL Server ODBC driver detected; skipping installation.' }
$iniRoot = $phpRoot
if ($ownPhp) {
    $zip = Join-Path $cache 'sqlsrv-5.13.0.zip'
    Get-VerifiedDownload 'https://github.com/microsoft/msphpsql/releases/download/v5.13.0/Windows_5.13.0RTW.zip' $zip '6D3A11C01ABC373DFCB028E8D5E6A63C812802CE00CEDE53AC61777E6D6FB558'
    $sqlRoot = Join-Path $cache 'sqlsrv-5.13.0'
    Expand-Archive -LiteralPath $zip -DestinationPath $sqlRoot -Force
    foreach ($driver in @('php_sqlsrv_83_ts_x64.dll','php_pdo_sqlsrv_83_ts_x64.dll')) {
        Copy-Item -LiteralPath (Join-Path $sqlRoot "Windows\$driver") -Destination (Join-Path $phpRoot "ext\$driver") -Force
    }
    $iniRoot = Join-Path $InstallRoot 'config'
    New-Item -ItemType Directory -Path $iniRoot,(Join-Path $InstallRoot 'sessions') -Force | Out-Null
    $ini = Get-Content -LiteralPath (Join-Path $phpRoot 'php.ini-production') -Raw
    $ini += "`r`nextension_dir=`"$(ConvertTo-ApachePath (Join-Path $phpRoot 'ext'))`"`r`n"
    foreach ($extension in @('curl','fileinfo','gd','mbstring','openssl','zip','mysqli','pdo_mysql','php_sqlsrv_83_ts_x64.dll','php_pdo_sqlsrv_83_ts_x64.dll')) { $ini += "extension=$extension`r`n" }
    $ini += "date.timezone=Asia/Taipei`r`nsession.save_path=`"$(ConvertTo-ApachePath (Join-Path $InstallRoot 'sessions'))`"`r`nlog_errors=On`r`ndisplay_errors=Off`r`nerror_log=`"$(ConvertTo-ApachePath (Join-Path $InstallRoot 'logs\php-error.log'))`"`r`n"
    [IO.File]::WriteAllText((Join-Path $iniRoot 'php.ini'),$ini,(New-Object Text.UTF8Encoding($false)))
}
# Existing PHP installations are reused as configured, never rewritten.
$modulesJson = & $PhpExe -c $iniRoot -r 'echo json_encode(get_loaded_extensions());'
if ($LASTEXITCODE -ne 0) { throw 'PHP extension loading failed.' }
$modules = $modulesJson | ConvertFrom-Json
$requiredModules = @('ctype','dom','curl','openssl','fileinfo','gd','iconv','mbstring','SimpleXML','xml','xmlreader','xmlwriter','zip','sqlsrv','pdo_sqlsrv','mysqli','pdo_mysql')
$missing = @($requiredModules | Where-Object { $_ -notin $modules })
if ($missing.Count) { throw "PHP extensions missing: $($missing -join ', '). Enable them in the existing PHP configuration, then rerun. Existing PHP configuration was preserved." }
$webRoot = Join-Path $InstallRoot 'www'
$junction = Join-Path $webRoot 'DemoBeta'
if (Test-Path -LiteralPath $junction) {
    $item = Get-Item -LiteralPath $junction
    if ($item.LinkType -ne 'Junction' -or [IO.Path]::GetFullPath([string]$item.Target[0]).TrimEnd('\') -ne $appRoot.TrimEnd('\')) { throw 'DemoBeta website directory points to another project. Use the original project location or a different -InstallRoot.' }
} else { New-Item -ItemType Junction -Path $junction -Target $appRoot | Out-Null }
$configPath = Join-Path $InstallRoot 'httpd.conf'
$config = New-IdccApacheConfig $apacheRoot $phpRoot $iniRoot $webRoot $appRoot $InstallRoot $Port
$candidateConfig = Join-Path $InstallRoot 'httpd.candidate.conf'
[IO.File]::WriteAllText($candidateConfig,$config,(New-Object Text.UTF8Encoding($false)))
Invoke-Native $ApacheExe @('-t','-f',$candidateConfig)
$connections = @(Get-NetTCPConnection -State Listen -LocalPort $Port -ErrorAction SilentlyContinue)
$servicePid = if ($existingService) { (Get-CimInstance Win32_Service -Filter "Name='IDCC-DemoBeta'").ProcessId } else { 0 }
$ownPids = @($servicePid)
if ($servicePid) { $ownPids += @(Get-CimInstance Win32_Process -Filter "ParentProcessId=$servicePid" | Select-Object -ExpandProperty ProcessId) }
if (@($connections | Where-Object { $_.OwningProcess -notin $ownPids }).Count) { throw "Port $Port is occupied. Rerun with -Port 8081 (or another free port)." }
if ($existingService -and $existingService.Status -ne 'Stopped') { Stop-Service 'IDCC-DemoBeta' -ErrorAction Stop }
$listener = New-Object Net.Sockets.TcpListener([Net.IPAddress]::Loopback,$Port)
try { $listener.Start() } catch { throw "Port $Port is occupied. Rerun with -Port 8081 (or another free port)." } finally { $listener.Stop() }
if (Test-Path $configPath) { Copy-Item $configPath "$configPath.previous" -Force }
Copy-Item $candidateConfig $configPath -Force
if (!$existingService) { Invoke-Native $ApacheExe @('-k','install','-n','IDCC-DemoBeta','-f',$configPath) }
@{ ApacheExe=$ApacheExe; PhpExe=$PhpExe; Project=$appRoot; Port=$Port; Config=$configPath } | ConvertTo-Json | Set-Content -LiteralPath $stateFile -Encoding UTF8
Set-Service 'IDCC-DemoBeta' -StartupType Automatic
Start-Service 'IDCC-DemoBeta'
# Verify PHP through Apache, using a temporary local endpoint removed afterwards.
$probeName = 'idcc-check-' + [guid]::NewGuid().ToString('N') + '.php'
$probeFile = Join-Path $appRoot $probeName
try {
    [IO.File]::WriteAllText($probeFile,'<?php header("Content-Type: application/json"); echo json_encode(["php"=>PHP_VERSION,"sqlsrv"=>extension_loaded("sqlsrv"),"pdo_sqlsrv"=>extension_loaded("pdo_sqlsrv")]);',(New-Object Text.UTF8Encoding($false)))
    $health = $null
    for ($attempt=0; $attempt -lt 10; $attempt++) {
        try { $health = Invoke-RestMethod "http://127.0.0.1:$Port/DemoBeta/$probeName" -TimeoutSec 5; break } catch { if($attempt -eq 9){throw}; Start-Sleep -Seconds 1 }
    }
    if (!$health.php -or !$health.sqlsrv -or !$health.pdo_sqlsrv) { throw 'Apache/PHP/SQL Server extension health check failed.' }
} finally { if (Test-Path -LiteralPath $probeFile) { Remove-Item -LiteralPath $probeFile -Force } }
Write-Host "Environment verified. Open http://127.0.0.1:$Port/DemoBeta/Home/" -ForegroundColor Green
Write-Host "Logs and runtime: $InstallRoot"
Write-Warning 'The bundled PhpSpreadsheet 1.12.0 declares PHP ^7.1. Application compatibility with PHP 8 needs separate validation/update. No application dependencies were changed.'
Write-Host 'Database credentials and database availability must be configured separately in env. SQL Server itself is not installed.'
