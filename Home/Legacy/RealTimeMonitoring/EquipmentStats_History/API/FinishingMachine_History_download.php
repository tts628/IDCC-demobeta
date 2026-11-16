<?php

date_default_timezone_set('Asia/Taipei');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 處理資料庫連結錯誤，回傳json格式
if ($conn === false) {
    die(json_encode(array("status" => "error", "message" => "Database connection failed", "error" => sqlsrv_errors())));
}
//====================Mapping映射(依據前端傳來的數據做映射)=====================
$tableNames = [
    '定型' => [
        'T01定型機' => [
            'table' => '[FinishMachines_Stenter_Machine_T01]',
            'fields' => '*'
        ],
        'T03定型機' => [
            'table' => '[FinishMachines_Stenter_Machine_T03]',
            'fields' => '*'
        ],
        'T05定型機' => [
            'table' => '[FinishMachines_Stenter_Machine_T05]',
            'fields' => '*'
        ],
    ], 
    '刷毛' => [
        'B01刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B01]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CONTRO_I] AS VAL_CONTRO_I, [VAL_CONTRO_S] AS VAL_CONTRO_S, [VAL_FOLDER] AS VAL_FOLDER,  [UploadTime]'

        ],
        'B02刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B02]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CPELO_INF] AS VAL_CPELO_INF, [VAL_CPELO_SUP] AS VAL_CPELO_SUP, [VAL_FOLDER] AS VAL_FOLDER, [VAL_PELO_INF] AS VAL_PELO_INF, [VAL_PELO_SUP] AS VAL_PELO_SUP, [VAL_SVOLGITORE] AS VAL_SVOLGITORE, [VAL_TENS_INF] AS VAL_TENS_INF, [VAL_TENS_SUP] AS VAL_TENS_INF, [VAL_VELO_MT] AS MainSpeed, [UploadTime]'

        ],
        'B03刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B03]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CONTRO_I] AS VAL_CONTRO_I, [VAL_CONTRO_S] AS VAL_CONTRO_S, [VAL_FOLDER] AS VAL_FOLDER,  [VAL_SVOLGITORE] AS VAL_SVOLGITORE, [VAL_TENS_INF] AS VAL_TENS_INF, [VAL_TENS_SUP] AS VAL_TENS_SUP, [VAL_VELO_MT] AS VAL_VELO_MT, [UploadTime]'

        ],
        'B04刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B04]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CPELO_INF] AS VAL_CPELO_INF, [VAL_CPELO_SUP] AS VAL_CPELO_SUP, [VAL_FOLDER] AS VAL_FOLDER, [VAL_PELO_INF] AS VAL_PELO_INF, [VAL_PELO_SUP] AS VAL_PELO_SUP, [VAL_SVOLGITORE] AS VAL_SVOLGITORE, [VAL_TENS_INF] AS VAL_TENS_INF, [VAL_TENS_SUP] AS VAL_TENS_SUP, [VAL_VELO_MT] AS MainSpeed, [UploadTime]'
        ],
        'B05刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B05]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CPELO_INF] AS VAL_CPELO_INF, [VAL_CPELO_SUP] AS VAL_CPELO_SUP, [VAL_FOLDER] AS VAL_FOLDER, [VAL_PELO_INF] AS VAL_PELO_INF, [VAL_PELO_SUP] AS VAL_PELO_SUP, [VAL_SVOLGITORE] AS VAL_SVOLGITORE, [VAL_TENS_INF] AS VAL_TENS_INF, [VAL_TENS_SUP] AS VAL_TENS_SUP, [VAL_TIRO_FALDA] AS VAL_TIRO_FALDA, [VAL_VELO_MT] AS MainSpeed, [UploadTime]'
        ],
        'B06刷毛機' => [
            'table' => '[FinishMachines_Brushing_Machine_B06]',
            'fields' => '[VAL_BOTTLE_INF] AS VAL_BOTTLE_INF, [VAL_BOTTLE_SUP] AS VAL_BOTTLE_SUP, [VAL_CPELO_INF] AS VAL_CPELO_INF, [VAL_CPELO_SUP] AS VAL_CPELO_SUP, [VAL_FOLDER] AS VAL_FOLDER, [VAL_PELO_INF] AS VAL_PELO_INF, [VAL_PELO_SUP] AS VAL_PELO_SUP, [VAL_SVOLGITORE] AS VAL_SVOLGITORE, [VAL_TENS_INF] AS VAL_TENS_INF, [VAL_TENS_SUP] AS VAL_TENS_SUP, [VAL_TIRO_FALDA] AS VAL_TIRO_FALDA, [VAL_VELO_MT] AS MainSpeed, [UploadTime]'
        ]
    ],
    '磨毛' =>[
        'G01磨毛機' => [
            'table' => '[FinishMachines_Grinding_Machine_G01]',
            'fields' => '[VAL_SPAZZOLA_1] AS GrindingRPM1, [VAL_SPAZZOLA_2] AS GrindingRPM2, [VAL_SPAZZOLA_3] AS GrindingRPM3, [VAL_SPAZZOLA_4] AS GrindingRPM4, [VAL_SPAZZOLA_5] AS GrindingRPM5, [VAL_SPAZZOLA_6] AS GrindingRPM6, [UploadTime]'
        ]
    ],
    '剪毛'=>[
        'C01剪毛機' => [
            'table' => '[FinishMachines_Shearing_Machine_C01]',
            'fields' => '[RPM_SHEARING] AS RPM_SHEARING, [VEL_TESS_MTM] AS MainSpeed, [RPM_VELVET] AS RPM_VELVET, [TEN_FOLDER] AS OutletTension, [TEN_INLET] AS InletTension,  [UploadTime]'
        ],
        'C02剪毛機' => [
            'table' => '[FinishMachines_Shearing_Machine_C02]',
            'fields' => '[RPM_SHEARING] AS RPM_SHEARING, [VEL_TESS_MTM] AS MainSpeed, [RPM_VELVET] AS RPM_VELVET, [TEN_FOLDER] AS OutletTension, [TEN_INLET] AS InletTension,  [UploadTime]'
        ],
        'C03剪毛機' => [
            'table' => '[FinishMachines_Shearing_Machine_C03]',
            'fields' => '[RPM_SHEARING] AS RPM_SHEARING, [VEL_TESS_MTM] AS MainSpeed, [RPM_VELVET] AS RPM_VELVET, [TEN_FOLDER] AS OutletTension, [TEN_INLET] AS InletTension, [UploadTime]'
        ]
    ],
    '燒毛'=>[
        'SG01燒毛機' => [
            'table' => '[FinishMachines_Burning_Machine_D_SG1]',
            'fields' => '[D7100] AS M2, [D7102] AS M3, [D7104] AS M4, [D7106] AS M5, [D7108] AS M6, [D7110] AS M7, [D7112] AS M8, [D7114] AS M9, [D7116] AS M10, [D7118] AS M11, [D7120] AS M12, [UploadTime]'
        ],
        'SG02燒毛機' => [
            'table' => '[FinishMachines_Burning_Machine_D_SG2]',
            'fields' => '[D7100] AS M2, [D7102] AS M3, [D7104] AS M4, [D7106] AS M5, [D7108] AS M6, [D7110] AS M7, [D7112] AS M8, [D7114] AS M9, [D7116] AS M10, [D7118] AS M11, [D7120] AS M12, [UploadTime]'
        ]
    ],
    '連續水洗機'=>[
        'RW1連續水洗機' => [
            'table' => '[FinishMachines_SeriesWashing_Machine_RW1]',
            'fields' => '[SteamerUsed] AS SteamerUsed_KgH, [WaterUsed] AS WaterUsed_L, [PowerUsedKW] AS PowerUsedKW_KwH, [StainlessPrewashHz], [RollerPrewashHz], [StainlessBeltPrewashHz], [TensionPVkg], [TensionSVkg], [FabricFeedMotorPV], [FabricFeedMotorStatus], [FabricFeedMotorPV], [VoltRS], [CurrentRS], [UploadTime]'
        ]
    ],
    '蒸化機'=>[
        'CS1蒸化機' => [
            'table' => '[FinishMachines_Steamer_Machine_CS1]',
            'fields' => '[Volt], [PowerKW], [Flow], [GasFlow], [Yard], [JobRatio], [FabricFeedRatio], [FrontcirculationHZ], [RearcirculationStageHZ], [ExhaustVel], [DownSecondSV], [FrontEvaTemp01], [RearEvaTemp01],  [FrontEvaTemp02],  [RearEvaTemp02], [UploadTime]'
        ] 
    ],
    '剖布'=>[
        'RS1對線剖布機' => [
            'table' => '[FinishMachines_Alignment_Cutting_D_RS1]',
            'fields' => '[D7100] AS UpperFeedRollerSpeed_per, [D7102] AS LowerFeedRollerSpeed_per, [D7104] AS S_FeedRollerSpeed_per, [D7106] AS OutClothSpeed, [D7108] AS BeaterRollerSpeed_per, [D7110] AS UntwistHeadSpeed_per, [D7112] AS CardingRollerSpeed_per, [UploadTime]'
        ]
    ],
    '解捻'=>[
        'RC1開幅解捻機' => [
            'table' => '[FinishMachines_Untwisting_Machine_D_RC1]',
            'fields' => '[D7100] AS M2, [D7102] AS M3, [D7104] AS M4, [D7106] AS M5, [D7108] AS M6, [D7110] AS M7, [UploadTime]'
        ],
        'RC2開幅解捻機' => [
            'table' => '[FinishMachines_Untwisting_Machine_D_RC2]',
            'fields' => '[D7100] AS M2, [D7102] AS M3, [D7104] AS M4, [D7106] AS M5, [D7108] AS M6, [D7110] AS M7, [UploadTime]'
        ]

    ],
    '防縮'=>[
        'AS1防縮機' => [
            'table' => '[FinishMachines_Shrunking_Machine_AS1]',
            'fields' => '[RubberCellPressure_Rkg], [RubberCellPressure_Wkg], [MainSpeed_Mt_min], [Chian_mm], [F8_per], [TotalYards], [SteamLettaBar], [RubberTemp], [ProductionHours], [UploadTime]'
        ],
        'AS2防縮機' => [
            'table' => '[FinishMachines_Shrunking_Machine_AS2]',
            'fields' => '[RubberCellPressure_Rkg], [RubberCellPressure_Wkg], [MainSpeed_Mt_min], [Chian_mm], [F8_per], [TotalYards], [SteamLettaBar], [RubberTemp], [ProductionHours], [UploadTime]'
        ]
    ],
    '柔軟'=>[
        'CP1柔軟機' => [
            'table' => '[FinishMachines_Softening_Machine_D_CP1]',
            'fields' => '[D7100] AS M12, [D7102] AS M3, [D7104] AS M4, [D7106] AS M5, [D7108] AS M6, [D7110] AS M7, [D7112] AS M8, [D7114] AS M9, [D7116] AS M10, [D7118] AS M11, [D7120] AS M12, [D7122] AS M13, [D7124] AS M14, [UploadTime]'
        ]
    ],
    '縮練'=>[
        'WH1縮練機' => [
            'table' => '[allseven_alm]',
            'fields' => '[EL1異常] AS EL1alarm, [EL2異常] AS EL2alarm, [EL3異常] AS EL3alarm, [EL4異常] AS EL4alarm, [EL5異常] AS EL5alarm, [EL6異常] AS EL6alarm, [#A攪拌機異常] AS A_mixer_Anomaly, [#B攪拌機異常] AS B_mixer_Alarm, [加藥馬達異常] AS ChemicalDosingMotor_Alarm, [中心校正異常] AS CenteringCalibration_Alarm, [UploadTime]' 
        ]
    ]            
    // 可擴增...
];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查所有預期的 POST 參數是否存在
    if (isset($_POST['selectedOption']) && isset($_POST['selectedOption_machine_number_history']) && isset($_POST['startDateTime']) && isset($_POST['endDateTime']) && isset($_POST['selectedOption_History'])) {
        //====================接收 POST 參數=====================
        $selectedOption = $_POST['selectedOption'];
        $selectedOption_machine_number = $_POST['selectedOption_machine_number_history'];
        $startDateTime = $_POST['startDateTime'];
        $endDateTime = $_POST['endDateTime'];
        $selectedOptionHistory = $_POST['selectedOption_History'];
        try{
            //====================執行SQL計算與寫入CSV工作=====================
            //$table_name = getTableName_v3($selectedOption, $selectedOption_machine_number);
            $config = $tableNames[$selectedOption][$selectedOption_machine_number];//已經為兩階層映射
            $field = $config['fields'];
            $table_name = $config['table'];
            $sql = '';
            $params = [$startDateTime, $endDateTime];
            //====================Switch語句返回不同SQL=====================
            switch ($selectedOption) {
                case '定型':
                    $sql = "SELECT $field FROM $table_name WHERE [LogTimeStamp] > ? AND [LogTimeStamp] < ? ORDER BY [LogTimeStamp] DESC";
                    break;
                case '磨毛':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '刷毛':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '剪毛':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '燒毛':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '連續水洗機':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '蒸化機':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '剖布':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '解捻':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '防縮':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '柔軟':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                case '縮練':
                    $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > ? AND [UploadTime] < ? ORDER BY [UploadTime] DESC";
                    break;
                default:
                    throw new Exception("Invalid selected option");
                }
            //$sqlquery = SQLQuery($selectedOption, $config['fields'], $config['table'], $selectedOption_machine_number, $startDateTime, $endDateTime);

            // sqlsrv預處理語句成功返回$stmt
            $stmt = sqlsrv_prepare($conn, $sql, $params);
            if ($stmt === false) {
                throw new Exception(print_r(sqlsrv_errors(), true));
            }
            // sqlsrv執行語句成功返回true
            if (!sqlsrv_execute($stmt)) {
                throw new Exception(print_r(sqlsrv_errors(), true));
            }
            //====================執行下載CSV========================
            writeToCSV_V3($stmt, $selectedOption_machine_number, $startDateTime, $endDateTime);
            // 響應
            $response = array(
                "selectedOption" => $selectedOption,
                "selectedOption_machine_number_history" => $selectedOption_machine_number,
                "startDateTime" => $startDateTime,
                "endDateTime" => $endDateTime,
                "selectedOption_History" => $selectedOptionHistory,
                "tablename" => $table_name
            );        
            $combinedResponse = array(
                "data" => $sql, // 將原來的 $response 放入 'data' 鍵中
                "status" => "success",
                "message" => "Data processed successfully"
            );
            //echo json_encode($combinedResponse);

            exit();
        } catch (Exception $e){

             echo json_encode(array("status" => "error", "message" => "Data processing failed", "error" => $e->getMessage()));
             exit();
        }    
        // 記錄收到的參數
        //error_log("selectedOption: $selectedOption");
        //error_log("selectedOptionMachineNumberHistory: $selectedOptionMachineNumberHistory");
        //error_log("startDateTime: $startDateTime");
        //error_log("endDateTime: $endDateTime");
        //error_log("selectedOptionHistory: $selectedOptionHistory");
        // 成功處理的響應
        //echo json_encode(array("status" => "success", "message" => "Data processed successfully" + $selectedOption));
    }
     else {
        // 如果缺少參數，返回錯誤信息
        echo json_encode(array("status" => "error", "message" => "Missing parameters"));
        exit();
    }
} else {
    // 非 POST 請求
    echo json_encode(array("status" => "error", "message" => "Invalid request method"));
}
sqlsrv_close($conn);
//====================通用函式：用if找資料表 getTableName_v2()===================
function getTableName_v2($selectedOption, $selectedOption_machine_number) {
  $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);
  if ($selectedOption == '定型') {
      return '[FinishMachines_Stenter_Machine_' . $selectedOption_machine_number_prefix . ']';
  } elseif ($selectedOption == '磨毛') {
      return '[FinishMachines_Grinding_Machine_' . $selectedOption_machine_number_prefix . ']';
  } elseif ($selectedOption == '刷毛') {
      return '[FinishMachines_Brushing_Machine_' . $selectedOption_machine_number_prefix . ']';
  } elseif ($selectedOption == '剪毛') {
      return '[FinishMachines_Shearing_Machine_' . $selectedOption_machine_number_prefix . ']';
  } elseif ($selectedOption == '解捻') {
      return '[FinishMachines_Untwisting_Machine_D_' . $selectedOption_machine_number_prefix . ']';
  } elseif ($selectedOption == '柔軟') {
      return '[FinishMachines_Softening_Machine_D_' . $selectedOption_machine_number_prefix . ']';
  } else {
      // 如果未匹配到任何选项，返回一个默认的表名或者触发一个错误
      return '[FinishMachines_Stenter_Machine_T01]';
  }
}

