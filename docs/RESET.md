# 重設 DemoBeta 執行環境

適用於本專案 Docker 安裝。Windows 雙擊根目錄 `reset.cmd`；Ubuntu 在專案目錄執行 `bash reset.sh`。先啟動 Docker。工具列出範圍與容器，必須輸入大寫 `RESET` 才會清除；取消不做更動。

移除固定 Compose 專案 `idcc-demobata` 的容器（包含 PostgreSQL 容器）、專案網路，以及應用映像 `idcc-demobata-web:local`。相同 Docker 引擎上的其他解壓縮副本如果使用相同專案名稱，實際上共用這組容器，也會暫停服務。

保留 PostgreSQL 的所有 volume 與資料、`.env`、`.env.postgres`、程式與上傳檔案。遠端 SQL Server 資料、Docker、WSL、原生 Apache/PHP、其他 Docker 專案也不會被清除。共用的 PostgreSQL 官方映像與 Docker 建置快取保留；不執行全域 prune。應用映像若仍被其他容器使用，移除會失敗並報錯，不會強制刪除。

僅預覽（不需要 Docker，也不會清除）：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\reset.ps1 -Plan
```

```sh
bash reset.sh --plan
```

重設後不會自動啟動，Windows 再執行 `install.cmd`，Ubuntu 再執行 `bash install.sh`。保持原專案目錄與 `.env.postgres`，安裝程式會重建容器、重新使用原 PostgreSQL 資料及密碼。也會沿用 `.env` 裡的 Demo 開關。

重設流程使用獨立的 `docker/reset.compose.yaml`，即使尚未產生 `.env.postgres` 也能清除。此工具不提供清空資料庫或刪除設定的選項。
