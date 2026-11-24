# DemoIDCC 與正式 IDCC

詳細設定請看 [三組連線說明](DATABASE-SETUP.md)。

- Demo 預設開啟：網站 IDCC 使用 DemoIDCC SQL Server。
- 手動關閉 Demo：網站 IDCC 選用安裝建立的 PostgreSQL。
- SPC 永遠使用自己的 SQL Server 連線。

Windows 執行 `windows/install.cmd` 或圖形介面 `windows/setup.cmd`；Ubuntu 執行 `bash ubuntu/setup.sh` 或 `bash ubuntu/install.sh`。PostgreSQL 建立空的 idcc 資料庫，安裝自動填入 IDCC Server，密碼保留在 `.env.postgres`；不匯入展示資料，不更動既有 Demo 或 SPC 資料。

開啟 Demo 不會因 PostgreSQL 已安裝就自動切過去。DemoIDCC 未設定或失敗時會顯示錯誤，不改用正式庫；SPC 也不會被 Demo 開關替換。

程式連線：`getPdo(['IDCC'])` 依 Demo 開關選擇；`getPdo(['DemoIDCC'])` 明確選擇測試 SQL Server，`getPdo(['IDCC_SERVER'])` 明確選擇正式 PostgreSQL。舊名稱 IDCC_DEMO 仍可相容使用。舊頁面的 `getLink(['IDCC'])` 在關閉 Demo 時會提示需要移植，不會把 PostgreSQL PDO 傳給 sqlsrv_*。

PostgreSQL 以 named volume 保存。一般容器重建與 reset 會保留資料，請勿使用 `docker compose down -v`，它會刪除資料卷。資料夾備份不包含資料卷內容。
