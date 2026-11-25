-- All source-row filters removed. Read from the currently selected SPC database.
WITH CombinedResults AS (
    SELECT 
        [Machine],
        COALESCE(Dyelot, '') AS Dyelot,
        COALESCE(ReDye, 0) AS ReDye,
        COALESCE(Name, '') AS Description,
        COALESCE(Name, '') AS Description_BIG,
        COALESCE(Name, '') AS Description_Local,
        CONVERT(varchar, CAST(StartTime AS datetime) + 0.291667, 120) AS CreateDate,
        CONVERT(varchar, CAST(EndTime AS datetime) + 0.291667, 120) AS ConfirmDate,
        CONVERT(varchar, CAST(EndTime AS datetime) + 0.291667, 120) AS FinishDate,
        COALESCE(Duration, '') AS Duration
    FROM 
        [dbo].[Delays]

    UNION ALL

    SELECT 
        [Machine],
        COALESCE(Dyelot, '') AS Dyelot,
        COALESCE(ReDye, 0) AS ReDye,
        COALESCE(Name, '') AS Description,
        COALESCE(Name, '') AS Description_BIG,
        COALESCE(Name, '') AS Description_Local,
        CONVERT(varchar, CAST(StartTime AS datetime) + 0.291667, 120) AS CreateDate,
        CONVERT(varchar, CAST(EndTime AS datetime) + 0.291667, 120) AS ConfirmDate,
        CONVERT(varchar, CAST(EndTime AS datetime) + 0.291667, 120) AS FinishDate,
        COALESCE(Duration, '') AS Duration
    FROM 
        [dbo].[Alarms]
)
SELECT 
    CASE
        WHEN Description LIKE '%異常%' OR 
             Description LIKE '%過載%' OR 
             Description LIKE '%短路%' OR 
             Description LIKE '%斷路%' OR 
             Description LIKE '%Low%' OR 
             Description LIKE '%不足%' OR 
             Description LIKE '%纏車%' OR 
             Description LIKE '%超溫%' THEN '3'
        WHEN Description LIKE '%超時%' OR 
             Description LIKE '%太%' OR 
             Description LIKE '%無法%' OR 
             Description LIKE '%等待%' THEN '2'
        WHEN Description LIKE '%暫停%' THEN '2'
        WHEN Description LIKE '%故障%' OR 
             Description LIKE '%無PLC%' THEN '4'
        ELSE '1'
    END AS EventType,
    Machine,
    Dyelot,
    ReDye,
    Description,
    Description_BIG,
    Description_Local,
    CreateDate,
    ConfirmDate,
    FinishDate,
    Duration,
    CASE
        WHEN Description LIKE '%異常%' OR 
             Description LIKE '%過載%' OR 
             Description LIKE '%短路%' OR 
             Description LIKE '%斷路%' OR 
             Description LIKE '%Low%' OR 
             Description LIKE '%不足%' OR 
             Description LIKE '%纏車%' OR 
             Description LIKE '%超溫%' THEN '1'
        WHEN Description LIKE '%超時%' OR 
             Description LIKE '%太%' OR 
             Description LIKE '%無法%' OR 
             Description LIKE '%等待%' THEN '2'
        WHEN Description LIKE '%暫停%' THEN '3'
        WHEN Description LIKE '%故障%' OR 
             Description LIKE '%無PLC%' THEN '5'
        ELSE '4'
    END AS IdccEventType
FROM 
    CombinedResults;
