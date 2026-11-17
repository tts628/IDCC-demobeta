# 安裝入口

| 作業系統 | 在完整解壓縮的專案根目錄執行 |
| --- | --- |
| Windows | 雙擊 `windows/install.cmd`，詳見 [Windows 說明](WINDOWS-SETUP.md) |
| Ubuntu | 終端機執行 `bash ubuntu/install.sh`，詳見 [Ubuntu 說明](UBUNTU-SETUP.md) |

兩種入口都會偵測已有的 Docker，缺少時安裝，再啟動 Apache／PHP 容器。Ubuntu 腳本會驗證作業系統版本，Windows 不需要執行 `.sh` 檔。

完成後在安裝電腦開啟 **http://127.0.0.1:8080/DemoBeta/Home/**。請保留整個專案資料夾與 `env`。資料庫仍需依實際環境配置。