//====================通用函式：返回SQL SQLQuery()===================
function SQLQuery($selectedOption, $field, $table_name, $selectedOption_machine_number, $today_start, $today_end) {
    $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);
    //echo ($selectedOption_machine_number_prefix);
    //echo ($selectedOption_machine_number);
    //echo ($table_name);
    $sql = '';
    switch ($selectedOption) {
        case '定型':
            $sql = "SELECT $field FROM $table_name WHERE [LogTimeStamp] > '$today_start' AND [LogTimeStamp] < '$today_end' ORDER BY [LogTimeStamp] DESC";
            break;
        case '磨毛':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '刷毛':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '剪毛':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '燒毛':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '連續水洗機':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '蒸化機':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '剖布':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '解捻':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '防縮':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '柔軟':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        case '縮練':
            $sql = "SELECT $field FROM $table_name WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end' ORDER BY [UploadTime] DESC";
            break;
        default:
            //$sql = "SELECT [Machine] FROM [FinishMachines_Shearing_Machine_C01] WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end'";
            break;
        //echo "Generated SQL: " . $sql . "\n";
        }
    return $sql;
}
//====================專屬門富士定型機稼動率計算函式：寫入CSV writeToCSV()===================
function writeToCSV($stmt, $output) {
  while ($row_machine = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $date = $row_machine['FormattedUploadTime'];
      $run_time = (float) $row_machine['run_time'];
    
      $daily_runtimes[$date] = isset($daily_runtimes[$date]) ? $daily_runtimes[$date] + $run_time : $run_time;
  }
  
  foreach ($daily_runtimes as $date => $total_runtime) {
      $activation = number_format(($total_runtime / (24 * 60)) * 100, 2);
      $runtime = number_format($total_runtime / 60, 2);
      fputcsv($output, [$date, $runtime, $activation]);
  }
}
//====================後整理機台稼動率統一計算函式：寫入CSV writeToCSV_V2()===================
function writeToCSV_V2($stmt, $selectedOption_machine_number, $startDateTime, $endDateTime) {
  // 设置为下载
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment;filename=' . $selectedOption_machine_number . '稼動率' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');
  $output = fopen('php://output', 'w');

  // 清理暫存區
  ob_clean();

  // 写入 BOM
  fwrite($output, "\xEF\xBB\xBF");
  fputcsv($output, ['日期', '實際運作時間_小時', '稼動率%']);

  $daily_runtimes = [];
  while ($row_machine = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $date = $row_machine['FormattedUploadTime'];
      $run_time = (float) $row_machine['run_time'];
  
      $daily_runtimes[$date] = isset($daily_runtimes[$date]) ? $daily_runtimes[$date] + $run_time : $run_time;
  }
  
  foreach ($daily_runtimes as $date => $total_runtime) {
      $activation = number_format(($total_runtime / (24 * 60)) * 100, 2);
      $runtime = number_format($total_runtime / 60, 2);
      fputcsv($output, [$date, $runtime, $activation]);
  }

  // 关闭输出
  fclose($output);
}
//====================後整理機台歷史查詢函式：寫入CSV writeToCSV_V3()===================
function writeToCSV_V3($stmt, $selectedOption_machine_number, $startDateTime, $endDateTime) {
    $data_table = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // 轉換解碼成 UTF-8
        array_walk($row, function(&$value) {
            $value = mb_convert_encoding($value, 'UTF-8', 'auto');
        });
      $data_table[] = $row;
    }   

    // Ensure the column titles are correctly set
    if (count($data_table) > 0) {
        $columnTitles = array_keys($data_table[0]);
        array_walk($columnTitles, function(&$value) {
            $value = mb_convert_encoding($value, 'UTF-8', 'auto');
        });
    } else {
        // If no data fetched, handle the case appropriately
        $columnTitles = [];
    }
    header('Content-Type: text/csv; charset=utf-8');
    //header('Content-Disposition: attachment;filename=' . $selectedOption_machine_number . '歷史紀錄' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');
    $output = fopen('php://output', 'w');
    ob_clean();
    fwrite($output, "\xEF\xBB\xBF");

    // Write the column titles to the CSV file if they exist
    if (!empty($columnTitles)) {
        fputcsv($output, $columnTitles);
    }
    foreach ($data_table as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}
