<?php
// LabOperatorExtractor.php
namespace DataUpload\Models\Extractor;

use DataUpload\Models\ExtractorAbstract;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class LabOperatorExtractor
 * Handles the extraction of Lab Operator data from a given spreadsheet.
 * 
 * Utilizes traits for database extraction and specific lab data extraction
 * methods. The class searches for specific terms within the spreadsheet and
 * returns extracted data in a standardized format.
 */
class LabOperatorExtractor extends ExtractorAbstract
{
    use ExcelExtractorDBTraits;
    use LabExtractorTraits;

    /**
     * Extracts lab operator data from the provided spreadsheet.
     *
     * @param Spreadsheet $spreadsheet The spreadsheet from which to extract data.
     * @return array The extracted lab operator data in a standardized format.
     */
    public function extractData(Spreadsheet $spreadsheet): array
    {
        $worksheet = $this->checkForTerm($spreadsheet, "化驗室操作員代碼", "title");
        if ($worksheet) {
            $data = $this->extractLabOperator($worksheet);
            return $this->prepareResult($data);
        } else {
            return $this->prepareResult('');
        }
    }

    /**
     * Extracts the lab operator information from the provided worksheet.
     *
     * @param Worksheet $worksheet The worksheet containing lab operator data.
     * @return array The extracted lab operator data.
     * @throws \RuntimeException If the expected column headers are not found.
     */
    private function extractLabOperator(Worksheet $worksheet): array
    {
        $data = [];

        foreach ($worksheet->getRowIterator() as $index => $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();

            foreach ($cellIterator as $cell) {
                $value = $cell->getValue();
                if ($value != null) {
                    $rowData[] = $value;
                }
            }

            if ($index === 1) {
                if ($rowData !== ['代碼', '中文名字']) {
                    throw new \RuntimeException(
                        "檔案格式錯誤. <br>資料識別為: '化驗室操作員代碼', 檔案中應存在 '<b>代碼</b>' 及 '<b>中文名字</b>' 欄位, 偵測欄位為: '<b>" .
                        implode("</b>', '<b>", $rowData) .
                        "</b>'"
                    );
                }
            }

            if (count($rowData) === 1) {
                $rowData[] = NULL;
            }

            $data[] = $rowData;
        }
        return $data;
    }

    /**
     * Prepares the extracted lab operator data in a structured format.
     *
     * @param array|string $data The data to be prepared. If an empty string is passed, the result will reflect no data.
     * @return array The structured result containing the identity and data fields.
     */
    private function prepareResult($data): array
    {
        $result = [
            'identity' => '',
            'data' => []
        ];

        if (!empty($data)) {
            $result['identity'] = '操作員代碼表';
            $result['data'] = $data;
        }

        return $result;
    }
}
