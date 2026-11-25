-- All source-row filters removed. Read from the currently selected SPC database.
SELECT 
    M.Name,
    COALESCE(M.DispenseDyelot, '') AS Dyelot,
    COALESCE(M.DispenseReDye, 0) AS Redye,
    COALESCE(D.Batch, NULL) AS Batch,
    COALESCE(D.Color, 0) AS Color,
    COALESCE(D.TotalWeight, 0) AS TotalWeight,
    COALESCE(D.TotalVolume, 0) AS TotalVolume,
	
CASE 
    
    
    WHEN TRY_CAST(M.name AS INT) IN (1, 2, 7, 11, 12, 13, 14, 29, 34) THEN 'B'
    
    WHEN TRY_CAST(M.name AS INT) IN (3, 4, 5, 17, 18, 19, 30, 31, 35) THEN 'M'
    
    WHEN TRY_CAST(M.name AS INT) IN (9, 20, 21, 22, 23, 24, 25, 26, 32, 33) THEN 'S'
    
    
    ELSE 'S'
END AS Capacity, 
    COALESCE(D.FabricName, '') AS FabricName,
    COALESCE(D.ColorName, '') AS ColorName,
    COALESCE(M.MainTemperature, '') AS MainTemperature,
    COALESCE(M.ProgramNumberAndName, '') AS Program,
    COALESCE(M.StepNumberAndText, '') AS Step,
	COALESCE(M.ActiveAlarms, '') AS ActiveAlarms,
    CONVERT(varchar, CAST(D.StartTime AS datetime) + 0.291667, 120) AS DyelotStartTime,
    CONVERT(varchar, CAST(D.EndTime AS datetime) + 0.291667, 120) AS DyelotEndTime,
    COALESCE(D1.Dyelot, '') AS Dyelot1,
    COALESCE(D1.ReDye, 0) AS Redye1,
	COALESCE(D1.Color, '') AS Color1,
    COALESCE(D1.FabricName, '') AS FabricName1,
    COALESCE(D1.ColorName, '') AS ColorName1,
    COALESCE(D1.TotalWeight, 0) AS TotalWeight1,
    CONVERT(varchar, CAST(D1.StartTime AS datetime) + 0.291667, 120) AS Dyelot1StartTime,
    CONVERT(varchar, CAST(D1.EndTime AS datetime) + 0.291667, 120) AS Dyelot1EndTime,
    COALESCE(D2.Dyelot, '') AS Dyelot2,
    COALESCE(D2.ReDye, 0) AS Redye2,
	COALESCE(D2.Color, 0) AS Color2,
    COALESCE(D2.FabricName, '') AS FabricName2,
    COALESCE(D2.ColorName, '') AS ColorName2,
    COALESCE(D2.TotalWeight, 0) AS TotalWeight2,
    CONVERT(varchar, CAST(D2.StartTime AS datetime) + 0.291667, 120) AS Dyelot2StartTime,
    CONVERT(varchar, CAST(D2.EndTime AS datetime) + 0.291667, 120) AS Dyelot2EndTime,
    COALESCE(M.ActiveAlarms, '') AS Note

FROM 
    [dbo].[Machines] M
LEFT JOIN 
    [dbo].[Dyelots] D ON (D.Machine = M.Name AND D.Dyelot = M.DispenseDyelot AND D.ReDye = M.DispenseReDye)
LEFT JOIN 
    (SELECT TOP 1 * 
     FROM [dbo].[Dyelots]  
     ORDER BY StartTime ASC) AS D1 ON D1.Machine = M.Name
LEFT JOIN 
    (SELECT *
     FROM [dbo].[Dyelots] 
     ORDER BY StartTime ASC
     OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY) AS D2 ON D2.Machine = M.Name;
