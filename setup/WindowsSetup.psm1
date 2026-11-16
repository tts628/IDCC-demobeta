Set-StrictMode -Version Latest
function Find-Executable {
    param([string]$Command, [string[]]$Candidates)
    $found = Get-Command $Command -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($found) { return $found.Source }
    foreach ($candidate in $Candidates) {
        foreach ($item in @(Get-Item -Path $candidate -ErrorAction SilentlyContinue)) {
            if (!$item.PSIsContainer) { return $item.FullName }
        }
    }
    return $null
}
function Get-VerifiedDownload {
    param([string]$Uri, [string]$Destination, [string]$Sha256)
    if (Test-Path -LiteralPath $Destination) {
        if ((Get-FileHash -LiteralPath $Destination -Algorithm SHA256).Hash -eq $Sha256) { return }
        throw "Cached download has an unexpected checksum: $Destination"
    }
    $partial = "$Destination.part"
    & curl.exe --fail --location --silent --show-error --retry 2 --connect-timeout 30 --max-time 900 --proto '=https' --proto-redir '=https' --output $partial $Uri
    if ($LASTEXITCODE -ne 0) { throw "Download failed: $Uri" }
    if ((Get-FileHash -LiteralPath $partial -Algorithm SHA256).Hash -ne $Sha256) { throw "SHA256 verification failed: $Uri" }
    Move-Item -LiteralPath $partial -Destination $Destination -Force
}
function ConvertTo-ApachePath {
    param([string]$Path)
    if ($Path -match '["\r\n\$]') { throw 'Path contains characters unsupported by the Apache configuration generator.' }
    $Path.Replace('\', '/')
}
function New-IdccApacheConfig {
    param([string]$ApacheRoot, [string]$PhpRoot, [string]$IniRoot, [string]$WebRoot, [string]$AppRoot, [string]$StateRoot, [int]$Port)
    $a = ConvertTo-ApachePath $ApacheRoot
    $p = ConvertTo-ApachePath $PhpRoot
    $i = ConvertTo-ApachePath $IniRoot
    $w = ConvertTo-ApachePath $WebRoot
    $r = ConvertTo-ApachePath $AppRoot
    $s = ConvertTo-ApachePath $StateRoot
    @"
ServerRoot "$a"
Listen 127.0.0.1:$Port
ServerName localhost:$Port
PidFile "$s/httpd.pid"
LoadModule authz_core_module modules/mod_authz_core.so
LoadModule authz_host_module modules/mod_authz_host.so
LoadModule dir_module modules/mod_dir.so
LoadModule mime_module modules/mod_mime.so
LoadModule alias_module modules/mod_alias.so
LoadModule log_config_module modules/mod_log_config.so
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule php_module "$p/php8apache2_4.dll"
PHPIniDir "$i"
DocumentRoot "$w"
Alias /DemoBeta/ "$r/"
RedirectMatch ^/DemoBeta$ /DemoBeta/
DirectoryIndex index.php index.html
TypesConfig "$a/conf/mime.types"
ErrorLog "$s/logs/apache-error.log"
LogLevel warn
CustomLog "$s/logs/apache-access.log" common
ServerTokens Prod
ServerSignature Off
<Directory />
    AllowOverride None
    Require all denied
</Directory>
<Directory "$w">
    Options FollowSymLinks
    AllowOverride None
    Require local
</Directory>
<Directory "$r">
    Options FollowSymLinks
    AllowOverride None
    Require local
    <FilesMatch "\.php$">
        SetHandler application/x-httpd-php
    </FilesMatch>
</Directory>
<Directory "$r/env">
    Require all denied
</Directory>
<Directory "$r/setup">
    Require all denied
</Directory>
<LocationMatch "(?i)(^|/)(\.git|\.env[^/]*|env|setup|vendor)(/|$)">
    Require all denied
</LocationMatch>
<FilesMatch "(?i)(^\.|\.(ps1|psm1|cmd|bat|lock|log|ini|sql|bak)$|^composer\.json$)">
    Require all denied
</FilesMatch>
"@
}
Export-ModuleMember -Function Find-Executable,Get-VerifiedDownload,ConvertTo-ApachePath,New-IdccApacheConfig
