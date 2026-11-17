# Windows 安裝：不需 Docker Desktop

新電腦使用 **WSL 2 + Ubuntu-24.04 + Docker Engine/Compose** 執行 Apache、PHP 與 PostgreSQL。不下載或安裝 Docker Desktop。需要支援 WSL 2 的 Windows 10/11 x64 與硬體虛擬化。

1. 完整下載專案、解壓縮到固定位置，設定根目錄 `.env`。
2. 雙擊 `windows/install.cmd`，以目前同一位 Windows 使用者授予管理員權限。
3. 若提示重開機，重開後再次執行同一入口。
4. 腳本安裝或沿用 Ubuntu-24.04。若 Ubuntu 首次啟動要求建立帳號，先完成畫面，再重跑安裝。
5. Ubuntu 內若已有 Docker 則沿用，缺少時從 Docker 官方 apt 儲存庫安裝 Engine 與 Compose。繼續建置服務並等待 healthy。
6. Windows 瀏覽器開啟 http://127.0.0.1:8080/DemoBeta/Home/ 。

既有 Docker Desktop 電腦會沿用原引擎，不會卸載或自動搬移 PostgreSQL volume。`.idcc-docker-backend` 記錄所選引擎（本機檔、不提交 Git），後續安裝、重設、命令代理都使用相同環境。不要手動改這個檔案來搬移資料；Desktop 與 WSL Engine 各自保存容器及 volume，轉換需另外備份與還原。

## 操作與驗證

在專案根目錄的 PowerShell 執行下列代理，無須安裝 Windows docker.exe。它會切到正確專案目錄，連接所選本機引擎：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose ps
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\docker.ps1 compose exec web php docker/diagnose-idcc.php
```

更新後重新執行 `windows/install.cmd`；重設使用 `windows/reset.cmd`，仍保留 PostgreSQL 資料與密碼。改用埠 8081：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\windows\install.ps1 -Port 8081
```

只預覽使用 `-Plan`；`-NoInstall` 不安裝 WSL、發行版或缺少的 Docker 套件，但仍會建置／下載應用映像。

## WSL 注意事項

Ubuntu 發行版與資料屬於 Windows 使用者，請始終用同一個 Windows 帳號安裝和操作。Ubuntu-24.04 須為 WSL 2；已有 WSL 1 的發行版不會被自動轉換，請先備份，再執行 `wsl --set-version Ubuntu-24.04 2`。

Ubuntu 24.04 預設使用 systemd。若現有發行版關閉了它，請在 Ubuntu 的 `/etc/wsl.conf` 既有內容內設定：

```ini
[boot]
systemd=true
```

儲存後結束該發行版的其他工作，再執行 `wsl --terminate Ubuntu-24.04`，重新安裝。腳本不會覆蓋設定或自動中止其他 WSL 工作。

安裝會啟動一個隱藏的 WSL 保持執行程序，避免服務因閒置而關閉；不建立登入排程。Windows 重開機、登出或 `wsl --shutdown` 後，重新執行安裝入口啟動環境。容器的 restart 設定在引擎恢復後生效。

預設沿用 WSL 的 localhost 轉送，Windows 使用 127.0.0.1:8080；若自己關閉 localhostForwarding 或有連接埠衝突，需調整 WSL／連接埠設定。遠端 SQL Server 仍使用原 `.env`；WSL Engine 的 host.docker.internal 指向 WSL Docker 主機，若 SQL Server 在 Windows 本機，須另外設定可由 WSL 到達的 Windows 位址。

原生 Apache/PHP 備用方式仍位於 scripts/native，與此流程分開。

依據：[Microsoft WSL 命令](https://learn.microsoft.com/en-us/windows/wsl/basic-commands)、[systemd 設定](https://learn.microsoft.com/en-us/windows/wsl/systemd)、[Docker Engine Ubuntu 安裝](https://docs.docker.com/engine/install/ubuntu/)。
