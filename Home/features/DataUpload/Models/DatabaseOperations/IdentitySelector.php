<?php
// IdentitySelector.php
namespace DataUpload\Models\DatabaseOperations;

class IdentitySelector
{
    private $conn; // Database connection
    private $enableChunking; // Dev switch for chunking

    public function __construct(\Config\DbMapper $dbMapper, $enableChunking = false)
    {
        $this->conn = $dbMapper->getPdo(['IDCC']);
        $this->enableChunking = $enableChunking;
    }
    
    public function selectIdentities(array $identities, $chunkSize = 2000)
    {
        $results = [];

        if ($this->enableChunking) {
            // If chunking is enabled, split identities into chunks
            foreach (array_chunk($identities, $chunkSize) as $chunk) {
                $results = array_merge($results, $this->executeSelect($chunk));
            }
        } else {
            // No chunking, run a single query
            $results = $this->executeSelect($identities);
        }

        return $results;
    }

    private function executeSelect(array $chunk)
    {
        $inQuery = implode(',', array_fill(0, count($chunk), '?'));
        $sql = "SELECT [Identity], DBTable FROM UploadMappingSheet WHERE [Identity] IN ($inQuery)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($chunk);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $formattedResult = [];

        // Format the output as expected: [ ["identity1" => DBTableValue], ... ]
        foreach ($rows as $row) {
            $formattedResult[] = [$row['Identity'] => $row['DBTable']];
        }

        return $formattedResult;
    }
}
