-- All source-row filters removed. Read from the currently selected SPC database.
SELECT     
    Dyelot,     
    ReDye,     
    Machine,     
    Batch,     
    CONVERT(varchar, CAST(StartTime AS datetime) + 0.29166, 120) AS StartTime,     
    CONVERT(varchar, CAST(EndTime AS datetime) + 0.29166, 120) AS EndTime,
    CAST(DATEDIFF_BIG(minute, [StartTime], [EndTime]) / 1440.0 AS VARCHAR) as ProcessTime,  
    ISNULL(StandardTime, 0) AS StandardTime,   
	CAST(DATEDIFF_BIG(minute, [StartTime], [EndTime]) / 1440.0 AS VARCHAR)-StandardTime as OverRunTime,
    Program,     
    Notes,     
    ISNULL(Color, 0) AS Color,     
    CreationTime,     
    DATEDIFF_BIG(second, GETDATE(), EndTime) / NULLIF(DATEDIFF_BIG(second, StartTime, EndTime), 0) AS Process,     
    TotalWeight,     
    TotalVolume,     
    SheetNo,     
    CustomerName,     
    CUSTOMER_CODE,     
    GRAMS_YARD,     
    FABRIC_CODE,     
    Shade 
FROM      
    [dbo].[Dyelots];
