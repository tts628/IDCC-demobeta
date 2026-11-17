# Ubuntu 安裝

## 操作步驟

1. 從 GitHub 下載最新版完整 ZIP，解壓縮到一般使用者擁有的固定目錄，例如 `~/IDCC-demobeta-main`。
2. 在該目錄開啟終端機，執行：

```bash
bash ubuntu/install.sh
```

3. 若出現 sudo 密碼提示，輸入 Ubuntu 使用者密碼（輸入時畫面不會顯示字元）。已有可用 Docker／Compose 時會跳過安裝。
4. 等到 **Docker environment verified**，在同一台電腦開啟 **http://127.0.0.1:8080/DemoBeta/Home/**。

不用先執行 `chmod +x`。`ubuntu/install.sh` 會確認目前確實為 Ubuntu；Windows 仍使用 `windows/install.cmd`，兩者啟動相同的 Apache／PHP 容器。

共用前端套件與語系檔已隨專案的 `common` 目錄提供。若從舊版更新，請將新版檔案覆蓋到原本專案資料夾，保留自己的 `env` 和資料檔，再執行 `bash ubuntu/install.sh`；腳本會重建映像，套用 `/common` 路徑映射。

## 支援與行為

- 支援 Ubuntu 22.04、24.04、26.04、26.10，amd64／arm64，需要網路。
- 缺少 Docker 時使用 Docker 官方 apt 儲存庫安裝 Docker Engine、Compose 與 Buildx；不安裝 Docker Desktop，也不在 Ubuntu 主機另外安裝 Apache、PHP。
- 已有 Docker 時不重裝或升級引擎，只在 Compose 缺少時安裝對應套件。自訂安裝方式若無法安全判斷 Compose 來源，會保留既有環境並提示處理。
- 不會自動刪除或替換衝突的既有 containerd、runc、Podman 或 Docker 套件。若有衝突，會列出並停止。
- 本機 Docker 需要權限時，腳本使用 sudo 執行，不自動將使用者加入 docker 群組。服務未啟動時，會透過 systemd 啟動 Docker；沒有 systemd 的環境須先自行啟動引擎。
- 腳本固定使用本機 Unix socket，忽略遠端 Docker context；`DOCKER_HOST` 若有設定，僅接受 `unix:///...`。
- 自動把 Apache 工作程序對應到專案目錄的非 root UID／GID，使輸出檔案可寫回主機；不對整份專案執行 chmod 或 chown。主機 `env` 仍為唯讀掛載。
- 請以一般使用者解壓縮專案，再執行 `bash ubuntu/install.sh`，不需要整個腳本加 sudo。若目錄屬於 root，Apache 會維持 www-data，輸出目錄權限須另外配置。
- 服務只綁定本機 `127.0.0.1`；遠端 Ubuntu 主機的網址要透過 SSH 轉送，例如 `ssh -L 8080:127.0.0.1:8080 使用者@伺服器`，再在自己的瀏覽器開啟上述網址。

## 其他選項

```bash
bash ubuntu/install.sh --plan             # 只看偵測計畫，不修改系統
bash ubuntu/install.sh --no-install       # 不安裝套件；仍可啟動既有 Docker 服務及專案
bash ubuntu/install.sh --port 8081        # 改用 8081，之後重跑請維持相同參數
```

資料庫不會自動建立；依 `env` 指定既有資料庫。容器中的 localhost 是容器本身；資料庫若在 Ubuntu 主機，可用 `host.docker.internal`，並確認資料庫接受 Docker 網段的 TCP 連線。

## 手動管理

在專案目錄執行。若一般使用者無權存取 Docker，使用 sudo：

```bash
sudo docker compose ps
sudo docker compose logs -f web
sudo docker compose stop
sudo docker compose start
sudo docker compose down
```

`start`／`stop` 不會重建設定。若手動執行 `up --build`，請保留與安裝時相同的環境變數，例如：

```bash
sudo env IDCC_HTTP_PORT=8080 \
  IDCC_APACHE_USER="#$(stat -c '%u' .)" \
  IDCC_APACHE_GROUP="#$(stat -c '%g' .)" \
  docker compose up -d --build --wait
```

此範例限一般使用者擁有的專案，不要把 Apache 工作程序設為 UID 0。

## 驗證範圍

已做 Bash 語法／ShellCheck 靜態檢查，以及 Docker Compose 設定與 UID／GID 環境變數驗證。開發環境是 Windows，尚未在真正的 Ubuntu 上完成 apt 安裝及 Docker 映像建置。原專案的 PhpSpreadsheet 1.12.0 與 PHP 8.3 相容性仍需另外處理；環境健康不代表應用程式所有功能已驗收。

參考：[Docker 官方 Ubuntu 安裝](https://docs.docker.com/engine/install/ubuntu/)、[Apache UID／GID 設定](https://httpd.apache.org/docs/2.4/mod/mod_unixd.html)。
