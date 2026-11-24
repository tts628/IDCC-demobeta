> Ubuntu 終端機選單：`bash ubuntu/setup.sh`；獨立資料庫設定：`bash ubuntu/database-setup.sh`；頁面勾選：`bash ubuntu/pages-setup.sh`。詳見 [終端機操作說明](docs/UBUNTU-TUI.md)。

> Windows 圖形介面：`windows/setup.cmd`；獨立資料庫設定：`windows/database-setup.cmd`；頁面選擇：`windows/pages-setup.cmd`。可先略過資料庫安裝環境，詳見 [介面操作說明](docs/WINDOWS-GUI.md)。

> Windows 新安裝不再安裝 Docker Desktop，改用 WSL 2 + Ubuntu Docker Engine。PowerShell 的 Docker 操作請使用 `powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose ps` 等命令代理；詳見 [Windows 操作說明](docs/WINDOWS-SETUP.md)。

## 重設執行環境

Windows 執行 `windows/reset.cmd`；Ubuntu 執行 `bash ubuntu/reset.sh`。輸入 `RESET` 確認後，移除本專案容器、網路與應用映像，保留 PostgreSQL 資料、密碼及 `.env`。之後重跑安裝程式即可重建。詳見 [重設說明](docs/RESET.md)。

## IDCC PostgreSQL 與 Demo 模式

預設 Demo 模式沿用現有 SQL Server；Docker 安裝同時建立空的 PostgreSQL，密碼自動產生且保存在本機 `.env.postgres`。請依 [切換與驗證說明](docs/POSTGRES-DEMO.md) 操作。關閉 Demo 前仍需完成資料表與 SQL 查詢移植。

# IDCC DemoBeta

使用 Docker 執行 Apache、PHP 8.3 與 SQL Server 驅動的 IDCC 展示專案。支援 Windows 與 Ubuntu 安裝入口，Demo 模式使用既有 SQL Server，並同時建立空的 PostgreSQL；不會匯入展示資料。

> 安裝完成分成三步：**容器 healthy → 資料庫連線成功 → 確認頁面查詢範圍內有資料**。只有 healthy 不代表真實資料庫已連上。

## 目錄結構

```text
IDCC-demobeta/
├─ README.md                 安裝與維護總入口
├─ compose.yaml              Docker 服務定義
├─ .env.example              本機連線設定範本
├─ windows/                  Windows 安裝與重設（install.cmd、reset.cmd）
├─ ubuntu/                   Ubuntu 安裝與重設（install.sh、reset.sh）
├─ docs/                     平台、資料庫與 Docker 詳細說明
├─ scripts/
│  └─ native/                Windows 原生 Apache/PHP 備用安裝
├─ docker/                   Dockerfile、Apache/PHP 設定、健康檢查及診斷
├─ Home/                     原有網站與報表
├─ Login/                    原有登入功能
├─ bootstrap/                原有頁面載入框架
├─ chatui/                   原有聊天介面
├─ common/                   共用套件、語系與連線載入器
├─ env/                      讀取環境變數的 PHP 連線程式
└─ test/                     原有測試檔
```

網站目錄維持原位；安裝與重設入口已依作業系統集中到 `windows/`、`ubuntu/`。Dockerfile 已移到 `docker/Dockerfile`，由 compose.yaml 自動指定。若手動建置，請使用 `docker build -f docker/Dockerfile .`。

從舊版 ZIP 合併更新時，舊根目錄的說明與原生安裝檔可能仍留在本機；新版入口位於 windows/ 與 ubuntu/，文件位於 docs/，不需操作這些舊副本。確認新版可用並備份後，可手動移除舊副本，勿刪除自己的 .env 或資料。

## 1. 下載專案

