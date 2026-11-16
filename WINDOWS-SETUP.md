# Windows 安裝入口：Docker

目前預設使用 **Docker 容器執行 Apache、PHP**。Windows 主機只需要 Docker Desktop／WSL 2，不會另外安裝原生 Apache、PHP。

1. 從 GitHub 下載整個儲存庫 ZIP，完整解壓縮到固定位置。
2. 雙擊根目錄 `install.cmd`，同意 Windows 管理員授權。
3. 若提示重開機，儲存工作並重開機，再雙擊一次 `install.cmd`。
4. Docker Desktop 首次安裝時，依畫面完成安裝與使用條款確認。已有 Docker 的電腦會跳過安裝。
5. 等待建置完成及 **Docker environment verified**。
6. 開啟 **http://127.0.0.1:8080/DemoBeta/Home/**。

檢測到 Docker Desktop 但引擎未啟動時，會嘗試啟動並等待；請完成 Docker Desktop 視窗內的首次設定。若尚未就緒，腳本會停止並說明，不會宣告安裝成功。Docker 啟動本身可能要求硬體虛擬化、WSL 更新或重開機；腳本不會自行重開電腦或調整 BIOS。

更多容器設定、資料庫連線及操作方式請看 [DOCKER-SETUP.md](DOCKER-SETUP.md)。

原生 Windows 安裝保留在 `install-native.cmd`／`install-native.ps1`，只在刻意選擇原生方式時使用；說明見 [WINDOWS-NATIVE-SETUP.md](WINDOWS-NATIVE-SETUP.md)。不需要兩種都安裝。如果先前已建立 `IDCC-DemoBeta` 原生服務，Docker 安裝不會停止它；遇到 8080 衝突時，可參考 Docker 說明改用其他連接埠。
