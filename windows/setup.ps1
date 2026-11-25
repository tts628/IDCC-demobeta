#Requires -Version 5.1
[CmdletBinding()]
param([ValidateSet('Install','Database','Pages','Exchange')][string]$Tab='Install',[switch]$SmokeTest)
$ErrorActionPreference='Stop'
Add-Type -AssemblyName System.Windows.Forms
Add-Type -AssemblyName System.Drawing
[Windows.Forms.Application]::EnableVisualStyles()
Import-Module (Join-Path $PSScriptRoot 'SetupTaskView.psm1') -Force
Import-Module (Join-Path $PSScriptRoot 'ConnectionProfiles.psm1') -Force
$script:root=[IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$script:utf8=New-Object Text.UTF8Encoding($false)
$script:busy=$false
$script:updating=$false
$script:catalog=Get-Content (Join-Path $script:root 'common\Config\pages.json') -Raw -Encoding UTF8 | ConvertFrom-Json
$script:catalog=@($script:catalog | Sort-Object group,label)
function Read-Settings {
    $result=@{}
    $file=Join-Path $script:root '.env'
    if(Test-Path -LiteralPath $file){
        foreach($line in [IO.File]::ReadAllLines($file)){
            if($line -match '^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$'){
                $key=$Matches[1];$value=$Matches[2]
                if($value.Length -ge 2 -and $value[0] -eq "'" -and $value[$value.Length-1] -eq "'"){$value=$value.Substring(1,$value.Length-2).Replace("\'","'")}
                elseif($value.Length -ge 2 -and $value[0] -eq '"' -and $value[$value.Length-1] -eq '"'){$value=[regex]::Replace($value.Substring(1,$value.Length-2), '\\([\\"])', '$1').Replace('$$','$')}
                else{$value=($value -split '\s+#',2)[0].Trim()}
                $result[$key]=$value
            }
        }
    }
    return $result
}
function Encode-EnvValue([string]$Value){
    if($Value -match '[\r\n\x00]'){throw '設定值不能包含換行或空字元。'}
    return '"'+$Value.Replace('\','\\').Replace('"','\"').Replace('$','$$')+'"'
}
function Write-Settings([hashtable]$Values){
    $file=Join-Path $script:root '.env'
    $lines=New-Object 'Collections.Generic.List[string]'
    $seen=@{}
    if(Test-Path -LiteralPath $file){foreach($line in [IO.File]::ReadAllLines($file)){
        if($line -match '^\s*([A-Za-z_][A-Za-z0-9_]*)\s*='){
            $key=$Matches[1]
            if($Values.ContainsKey($key)){
                if(!$seen.ContainsKey($key)){$lines.Add($key+'='+(Encode-EnvValue ([string]$Values[$key])));$seen[$key]=$true}
                continue
            }
        }
        $lines.Add($line)
    }}
    foreach($key in $Values.Keys){
        if(([string]$Values[$key]) -match '[\r\n\x00]'){throw '設定值不能包含換行或空字元。'}
        if(!$seen.ContainsKey($key)){$lines.Add($key+'='+(Encode-EnvValue ([string]$Values[$key])))}
    }
    # Atomic replacement in the same directory; do not make plaintext password backups.
    $temp=$file+'.'+[guid]::NewGuid().ToString('N')+'.tmp'
    try{[IO.File]::WriteAllText($temp,($lines -join "`n")+"`n",$script:utf8);Move-Item -LiteralPath $temp -Destination $file -Force}
    finally{if(Test-Path -LiteralPath $temp){Remove-Item -LiteralPath $temp}}
}
function Note([string]$Text){[void][Windows.Forms.MessageBox]::Show($Text,'IDCC 安裝工具')}
function New-Button([Windows.Forms.Control]$Parent,[string]$Text,[scriptblock]$Click){
    $b=New-Object Windows.Forms.Button;$b.Text=$Text;$b.AutoSize=$true;$b.Height=34;$b.Margin='5,5,5,5';$b.Add_Click($Click);$Parent.Controls.Add($b);return $b
}
function New-Label([string]$Text){$l=New-Object Windows.Forms.Label;$l.Text=$Text;$l.AutoSize=$true;$l.Margin='10,8,10,8';return $l}
$script:settings=Read-Settings
$script:profiles=Get-IdccConnectionProfiles $script:root
$form=New-Object Windows.Forms.Form
$form.Text='IDCC DemoBeta 安裝與設定';$form.Size=New-Object Drawing.Size(1040,820);$form.MinimumSize=New-Object Drawing.Size(920,740);$form.StartPosition='CenterScreen';$form.Font=New-Object Drawing.Font('Microsoft JhengHei UI',10)
$outer=New-Object Windows.Forms.TableLayoutPanel;$outer.Dock='Fill';$outer.RowCount=3;$outer.ColumnCount=1
[void]$outer.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',54)))
[void]$outer.RowStyles.Add((New-Object Windows.Forms.RowStyle('Percent',100)))
[void]$outer.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',265)))
$form.Controls.Add($outer)
$outer.Controls.Add((New-Label "IDCC 環境管理    ｜    $script:root"),0,0)
$tabs=New-Object Windows.Forms.TabControl;$tabs.Dock='Fill';$outer.Controls.Add($tabs,0,1)
$install=New-Object Windows.Forms.TabPage;$install.Text='1  安裝環境';[void]$tabs.TabPages.Add($install)
$db=New-Object Windows.Forms.TabPage;$db.Text='2  資料庫設定（可稍後）';[void]$tabs.TabPages.Add($db)
$pages=New-Object Windows.Forms.TabPage;$pages.Text='3  頁面選擇';[void]$tabs.TabPages.Add($pages)
$exchangeTab=New-Object Windows.Forms.TabPage;$exchangeTab.Text='4  定時資料同步（選用）';[void]$tabs.TabPages.Add($exchangeTab)
# Each tab owns independent progress controls and a retained result.
$progressHost=New-Object Windows.Forms.Panel;$progressHost.Dock='Fill';$outer.Controls.Add($progressHost,0,2)
$script:tabProgress=@{};$script:activeTaskTab=0
$titles=@('安裝環境','資料庫設定','頁面選擇','定時資料同步')
$hints=@('按安裝／啟動環境開始；此處保留安裝、檢查計畫或重設的結果。','可稍後填寫連線設定；此處保留儲存、套用或連線測試的結果。','勾選需要的頁面並儲存；此處顯示頁面設定結果。','這是選用流程；此處保留檢查、啟用或停用的操作結果。背景同步狀態請看上方。')
foreach($index in 0..3){
    $box=New-Object Windows.Forms.GroupBox;$box.Text=$titles[$index]+' — 執行進度';$box.Dock='Fill';$box.Padding='10,14,10,8';$progressHost.Controls.Add($box)
    $layout=New-Object Windows.Forms.TableLayoutPanel;$layout.Dock='Fill';$layout.ColumnCount=1;$layout.RowCount=4
    foreach($height in @(32,25,127,22)){[void]$layout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',$height)))}
    $box.Controls.Add($layout)
    $title=New-Object Windows.Forms.Label;$title.Text='準備就緒';$title.Dock='Fill';$title.TextAlign='MiddleLeft';$title.Font=New-Object Drawing.Font($form.Font.FontFamily,12,[Drawing.FontStyle]::Bold);$layout.Controls.Add($title,0,0)
    $bar=New-Object Windows.Forms.ProgressBar;$bar.Dock='Fill';$bar.Margin='0,2,0,3';$bar.Minimum=0;$bar.Maximum=100;$layout.Controls.Add($bar,0,1)
    $detail=New-Object Windows.Forms.Label;$detail.Text=$hints[$index];$detail.Dock='Fill';$detail.Padding='0,7,0,0';$layout.Controls.Add($detail,0,2)
    $time=New-Object Windows.Forms.Label;$time.Dock='Fill';$time.ForeColor=[Drawing.Color]::DimGray;$layout.Controls.Add($time,0,3)
    $script:tabProgress[$index]=@{Box=$box;Title=$title;Bar=$bar;Detail=$detail;Time=$time;LastProgress=0}
}
function Show-TabProgress {
    foreach($index in 0..3){$script:tabProgress[$index].Box.Visible=($index -eq $tabs.SelectedIndex)}
    if($tabs.SelectedIndex -ge 0){$script:tabProgress[$tabs.SelectedIndex].Box.BringToFront()}
}
Show-TabProgress
$flow=New-Object Windows.Forms.FlowLayoutPanel;$flow.Dock='Fill';$flow.FlowDirection='TopDown';$flow.WrapContents=$false;$flow.AutoScroll=$true;$install.Controls.Add($flow)
# Current environment state is independent from installation task progress.
$environmentBox=New-Object Windows.Forms.GroupBox;$environmentBox.Text='目前環境狀態';$environmentBox.Width=940;$environmentBox.Height=190;$environmentBox.Margin='6,6,6,6'
$environmentLayout=New-Object Windows.Forms.TableLayoutPanel;$environmentLayout.Dock='Fill';$environmentLayout.ColumnCount=1;$environmentLayout.RowCount=6
foreach($i in 0..4){[void]$environmentLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',24)))}
[void]$environmentLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',44)))
$script:environmentLabels=@{}
foreach($key in @('backend','docker','image','web','postgres')){
    $label=New-Object Windows.Forms.Label;$label.Text='尚未查詢';$label.Dock='Fill';$label.TextAlign='MiddleLeft';$label.Padding='10,0,0,0';$script:environmentLabels[$key]=$label
    $environmentLayout.Controls.Add($label,0,$environmentLayout.Controls.Count)
}
$environmentButtons=New-Object Windows.Forms.FlowLayoutPanel;$environmentButtons.Dock='Fill';$environmentLayout.Controls.Add($environmentButtons,0,5)
$environmentRefresh=New-Button $environmentButtons '重新整理環境狀態' {Start-EnvironmentMonitor}
$environmentUpdated=New-Label '尚未查詢；不會自動安裝或啟動服務';$environmentButtons.Controls.Add($environmentUpdated)
$environmentBox.Controls.Add($environmentLayout);$flow.Controls.Add($environmentBox)
$flow.Add_Resize({$environmentBox.Width=[Math]::Max(300,$flow.ClientSize.Width-28)})
$flow.Controls.Add((New-Label "新電腦：WSL 2 → Ubuntu 24.04 → Docker Engine → Apache/PHP + PostgreSQL。`n已有 Docker Desktop 時沿用既有引擎。首次可能需要重開機，再開啟本工具。"))
$portRow=New-Object Windows.Forms.FlowLayoutPanel;$portRow.AutoSize=$true;$portRow.Controls.Add((New-Label '網站連接埠'))
$port=New-Object Windows.Forms.NumericUpDown;$port.Minimum=1024;$port.Maximum=65535;$port.Value=8080;$port.Width=110
if($script:settings['IDCC_HTTP_PORT'] -match '^\d+$'){$n=[int]$script:settings['IDCC_HTTP_PORT'];if($n -ge 1024 -and $n -le 65535){$port.Value=$n}}
$portRow.Controls.Add($port);$flow.Controls.Add($portRow)
$installButton=New-Button $flow '安裝／啟動環境（資料庫可略過）' {try{Write-Settings @{IDCC_HTTP_PORT=[string]$port.Value};Start-Task Install}catch{Show-LocalResult 0 '設定未能儲存' $_.Exception.Message -Failed;Note $_.Exception.Message}}
$previewButton=New-Button $flow '檢查安裝計畫（不安裝）' {Start-Task Plan}
$openButton=New-Button $flow '開啟網站' {Start-Process "http://127.0.0.1:$($port.Value)/DemoBeta/Home/"}
$resetButton=New-Button $flow '重設環境（保留資料與設定）' {
    $answer=[Windows.Forms.MessageBox]::Show("將停止並移除本專案容器、網路與應用映像。`n保留 PostgreSQL 資料、密碼、.env、頁面選擇及程式。`n確定要繼續？",'確認重設',[Windows.Forms.MessageBoxButtons]::YesNo,[Windows.Forms.MessageBoxIcon]::Warning)
    if($answer -eq 'Yes'){Start-Task Reset}
}
$flow.Controls.Add((New-Label '安裝成功代表容器可執行；資料庫連線與業務資料另行檢查。'))
# Primary IDCC is installed PostgreSQL; DemoIDCC and SPC are separate SQL Server profiles.
$dbLayout=New-Object Windows.Forms.TableLayoutPanel;$dbLayout.Dock='Fill';$dbLayout.ColumnCount=3;$dbLayout.RowCount=4
foreach($column in 0..2){[void]$dbLayout.ColumnStyles.Add((New-Object Windows.Forms.ColumnStyle('Percent',33.333)))}
[void]$dbLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',42)));[void]$dbLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Percent',100)));[void]$dbLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',58)));[void]$dbLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',66)))
$db.Controls.Add($dbLayout)
$demo=New-Object Windows.Forms.CheckBox;$demo.Text='Demo 模式：IDCC 一律使用 DemoIDCC；SPC 保持自己的連線';$demo.AutoSize=$true;$demo.Margin='12,10,5,5';$demo.Checked=($script:settings['IDCC_DEMO_MODE'] -notin @('false','0','off','no'));$dbLayout.Controls.Add($demo,0,0);$dbLayout.SetColumnSpan($demo,3)
$script:fields=@{};$script:trust=@{}
foreach($slot in @('IDCC','DEMOIDCC','SPC')){
    $primary=$slot -eq 'IDCC'
    $box=New-Object Windows.Forms.GroupBox;$box.Text=switch($slot){'IDCC'{'IDCC Server（PostgreSQL）'};'DEMOIDCC'{'DemoIDCC Server（SQL Server）'};default{'SPC Server（SQL Server）'}};$box.Dock='Fill'
    $table=New-Object Windows.Forms.TableLayoutPanel;$table.Dock='Fill';$table.ColumnCount=2;$table.RowCount=6
    [void]$table.ColumnStyles.Add((New-Object Windows.Forms.ColumnStyle('Absolute',85)));[void]$table.ColumnStyles.Add((New-Object Windows.Forms.ColumnStyle('Percent',100)))
    foreach($row in 0..5){[void]$table.RowStyles.Add((New-Object Windows.Forms.RowStyle('Percent',16.667)))}
    $entries=@(@('HOST','主機／IP'),@('NAME','資料庫名稱'),@('UID','登入帳號'),@('PWD','密碼'))
    if($primary){$entries=@(@('HOST','主機／IP'),@('PORT','連接埠'),@('NAME','資料庫名稱'),@('UID','登入帳號'),@('PWD','密碼'))}
    $i=0
    foreach($entry in $entries){
        $table.Controls.Add((New-Label $entry[1]),0,$i)
        $input=New-Object Windows.Forms.TextBox;$input.Dock='Top';$input.Margin='5,6,8,4';$input.ReadOnly=$primary
        $key=$slot+'_DB_'+$entry[0];$input.Text=[string]$script:profiles[$slot][$entry[0]]
        if($entry[0] -eq 'PWD'){$input.UseSystemPasswordChar=$true}
        $script:fields[$key]=$input;$table.Controls.Add($input,1,$i);$i++
    }
    if($primary){$caption=New-Label '安裝後自動填入，密碼已遮蔽';$table.Controls.Add($caption,0,5);$table.SetColumnSpan($caption,2)}
    else{
        $check=New-Object Windows.Forms.CheckBox;$check.Text='信任測試伺服器憑證';$check.AutoSize=$true;$check.Checked=($script:profiles[$slot]['TRUST_SERVER_CERTIFICATE'] -in @('true','1','yes','on'));$table.Controls.Add($check,0,4);$table.SetColumnSpan($check,2);$script:trust[$slot]=$check
    }
    $box.Controls.Add($table);$column=@('IDCC','DEMOIDCC','SPC').IndexOf($slot);$dbLayout.Controls.Add($box,$column,1)
}
function Refresh-PrimaryConnection {
    $profile=Get-IdccConnectionProfiles $script:root
    foreach($suffix in @('HOST','PORT','NAME','UID','PWD')){$script:fields['IDCC_DB_'+$suffix].Text=[string]$profile.IDCC[$suffix]}
}
$dbButtons=New-Object Windows.Forms.FlowLayoutPanel;$dbButtons.Dock='Fill';$dbLayout.Controls.Add($dbButtons,0,2);$dbLayout.SetColumnSpan($dbButtons,3)
function Save-Database {
    $values=@{IDCC_DEMO_MODE=([string]$demo.Checked).ToLowerInvariant();IDCC_HTTP_PORT=[string]$port.Value}
    foreach($slot in @('DEMOIDCC','SPC')){
        $filled=0
        foreach($suffix in @('HOST','NAME','UID','PWD')){$key=$slot+'_DB_'+$suffix;$value=$script:fields[$key].Text;if($suffix -ne 'PWD'){$value=$value.Trim()};$values[$key]=$value;if($value -ne ''){$filled++}}
        if($filled -gt 0 -and $filled -lt 4){throw "$slot 請填完整四個欄位，或全部留空稍後設定。"}
        $values[$slot+'_DB_TRUST_SERVER_CERTIFICATE']=([string]$script:trust[$slot].Checked).ToLowerInvariant()
    }
    if(!$demo.Checked){$r=[Windows.Forms.MessageBox]::Show('PostgreSQL 目前為空庫，舊頁面的查詢尚未移植。確定關閉 Demo？','切換模式',[Windows.Forms.MessageBoxButtons]::YesNo);if($r -ne 'Yes'){return $false}}
    Write-Settings $values
    Initialize-IdccConnectionProfiles $script:root
    Refresh-PrimaryConnection
    return $true
}
$save=New-Button $dbButtons '只儲存設定' {try{if(Save-Database){Show-LocalResult 1 '資料庫設定已儲存' '容器需重新建立才會套用。';Note '已儲存。容器需重新建立才會套用。'}}catch{Show-LocalResult 1 '設定未能儲存' $_.Exception.Message -Failed;Note $_.Exception.Message}}
$apply=New-Button $dbButtons '儲存並套用' {try{if(Save-Database){Start-Task Apply}}catch{Show-LocalResult 1 '設定未能儲存' $_.Exception.Message -Failed;Note $_.Exception.Message}}
$test=New-Button $dbButtons '測試已儲存的連線' {Start-Task Diagnose}
$dbLayout.Controls.Add((New-Label "IDCC 的 PostgreSQL 連線由安裝自動填入；DemoIDCC 與 SPC 可稍後設定，兩者互不替代。`n只測試連線，不檢查資料表。請先儲存並套用；未儲存的輸入不會參與測試。"),0,3);$dbLayout.SetColumnSpan($dbLayout.GetControlFromPosition(0,3),3)
# Pages are switches; modules sharing a directory are linked.
$pageLayout=New-Object Windows.Forms.TableLayoutPanel;$pageLayout.Dock='Fill';$pageLayout.RowCount=3;$pageLayout.ColumnCount=1
[void]$pageLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',62)));[void]$pageLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Percent',100)));[void]$pageLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',54)));$pages.Controls.Add($pageLayout)
$pageLayout.Controls.Add((New-Label '勾選要保留的左側選單。共用同一底層目錄的項目會連動勾選。保留程式檔，可隨時重新啟用。'),0,0)
$list=New-Object Windows.Forms.CheckedListBox;$list.Dock='Fill';$list.CheckOnClick=$true;$list.HorizontalScrollbar=$true;$pageLayout.Controls.Add($list,0,1)
$selectionFile=Join-Path $script:root '.idcc-pages.json'
$enabled=@($script:catalog.id)
if(Test-Path -LiteralPath $selectionFile){
    try{$data=Get-Content $selectionFile -Raw -Encoding UTF8 | ConvertFrom-Json;if($data.version -ne 1){throw '版本不正確'};$enabled=@($data.enabled)}
    catch{Note '頁面設定無法讀取，請重新勾選並儲存。';$enabled=@()}
}
foreach($page in $script:catalog){[void]$list.Items.Add("[$($page.group)] $($page.label)  —  $($page.id)",($enabled -contains $page.id))}
$list.Add_ItemCheck({param($sender,$e)
    if($script:updating){return}
    $prefix=$script:catalog[$e.Index].prefix
    if(!$prefix){return}
    $script:updating=$true
    try{for($i=0;$i -lt $script:catalog.Count;$i++){if($i -ne $e.Index -and $script:catalog[$i].prefix -eq $prefix){$list.SetItemChecked($i,($e.NewValue -eq 'Checked'))}}}finally{$script:updating=$false}
})
$pageButtons=New-Object Windows.Forms.FlowLayoutPanel;$pageButtons.Dock='Fill';$pageLayout.Controls.Add($pageButtons,0,2)
$all=New-Button $pageButtons '全部保留' {for($i=0;$i -lt $list.Items.Count;$i++){$list.SetItemChecked($i,$true)}}
$none=New-Button $pageButtons '全部取消' {for($i=0;$i -lt $list.Items.Count;$i++){$list.SetItemChecked($i,$false)}}
$savePages=New-Button $pageButtons '儲存頁面選擇' {
    try{
        $ids=@(for($i=0;$i -lt $script:catalog.Count;$i++){if($list.GetItemChecked($i)){$script:catalog[$i].id}})
        $json=@{version=1;enabled=$ids}|ConvertTo-Json -Depth 5
        $tmp=$selectionFile+'.'+[guid]::NewGuid().ToString('N')+'.tmp'
        try{[IO.File]::WriteAllText($tmp,$json,$script:utf8);Move-Item -LiteralPath $tmp -Destination $selectionFile -Force}finally{if(Test-Path -LiteralPath $tmp){Remove-Item -LiteralPath $tmp}}
        Show-LocalResult 2 '頁面選擇已儲存' '首次更新需重建映像，之後按 Ctrl+F5 重新整理即可套用。'
        Note '頁面設定已儲存。首次更新需重建映像，之後按 Ctrl+F5 重新整理即可套用。'
    }catch{Show-LocalResult 2 '頁面設定未能儲存' $_.Exception.Message -Failed;Note $_.Exception.Message}
}
# Optional scheduler; opening setup never enables data exchange.
$exchangeLayout=New-Object Windows.Forms.TableLayoutPanel;$exchangeLayout.Dock='Fill';$exchangeLayout.ColumnCount=1;$exchangeLayout.RowCount=4
foreach($height in @(90,48,78)){[void]$exchangeLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',$height)))}
[void]$exchangeLayout.RowStyles.Add((New-Object Windows.Forms.RowStyle('Percent',100)));$exchangeTab.Controls.Add($exchangeLayout)
$exchangeLayout.Controls.Add((New-Label "選用流程：先安裝環境，並在資料庫設定儲存、套用 SPC 連線。`n啟用後立即執行，再每 10 分鐘將 SPC 來源實體表全欄位替換至正式 IDCC PostgreSQL 的七張表。`n不受 Demo 開關影響；來源零筆也會清空目標。上一輪未完成時不重複執行。"),0,0)
$exchangeButtons=New-Object Windows.Forms.FlowLayoutPanel;$exchangeButtons.Dock='Fill';$exchangeLayout.Controls.Add($exchangeButtons,0,1)
$exchangeCheck=New-Button $exchangeButtons '先檢查（不寫入）' {Start-Task SyncCheck}
$exchangeEnable=New-Button $exchangeButtons '啟用每 10 分鐘同步' {
    $answer=[Windows.Forms.MessageBox]::Show("啟用後立即同步，之後每 10 分鐘持續完整替換正式 IDCC 的七張表。`n來源零筆時對應目標表會清空，Demo 模式也不會略過。`n確定啟用？",'啟用定時資料同步',[Windows.Forms.MessageBoxButtons]::YesNo,[Windows.Forms.MessageBoxIcon]::Warning)
    if($answer -eq 'Yes'){Start-Task SyncEnable}
}
$exchangeDisable=New-Button $exchangeButtons '停用同步' {Start-Task SyncDisable}
$exchangeRefresh=New-Button $exchangeButtons '重新整理狀態與紀錄' {Start-ExchangeMonitor}
$exchangeStatus=New-Object Windows.Forms.TableLayoutPanel;$exchangeStatus.Dock='Fill';$exchangeStatus.ColumnCount=1;$exchangeStatus.RowCount=2
[void]$exchangeStatus.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',36)));[void]$exchangeStatus.RowStyles.Add((New-Object Windows.Forms.RowStyle('Absolute',34)));$exchangeLayout.Controls.Add($exchangeStatus,0,2)
$exchangeState=New-Object Windows.Forms.Label;$exchangeState.Text='同步排程：尚未查詢';$exchangeState.Dock='Fill';$exchangeState.TextAlign='MiddleLeft';$exchangeState.Padding='10,0,0,0';$exchangeState.Font=New-Object Drawing.Font($form.Font.FontFamily,12,[Drawing.FontStyle]::Bold);$exchangeState.ForeColor=[Drawing.Color]::DimGray;$exchangeStatus.Controls.Add($exchangeState,0,0)
$exchangeMeta=New-Object Windows.Forms.FlowLayoutPanel;$exchangeMeta.Dock='Fill';$exchangeStatus.Controls.Add($exchangeMeta,0,1)
$exchangeAuto=New-Object Windows.Forms.CheckBox;$exchangeAuto.Text='每 10 秒背景更新紀錄';$exchangeAuto.Checked=$true;$exchangeAuto.AutoSize=$true;$exchangeAuto.Margin='10,5,0,0';$exchangeMeta.Controls.Add($exchangeAuto)
$exchangeUpdated=New-Label '尚未更新';$exchangeUpdated.Margin='12,5,0,0';$exchangeMeta.Controls.Add($exchangeUpdated)
$exchangeLog=New-Object Windows.Forms.TextBox;$exchangeLog.Multiline=$true;$exchangeLog.ReadOnly=$true;$exchangeLog.ScrollBars='Both';$exchangeLog.WordWrap=$false;$exchangeLog.Dock='Fill';$exchangeLog.Text='啟用為選用步驟，可直接略過。狀態為執行中代表排程容器運作；請以紀錄中的 exit code 0 確認每次同步成功。關閉 setup 不會停止已啟用的同步。';$exchangeLayout.Controls.Add($exchangeLog,0,3)

# Child process runs outside the UI thread. Poll log files so the window stays responsive.
$script:job=$null;$timer=New-Object Windows.Forms.Timer;$timer.Interval=500
function New-SetupJobDirectory([string]$Prefix){
    # TEMP may contain an obsolete 8.3 alias. Prefer the Windows known-folder path.
    $base=[Environment]::GetFolderPath([Environment+SpecialFolder]::LocalApplicationData)
    if([string]::IsNullOrWhiteSpace($base)){$base=[IO.Path]::GetTempPath()}
    $parent=[IO.Path]::Combine($base,'IDCC-DemoBeta','jobs')
    $path=[IO.Path]::Combine($parent,($Prefix+'-'+[guid]::NewGuid().ToString('N')))
    [void][IO.Directory]::CreateDirectory($path)
    return $path
}
function Read-Log([string]$Path){
    try{
        if([string]::IsNullOrWhiteSpace($Path) -or ![IO.File]::Exists($Path)){return ''}
        $stream=[IO.File]::Open($Path,'Open','Read','ReadWrite');$reader=New-Object IO.StreamReader($stream)
        try{return $reader.ReadToEnd()}finally{$reader.Dispose()}
    }catch{return ''}
}
function Remove-MonitorFiles {
    # Best-effort, non-recursive cleanup via .NET, without PowerShell provider normalization.
    foreach($file in @($script:exchangeOut,$script:exchangeErr)){
        try{if(![string]::IsNullOrWhiteSpace($file)){[IO.File]::Delete($file)}}catch{}
    }
    try{if(![string]::IsNullOrWhiteSpace($script:exchangeDir)){[IO.Directory]::Delete($script:exchangeDir,$false)}}catch{}
}
function Show-TaskView($View,[int]$Tab=$script:activeTaskTab){
    $entry=$script:tabProgress[$Tab]
    $entry.Title.Text=$View.Title
    $entry.Title.ForeColor=switch($View.State){'Error'{[Drawing.Color]::Firebrick};'Warning'{[Drawing.Color]::DarkOrange};'Success'{[Drawing.Color]::ForestGreen};default{[Drawing.Color]::SteelBlue}}
    $entry.Bar.Style='Blocks'
    $entry.LastProgress=[Math]::Max([int]$entry.LastProgress,[int]$View.Progress)
    $entry.Bar.Value=[Math]::Min(100,$entry.LastProgress)
    $note=if($View.Complete){"流程步驟進度：$($entry.Bar.Value)%"}else{"流程步驟進度：$($entry.Bar.Value)%（不是下載百分比；此階段完成後才會前進）。"}
    $entry.Detail.Text=$View.Detail+"`r`n"+$note
}
function Set-TaskTime([string]$Text,[int]$Tab=$script:activeTaskTab){$script:tabProgress[$Tab].Time.Text=$Text}
function Show-LocalResult([int]$Tab,[string]$Title,[string]$Detail,[switch]$Failed){
    $script:tabProgress[$Tab].LastProgress=0
    $state=if($Failed){'Error'}else{'Success'};$value=if($Failed){0}else{100}
    Show-TaskView ([pscustomobject]@{Title=$Title;Detail=$Detail;State=$state;Progress=$value;Complete=$true}) -Tab $Tab
    Set-TaskTime ('操作結束：'+(Get-Date -Format 'HH:mm:ss')) -Tab $Tab
}
function Set-TaskBusy([bool]$Busy){
    $script:busy=$Busy
    # Keep tab navigation available, while preventing concurrent configuration changes.
    foreach($content in @($flow,$dbLayout,$pageLayout,$exchangeLayout)){$content.Enabled=!$Busy}
}
function Start-Task([string]$Action){
    if($Action -eq 'SyncInspect'){Start-ExchangeMonitor;return}
    if($script:busy){return}
    $script:exchangeGeneration++
    $script:activeTaskTab=switch($Action){'Apply'{1};'Diagnose'{1};'SyncCheck'{3};'SyncEnable'{3};'SyncDisable'{3};default{0}}
    $script:tabProgress[$script:activeTaskTab].LastProgress=0;$script:tabProgress[$script:activeTaskTab].Bar.Value=0
    try{
        $script:jobDir=New-SetupJobDirectory 'setup'
        $script:outFile=[IO.Path]::Combine($script:jobDir,'stdout.log');$script:errFile=[IO.Path]::Combine($script:jobDir,'stderr.log')
        $runner=[IO.Path]::Combine($PSScriptRoot,'setup-action.ps1')
        $script:activeAction=$Action;Set-TaskBusy $true
        $script:watch=[Diagnostics.Stopwatch]::StartNew();Set-TaskTime '處理中，請保持視窗開啟。'
        Show-TaskView (Get-SetupTaskView -Action $Action)
        $script:job=Start-Process powershell.exe -WindowStyle Hidden -WorkingDirectory $script:root -PassThru -RedirectStandardOutput $script:outFile -RedirectStandardError $script:errFile -ArgumentList @('-NoProfile','-ExecutionPolicy','Bypass','-File',('"'+$runner+'"'),'-Action',$Action,'-Port',[string]$port.Value)
        $null=$script:job.Handle
        $timer.Start()
    }catch{
        Set-TaskBusy $false
        Show-TaskView (Get-SetupTaskView -Action $Action -ExitCode 1)
        Set-TaskTime '作業未能啟動。請確認專案檔案完整，或重新開啟安裝工具。'
    }
}
$timer.Add_Tick({
    if(!$script:job){return}
    # Native output stays in temporary diagnostic files, never in the visible interface.
    $text=(Read-Log $script:outFile)+"`r`n"+(Read-Log $script:errFile)
    $elapsed=$script:watch.Elapsed
    Set-TaskTime ('已執行 {0:00} 分 {1:00} 秒；時間依網路與電腦速度而定。' -f [int][Math]::Floor($elapsed.TotalMinutes),$elapsed.Seconds)
    if($script:job.HasExited){
        $script:job.WaitForExit();$code=$script:job.ExitCode;$timer.Stop();Set-TaskBusy $false;$script:watch.Stop()
        $text=(Read-Log $script:outFile)+"`r`n"+(Read-Log $script:errFile)
        $view=Get-SetupTaskView -Action $script:activeAction -Text $text -ExitCode $code
        Show-TaskView $view
        if($script:activeAction -in @('SyncCheck','SyncEnable','SyncDisable')){
            $exchangeLog.Text=$text
        }
        if($code -eq 3010){Set-TaskTime '請重新啟動 Windows 後再繼續。'}elseif($view.State -eq 'Error'){Set-TaskTime '請依上方說明處理後重試。'}else{Set-TaskTime '操作已結束，可以繼續使用其他功能。'}
        if($code -eq 0 -and $script:activeAction -in @('Install','Apply')){Refresh-PrimaryConnection}
        $script:job.Dispose();$script:job=$null
        if($script:activeAction -in @('SyncEnable','SyncDisable','Install','Apply','Reset')){Start-ExchangeMonitor}
        if($script:activeAction -in @('Install','Apply','Reset')){Start-EnvironmentMonitor}
    }else{Show-TaskView (Get-SetupTaskView -Action $script:activeAction -Text $text)}
})
$form.Add_FormClosing({param($sender,$e) if($script:busy){$e.Cancel=$true;Note '作業執行中，請等候完成再關閉。'}})
# Read-only monitoring has its own worker: never changes task progress or disables controls.
$script:exchangeJob=$null;$script:exchangeGeneration=0
$exchangePoll=New-Object Windows.Forms.Timer;$exchangePoll.Interval=300
function Start-ExchangeMonitor {
    if($script:busy -or $script:exchangeJob -or $form.IsDisposed){return}
    try{
        $script:exchangeDir=New-SetupJobDirectory 'monitor'
        $script:exchangeOut=[IO.Path]::Combine($script:exchangeDir,'stdout.log');$script:exchangeErr=[IO.Path]::Combine($script:exchangeDir,'stderr.log')
        $runner=[IO.Path]::Combine($PSScriptRoot,'setup-action.ps1')
        $script:exchangeReadGeneration=$script:exchangeGeneration
        $script:exchangeWatch=[Diagnostics.Stopwatch]::StartNew()
        $script:exchangeJob=Start-Process powershell.exe -WindowStyle Hidden -WorkingDirectory $script:root -PassThru -RedirectStandardOutput $script:exchangeOut -RedirectStandardError $script:exchangeErr -ArgumentList @('-NoProfile','-ExecutionPolicy','Bypass','-File',('"'+$runner+'"'),'-Action','SyncInspect')
        $null=$script:exchangeJob.Handle;$exchangePoll.Start()
    }catch{
        $exchangeState.Text='同步排程：狀態未知（無法查詢）';$exchangeState.ForeColor=[Drawing.Color]::DarkOrange
        if($script:exchangeJob){try{if(!$script:exchangeJob.HasExited){$script:exchangeJob.Kill()};$script:exchangeJob.Dispose()}catch{}}
        $script:exchangeJob=$null;$exchangePoll.Stop();Remove-MonitorFiles
    }
}
$exchangePoll.Add_Tick({
    if(!$script:exchangeJob){return}
    $release=$false
    try {
        if(!$script:exchangeJob.HasExited -and $script:exchangeWatch.Elapsed.TotalSeconds -lt 45){return}
        $release=$true
        $timedOut=!$script:exchangeJob.HasExited
        if($timedOut){try{$script:exchangeJob.Kill()}catch{}; $code=1}else{$script:exchangeJob.WaitForExit();$code=$script:exchangeJob.ExitCode}
        $text=(Read-Log $script:exchangeOut)+"`r`n"+(Read-Log $script:exchangeErr)
        if(!$script:busy -and $script:exchangeReadGeneration -eq $script:exchangeGeneration){
            $exchangeState.ForeColor=[Drawing.Color]::DimGray
            if($code -ne 0){$exchangeState.Text='同步排程：狀態未知（請確認 Docker 環境）';$exchangeState.ForeColor=[Drawing.Color]::DarkOrange}
            elseif($text -match 'IDCC_SYNC_STATUS:running'){$exchangeState.Text='同步排程：已啟用｜每 10 分鐘';$exchangeState.ForeColor=[Drawing.Color]::ForestGreen}
            elseif($text -match 'IDCC_SYNC_STATUS:missing'){$exchangeState.Text='同步排程：尚未啟用'}
            elseif($text -match 'IDCC_SYNC_STATUS:stopped'){$exchangeState.Text='同步排程：已停用'}
            else{$exchangeState.Text='同步排程：未就緒（請查看紀錄）';$exchangeState.ForeColor=[Drawing.Color]::DarkOrange}
            $exchangeUpdated.Text='最後查詢：'+(Get-Date -Format 'HH:mm:ss')
            $logs=($text -replace '(?m)^IDCC_SYNC_STATUS:[^\r\n]*','').Trim()
            if(!$logs){$logs='目前沒有同步紀錄。啟用後會立即執行第一次同步。'}
            if($logs -ne $exchangeLog.Text){
                $selection=$exchangeLog.SelectionStart;$length=$exchangeLog.SelectionLength
                $atEnd=($selection -ge [Math]::Max(0,$exchangeLog.TextLength-1))
                $exchangeLog.Text=$logs
                if($atEnd){$exchangeLog.SelectionStart=$exchangeLog.TextLength;$exchangeLog.ScrollToCaret()}
                else{$exchangeLog.SelectionStart=[Math]::Min($selection,$exchangeLog.TextLength);$exchangeLog.SelectionLength=[Math]::Min($length,$exchangeLog.TextLength-$exchangeLog.SelectionStart)}
            }
        }
    }catch{
        $release=$true
        # A read-only timer must never raise a WinForms unhandled-exception dialog.
        if(!$form.IsDisposed){$exchangeState.Text='同步排程：狀態未知（查詢未完成，請重新整理）';$exchangeState.ForeColor=[Drawing.Color]::DarkOrange}
    }finally{
        # The early return above keeps a running query alive; completed/error queries are released.
        if($release){
            try{if($script:exchangeJob -and !$script:exchangeJob.HasExited){$script:exchangeJob.Kill()};if($script:exchangeJob){$script:exchangeJob.Dispose()}}catch{}
            $script:exchangeJob=$null;$exchangePoll.Stop();if($script:exchangeWatch){$script:exchangeWatch.Stop()};Remove-MonitorFiles
        }
    }
})
$script:environmentJob=$null
$environmentPoll=New-Object Windows.Forms.Timer;$environmentPoll.Interval=300
function Clear-EnvironmentMonitor {
    try{if($script:environmentJob){if(!$script:environmentJob.HasExited){$script:environmentJob.Kill()};$script:environmentJob.Dispose()}}catch{}
    $script:environmentJob=$null;$environmentPoll.Stop()
    foreach($path in @($script:environmentOut,$script:environmentErr)){try{if($path){[IO.File]::Delete($path)}}catch{}}
    try{if($script:environmentDir){[IO.Directory]::Delete($script:environmentDir,$false)}}catch{}
}
function Show-EnvironmentState($record){
    $names=@{backend='執行環境';docker='Docker 引擎';image='Apache／PHP 映像';web='網站服務';postgres='PostgreSQL'}
    $states=@{not_configured='尚未完成安裝／引擎設定';unknown='狀態未知';ready='已就緒';missing='尚未建立';unavailable='無法連線，可能尚未啟動';healthy='已啟動，健康檢查通過';running='已啟動，尚無健康確認';stopped='已停止';starting='啟動中';unhealthy='已啟動，健康檢查未通過'}
    foreach($key in @('backend','docker','image','web','postgres')){
        $state=[string]$record.$key;$description=$states[$state]
        if($key -eq 'backend'){$description=switch($state){'wsl'{'WSL 2／Ubuntu 24.04／Docker Engine'};'desktop'{'沿用 Docker Desktop'};'unknown'{'狀態未知'};default{'尚未完成安裝／引擎設定'}}}
        if(!$description){$description='狀態未知'}
        if($key -eq 'docker' -and $state -eq 'ready' -and $record.version){$description+='（'+[string]$record.version+'）'}
        $label=$script:environmentLabels[$key];$label.Text=$names[$key]+'：'+$description
        $label.ForeColor=if($state -in @('ready','healthy','wsl','desktop')){[Drawing.Color]::ForestGreen}elseif($state -in @('unknown','unavailable','unhealthy','starting')){[Drawing.Color]::DarkOrange}else{[Drawing.Color]::DimGray}
    }
    $environmentUpdated.Text='最後查詢：'+(Get-Date -Format 'HH:mm:ss')+'（不測試 SPC 業務連線）'
}
function Start-EnvironmentMonitor {
    if($script:busy -or $script:environmentJob -or $form.IsDisposed){return}
    try{
        $script:environmentDir=New-SetupJobDirectory 'environment'
        $script:environmentOut=[IO.Path]::Combine($script:environmentDir,'stdout.log');$script:environmentErr=[IO.Path]::Combine($script:environmentDir,'stderr.log')
        $script:environmentGeneration=$script:exchangeGeneration;$script:environmentWatch=[Diagnostics.Stopwatch]::StartNew()
        $runner=[IO.Path]::Combine($PSScriptRoot,'setup-action.ps1')
        $script:environmentJob=Start-Process powershell.exe -WindowStyle Hidden -WorkingDirectory $script:root -PassThru -RedirectStandardOutput $script:environmentOut -RedirectStandardError $script:environmentErr -ArgumentList @('-NoProfile','-ExecutionPolicy','Bypass','-File',('"'+$runner+'"'),'-Action','EnvironmentInspect')
        $null=$script:environmentJob.Handle;$environmentPoll.Start()
    }catch{Clear-EnvironmentMonitor;Show-EnvironmentState ([pscustomobject]@{backend='unknown';docker='unknown';image='unknown';web='unknown';postgres='unknown'})}
}
$environmentPoll.Add_Tick({
    if(!$script:environmentJob){return}
    $release=$false
    try{
        if(!$script:environmentJob.HasExited -and $script:environmentWatch.Elapsed.TotalSeconds -lt 45){return}
        $release=$true
        if(!$script:environmentJob.HasExited){throw 'Status query timed out'}
        $script:environmentJob.WaitForExit()
        if($script:environmentJob.ExitCode -ne 0){throw 'Status query failed'}
        $record=Read-Log $script:environmentOut | ConvertFrom-Json
        if($record.stage -ne 'environment_status'){throw 'No status record'}
        if(!$script:busy -and $script:environmentGeneration -eq $script:exchangeGeneration){Show-EnvironmentState $record}
    }catch{
        $release=$true
        if(!$script:busy -and $script:environmentGeneration -eq $script:exchangeGeneration){Show-EnvironmentState ([pscustomobject]@{backend='unknown';docker='unknown';image='unknown';web='unknown';postgres='unknown'})}
    }finally{if($release){Clear-EnvironmentMonitor}}
})
$environmentTimer=New-Object Windows.Forms.Timer;$environmentTimer.Interval=30000
$environmentTimer.Add_Tick({if($tabs.SelectedTab -eq $install){Start-EnvironmentMonitor}})
$environmentTimer.Start()
$exchangeTimer=New-Object Windows.Forms.Timer;$exchangeTimer.Interval=10000
$exchangeTimer.Add_Tick({if($tabs.SelectedTab -eq $exchangeTab -and $exchangeAuto.Checked){Start-ExchangeMonitor}})
$tabs.Add_SelectedIndexChanged({Show-TabProgress;if(!$SmokeTest){if($tabs.SelectedTab -eq $exchangeTab){Start-ExchangeMonitor};if($tabs.SelectedTab -eq $install){Start-EnvironmentMonitor}}})
$form.Add_Shown({Show-TabProgress;if(!$SmokeTest){Start-ExchangeMonitor;Start-EnvironmentMonitor}})
$exchangeTimer.Start()
$form.Add_FormClosed({
    $timer.Dispose();$exchangeTimer.Dispose();$exchangePoll.Dispose();$environmentTimer.Dispose();Clear-EnvironmentMonitor;$environmentPoll.Dispose()
    if($script:exchangeJob){try{if(!$script:exchangeJob.HasExited){$script:exchangeJob.Kill()}}catch{};$script:exchangeJob.Dispose()}
})
$tabs.SelectedIndex=@('Install','Database','Pages','Exchange').IndexOf($Tab)
if($SmokeTest){
    if($list.Items.Count -ne 77){throw '頁面目錄不完整'}
    if($tabs.TabPages.Count -ne 4 -or $script:tabProgress.Count -ne 4 -or !$exchangeLog.ReadOnly){throw 'Exchange tab construction failed'}
    $exchangeTimer.Dispose();$exchangePoll.Dispose();$environmentTimer.Dispose();$environmentPoll.Dispose();$timer.Dispose();$form.Dispose();'GUI construction OK';return
}
[void]$form.ShowDialog()
