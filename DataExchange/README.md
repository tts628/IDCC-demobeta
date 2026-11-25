# DataExchange：SPC 來源表 → 正式 IDCC 全量交換

直接讀取八份 SQL 所用的七張 **SPC SQL Server 實體表**，以 `SELECT *` 取得實際存在的全部欄位與全部資料列，寫入正式 **IDCC PostgreSQL** 的同名表。不再依 View 的欄位投影、計算、JOIN 或篩選讀取，因此不要求來源有 WaterUsed、PowerUsed、SteamUsed 等預設欄位。不改動來源／DemoIDCC，也不受 Demo 開關影響。

| SPC 來源 | PostgreSQL 目標 |
|---|---|
| dbo.DispenserEvent | public."DispenserEvent" |
| dbo.DyelotsBulkedRecipe | public."DyelotsBulkedRecipe" |
| dbo.Dyelots | public."Dyelots" |
| dbo.StepVisits | public."StepVisits" |
| dbo.Delays | public."Delays" |
| dbo.Alarms | public."Alarms" |
| dbo.Machines | public."Machines" |

`StepVisits`、`Dyelots` 被多份 View 共用，各只同步一次。這不是整個 SPC 資料庫備份。舊版的 DispenserProduction、DyeEnergyUsage、DyeEvent、DyeMachines、DyeProduction、DyeStepVisits 計算結果不再產生／更新；如果它們已存在，不會自動刪除。

## Windows 操作

更新完整專案，重新執行安裝／重建映像以套用 DataExchange 的 HTTP 存取封鎖。環境必須已啟動，SPC 與正式 IDCC 設定已儲存並套用。先在 CMD 檢查：

```cmd
cd /d C:\IDCC-demobeta-main
powershell.exe -NoProfile -ExecutionPolicy Bypass -File DataExchange\sync.ps1 -Check
```

檢查只讀取連線、SQL 欄位及目標結構，沒有寫入資料，也不預先計算全表筆數。全部通過後，雙擊 `DataExchange\sync.cmd`，或在 CMD 執行：

```cmd
powershell.exe -NoProfile -ExecutionPolicy Bypass -File DataExchange\sync.ps1
```

列出七張目標表後，輸入大寫 **SYNC** 才會執行。這是全量替換，不是追加：來源零筆也會清空對應目標表，本機手動新增到這七張表的資料也會被來源結果取代。其他表不在範圍。請保留自己的資料庫備份。

若使用不同專案路徑，先切換到自己的專案根目錄；必須保留整個 DataExchange 目錄與專案 windows/docker.ps1，不是只複製 sync.php。

## Ubuntu 操作

```bash
bash DataExchange/sync.sh --check
bash DataExchange/sync.sh
```

第二行同樣須輸入 SYNC。沒有 Docker socket 存取權時會使用 sudo，不連到遠端 Docker context。

## 直接在網站容器執行

```sh
php DataExchange/sync.php --check
php DataExchange/sync.php --sync
```

