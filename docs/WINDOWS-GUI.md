# Windows 安裝與設定介面

開啟 `windows/setup.cmd`。需要 Windows PowerShell 5.1 與內建 Windows Forms，使用同一 Windows 帳號授予管理員權限。GUI 只呼叫原本安裝／重設流程，不會自動卸載既有 Docker Desktop。

## 可以先不設定資料庫

「安裝環境」頁選擇連接埠後按「安裝／啟動」。資料庫連線可以全部留空。環境仍會建立 Apache/PHP 與空的 PostgreSQL。缺少 SQL Server 設定的頁面回應「尚未設定資料庫」，不代表安裝失敗。下方以進度條與白話文字顯示目前步驟，首次啟用 WSL 可能需要重開機；重開後再開啟此工具繼續。

## 獨立設定資料庫

之後可只開 `windows/database-setup.cmd`。IDCC、SPC 分開填寫。每組四欄須完整，或全部留空。密碼遮蔽顯示，存本機 `.env`，不記錄在執行參數或日誌。原有其他環境變數與註解保留。工具儲存時會處理密碼中的引號、反斜線與 $。原有使用變數插值的複雜 `.env` 設定請用文字編輯器維護。

「只儲存」不改正在執行的容器；「儲存並套用」重建容器使環境變數生效；請先完成環境安裝。「測試已儲存的連線」測試容器目前設定，檢查 PostgreSQL 並診斷已填寫的 IDCC/SPC SQL Server，留空的 SQL Server 會略過。不要把診斷中的略過當成已連線成功。

Demo 預設勾選，使用既有 SQL Server。取消勾選會切到空的 PostgreSQL；仍需自己完成業務資料表及 SQL 移植，舊頁面不能直接運作。

## 選擇左側頁面

開啟 `windows/pages-setup.cmd` 或主介面的「頁面選擇」。目前列出 77 個選單項目，依原分類顯示，提供全部保留／全部取消。儲存於本機 `.idcc-pages.json`，不刪程式檔、不提交 Git；沒有設定檔時全部保留。共用底層目錄的項目會連動，以免隱藏選單後仍透過共用 API 進入。

第一次更新到此版本必須重建映像，啟用 PHP 的 request guard。之後修改勾選，按 Ctrl+F5 即可更新選單。停用的本機頁面目錄內 PHP 入口／API 會回應 403；設定檔格式錯誤會回應 503，請用設定工具修正。首頁、共用框架、非選單模組保持可用。外部網站只能隱藏本機入口，無法限制外站存取。這是部署功能開關，不是使用者帳號權限系統，也不防止本機管理者修改程式／設定。

## 重設與更新

主介面的重設按鈕先顯示確認對話框，保留資料、密碼、`.env` 與頁面設定。原本 `windows/reset.cmd` 仍要求輸入 RESET。作業中無法關閉主視窗，避免誤以為安裝已結束。

更新時除了保留 `.env`、`.env.postgres`、`.idcc-docker-backend` 與 `env.json`，也要保留 `.idcc-pages.json`。手動鏡像同步的 `/XF` 清單請加入 `.idcc-pages.json`。PostgreSQL 資料仍在 Docker volume，資料夾備份不包含該資料卷。

## 開啟時閃退

新版入口改用獨立 launch-setup.ps1，固定使用 Windows PowerShell 5.1 的 STA 模式，失敗時保留視窗。開啟介面需要完整專案，不能只下載 windows 資料夾。可能原因包含檔案未同步完整、啟動參數／路徑、UAC 取消，或介面載入錯誤；需以實際錯誤判斷。

啟動失敗紀錄保存在 `%LOCALAPPDATA%\IDCC-DemoBeta\setup-startup.log`。只記錄啟動錯誤，不列出資料庫設定或環境變數。若舊版仍然閃退，可在 PowerShell 手動執行並保留錯誤：

```powershell
powershell.exe -NoProfile -STA -NoExit -ExecutionPolicy Bypass -File 'C:\IDCC-demobeta-main\windows\setup.ps1'
```

請提供錯誤訊息，以確認原因。

## 進度與結果

執行安裝、套用資料庫設定、連線測試、重設時，介面只顯示進度條、目前步驟與白話結果，不顯示指令、JSON 或結束代碼。執行中的移動式進度條表示仍在處理，不是假裝已下載多少百分比；安裝與下載時間會依電腦和網路而不同。

「資料庫設定已套用」代表容器已載入設定，仍須另按測試確認連線。「連線測試」會區分登入失敗、憑證、網路、資料表問題，留空略過的連線不會顯示為測試成功。需要重新啟動 Windows 時也會另行提示。原始輸出只保留在本次執行的暫存診斷檔，介面不直接顯示。
