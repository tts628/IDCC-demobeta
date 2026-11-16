# Docker 資料庫設定

連線資訊放在**專案根目錄 `.env`**。`env/database_IDCC.php` 與 `env/database_SPC.php` 透過 `getenv()` 讀取容器環境變數，通常不需要直接改這兩個 PHP 檔案。

Docker Compose 透過 `env_file` 把 `.env` 傳入容器，並非僅靠 Compose 的變數替換。未建立 `.env` 時仍能啟動網站環境，但資料庫功能不會具備正確連線設定。此設定需要支援 optional env_file 的 Compose 2.24 以上。

Windows PowerShell，在專案目錄執行：

```powershell
if (!(Test-Path .env)) { Copy-Item .env.example .env }
notepad .env
```

Ubuntu：

```bash
test -f .env || cp .env.example .env
nano .env
```

| 用途 | IDCC | SPC |
| --- | --- | --- |
| 伺服器 | IDCC_DB_HOST | SPC_DB_HOST |
| 資料庫 | IDCC_DB_NAME | SPC_DB_NAME |
| SQL 帳號 | IDCC_DB_UID | SPC_DB_UID |
| SQL 密碼 | IDCC_DB_PWD | SPC_DB_PWD |

SPC 保留舊變數 `HOST`、`MDSVN_SPC_DB`、`UID`、`PWD` 作為相容備援；新設定請使用 SPC_DB_*，避免與作業系統的 UID／PWD 變數混淆。

若 SQL Server 在同一台主機，HOST 使用 `host.docker.internal`；若在另一台電腦，填可連線的 IP／主機名稱。指定 TCP 連接埠可用 `主機,1433`，實際連接埠依你的 SQL Server 設定。資料庫仍需開放 TCP 並具備帳號及資料表。

密碼建議用單引號包住，避免 `$` 被當成變數展開；若密碼本身含單引號，需依 Compose env_file 語法以反斜線跳脫。範本只是佔位文字，安裝腳本不會代填或驗證真實帳密。

儲存後重新建立容器，單純 restart 不會載入新增或修改的容器環境變數：

```powershell
docker compose up -d --force-recreate --wait
```

Ubuntu 若原本使用 install.sh 設定 UID／GID，請執行 `bash ./install.sh` 以保留映射設定。

`.env` 已加入 `.gitignore` 和 `.dockerignore`，不要上傳，也不要使用 `git add --force .env`。`docker compose config` 可能顯示解析後的密碼，請勿把完整輸出貼到公開地方。
