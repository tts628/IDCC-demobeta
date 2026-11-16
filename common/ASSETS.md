# 共用前端檔案

此目錄補入 DemoBeta 原先透過 `/common/...` 使用、但儲存庫未包含的本機既有共用依賴，包括 jQuery、Bootstrap、Popper、jQuery UI、Bootstrap Toggle、Font Awesome 字型、SweetAlert、AG Grid、ApexCharts，以及舊模組引用的可找到靜態套件、圖片和語系 PHP 陣列。檔案保留原有版本與授權標頭；找到的本機 LICENSE／NOTICE 也一併保留。

Docker 在映像中建立 `/var/www/html/common` → `/var/www/html/DemoBeta/common`，因此 HTTP `/common/...` 與 PHP 的 `DOCUMENT_ROOT . '/common/...'` 都指到這份目錄。Windows 原生備用安裝則建立對應的目錄連結。

沒有從其他網站複製資料庫帳密、客戶 Config 或伺服器環境設定。已補上舊首頁模板與共用 autoload；專案專用的 Config/DbMapper 僅載入本專案 env/database_IDCC.php 與 env/database_SPC.php，分別提供 SQLSRV 與 PDO 連線。其他舊版報表若依賴額外的 /common/database*.php 或客戶服務，仍需依 DemoBeta 實際後端設定處理。

完整掃描也發現部分舊 CSS／註解引用的裝飾圖片在已檢查的本機來源中不存在，例如 `404.svg`、`Dark_background_1920x1080.png`、`robot.png`、`tick*.svg`、`weather_bck.png`、`cotoneaster.jpg`、`istanbul.jpg`、`tokyo.jpg`、`sao_paulo.jpg` 等。這些不在首頁必要依賴清單中，未以無關檔案代替；若使用到對應舊模組，需再提供其原始素材。

已驗證首頁 HTTP、首頁外部套件與字型、側邊欄 JSON、語系 API，以及加強後的健康檢查。不含真實資料庫功能驗收。