上面是容器內指令。從主機呼叫須使用原本 Docker 命令代理，例如 Windows：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose exec -T web php DataExchange/sync.php --check
```

`--sync --yes` 可略過互動確認，用於自己明確安排的批次執行；也可使用下面的 Docker 定時同步服務。

## 連線來源

- SPC：使用容器已載入的 SPC_DB_HOST／NAME／UID／PWD 與 SPC_DB_TRUST_SERVER_CERTIFICATE，並相容既有 SPC 舊別名。
- 正式 IDCC：使用安裝自動填入的 PostgreSQL IDCC_DB_*；舊版 PostgreSQL 設定相容 POSTGRES_*／IDCC_PG_*。
- 不透過會依 Demo 開關切換的 DbMapper IDCC 路由，因此開著 Demo 也不會誤寫 DemoIDCC。
- 不讀取或輸出密碼檔內容到畫面；SQL Server / PostgreSQL 原始錯誤可能帶資料列，工具只顯示安全錯誤類別或 SQLSTATE。

## 交換方式與保留範圍

1. 在來源以 prepared statement metadata 檢查七個 SELECT 欄位，並檢查目標結構。
2. 在 PostgreSQL 開啟一個交易與互斥鎖，避免同時執行兩次交換。
3. 來源逐列讀取，分批寫入 session 暫存表；預設每批最多 500 筆，並按參數數量與約 4 MiB 內容自動縮小。
4. 七張暫存表筆數核對成功後，短暫鎖定這七張目標表、刪除舊資料、寫入新資料、再次核對筆數，最後一次提交。

提交前出錯會回復整批目標變更。若最後提交時網路中斷，程式會明確標示「提交結果未能確認」，不能假設一定沒提交，請先檢查目標筆數。工具不使用 DROP TABLE、TRUNCATE CASCADE 或全域清除。

若來源資料庫已允許 SNAPSHOT，會使用同一個唯讀快照讀取；未啟用時採逐表讀取，來源持續寫入時，各表讀取時點可能不同。工具不會替來源開啟 SNAPSHOT，也不會執行 ALTER DATABASE。SQL 中 GETDATE 計算仍使用各查詢的執行時間。

目標表不存在時自動建立，保留來源欄位名稱大小寫，欄位允許 NULL。已存在且僅名稱大小寫不同的表會重用，欄位按名稱對應，不重複建立另一張大小寫不同的表。現有一般索引與唯一值限制保留，資料不符合限制就失敗回復，不會自動更改現有欄位。

為避免間接影響其他資料，若既有目標是 View、分割／繼承表、啟用 RLS，或存在外鍵、觸發器、identity/generated/sequence 欄位，檢查會停止，不會自行刪除這些限制。這是交換用資料表，不複製 SQL Server 的索引、主鍵、權限、預設值、觸發器或 stored procedure。

## 來源欄位與 SQL

`tables.json` 固定七張表；`sql/` 每檔只有 `SELECT * FROM [dbo].[表名];`，直接使用設定的 SPC 資料庫。來源每張表的實際欄位與型別由 SQLSRV metadata 取得，不要求固定欄位數或名稱。新目標表依這些欄位建立，不會自行加上計算欄位。

`references/` 保存原始八份 SQL（manifest 保留原始 SHA256），`references/view-queries/` 保存舊版轉換查詢，兩者皆不執行。新流程不需 DATEDIFF_BIG、LAG、時間偏移或資料關聯；時間及數值直接取來源欄位，不重新計算。

已存在的目標表仍須能容納來源欄位；若來源日後新增／刪除欄位造成結構不同，會停止並指出目標結構問題，避免自動刪除既有欄位或依賴。這個限制和 View 預期欄位檢查不同；目前不再要求來源存在任何指定的業務欄位。

## 型別與精度

依 SQLSRV metadata 對應 PostgreSQL：整數、decimal/money、浮點、布林、UUID、文字、binary、date/time 等。decimal 保留來源 precision/scale；binary 使用 bytea；空字串與 NULL 分開。未支援的 SQL 型別會停止，不自動猜測。

SQL Server 的第七位時間小數無法由 PostgreSQL 的六位精度完整表示，因此該類欄位以 text 保存；原 View 的 varchar 時間欄位也保持文字。保留大小寫的欄位／新表在 PostgreSQL SQL 中須雙引號，例如：

```sql
SELECT * FROM public."Dyelots";
```

工具不會偷偷刪除文字中的 NUL 或替換無效字元，這些內容不相容時會停止回復。已有目標表的型別與長度仍由 PostgreSQL 驗證，結構不合需先調整。特大型單筆 LOB 仍需要足夠 PHP 記憶體；分批處理不代表單筆可無限大。

## 選項與測試

Windows `sync.ps1 -BatchSize 1000 -SourceTimeout 600`；Ubuntu `sync.sh --batch-size 1000 --source-timeout 600`。來源查詢預設 0 秒即不限制，連線仍有登入逾時。執行中只顯示表名與累積筆數，不顯示資料列內容。

不連真實資料庫的測試可在容器執行：

```sh
php DataExchange/tests/test.php
php DataExchange/tests/test-target.php
```

它們用模擬來源／目標驗證全量替換、空來源、重跑不追加、失敗回復、精度與保護條件，不能取代真實 SQL Server／PostgreSQL 整合測試。正式交換前請先執行 --check。資料寫入完成也不代表原網站已完成 PostgreSQL SQL 移植，工具不會自動關閉 Demo。

技術依據：[Microsoft SQLSRV 欄位 metadata](https://learn.microsoft.com/en-us/sql/connect/php/sqlsrv-field-metadata)、[ODBC 日期時間型別](https://learn.microsoft.com/en-us/sql/relational-databases/native-client-odbc-date-time/data-type-support-for-odbc-date-and-time-improvements)。

## 每十分鐘自動同步

定時服務和手動工具使用相同的 SPC／正式 IDCC PostgreSQL 連線，不受 Demo 開關影響，仍是七張表全量替換（零筆也清空）。一般安裝預設不啟用。**啟用代表授權持續替換這七張表，啟動會立即同步一次**；請先檢查連線與同步範圍。

Windows 在專案根目錄執行：

```powershell
# 啟用（先檢查連線／結構，通過才建立排程容器）
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\DataExchange\schedule.ps1 -Action Enable
# 查看是否執行中
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\DataExchange\schedule.ps1 -Action Status
# 查看各次開始時間、結束代碼與錯誤
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\DataExchange\schedule.ps1 -Action Logs
# 停用，保留已同步的資料
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\DataExchange\schedule.ps1 -Action Disable
```

Ubuntu 在專案根目錄執行（需要時在 docker 前加 sudo）：

```sh
bash DataExchange/sync.sh --check
docker compose --profile data-exchange up -d --no-build data-exchange
docker compose --profile data-exchange ps -a data-exchange
docker compose --profile data-exchange logs --tail 100 data-exchange
docker compose --profile data-exchange stop data-exchange
```

排程使用既有本機 PHP 映像，不需安裝 Windows 工作排程或 cron。先安裝／啟動專案，確保映像已建立。每次正常同步以開始時間相隔 600 秒；單次超過 600 秒則完成後等待 600 秒，不補跑、不累積工作。上一輪完成才開始下一輪；手動與排程亦共用 PostgreSQL 互斥鎖。同步失敗會記錄非零結束代碼，下一輪繼續嘗試；容器顯示 Up 只代表排程運作，須查看 Logs 確認同步成功（exit code 0）。時間記錄為 UTC。

電腦關機、休眠或 Docker 引擎停止期間不會同步。重開機需先啟動 Docker 引擎；引擎啟動後服務依 unless-stopped 自動恢復。主動 Disable 後不會自行恢復，需再次 Enable。修改 .env／.env.postgres 後再次 Enable 以重建載入新設定的容器；不會把密碼寫在命令列。清除專案容器後需要重新 Enable。日誌循環保留最多三份、每份約 10MB。

若要改頻率，在 compose.yaml 的 data-exchange.environment 調整 DATA_EXCHANGE_INTERVAL_SECONDS（預設 600，允許 60–86400），再 Enable；同步量很大時，全量交換可能持續超過十分鐘。

## Windows setup 視窗

開啟 `windows/setup.cmd`，選擇「4 定時資料同步（選用）」。可先按「先檢查（不寫入）」，再自行選擇「啟用每 10 分鐘同步」；完全可略過，不影響安裝環境、資料庫設定及頁面選擇。啟用前會說明全量替換範圍並請你確認。

同頁可停用同步、查看狀態與最近 100 行紀錄。停留此頁時預設每 10 秒更新，可取消勾選；亦可手動重新整理。狀態顯示排程執行中，並不代表每輪同步成功，請確認紀錄的 `exit code 0`。關閉 setup 不會停用排程。

## 從舊版 View 同步更新

若排程已啟用，更新整包程式前先停用，避免更新一半時啟動一輪。更新後在 setup 的「定時資料同步」先檢查再啟用，或執行 `schedule.ps1 -Action Enable`。Ubuntu 同樣先 stop，更新完整程式後再 up。原本的計算結果表保留，這次只更新上述七張來源同名表，不自動移除其他表。

setup 的固定「同步排程」狀態會顯示已啟用／已停用／尚未啟用／狀態未知。自動更新在背景讀取，不會重設下方流程進度或鎖住視窗；查詢失敗不會誤顯示已停用。已啟用代表排程服務運作，個別同步結果仍以紀錄中的結束代碼為準。