//====================設定輸出CSV文件函式：寫入CSV outputCSV(查詢結果, 機台代號, 起始時間, 結束時間)===================
function outputCSV($data, $selectedOption_machine_number, $startDateTime, $endDateTime) {
    // 清理暫存區
    ob_clean();
    // 設置為下載
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename=' . $selectedOption_machine_number . '歷史紀錄' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF");

    // 獲取數據並寫入CSV
    if (!empty($data)) {
        $firstRow = $data[0];
        fputcsv($output, array_keys($firstRow)); // 寫入CSV標頭
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    }
    fclose($output);
}
//====================通用函式：簡易executeQuery(資料庫連結, 資料表, 資料欄位,開始時間, 結束時間)===================
function executeQuery($conn, $table, $fields, $start, $end) {
    $sql = "SELECT $fields FROM $table WHERE [LogTimeStamp] > ? AND [LogTimeStamp] < ? ORDER BY [LogTimeStamp] DESC;";
    $params = [$start, $end];
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    
    if ($stmt) {
        if (sqlsrv_execute($stmt)) {
            $data_table = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data_table[] = $row;
            }
            return json_encode($data_table);
        } else {
            handleErrors();
        }
    } else {
        handleErrors();
    }
    return null;
}
//====================錯誤處理函式： handleErrors()===================
function handleErrors() {
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            // 記錄錯誤信息，這裡可以使用日誌系統
            error_log("SQLSTATE: " . $error['SQLSTATE']);
            error_log("Code: " . $error['code']);
            error_log("Message: " . $error['message']);
        }
    }
}
?>
