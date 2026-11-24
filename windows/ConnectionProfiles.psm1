function Read-IdccEnvFile([string]$Path){
    $values=@{}
    if(Test-Path -LiteralPath $Path){foreach($line in [IO.File]::ReadAllLines($Path)){
        if($line -match '^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$'){
            $key=$Matches[1];$value=$Matches[2].Trim()
            if($value.Length -ge 2 -and $value[0] -eq "'" -and $value[$value.Length-1] -eq "'"){$value=$value.Substring(1,$value.Length-2).Replace("\'","'")}
            elseif($value.Length -ge 2 -and $value[0] -eq '"' -and $value[$value.Length-1] -eq '"'){$value=[regex]::Replace($value.Substring(1,$value.Length-2),'\\([\\"])','$1').Replace('$$','$')}
            else{$value=($value -split '\s+#',2)[0].Trim()}
            $values[$key]=$value
        }
    }}
    return $values
}
function Write-IdccEnvFile([string]$Path,[hashtable]$Values,[string[]]$Remove=@()){
    $encoded=@{}
    foreach($key in $Values.Keys){
        $value=[string]$Values[$key]
        if($value -match '[\r\n\x00]'){throw 'Invalid multiline environment setting.'}
        $encoded[$key]='"'+$value.Replace('\','\\').Replace('"','\"').Replace('$','$$')+'"'
    }
    $lines=New-Object 'Collections.Generic.List[string]';$seen=@{}
    if(Test-Path -LiteralPath $Path){foreach($line in [IO.File]::ReadAllLines($Path)){
        if($line -match '^\s*([A-Za-z_][A-Za-z0-9_]*)\s*='){
            $key=$Matches[1]
            if($key -in $Remove){continue}
            if($encoded.ContainsKey($key)){
                if(!$seen.ContainsKey($key)){$lines.Add($key+'='+$encoded[$key]);$seen[$key]=$true}
                continue
            }
        }
        $lines.Add($line)
    }}
    foreach($key in $encoded.Keys){if(!$seen.ContainsKey($key)){$lines.Add($key+'='+$encoded[$key])}}
    $tmp=$Path+'.'+[guid]::NewGuid().ToString('N')+'.tmp'
    try{[IO.File]::WriteAllText($tmp,($lines -join "`n")+"`n",(New-Object Text.UTF8Encoding($false)));Move-Item -LiteralPath $tmp -Destination $Path -Force}
    finally{if(Test-Path -LiteralPath $tmp){Remove-Item -LiteralPath $tmp}}
}
function Get-IdccConnectionProfiles([string]$Root){
    $envValues=Read-IdccEnvFile (Join-Path $Root '.env');$pg=Read-IdccEnvFile (Join-Path $Root '.env.postgres')
    $hasDemo=@($envValues.Keys|Where-Object {$_ -like 'DEMOIDCC_DB_*'}).Count -gt 0
    $legacy=(!$hasDemo -and $envValues['IDCC_CONNECTION_LAYOUT'] -ne '2' -and $envValues['IDCC_DB_DRIVER'] -ne 'pgsql')
    $demo=@{};$spc=@{}
    foreach($suffix in @('HOST','NAME','UID','PWD','TRUST_SERVER_CERTIFICATE')){
        $prefix=if($legacy){'IDCC'}else{'DEMOIDCC'}
        $demo[$suffix]=[string]$envValues[$prefix+'_DB_'+$suffix]
        $spc[$suffix]=[string]$envValues['SPC_DB_'+$suffix]
    }
    return @{IDCC=@{HOST='postgres';PORT='5432';NAME='idcc';UID='idcc';PWD=[string]$pg['POSTGRES_PASSWORD'];DRIVER='pgsql'};DemoIDCC=$demo;SPC=$spc;Legacy=$legacy}
}
function Initialize-IdccConnectionProfiles([string]$Root){
    $profile=Get-IdccConnectionProfiles $Root
    $envValues=Read-IdccEnvFile (Join-Path $Root '.env')
    $updates=@{IDCC_CONNECTION_LAYOUT='2'}
    if(!$envValues.ContainsKey('IDCC_DEMO_MODE') -or $envValues['IDCC_DEMO_MODE'] -eq ''){$updates.IDCC_DEMO_MODE='true'}
    foreach($suffix in @('HOST','NAME','UID','PWD','TRUST_SERVER_CERTIFICATE')){
        $key='DEMOIDCC_DB_'+$suffix
        if(!$envValues.ContainsKey($key)){$updates[$key]=$profile.DemoIDCC[$suffix]}
    }
    $remove=@('HOST','PORT','NAME','UID','PWD','DRIVER','TRUST_SERVER_CERTIFICATE')|ForEach-Object {'IDCC_DB_'+$_}
    Write-IdccEnvFile (Join-Path $Root '.env') $updates $remove
    # PostgreSQL credentials are kept in the existing secret file; never regenerated here.
    if($profile.IDCC.PWD -ne ''){
        $primary=@{IDCC_DB_DRIVER='pgsql'}
        foreach($suffix in @('HOST','PORT','NAME','UID','PWD')){$primary['IDCC_DB_'+$suffix]=$profile.IDCC[$suffix]}
        Write-IdccEnvFile (Join-Path $Root '.env.postgres') $primary
    }
}
Export-ModuleMember -Function Get-IdccConnectionProfiles,Initialize-IdccConnectionProfiles
