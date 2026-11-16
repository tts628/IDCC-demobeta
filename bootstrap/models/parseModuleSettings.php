<?php
// // cache method
// class HtmlModel
// {
//     private $cache = []; // Array to store cached data

//     public function getHtmlDataFromModel($module)
//     {
//         $cacheKey = "html_data_$module";

//         // Check if data is already cached
//         if (isset ($this->cache[$cacheKey])) {
//             return $this->cache[$cacheKey];
//         }

//         $htmlJsonFilePath = $module . '/html.json'; // Construct file path based on module
//         $htmlJsonString = file_get_contents($htmlJsonFilePath);

//         if ($htmlJsonString === false) {
//             throw new Exception("Unable to read JSON file: $htmlJsonFilePath");
//         }

//         // ... (Optional) Basic processing of $htmlJsonString if needed

//         $htmlData = $htmlJsonString; // Store raw or processed data

//         // Add data to cache (adjust cache lifetime as needed)
//         $this->cache[$cacheKey] = $htmlData;
//         $cacheLifetime = 60; // Cache for 60 seconds (adjust as needed)
//         $this->cache = array_slice($this->cache, 0, 10, true); // Limit cache size (optional)

//         return $htmlData;
//     }
// }

class ModuleSettingsModel
{
    public function getModuleSettingsData($path)
    {
        $realPath = realpath($path);
        if ($realPath) {
            $JsonString = file_get_contents(realpath($path));
    
            if ($JsonString === false) {
                throw new Exception("Unable to read JSON file: $path");
            }
    
            // ... (Optional) Basic processing of $htmlJsonString if needed
    
            return $JsonString; // Return the raw or processed HTML data based on your logic
        } else {
            return ("module not available");
        }
    }
}