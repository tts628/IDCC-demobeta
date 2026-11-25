-- All source-row filters removed. Read from the currently selected SPC database.
SELECT
    Dyelot
    ,Redye
    ,Machine
    
    ,StartTime    
    ,EndTime    
    
    
    ,WaterUsed - LAG(WaterUsed, 1, 0) OVER (PARTITION BY Dyelot ORDER BY EndTime) AS WaterUsage    
    
    
    ,PowerUsed - LAG(PowerUsed, 1, 0) OVER (PARTITION BY Dyelot ORDER BY EndTime) AS PowerUsage    
    
    
    ,SteamUsed - LAG(SteamUsed, 1, 0) OVER (PARTITION BY Dyelot ORDER BY EndTime) AS SteamUsage    
    
    
    ,Notes AS Note 
FROM 
    [dbo].[StepVisits];
