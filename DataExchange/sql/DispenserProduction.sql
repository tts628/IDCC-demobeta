-- All source-row filters removed. Read from the currently selected SPC database.
SELECT 
    [ID],
    [Dyelot],
    [Redye],
    Dispenser AS 'Machine',
    (SELECT TOP 1 [Machine] FROM [dbo].[Dyelots] D WHERE  DBR.[Dyelot] = D.[Dyelot]) as 'Destination',
    [ProductCode],
    [ProductName],
    [ProductType],
    TRY_CAST(REPLACE(REPLACE(REPLACE([Amount], ',', ''), 'g', ''), ' ', '') AS FLOAT) as TargetGrams,
    [DispenseGrams],
    
    COALESCE([DispenseStartTime], [DispenseTime]) AS 'StartTime',
    [DispenseEndTime] AS 'EndTime',
    
    
    
    
    
    
    
    
    
    
    [Process],
    [StepNumber],
	DATEDIFF(SECOND, COALESCE([DispenseStartTime], [DispenseTime]), [DispenseEndTime]) AS 'DispenseSeconds',
	DispenseGrams-TRY_CAST(REPLACE(REPLACE(REPLACE([Amount], ',', ''), 'g', ''), ' ', '') AS FLOAT) as 'DispenseError'
    
FROM 
    [dbo].[DyelotsBulkedRecipe] DBR;
