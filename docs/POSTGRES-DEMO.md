# IDCC Demo 與 PostgreSQL

Windows 執行專案內的 `windows/install.cmd`，Ubuntu 執行 `bash ubuntu/install.sh`。安裝程式會保留原有 `.env`，首次產生 `.env.postgres` 隨機密碼，啟動 PHP/Apache 與 PostgreSQL 17。重跑不會覆寫密碼或清空資料。

預設 `IDCC_DEMO_MODE=true`（未設定亦為 true），現有頁面沿用原有 IDCC_DB_* SQL Server 測試連線。SPC 連線維持原設定。

PostgreSQL 僅在 Docker 內部網路開放，主機名稱 postgres、埠 5432、資料庫 idcc、使用者 idcc。目前為空資料庫，不建立業務資料表或搬移資料。密碼在本機 `.env.postgres`，不提交 Git，也不複製進映像。

## 驗證

```sh
docker compose ps
docker compose exec web php docker/diagnose-idcc.php
docker compose exec postgres psql -U idcc -d idcc -c "SELECT current_database();"
```

原有 SQL Server 診斷仍使用 `docker compose exec web php docker/diagnose-db.php IDCC`，該工具專門測試 SQL Server。

## 之後切換

在 `.env` 加入 `IDCC_DEMO_MODE=false`，再執行：

```sh
docker compose up -d --build --force-recreate --wait
```

此時 `env/database_IDCC.php` 的 `$PDOconn` 與 `DbMapper->getPdo(['IDCC'])` 會連到 PostgreSQL；不會偷偷退回 SQL Server。`getPdo(['IDCC_DEMO'])` 可明確取得原有測試庫連線。

**目前舊頁面的 sqlsrv_* 與 T-SQL 尚未移植，且 PostgreSQL 為空庫，因此關閉 Demo 後舊頁面不能直接運作。** `getLink(['IDCC'])` 會明確指出需要移植，避免將 PDO 傳給 sqlsrv_*。直接載入設定的舊程式同樣需要改寫。請先建立資料表、改寫查詢，再正式切換。重新設為 true 並重建 web 容器可切回測試庫。

## 保存與更新

資料保存在 Compose named volume `idcc_postgres_data`。一般 `up`、`down`、容器重建會保留資料；不要執行 `docker compose down -v`，它會刪除資料庫。更新專案時保留 `.env` **與 `.env.postgres`**。保留並備份密碼檔；已有資料庫時改該檔不會自動修改資料庫內的密碼。

首次安裝後若只用 Compose 手動啟動，也必須先執行安裝程式產生密碼檔。現有環境沿用同一專案名稱與專案目錄，避免建立另一組 volume。Windows 原生 Apache 備用安裝不包含 PostgreSQL；此流程使用 Docker。

Windows WSL 模式的 Docker 命令請使用 `powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1` 取代命令開頭的 `docker`。安裝／重設入口會自動使用所選引擎；不安裝或卸載 Docker Desktop。
