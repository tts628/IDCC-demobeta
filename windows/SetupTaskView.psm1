function Get-SetupTaskView {
    [CmdletBinding()]
    param([ValidateSet('Install','Plan','Apply','Diagnose','Reset')][string]$Action,[string]$Text='',[int]$ExitCode=-99999)
    $titles=@{Install='正在準備安裝環境';Plan='正在檢查安裝方式';Apply='正在準備套用資料庫設定';Diagnose='正在準備測試資料庫連線';Reset='正在準備重設環境'}
    $view=[pscustomobject]@{Title=$titles[$Action];Detail='請稍候，完成後會自動顯示結果。';State='Running';Complete=$false}
    switch($Action){
        Install {
            $view.Detail='首次安裝需要下載環境元件，時間會依網路及電腦速度而不同。'
            if($Text -match 'Installing.*Docker|Installing.*Ubuntu|Downloading.*Ubuntu|WSL update|Preparing WSL|apt-get'){$view.Title='正在準備系統與 Docker 環境'}
            if($Text -match 'Starting Docker|Waiting for Docker|Starting the existing Docker|Docker detected|Docker Desktop detected'){$view.Title='正在啟動容器引擎'}
            if($Text -match 'Building Apache/PHP|Validating Compose and building|load build definition|Building.*web'){$view.Title='正在建立 Apache 與 PHP 網站環境';$view.Detail='正在下載套件及建立網站映像，第一次執行通常較久。'}
            if($Text -match 'Container [^\r\n]*(Creating|Recreated|Starting|Waiting|Healthy|Started)|\bUp [^\r\n]*healthy'){$view.Title='正在啟動網站與 PostgreSQL';$view.Detail='正在等待服務就緒，並確認網站健康狀態。'}
        }
        Apply {
            $view.Detail='您的設定已儲存，正在重新建立容器，讓新的連線設定生效。'
            if($Text -match 'Recreat|Creat|Starting|Started'){$view.Title='正在重新啟動服務並載入設定'}
            if($Text -match 'Waiting|Healthy|healthy'){$view.Title='正在確認服務可以正常啟動'}
        }
        Diagnose {
            $view.Detail='正在檢查 PostgreSQL，以及已設定的 SQL Server 連線。'
            if($Text -match 'postgres_connect'){$view.Title='已檢查 PostgreSQL，正在檢查測試資料庫'}
            if($Text -match 'sqlsrv_connect|pdo_connect'){$view.Title='正在確認資料庫登入與資料存取'}
            if($Text -match '"stage"\s*:\s*"(table|data_window|homepage_api)"'){$view.Title='正在檢查資料表與頁面資料'}
        }
        Reset {
            $view.Detail='只清除本專案的執行環境，資料庫資料、密碼與設定會保留。'
            if($Text -match 'Stopping|Stopped|Removing|Removed'){$view.Title='正在停止並移除本專案容器與網路'}
            if($Text -match 'Untagged|Deleted:'){$view.Title='正在移除網站映像，保留資料與設定'}
        }
    }
    if($ExitCode -eq -99999){return $view}
    $view.Complete=$true
    if($ExitCode -eq 3010){$view.Title='需要重新啟動 Windows';$view.Detail='系統元件已準備好。請先儲存工作，重開機後再次開啟安裝工具繼續。';$view.State='Warning';return $view}
    $records=@()
    if($Action -eq 'Diagnose'){
        foreach($line in ($Text -split "`r?`n")){
            if($line.Trim().StartsWith('{')){try{$records+=($line|ConvertFrom-Json -ErrorAction Stop)}catch{}}
        }
    }
    $failed=@($records|Where-Object {$_.PSObject.Properties['ok'] -and $_.ok -eq $false})
    if($ExitCode -ne 0 -or $failed.Count){
        $view.Title=if($Action -eq 'Diagnose'){'連線測試尚未通過'}else{'操作尚未完成'}
        $view.State='Error'
        $view.Detail='請確認環境已安裝、Docker 正在執行，再重新操作。若仍失敗，請提供這個畫面的訊息。'
        if($Text -match 'TLS_CERTIFICATE'){$view.Detail='資料庫憑證驗證未通過。請使用有效憑證，或在受信任的測試環境確認「信任伺服器憑證」設定。'}
        elseif($Text -match 'LOGIN_FAILED|18456'){$view.Detail='資料庫拒絕登入。請檢查帳號及密碼，儲存並套用後再測試。'}
        elseif($Text -match 'DATABASE_UNAVAILABLE_OR_DENIED|4060'){$view.Detail='找不到指定資料庫或帳號沒有存取權限。請檢查資料庫名稱及權限。'}
        elseif($Text -match 'TABLE_OR_SCHEMA_NOT_FOUND|208'){$view.Detail='已連上資料庫，但找不到部分資料表。請確認選對資料庫，並檢查所需資料表。'}
        elseif($Text -match 'PERMISSION_DENIED|229'){$view.Detail='資料庫帳號沒有足夠的資料存取權限。請檢查帳號權限。'}
        elseif($Text -match 'NETWORK_OR_CONNECTION|TIMEOUT|Connection timed out'){$view.Detail='目前無法連到資料庫。請檢查主機位置、連接埠、網路及防火牆。'}
        elseif($Text -match 'Missing .*database|not_configured|missing_keys"\s*:\s*\[\s*"'){$view.Detail='資料庫設定尚未完整。請填妥主機、資料庫名稱、帳號及密碼，再儲存並套用。'}
        elseif($Text -match 'Start.*Docker|Cannot connect.*Docker|Docker.*not.*running|engine.*not ready|Docker CLI not found'){$view.Detail='Docker 引擎尚未就緒。請先從「安裝／啟動環境」啟動環境，再重試。'}
        elseif($Text -match 'Run windows\\install.cmd first|Ubuntu-24.04 is not installed|WSL is missing'){$view.Detail='請先完成環境安裝，再套用設定或測試資料庫。'}
        elseif($Text -match 'systemd'){$view.Detail='Ubuntu 的服務管理尚未就緒。請依安裝說明啟用 systemd，再重新啟動該 Ubuntu 環境。'}
        elseif($Text -match 'postgres_connect[^\r\n]*"ok"\s*:\s*false'){$view.Detail='PostgreSQL 尚未連線成功。請先確認環境安裝完成，以及資料庫容器已正常啟動。'}
        return $view
    }
    $view.State='Success'
    switch($Action){
        Install {$view.Title='環境安裝完成';$view.Detail='網站與資料庫服務已啟動。可以開啟網站；資料庫連線請另外按「測試已儲存的連線」確認。'}
        Apply {$view.Title='資料庫設定已套用';$view.Detail='服務已重新啟動並載入設定。請按「測試已儲存的連線」，確認是否能正常連線。'}
        Reset {$view.Title='環境已重設完成';$view.Detail='本專案容器、網路與網站映像已清除。資料庫資料、密碼與設定已保留。'}
        Plan {
            $view.Title='安裝方式檢查完成'
            $view.Detail=if($Text -match 'WSL 2 \+ Ubuntu'){'這台電腦會使用 WSL 2、Ubuntu 與 Docker Engine。此操作沒有安裝或更動環境。'}else{'這台電腦會沿用既有 Docker 環境。此操作沒有安裝或更動環境。'}
        }
        Diagnose {
            $pg=@($records|Where-Object {$_.stage -eq 'postgres_connect' -and $_.ok -eq $true})
            $skipped=@($records|Where-Object {$_.skipped -eq $true}|ForEach-Object {$_.database_slot})
            $seenSlots=@($records|Where-Object {$_.database_slot -in @('IDCC','SPC')}|ForEach-Object {$_.database_slot}|Select-Object -Unique)
            if(!$pg.Count -or $seenSlots.Count -ne 2){$view.Title='連線測試結果不完整';$view.Detail='尚未取得完整測試結果。請確認容器正在執行，再重新測試。';$view.State='Warning'}
            elseif($skipped.Count){$view.Title='已完成已設定項目的連線測試';$view.Detail='PostgreSQL 連線正常；'+($skipped -join '、')+' 尚未設定，已略過，不能視為已連線。';$view.State='Warning'}
            else{$view.Title='資料庫連線測試通過';$view.Detail='PostgreSQL 及已設定的測試資料庫皆可連線。'}
            if(@($records|Where-Object {($_.PSObject.Properties['has_rows'] -and $_.has_rows -eq $false) -or ($_.PSObject.Properties['has_rows_in_window'] -and $_.has_rows_in_window -eq $false)}).Count){$view.Title='資料庫可連線，部分查詢沒有資料';$view.Detail='請檢查選擇的資料庫、頁面日期範圍，以及資料表是否已有資料。';$view.State='Warning'}
        }
    }
    return $view
}
Export-ModuleMember -Function Get-SetupTaskView