在 [GitHub 儲存庫](https://github.com/tts628/IDCC-demobeta) 按 **Code → Download ZIP**，完整解壓縮到固定位置。

- Windows 範例：`C:\IDCC-demobeta-main`
- Ubuntu 範例：`~/IDCC-demobeta-main`

根目錄應直接看得到 `compose.yaml`、`docker/Dockerfile`、`windows`、`ubuntu`、`common`、`env`、`Home`。不要只複製部分資料夾，也不要將解壓縮資料夾刪除；容器會掛載這個目錄。

## 2. 設定資料庫

連線資訊填在**專案根目錄 `.env`**。`env/database_IDCC.php`、`env/database_SPC.php` 透過 `getenv()` 讀取容器環境變數，通常不需要直接修改 PHP 程式。

### Windows

在 PowerShell 執行：

```powershell
cd "C:\IDCC-demobeta-main"
if (!(Test-Path .env)) { Copy-Item .env.example .env }
notepad .env
```

檔名必須是 `.env`，不是 `.env.txt`。可用以下指令確認：

```powershell
Get-ChildItem -Force -Name ".env*"
```

### Ubuntu

```bash
cd ~/IDCC-demobeta-main
test -f .env || cp .env.example .env
nano .env
```

### 需要填寫的內容

以下都是範例，請改成實際資訊：

```dotenv
IDCC_DB_HOST=db.example.internal,1433
IDCC_DB_NAME=YOUR_IDCC_DATABASE
IDCC_DB_UID=YOUR_SQL_LOGIN
IDCC_DB_PWD='YOUR_SQL_PASSWORD'
IDCC_DB_TRUST_SERVER_CERTIFICATE=false

SPC_DB_HOST=db.example.internal,1433
SPC_DB_NAME=YOUR_SPC_DATABASE
SPC_DB_UID=YOUR_SQL_LOGIN
SPC_DB_PWD='YOUR_SQL_PASSWORD'
SPC_DB_TRUST_SERVER_CERTIFICATE=false
```

| 欄位 | 要填什麼 |
| --- | --- |
| `*_DB_HOST` | SQL Server 的 IP／主機名稱，可加 `,1433` 指定實際 TCP 連接埠 |
| `*_DB_NAME` | SQL Server 裡的**資料庫名稱**，不是 IP |
| `*_DB_UID` | SQL Server 登入帳號 |
| `*_DB_PWD` | 該帳號的實際密碼 |
| `*_DB_TRUST_SERVER_CERTIFICATE` | 是否略過憑證驗證；預設 `false` |

- 資料庫在**安裝網站的同一台主機**：HOST 使用 `host.docker.internal`。
- 資料庫在**另一台 Windows／Linux 主機**：HOST 使用那台資料庫的可連線 IP／DNS 名稱。容器內的 `localhost` 是容器自己。
- `.env` 使用英文符號。密碼建議用英文單引號包住，避免 `$` 被展開；密碼若含單引號，請依 Compose env_file 規則跳脫。
- IDCC 首頁使用 IDCC 連線；SPC 設定只供使用 SPC 的模組。未使用 SPC 時可先保留範本，實際使用前必須補齊。
- `.env` 不應上傳 GitHub，也不要貼到對話、截圖或公開日誌。專案已將它排除於 Git 與 Docker 映像建置。

### SQL Server 憑證

預設保留加密及憑證驗證。正式環境請讓容器信任伺服器憑證的簽發 CA，並使用與憑證相符的 DNS 名稱。

若是自己控管且已確認身分的展示資料庫，使用自簽憑證時可以明確設定：

```dotenv
IDCC_DB_TRUST_SERVER_CERTIFICATE=true
```

SPC 對應 `SPC_DB_TRUST_SERVER_CERTIFICATE=true`。這會**保留加密，但略過伺服器憑證驗證**，降低伺服器身分驗證保障。SQLSRV、PDO 與診斷腳本使用同一個選項。

## 3. 安裝並啟動

### Windows

1. 雙擊專案內的 **`windows/install.cmd`**，同意管理員授權。
2. 已有可用 Docker 時跳過安裝；新安裝使用 WSL 2／Ubuntu Docker Engine，不安裝 Docker Desktop。
3. 若提示重開機，先儲存工作、重開機，再執行一次 `windows/install.cmd`。
4. 新電腦依提示完成 WSL／Ubuntu 初始設定；已有 Docker Desktop 則沿用原引擎。
5. 等待映像建置及健康檢查完成，看到 **`Docker environment verified`**。

Windows 主機不需要另裝 Apache、PHP。預設不要執行原生備用入口 `scripts/native/install.cmd`。

需要換成 8081 時，在系統管理員 PowerShell 執行：

```powershell
cd "C:\IDCC-demobeta-main"
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\install.ps1 -Port 8081
```

### Ubuntu

以一般使用者解壓縮專案，在終端機執行：

```bash
cd ~/IDCC-demobeta-main
bash ubuntu/install.sh
```

需要時輸入 sudo 密碼。腳本會確認 Ubuntu 版本，使用已有的 Docker，或從 Docker 官方 apt 儲存庫安裝缺少的元件，再建置容器；不需要 Docker Desktop，也不用先 `chmod +x`。

支援 Ubuntu 22.04、24.04、26.04、26.10，amd64／arm64。一般使用者擁有專案時，腳本會對應 Apache 的 UID／GID，讓輸出檔案能寫回主機；不對整份專案執行 chmod／chown。

```bash
bash ubuntu/install.sh --plan           # 只看計畫
bash ubuntu/install.sh --no-install     # 不安裝套件，仍可啟動既有服務與專案
bash ubuntu/install.sh --port 8081      # 換連接埠
```

Ubuntu 有衝突的舊套件時，腳本會停止並列出，不自動刪除。更多細節見 [Ubuntu 說明](docs/UBUNTU-SETUP.md)。

## 4. 驗證安裝與資料庫

在專案根目錄執行；Ubuntu 若沒有 Docker 存取權限，在 `docker` 前加 `sudo`。

```text
docker compose ps
docker compose exec -T web php docker/diagnose-setup.php
```

容器 `web` 應為 **Up … (healthy)**。連線測試會分別列出 PostgreSQL、IDCC、SPC，只建立連線並執行不涉及資料表的 `SELECT 1`，不檢查業務資料表、日期範圍或首頁 API，也不修改資料或輸出帳密。

| 狀態 | 意義 |
| --- | --- |
| `success` | 該資料庫可以正常連線；SQL Server 的 SQLSRV 與 PDO 都通過 |
| `failed` | 該資料庫連線失敗，會附上憑證、帳密、網路或驅動等錯誤類別 |
| `not_configured` | 尚未設定或未填完整，已略過，不代表成功 |

Windows 使用 `windows/setup.cmd` 的「測試已儲存的連線」可看白話結果。Ubuntu 使用 `bash ubuntu/setup.sh`，或在 CLI 指令最後加 `--human`。若只想確認連線，請使用上述新工具；舊的 `docker/diagnose-db.php` 是額外深入檢查工具，仍包含資料表與首頁 API 檢查。

再以**安裝電腦的瀏覽器**開啟：

**http://127.0.0.1:8080/DemoBeta/Home/**

如改用 8081，網址也要改成 8081。預設僅限本機，不自動對外開防火牆；其他電腦的 `127.0.0.1` 不會連到這台網站。

首頁輸送統計查**最近 24 小時**，染色統計查**昨天**；歷史展示資料庫即使連線成功，也可能沒有該時段資料。

## 5. 修改設定後如何套用

修改 `.env` 後，Windows 執行：

```powershell
cd "C:\IDCC-demobeta-main"
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose up -d --force-recreate --wait
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose exec -T web php docker/diagnose-setup.php
```

**只做 `restart` 不會載入新的容器環境變數。** Ubuntu 建議重新執行 `bash ubuntu/install.sh`，保留 UID／GID 設定。自訂連接埠時也請保留原本的 `-Port`／`--port` 參數。

修改 Dockerfile、PHP 擴充套件或映像中的 Apache 設定時，需要重新建置：

```text
docker compose up -d --build --wait
```

## 6. 從 GitHub 更新

更新前先備份 `.env`、`env`、自行修改的程式與上傳／輸出資料。GitHub 同名程式或資料檔可能會覆蓋本機版本。

### Windows：原本使用 Download ZIP 安裝

在 PowerShell 執行以下整段。會從 GitHub 下載最新版，保留根目錄 `.env`、`.env.postgres` 與自訂 `env.json`，更新 PHP 連線程式等其他同名檔案；不會用 `/MIR` 刪除本機額外檔案。

```powershell
$ErrorActionPreference = "Stop"
$project = "C:\IDCC-demobeta-main"
if (!(Test-Path (Join-Path $project "compose.yaml"))) {
    throw "請先將 project 改成含有 compose.yaml 的原專案目錄。"
}
$stage = Join-Path $env:TEMP ("IDCC-update-" + [guid]::NewGuid())
New-Item -ItemType Directory -Path $stage | Out-Null

curl.exe --fail --location --retry 5 --retry-delay 3 `
    "https://codeload.github.com/tts628/IDCC-demobeta/zip/refs/heads/main" `
    --output "$stage\project.zip"
if ($LASTEXITCODE -ne 0) { throw "下載失敗，尚未更新原專案。" }

Expand-Archive "$stage\project.zip" "$stage\extracted"
$folders = @(Get-ChildItem "$stage\extracted" -Directory)
if ($folders.Count -ne 1) { throw "解壓縮內容不符合預期，請停止更新。" }

robocopy $folders[0].FullName $project /E /XJ /XD .git /XF .env .env.postgres .idcc-docker-backend .idcc-pages.json env.json
if ($LASTEXITCODE -ge 8) { throw "檔案更新失敗，請先不要繼續。" }

Set-Location $project
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose up -d --build --wait
if ($LASTEXITCODE -ne 0) { throw "容器重建失敗，請查看錯誤訊息。" }
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose ps
```

完成後開啟網站，按 **Ctrl＋F5** 強制重新整理。若原本 PHP 連線程式內仍有自行填寫的帳密，請先遷移至根目錄 `.env`，再更新程式。

### Ubuntu：ZIP 安裝

下載最新版並解壓縮到暫存目錄，將內容合併到原本專案位置，保留自己的 `.env`、`.env.postgres`、`env/env.json` 與上傳／輸出資料，再執行：

```bash
cd ~/IDCC-demobeta-main
bash ubuntu/install.sh
```

### 原本使用 Git clone 安裝

有安裝 Git 且此目錄本身是 Git 儲存庫時，先用 `git status` 確認並處理自己的程式修改，再執行 `git pull --ff-only`，最後依作業系統重建。下載 ZIP 的目錄不能直接 `git pull`。

## 7. 常見問題

| 問題 | 處理方式 |
| --- | --- |
| `no configuration file provided` | 先切換到含 `compose.yaml` 的專案根目錄 |
| 安裝要求重開機 | WSL／Docker 系統元件可能尚待啟用；重開後再執行 `windows/install.cmd` |
| `.env` 明明有填，診斷卻顯示 `missing_keys` | 確認檔名不是 `.env.txt`、Compose 已更新且包含 `env_file`，再 force-recreate |
| `TLS_CERTIFICATE` | 檢查 CA／主機名稱；可信任的展示環境可明確選擇上述信任憑證設定 |
| `18456`、`State: 8` | SQL Server 判定密碼不符；確認同一伺服器、帳密與 `.env` 語法，修改後重建容器 |
| `TABLE_OR_SCHEMA_NOT_FOUND` | 確認連到正確資料庫，且存在程式需要的資料表／schema |
| 畫面沒資料，但連線成功 | 查看 `data_window`、`homepage_api`，確認有最近 24 小時／昨天的資料 |
| `/common/...` 404、舊首頁缺 `head.php` | 確認完整更新 `common` 與新版程式，並以 `--build` 重建映像 |
| 8080 已被佔用 | 改用 8081，後續重跑與網址維持相同連接埠 |
| 下載 GitHub 時連線中斷 | 使用上述 curl 下載方式；下載失敗時腳本會停止，不進行覆蓋 |

查看網站日誌：

```text
docker compose logs --tail=100 web
```

提供錯誤輸出前先遮住可能包含的帳密。不要貼完整 `.env` 或含密碼的 `docker compose config` 輸出。

## 8. 日常管理與資料位置

```text
docker compose ps
docker compose logs -f web
docker compose stop
docker compose start
docker compose down
```

專案與輸出檔案留在主機資料夾，`env` 對容器是唯讀掛載。`down` 移除容器／網路，不刪除主機專案；操作前仍應維持資料備份。WSL 模式在 Windows 重開機後重新執行安裝入口啟動；已有 Docker Desktop 的自動啟動由其設定控制。

## 9. 已知限制與其他文件

- 健康檢查會檢查 PHP、必要擴充套件、共用依賴與首頁 HTTP 回應，**不驗證真實資料庫登入及所有功能**。
- 現有 PhpSpreadsheet 1.12.0 宣告 PHP `^7.1`，試算表功能與 PHP 8.3 的相容性仍需另行升級／驗證。
- 部分舊報表仍可能依賴額外的共用設定、素材或服務；診斷成功也不代表所有舊模組已完成驗收。
- Ubuntu 的完整 apt 安裝與 Docker 映像建置尚未在開發環境實機驗證；目前已做靜態檢查與可執行的 PHP／HTTP 測試。

| 文件 | 內容 |
| --- | --- |
| [Windows 安裝](docs/WINDOWS-SETUP.md) | WSL Docker Engine／既有引擎流程 |
| [Ubuntu 安裝](docs/UBUNTU-SETUP.md) | Docker Engine、sudo、UID／GID |
| [資料庫設定](docs/DATABASE-SETUP.md) | 環境變數與 TLS |
| [Docker 詳細說明](docs/DOCKER-SETUP.md) | 容器、掛載、網路與管理 |
| [共用依賴](common/ASSETS.md) | common 套件與舊版依賴範圍 |
| [原生 Windows 備用安裝](docs/WINDOWS-NATIVE-SETUP.md) | 刻意不使用 Docker 時的替代方式 |
