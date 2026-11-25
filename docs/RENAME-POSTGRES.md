# 將既有 PostgreSQL idcc 改名為 BatchDyeingCentral

只適用於舊版已建立 `idcc` 資料庫的安裝。新安裝已使用 `BatchDyeingCentral`，不需要執行。改名前請先保留資料庫備份，不需 reset、刪除 volume 或重新建庫。

1. 在 setup 停用定時同步，再關閉 setup，避免背景查詢。Windows 在專案根目錄執行：

   ```powershell
   powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose stop web
   ```

   Ubuntu 使用 `docker compose --profile data-exchange stop web data-exchange`。停止期間網站不能使用。

2. 在 DBeaver 關閉所有連向舊 `idcc` 的連線。另建立／修改連線到維護資料庫 `postgres`，主機 `127.0.0.1`、埠 `15432`、帳號 `idcc`、原密碼。確認在這個維護連線，開啟自動提交後執行：

   ```sql
   ALTER DATABASE idcc RENAME TO "BatchDyeingCentral";
   ```

   必須保留雙引號以保留大小寫。若有其他連線占用或目標資料庫已存在，PostgreSQL 會拒絕改名；先排除原因，不要刪除任一資料庫或重複建立空庫。本步驟成功後才繼續。

3. 編輯本機 `.env.postgres`，將下列兩個設定改為新庫名；其餘帳密、主機與連接埠保留：

   ```dotenv
   POSTGRES_DB=BatchDyeingCentral
   IDCC_DB_NAME=BatchDyeingCentral
   ```

4. 開啟 setup 的資料庫分頁，確認 IDCC Server 已顯示新名稱，按「儲存並套用」再測試連線。Ubuntu 執行 `bash ubuntu/install.sh` 套用現有設定。確認成功後，原本有定時同步者再自行啟用。

5. DBeaver 的 Database 改為 `BatchDyeingCentral`，重新連線確認原表與資料仍在。只改 Database，Username 仍是 `idcc`。
