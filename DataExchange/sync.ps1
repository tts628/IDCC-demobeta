#Requires -Version 5.1
[CmdletBinding()]
param([switch]$Check,[switch]$Yes,[ValidateRange(1,5000)][int]$BatchSize=500,[ValidateRange(0,86400)][int]$SourceTimeout=0)
$ErrorActionPreference='Stop'
[Console]::OutputEncoding=New-Object Text.UTF8Encoding($false)
$projectRoot=[IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$docker=Join-Path $projectRoot 'windows\docker.ps1'
try{
    Set-Location -LiteralPath $projectRoot
    & $docker compose exec -T web php DataExchange/sync.php --check --source-timeout $SourceTimeout
    if($LASTEXITCODE -ne 0){exit $LASTEXITCODE}
    if($Check){exit 0}
    if(!$Yes){
        Write-Host '將把 SPC 的七張來源實體表的全部欄位與資料完整替換到正式 IDCC PostgreSQL，來源零筆也會清空對應目標表。'
        Write-Host '不受 Demo 開關影響，不寫入 DemoIDCC，也不修改 SPC。'
        if((Read-Host '請輸入大寫 SYNC 確認，其他輸入取消') -cne 'SYNC'){Write-Host '已取消，沒有更動資料。';exit 0}
    }
    & $docker compose exec -T web php DataExchange/sync.php --sync --yes --batch-size $BatchSize --source-timeout $SourceTimeout
    exit $LASTEXITCODE
}catch{Write-Host '無法執行資料交換。請確認完整下載專案，並先安裝／啟動環境。' -ForegroundColor Red;exit 1}
