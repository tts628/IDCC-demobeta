# Windows 原生安裝（備用）

將 GitHub 儲存庫完整下載並解壓縮，進入 `scripts/native`，雙擊 `install.cmd`，同意 Windows 管理員授權。請保留 PowerShell 視窗，直到出現 **Environment verified**。單純下載 ZIP 不會觸發安裝。

預設網址：**http://127.0.0.1:8080/DemoBeta/Home/**

## 安裝內容

- Windows x64、Windows PowerShell 5.1 以上，需要網路和 Windows 內建的 `curl.exe`。
- Apache 2.4.71 x64、PHP 8.3.36 TS x64、必要的 Microsoft Visual C++ runtime。
- PHP 的檔案、試算表、MySQL 擴充套件，以及 SQLSRV / PDO_SQLSRV 5.13.0。
- 如果沒有 SQL Server ODBC Driver 17 或 18，安裝 Microsoft ODBC Driver 18（接受其授權條款）。不會安裝 SQL Server 資料庫本體。
- 註冊獨立的 `IDCC-DemoBeta` Windows 服務，隨開機啟動。
- 程式、快取、設定及日誌放在 `%ProgramData%\IDCC-DemoBeta`；專案留在原本解壓縮的位置。

固定版本 ZIP 下載後會比對 SHA256；Microsoft 安裝程式會檢查數位簽章。未通過就停止。版本是本次實作時驗證過的版本，日後更新需要一併更新網址、相容性與校驗碼。

## 已有環境就跳過

安裝腳本會依序檢查上次儲存的路徑、PATH，以及常見位置：

| 元件 | 自動偵測位置 |
| --- | --- |
| Apache | `C:\Apache24`、`C:\xampp\apache`、Wamp64、Laragon，以及 Apache Windows 服務 |
| PHP | `C:\php`、`C:\xampp\php`、Wamp64、Laragon |
| ODBC、VC runtime | Windows 已安裝的驅動／登錄資料 |

偵測到既有 Apache、PHP 就不重裝、不覆寫原有設定，改為使用其執行檔建立本專案的獨立 Apache 設定。既有 PHP 必須是 **PHP 8、x64、Thread Safe（TS）**，並具備 `php8apache2_4.dll` 和需要的擴充套件；Apache 須與此 PHP 模組相容。若不相容或缺少擴充套件，腳本會停止並列出原因，保留既有環境，不會自行換掉它。腳本管理的 PHP 則會自動配置擴充套件。

如果裝在其他位置，請在系統管理員 PowerShell 執行：

```powershell
cd "你的專案解壓縮目錄"
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts/native/install.ps1 -ApacheExe 'D:\Apache24\bin\httpd.exe' -PhpExe 'D:\php\php.exe'
```

只查看偵測結果（不安裝、不修改服務）：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts/native/install.ps1 -Plan
```

8080 被其他程式使用時，安裝會停止。可改用：

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\scripts/native/install.ps1 -Port 8081
```

重跑會重用既有環境；重新產生本專案的設定，並重啟本專案服務。請固定專案位置；安裝後移動或刪除解壓縮資料夾會讓網站失效。重新執行時請使用上次相同的 `-Port` 參數。

## 網站與資料庫

安裝只開放 `127.0.0.1`，不自動新增防火牆規則。所有專案共用 `/DemoBeta/` 網址前綴，即使 GitHub ZIP 解壓縮後資料夾叫 `IDCC-demobata-main`，也能維持原程式的路徑預期。

`env` 會完整保留，供 PHP 在伺服器端讀取；Apache 阻擋瀏覽器直接下載 `env`、`.git`、`vendor`、安裝腳本及設定檔。原始程式與資料庫密碼不會被安裝腳本改寫。

安裝成功會驗證 Apache 確實執行 PHP，並載入 SQLSRV 與 PDO_SQLSRV。此檢查不代表資料庫或每個應用程式功能都已可用：仍需要可連線的 SQL Server、資料表、網路權限，以及正確的 `env` 設定。ODBC 18 的連線加密預設值也可能要求資料庫使用有效憑證；請依部署環境配置。

## 目前已知的程式相容性問題

專案 `Home/composer.lock` 裡的 **PhpSpreadsheet 1.12.0** 宣告 `php: ^7.1`，與本安裝包的 PHP 8.3 不相容。安裝腳本沒有執行 `composer update`，也沒有更動 vendor 或升級舊程式。試算表功能及其他舊程式必須另行升級／測試；不要把「Environment verified」當成整個應用程式已通過驗收。此安裝包不會自動下載已停止維護的 PHP 7。

## 檢查與停止

```powershell
Get-Service IDCC-DemoBeta
Stop-Service IDCC-DemoBeta
Start-Service IDCC-DemoBeta
```

需要系統管理員 PowerShell。Apache 日誌位於 `%ProgramData%\IDCC-DemoBeta\logs`。使用腳本管理的 PHP 時，PHP 錯誤也會寫到此處；既有 PHP 維持它原有的錯誤日誌設定。

移除本專案服務（不移除共享的 Apache、PHP 或資料庫）：

```powershell
$state = Get-Content "$env:ProgramData\IDCC-DemoBeta\state.json" -Raw | ConvertFrom-Json
Stop-Service IDCC-DemoBeta
& $state.ApacheExe -k uninstall -n IDCC-DemoBeta
```

不要對 `www\DemoBeta` 目錄連結使用遞迴刪除指令，它指向原始專案。

## 參考

- [PHP Windows Apache 安裝](https://www.php.net/manual/en/install.windows.apache2.php)
- [Apache Lounge Windows binaries](https://www.apachelounge.com/download/)
- [Microsoft PHP SQL Server 驅動需求](https://learn.microsoft.com/en-us/sql/connect/php/system-requirements-for-the-php-sql-driver)
- [Microsoft ODBC 驅動](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server)
