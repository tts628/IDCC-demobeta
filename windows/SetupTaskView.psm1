function Get-ConnectionTaskView {
    param([string]$Text='',[int]$ExitCode=-99999)
    $reasons=@{
        TLS_CERTIFICATE='憑證驗證未通過，請檢查憑證或信任設定'
        LOGIN_FAILED='帳號或密碼不正確'
        DATABASE_UNAVAILABLE_OR_DENIED='資料庫不存在，或帳號無權限開啟'
        TIMEOUT='連線逾時，請檢查網路與連接埠'
        NETWORK_OR_CONNECTION='無法到達主機，請檢查位址、網路與防火牆'
        DRIVER_MISSING='缺少必要驅動，請重新建置網站環境'
        INVALID_SETTING='連線設定格式不正確'
        CONNECTION_FAILED='無法建立連線，請確認設定與資料庫服務'
        CHECK_FAILED='測試未能執行，請確認環境正常'
    }
    $slots=@('IDCC','DemoIDCC','SPC');$results=@{};$active=$null
    foreach($line in ($Text -split "`r?`n")){
        if(!$line.Trim().StartsWith('{')){continue}
        try{$record=$line|ConvertFrom-Json -ErrorAction Stop}catch{continue}
        if($record.database_slot -notin $slots){continue}
        if($record.stage -eq 'connection_start'){$active=$record.database_slot}
        if($record.stage -eq 'connection_result' -and $record.status -in @('success','failed','not_configured')){$results[$record.database_slot]=$record}
    }
    $view=[pscustomobject]@{Title='正在準備測試資料庫連線';Detail='';State='Running';Complete=($ExitCode -ne -99999);Progress=[Math]::Min(95,[int][Math]::Floor($results.Count*100/3))}
    if($active){$view.Title='正在測試 '+$active+' 連線'}
    $lines=New-Object 'Collections.Generic.List[string]'
    $passed=0;$failed=0;$skipped=0
    foreach($slot in $slots){
        if(!$results.ContainsKey($slot)){
            $state=if($view.Complete){'未取得結果，請確認網站容器已啟動後重試'}elseif($slot -eq $active){'測試中…'}else{'等待測試'}
            $lines.Add($slot+'：'+$state);continue
        }
        $result=$results[$slot]
        switch($result.status){
            success {$passed++;$lines.Add($slot+'：連線成功')}
            not_configured {$skipped++;$lines.Add($slot+'：尚未設定或設定未完整，已略過')}
            failed {
                $failed++
                $bad=@($result.drivers|Where-Object {$_.ok -eq $false})
                $good=@($result.drivers|Where-Object {$_.ok -eq $true})
                $categories=@($bad|ForEach-Object {$_.category}|Select-Object -Unique)
                $reason='無法建立連線，請確認設定與資料庫服務'
                if($categories.Count -eq 1 -and $reasons.ContainsKey([string]$categories[0])){$reason=$reasons[[string]$categories[0]]}
                elseif($categories.Count -gt 1){
                    $items=@(foreach($method in $bad){
                        $name=switch($method.name){'SQLSRV'{'SQLSRV'};'PDO_SQLSRV'{'PDO'};'PDO_PGSQL'{'PDO'};default{'連線'}}
                        $why=if($reasons.ContainsKey([string]$method.category)){$reasons[[string]$method.category]}else{$reasons.CONNECTION_FAILED}
                        $name+'：'+$why
                    });$reason=$items -join '；'
                }elseif($reasons.ContainsKey([string]$result.reason)){$reason=$reasons[[string]$result.reason]}
                if($good.Count -gt 0 -and $bad.Count -eq 1){
                    $methodName=if($bad[0].name -eq 'SQLSRV'){'SQLSRV'}else{'PDO'}
                    $lines.Add($slot+'：部分連線失敗（'+$methodName+'：'+$reason+'）')
                }else{$lines.Add($slot+'：連線失敗（'+$reason+'）')}
            }
        }
    }
    $view.Detail=$lines -join "`r`n"
    if(!$view.Complete){return $view}
    if($results.Count -ne 3){$view.Title='部分連線尚未取得測試結果';$view.State=if($ExitCode -eq 0){'Warning'}else{'Error'};return $view}
    if($failed -gt 0){$view.Title="連線測試完成：$passed 個成功，$failed 個失敗";$view.State='Error'}
    elseif($ExitCode -ne 0){$view.Title='測試未正常結束，請重新測試';$view.State='Error'}
    elseif($skipped -gt 0){$view.Title="連線測試完成：$passed 個成功，$skipped 個尚未設定";$view.State='Warning';$view.Progress=100}
    else{$view.Title='三個資料庫皆可正常連線';$view.State='Success';$view.Progress=100}
    return $view
}

