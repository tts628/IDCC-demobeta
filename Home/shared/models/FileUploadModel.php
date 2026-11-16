<?php
error_reporting(E_ALL);
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

class FileUploadModel
{
    public function uploadFile($inputFile, $postData)
    {
        switch($postData["module"]) {
            case "AllMachineList":
            case "MaintenanceForm":
                $targetDir = ROOT_PATH . '/' . CLIENT_ROOT . '/Home/Records/modules/' . $postData["module"] . '/uploads/images';
                break;
            default:
                $targetDir = "../uploads/images";
        }
        $allowedTypes = [
            'jpg',
            'png',
            'jpeg',
            'gif',
            'xbm',
            'tif',
            'jfif',
            'ico',
            'tiff',
            'gif',
            'svg',
            'jpeg',
            'svgz',
            'jpg',
            'webp',
            'png',
            'bmp',
            'pjp',
            'apng',
            'pjpeg',
            'avif'
        ];  // Allowed file types
        
        if (!realpath(($targetDir))) {
            if (!mkdir($targetDir, 0777, true)) {
                // Directory creation failed
                echo "Failed to create directory: $targetDir";
            }
        }

        $file = $inputFile["image-input"];

        $fileTmpName = $file['tmp_name'];
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        date_default_timezone_set('Asia/Taipei');
        $date = new DateTime();
        $formattedDate = $date->format('Ymd_His');
        $baseName = pathinfo($file['name'], PATHINFO_FILENAME);
        $formattedFileName = $baseName . "__" . $formattedDate . "." . $fileType;
        $targetFilePath = $targetDir . '/' . $formattedFileName;

        if (in_array($fileType, $allowedTypes)) {
            if ($file['error'] === 0) {
                if (!file_exists($targetFilePath)) {
                    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                        return [
                            "success" => "已上傳 {$formattedFileName}",
                            "fileName" => $formattedFileName
                        ];
                    } else {
                        return [
                            "error" => "Error: 圖檔 {$formattedFileName} 上傳失敗!",
                            "fileName" => $formattedFileName
                        ];
                    }
                } else {
                    return [
                        "warning" => "Warning: 圖檔 {$formattedFileName} 已存在",
                        "fileName" => $formattedFileName
                    ];
                }
            } else {
                return [
                    "error" => "Error: 圖檔 {$formattedFileName} 上傳錯誤. Error code: {$file['error']}",
                    "fileName" => $formattedFileName
                ];
            }
        } else {
            return [
                "error" => "Error: 圖檔 {$formattedFileName} 格式錯誤",
                "fileName" => $formattedFileName
            ];
        }
    }
}
