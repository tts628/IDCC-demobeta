<?php
// InsertHandler.php
namespace DataUpload\Models\DatabaseOperations;

/**
 * Class InsertHandler
 * Handles the process of inserting validated data into the database.
 */
class InsertHandler
{
    private $dataInserter;
    private $identitySelector;

    public function __construct(DataInserter $dataInserter, IdentitySelector $identitySelector)
    {
        $this->dataInserter = $dataInserter;
        $this->identitySelector = $identitySelector;
    }

    /**
     * Inserts validated data into the database.
     *
     * @param array $trimmedData The data to be inserted, including identity and validation keys.
     * @return array An array containing 'error' and 'success' keys indicating the result of the insertion process.
     */
    public function handleInsertProcess(array $trimmedData): array
    {
        if (
            !isset($trimmedData["validation"]) || empty($trimmedData["validation"]) ||
            !isset($trimmedData["validation"]["identity"]) || empty($trimmedData["validation"]["identity"]) ||
            !isset($trimmedData["validation"]["data"]) || empty($trimmedData["validation"]["data"])
        ) {
            return [
                'error' => ["sheet" => "ALL", "message" => "validation key not exist or is empty"],
                'success' => [],
            ];
        }

        $insertResults = [
            'error' => [],
            'success' => [],
        ];

        $tableMaps = $this->identitySelector->selectIdentities($trimmedData["validation"]["identity"]);
        
        $datetimeFkId = $this->dataInserter->insertCurrentDatetimeIntoLookup();

        for ($i = 0; $i < count($trimmedData["validation"]["identity"]); $i++) {
            $currentDataName = $trimmedData["validation"]["identity"][$i];
            $sheetData = $trimmedData["validation"]["data"][$currentDataName];

            if (empty($sheetData)) {
                $insertResults['error'][] = ["sheet" => $currentDataName, "message" => "無資料 (未提供資料欄位, 或活頁簿名稱錯誤)"];
                continue;
            }

            if (count($sheetData) === 1) {
                $insertResults['error'][] = ["sheet" => $currentDataName, "message" => "無資料 (無資料參數)"];
                continue;
            }

            $mappedFlag = false;
            foreach ($tableMaps as $map) {
                if (isset($map[$currentDataName])) {
                    $mappedFlag = true;
                    $tableName = $map[$currentDataName];
                    break;
                }
            }

            if (!$mappedFlag) {
                $insertResults['error'][] = [
                    "sheet" => $currentDataName, 
                    "message" => "invalid mapping for $currentDataName, available map: " . implode($tableMaps)
                ];
                continue;
            }

            $result = $this->dataInserter->insertData($sheetData, $tableName, $datetimeFkId);

            if (!empty($result['error'])) {
                $insertResults['error'][] = $result['error'];
            } else {
                $insertResults['success'][] = $result['success'];
            }
        }

        return $insertResults;
    }
}
