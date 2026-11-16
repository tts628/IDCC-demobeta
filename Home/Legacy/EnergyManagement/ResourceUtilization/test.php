<?php

$file = fopen('test2.csv', 'r'); // 開啟CSV檔案
$data = array(); // 儲存表格資料的陣列
// var_dump(($file));
// 讀取標題列
$header = fgetcsv($file);
// var_dump(($header));
while (($row = fgetcsv($file)) !== false) {
    // 判斷是否包含指定的標題
    if (in_array('目前實際使用量', $header) && in_array('時間', $header)) {
        // 取得Name和Age欄位的索引值
        $nameIndex = array_search('目前實際使用量', $header);
        $ageIndex = array_search('時間', $header);

        // 將符合條件的資料存入陣列中
        $data[] = array(
            '時間' => date('H:i', strtotime($row[$ageIndex])),
            '目前實際使用量' => $row[$nameIndex]
        );
        //畫圖
        $data2[] = [
            date('H:i:s', strtotime($row[$ageIndex])),
            (int) $row[$nameIndex]
        ]
        ;
        $data3[] = date('H:i', strtotime($row[$ageIndex]));
    }
}

fclose($file); // 關閉檔案

// 顯示表格資料
foreach ($data as $row) {
    // echo $row['目前實際使用量'] . ', ' . $row['時間'] . '<br>'; 
}

usort($data, function ($a, $b) {
    return strtotime($a['時間']) - strtotime($b['時間']);
});

// 取得最後一筆資料的目前實際使用量
$latestUsage = end($data)['目前實際使用量'];

// echo '最新時間的目前實際使用量為：' . $latestUsage;
// var_dump($row['目前實際使用量']);

$water_data = json_encode($data2);

$water_data3 = json_encode($data3);


// $file = fopen('test.csv', 'r'); // 開啟CSV檔案
// $data = array(); // 儲存表格資料的陣列

// // 讀取標題列
// $header = fgetcsv($file);

// while (($row = fgetcsv($file)) !== false) {
//     $data[] = $row; // 將每一行的資料存入陣列中
// }

// fclose($file); // 關閉檔案

// // 顯示表格資料
// foreach ($data as $row) {
//     echo implode(', ', $row) . '<br>';
// }


?>