function Get-SetupTaskView {
    [CmdletBinding()]
    param([ValidateSet('Install','Plan','Apply','Diagnose','Reset','SyncCheck','SyncEnable','SyncDisable','SyncInspect')][string]$Action,[string]$Text='',[int]$ExitCode=-99999)
    if($Action -like 'Sync*'){
        $titles=@{SyncCheck='正在檢查同步連線與資料表';SyncEnable='正在檢查設定並啟用定時同步';SyncDisable='正在停用定時同步';SyncInspect='正在讀取同步狀態與紀錄'}
        $view=[pscustomobject]@{Title=$titles[$Action];Detail='不顯示帳號密碼。請稍候，完成後可在同步頁查看紀錄。';State='Running';Complete=($ExitCode -ne -99999);Progress=0}
        if(!$view.Complete){return $view}
        if($ExitCode -ne 0){$view.Title='同步操作未完成';$view.Detail='請先完成環境安裝、儲存並套用 SPC 設定；同步頁的紀錄會顯示失敗原因。';$view.State='Error';return $view}
        $view.State='Success';$view.Progress=100
        switch($Action){
            SyncCheck {$view.Title='同步檢查通過';$view.Detail='連線與結構檢查通過，沒有寫入資料。可選擇啟用定時同步，也可略過。'}
            SyncEnable {$view.Title='定時同步已啟用';$view.Detail='已啟動背景排程，立即同步一次，之後每 10 分鐘執行。啟用成功不代表資料同步成功，請查看執行紀錄。'}
            SyncDisable {$view.Title='定時同步已停用';$view.Detail='背景排程已停止，已提交的資料保留。'}
            SyncInspect {$view.Title='同步狀態與紀錄已更新';$view.Detail='請在同步頁查看最近紀錄；exit code 0 代表該次同步成功。'}
        }
        return $view
    }
    if($Action -eq 'Diagnose'){return Get-ConnectionTaskView -Text $Text -ExitCode $ExitCode}
    $titles=@{Install='正在準備安裝環境';Plan='正在檢查安裝方式';Apply='正在準備套用資料庫設定';Diagnose='正在準備測試資料庫連線';Reset='正在準備重設環境'}
    $view=[pscustomobject]@{Title=$titles[$Action];Detail='請稍候，完成後會自動顯示結果。';State='Running';Complete=$false;Progress=0}
    switch($Action){
        Install {
            $view.Detail='首次安裝需要下載環境元件，時間會依網路及電腦速度而不同。'
            if($Text -match 'Installing.*Docker|Installing.*Ubuntu|Downloading.*Ubuntu|WSL update|Preparing WSL|apt-get'){$view.Title='正在準備系統與 Docker 環境';$view.Progress=20}
            if($Text -match 'Starting Docker|Waiting for Docker|Starting the existing Docker|Docker detected|Docker Desktop detected'){$view.Title='正在啟動容器引擎';$view.Progress=40}
            if($Text -match 'Building Apache/PHP|Validating Compose and building|load build definition|Building.*web'){$view.Title='正在建立 Apache 與 PHP 網站環境';$view.Progress=60;$view.Detail='正在下載套件及建立網站映像，第一次執行通常較久。'}
            if($Text -match 'Container [^\r\n]*(Creating|Recreated|Starting|Waiting|Healthy|Started)|\bUp [^\r\n]*healthy'){$view.Title='正在啟動網站與 PostgreSQL';$view.Progress=80;$view.Detail='正在等待服務就緒，並確認網站健康狀態。'}
        }
        Apply {
            $view.Detail='您的設定已儲存，正在重新建立容器，讓新的連線設定生效。'
            if($Text -match 'Recreat|Creat|Starting|Started'){$view.Title='正在重新啟動服務並載入設定';$view.Progress=33}
            if($Text -match 'Waiting|Healthy|healthy'){$view.Title='正在確認服務可以正常啟動';$view.Progress=67}
        }
        Reset {
            $view.Detail='只清除本專案的執行環境，資料庫資料、密碼與設定會保留。'
            if($Text -match 'Stopping|Stopped|Removing|Removed'){$view.Title='正在停止並移除本專案容器與網路';$view.Progress=33}
            if($Text -match 'Untagged|Deleted:'){$view.Title='正在移除網站映像，保留資料與設定';$view.Progress=67}
        }
    }
    if($ExitCode -eq -99999){return $view}
    $view.Complete=$true
    if($ExitCode -eq 3010){$view.Title='需要重新啟動 Windows';$view.Detail='系統元件已準備好。請先儲存工作，重開機後再次開啟安裝工具繼續。';$view.State='Warning';return $view}
    if($ExitCode -ne 0){
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

    }
    if($view.State -eq 'Success' -or ($view.State -eq 'Warning' -and $view.Title -ne '連線測試結果不完整')){$view.Progress=100}
    return $view
}
Export-ModuleMember -Function Get-SetupTaskView
