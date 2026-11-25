# 三組資料庫連線設定

| 設定名稱 | 類型 | 用途與設定來源 |
|---|---|---|
| IDCC Server | PostgreSQL | 安裝時自動建立並填入正式連線，保存在 `.env.postgres` |
| DemoIDCC Server | SQL Server | 展示／測試連線，保存在 `.env` 的 `DEMOIDCC_DB_*` |
| SPC Server | SQL Server | 獨立連線，保存在 `.env` 的 `SPC_DB_*`，不受 Demo 切換影響 |

DemoIDCC 是連線設定的名稱，不會把原 SQL Server 資料庫改名，也不需要建立名為 DemoIDCC 的資料庫；資料庫名稱仍填原本的名稱。

## 切換規則

新安裝預設 `IDCC_DEMO_MODE=true`。開啟時，網站的 IDCC 頁面只使用 DemoIDCC SQL Server；關閉時只選用正式 IDCC PostgreSQL。連線失敗不會偷偷改用另一個連線。SPC 頁面在兩種模式下都繼續使用自己的 SPC SQL Server。

**PostgreSQL 目前是空的業務資料庫，舊頁面仍有 SQLSRV／T-SQL，關閉 Demo 前必須先移植資料表與查詢。** 容器安裝成功不代表業務頁面已完成移植。

## Windows 與 Ubuntu 操作

Windows 開啟 `windows/database-setup.cmd`，介面顯示三欄：IDCC 的 PostgreSQL 自動填入、僅供查看；DemoIDCC 與 SPC 可以編輯，密碼均遮蔽顯示。可先留空完成環境安裝。Ubuntu 使用 `bash ubuntu/database-setup.sh`，也能查看正式 IDCC 自動資訊並分開設定另外兩組連線。

修改後按「儲存並套用」重建容器，才會讓新環境變數生效。「只儲存」不會改變執行中的容器。再按連線測試，會分別列出 IDCC（PostgreSQL）、DemoIDCC、SPC 的結果；只建立連線與執行 SELECT 1，不檢查業務表，也不修改資料。

## Demo 與 SPC 的 .env 範例

```dotenv
IDCC_DEMO_MODE=true
IDCC_CONNECTION_LAYOUT=2
DEMOIDCC_DB_HOST=YOUR_DEMO_SQL_SERVER,1433
DEMOIDCC_DB_NAME=YOUR_EXISTING_DATABASE
DEMOIDCC_DB_UID=YOUR_SQL_LOGIN
DEMOIDCC_DB_PWD='YOUR_SQL_PASSWORD'
DEMOIDCC_DB_TRUST_SERVER_CERTIFICATE=false

SPC_DB_HOST=YOUR_SPC_SQL_SERVER,1433
SPC_DB_NAME=YOUR_SPC_DATABASE
SPC_DB_UID=YOUR_SQL_LOGIN
SPC_DB_PWD='YOUR_SQL_PASSWORD'
SPC_DB_TRUST_SERVER_CERTIFICATE=false
```

主機欄是可由容器到達的主機名／IP，可加 `,埠`；資料庫名稱是另一欄。Windows 能解析的短主機名稱，容器可能無法解析，必要時使用完整 DNS 名稱或正確 IP。憑證旗標預設 false，只有信任的測試伺服器才明確選擇略過憑證驗證。不要把密碼或完整設定檔貼到聊天或提交 Git。

## 自動填入的正式 IDCC

安裝程式保留原 `.env.postgres` 的 `POSTGRES_PASSWORD`，不重新產生，並在同檔同步：

```dotenv
IDCC_DB_DRIVER=pgsql
IDCC_DB_HOST=postgres
IDCC_DB_PORT=5432
IDCC_DB_NAME=BatchDyeingCentral
IDCC_DB_UID=idcc
# IDCC_DB_PWD 由安裝程式同步為既有 POSTGRES_PASSWORD，這裡不示範真實密碼。
```

這些是網站容器內的正式連線資訊，請勿在根目錄 `.env` 再放舊的 IDCC_DB_* SQL Server 帳密。DBeaver 在 Windows 主機上連線時使用主機映射的 IP／埠，不能直接把容器名稱 postgres 當成 Windows 主機名稱。專案預設映射 `127.0.0.1:15432:5432`，同台電腦的 DBeaver 使用 Host `127.0.0.1`、Port `15432`、Database `BatchDyeingCentral`、Username `idcc`，密碼填本機 `.env.postgres` 的 `POSTGRES_PASSWORD` 值。網站容器內仍使用 `postgres:5432`。此映射僅接受本機連線。

更新既有環境後，Windows 在專案根目錄執行：

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose up -d postgres
```

Ubuntu 執行 `docker compose up -d postgres`（需要時加 sudo）。套用會短暫重建資料庫容器，既有資料卷與資料保留，不需 reset。

## 從舊版更新

更新完整程式後，重跑安裝，或在設定介面按「儲存並套用」。程式會將根目錄舊 IDCC_DB_* SQL Server 設定轉為 DEMOIDCC_DB_*，保留原資料庫名稱、帳密與憑證選項，再把正式 IDCC PostgreSQL 資訊寫入 `.env.postgres`。已明確設定的 DemoIDCC 不會被覆寫，SPC 不會被轉換或更換；已手動關閉的 Demo 也會保留選擇。

轉換只調整本機設定檔，不搬移、改名或清除資料庫。仍請保留自己的備份；更新保留 `.env`、`.env.postgres`、`.idcc-docker-backend`、`.idcc-pages.json` 與自訂資料。重跑會重用既有 PostgreSQL 密碼與資料卷。若只覆蓋程式未重建容器，容器仍使用舊環境變數；轉換前的舊 SQL 設定僅為升級相容保留，不是連線失敗時的備援。

新安裝預設資料庫為 `BatchDyeingCentral`，帳號仍為 `idcc`。既有安裝保留 `.env.postgres` 中已儲存的資料庫名稱；舊版只有密碼的設定視為原本的 `idcc`，不會切換到尚未建立的空庫。若要將既有庫改名，請依 [保留資料改名](RENAME-POSTGRES.md) 操作。
