# Docker 部署說明

## 架構

Windows 主機 → Docker Desktop 的 Linux 容器引擎 → `web` 容器（Apache + PHP 8.3 + PHP 擴充套件 + Microsoft SQL Server ODBC 18）。資料庫仍使用 `env` 指定的既有伺服器，不額外建立 SQL Server 或 MySQL 資料庫容器。

Windows 安裝入口是 `install.cmd`，PowerShell 入口是 `install.ps1`。Ubuntu 在專案根目錄執行 `bash ./install.sh`，會偵測並安裝缺少的 Docker Engine／Compose；詳見 [UBUNTU-SETUP.md](UBUNTU-SETUP.md)。Linux／macOS 已有 Docker 與 Compose 的使用者也可直接執行下方的 Compose 指令。

## 初次安裝與重複執行

- 需要符合 Docker Desktop 要求的 Windows 版本、硬體虛擬化及足夠磁碟空間。
- 已有可用的本機 Linux Docker 引擎就跳過安裝；已有 Docker Desktop 時，保留它的版本與設定，嘗試啟動。
- 沒有 Docker Desktop 時，啟用必要 WSL 功能，準備 WSL，再下載 Docker 官方安裝程式。下載後檢查有效的 Docker 數位簽章，安裝介面由使用者完成條款確認。
- WSL 功能首次啟用可能要求重開機。腳本以 `3010` 結束並提示重跑，不會自己重新啟動 Windows。
- Docker 必須使用 **Linux containers**。腳本固定連接本機 Windows Docker pipe，不使用其他遠端 Docker context。
- 本機原生 Apache、PHP 即使存在，也不會被容器安裝修改或重裝。
- 初次建置會下載映像與 Linux 套件，需要網路；後續重跑通常會使用 Docker 建置快取。

只查看安裝計畫，不修改系統：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\install.ps1 -Plan
```

已有 Docker，只允許啟動網站、不允許安裝系統元件：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\install.ps1 -NoInstall
```

改用其他連接埠：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\install.ps1 -Port 8081
```

上述網址就會改成 `http://127.0.0.1:8081/DemoBeta/Home/`。後續啟動請保持相同 `-Port`；手動 Compose 操作則先設定 `$env:IDCC_HTTP_PORT='8081'`。預設只綁定本機 `127.0.0.1`，不自動對外開放防火牆。

## 手動操作

在專案根目錄執行，並確定 Docker Desktop 已啟動且目前 context 是本機 Docker Desktop：

```powershell
docker compose up -d --build --wait
docker compose ps
docker compose logs -f web
docker compose stop
docker compose start
docker compose down
```

`down` 移除本專案容器與網路，不會刪除主機的程式、`env` 或輸出檔案。`restart: unless-stopped` 會在 Docker 引擎重新啟動後恢復未手動停止的容器；Windows 登入時是否自動啟動 Docker Desktop，由 Docker Desktop 的設定控制。

## 專案、env 與持久化

- 網站固定使用 `/DemoBeta/` 路徑，容器內的 `DOCUMENT_ROOT` 是 `/var/www/html`，專案位於 `/var/www/html/DemoBeta`。
- 主機整個專案資料夾掛載到容器，程式修改立即生效；應用程式寫入的報表、上傳檔等會保留在主機資料夾。這也表示容器內 PHP 可以修改該資料夾，請維持原有備份習慣。
- `env` 再以唯讀掛載覆蓋，不會被容器內 PHP 修改。
- `.dockerignore` 把 `env` 和 `.env` 排除於映像建置內容之外，資料庫密碼不會被 COPY 進映像；實際執行仍需要主機上有完整 `env`。
- 不要安裝完成後移動或刪除專案資料夾；否則先 `docker compose down`，搬移後再從新位置啟動。
- Apache 阻擋 HTTP 讀取 `env`、`.git`、`vendor`、Docker 設定與安裝腳本。PHP 仍能在伺服器端讀取 `env`。
- Ubuntu 安裝腳本會將 Apache 工作程序的 UID／GID 對應到專案的非 root 擁有者，不修改整份專案的 chmod／chown。直接使用 Compose 時預設仍為 www-data；手動重建請參考 Ubuntu 說明保留 UID／GID 設定。Windows Docker Desktop 維持 www-data。

## 資料庫位置

容器中的 `localhost`／`127.0.0.1` 指的是容器自己。如果資料庫裝在這台 Windows 主機，請在 `env` 把伺服器主機名稱設為 **`host.docker.internal`**，並確認資料庫接受 TCP 連線。資料庫若在其他機器，使用其可連線的主機名稱或 IP。

安裝腳本不改寫帳號、密碼、資料庫位址、資料表或原有程式。ODBC 18 的預設加密與憑證驗證可能與舊版不同，需依實際 SQL Server 憑證配置。使用 Windows 整合驗證的環境需要另外配置 Linux 容器的 Kerberos／驗證機制。

## 健康檢查與已知限制

Docker 映像建置時會驗證 Apache 設定及 PHP 擴充套件。容器健康檢查透過 HTTP 確認 Apache 正在執行 PHP，以及 SQLSRV、PDO_SQLSRV、GD、ZIP 等擴充套件已載入；不會拿資料庫密碼測試實際資料庫連線。

現有 `Home/composer.lock` 的 **PhpSpreadsheet 1.12.0 宣告 PHP `^7.1`**，與 PHP 8.3 不相容；這次不更動 vendor、composer.lock 或應用程式，仍需另外升級／測試試算表功能。另須留意 Linux 路徑大小寫、Windows 專屬檔案路徑與指令的相容性。健康狀態只代表環境可用，不等於所有舊程式已通過驗收。

`php:8.3-apache-bookworm` 會隨上游更新 8.3 修補版；SQLSRV 與 PDO_SQLSRV 暫固定於 5.13.3。若要拉取更新的 PHP 基礎映像：

```powershell
docker compose build --pull
docker compose up -d --wait
```

本次開發環境沒有 Docker 引擎，已做 Compose 靜態驗證、PowerShell 語法與偵測測試、PHP 健康檢查邏輯測試；完整映像建置、Docker Desktop／WSL 的管理員安裝流程尚待目標電腦執行驗證。

## 官方參考

- [Docker Desktop Windows 安裝與系統需求](https://docs.docker.com/desktop/setup/install/windows-install/)
- [Docker Compose 設定](https://docs.docker.com/reference/compose-file/services/)
- [Docker 官方 PHP 映像](https://hub.docker.com/_/php)
- [Microsoft PHP SQL Server Linux 驅動安裝](https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac)
