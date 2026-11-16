gantt.config.min_column_width = 50;
gantt.config.scale_height = 60;
gantt.config.duration_unit = "hour";
gantt.config.autosize = "y";
gantt.config.readonly = true;
gantt.config.date_format = "%Y-%m-%d %H:%i";
gantt.config.date_grid = "%Y-%m-%d %H:%i";
gantt.config.touch_tooltip = true
gantt.config.show_tasks_outside_timescale=true


gantt.config.scales = [
    {unit: "year", step: 1, format: "%Y"},
    {unit: "day", format: "%m/%d"},
    {unit: "hour", step: 12, format: "%H:%i"}
];

gantt.config.columns = [
    {name: "text", label: Machine_name, tree:true,width: '90', align: "left"},
];


gantt.plugins({
        marker: true,
        tooltip: true 
});
var dateToStr = gantt.date.date_to_str(gantt.config.task_date);
 //甘特圖顯示資訊
gantt.templates.tooltip_datetime_format=function (datetime){
var formatFunc = gantt.date.date_to_str("%Y-%m-%d %H:%i");
return formatFunc(datetime);
};
gantt.templates.tooltip_text = function(start,end,task){
    //缸號
    if(task.Batch!=''){
        var Batch="<b>"+order_Batch+": </b>"+task.Batch+"<br>";
    }
    else{
        var Batch='';
    }
    //品名
    if(task.FabricName!=''){
        var FabricName="<b>"+order_FabricName+": </b>"+task.FabricName+"<br>";
    }
    else{
        var FabricName='';
    }
    //色號
    if(task.ColorCode!=''){
        var ColorCode="<b>"+order_ColorCode+": </b>"+task.ColorCode+"<br>";
    }
    else{
        var ColorCode='';
    } 

    //色名
    if(task.ColorName!=''){
        var ColorName="<b>"+order_ColorName+": </b>"+task.ColorName+"<br>";
    }
    else{
        var ColorName='';
    } 
    //客戶
    if(task.Customer!=''){
        var Customer="<b>"+order_Customer+": </b>"+task.Customer+"<br>";
    }
    else{
        var Customer='';
    } 
    //訂單
    if(task.Order!=''){
        var Order="<b>"+order_Order+": </b>"+task.Order+"<br>";
    }
    else{
        var Order='';
    } 
    //布重
    if(task.TotalWeight!=''){
        var TotalWeight="<b>"+order_TotalWeight+": </b>"+task.TotalWeight+"<br>";
    }
    else{
        var TotalWeight='';
    }
    //浴比
    if(task.LiquorRatio!=''){
        var LiquorRatio="<b>"+order_LiquorRatio+": </b>"+task.LiquorRatio+"<br>";
    }
    else{
        var LiquorRatio='';
    } 
    //總浴量
    if(task.TotalVolume!=''){
        var TotalVolume="<b>"+order_TotalVolume+": </b>"+task.TotalVolume+"<br>";
    }
    else{
        var TotalVolume='';
    } 
    //配方
    if(task.BulkedRecipe!=''){
        var BulkedRecipe="<b>"+order_BulkedRecpie+": </b><br>"+task.BulkedRecipe+"<br>";
    }
    else{
        var BulkedRecipe='';
    } 
    //染程
    if(task.Program!=''){
        var Program="<b>"+order_Program+": </b>"+task.Program+"<br>";
    }
    else{
        var Program='';
    } 
    //交期
    if(task.DeliveryTime!=''){
		var dateTime = new Date(task.DeliveryTime);
        var year = dateTime.getFullYear();
        var month = dateTime.getMonth()+1;//js從0開始取
        var date = dateTime.getDate();
        if(month<10){
            month = "0" + month;
        }
        if(date<10){
            date = "0" + date;
        }
		var TimeString=year+"-"+month+"-"+date
		if (TimeString=="2021-01-01"){
            TimeString = "急單";
        }
        var DeliveryTime="<b>"+order_DeliveryTime+": </b>"+TimeString+"<br>";
    }
    else{
        var DeliveryTime='';
    } 
    //廠內色號
    if(task.ColorNo!=''){
        var ColorNo="<b>"+order_ColorNo+": </b>"+task.ColorNo+"<br>";
    }
    else{
        var ColorNo='';
    } 
    //產品編號
    if(task.ProductCode!=''){
        var ProductCode="<b>"+order_ProductCode+": </b>"+task.ProductCode+"<br>";
    }
    else{
        var ProductCode='';
    } 
    //紗批號
    if(task.YarnLotNo!=''){
        var YarnLotNo="<b>"+order_YarnLotNo+": </b>"+task.YarnLotNo+"<br>";
    }
    else{
        var YarnLotNo='';
    } 
    //色系
    if(task.ColorShade!=''){
        var ColorShade="<b>"+order_ColorShade+": </b>"+task.ColorShade+"<br>";
    }
    else{
        var ColorShade='';
    } 

    //預估時間
    if(task.Estimate_start_date!=''){
        var Estimate_start_date="<b>"+"預估開始時間"+": </b>"+task.Estimate_start_date+"<br>";
    }
    else{
        var Estimate_start_date='';
    } 

    if(task.Estimate_end_date!=''){
        var Estimate_end_date="<b>"+"預估結束時間"+": </b>"+task.Estimate_end_date+"<br>";
    }
    else{
        var Estimate_end_date='';
    } 
    return "<b>"+order_Number+": </b> "+task.Dyelot+"<br/>"+
	"<b>"+order_Startday+": </b> " + gantt.templates.tooltip_datetime_format(start)+ "<br/>"+
	"<b>"+order_Endday+": </b> "+gantt.templates.tooltip_datetime_format(end)+ "</b><br/>"+

    Estimate_start_date+
    Estimate_end_date+
	DeliveryTime+
	Batch+
	FabricName+
	ColorCode+
	ColorName+
	Customer+
	Order+
	TotalWeight+
	LiquorRatio+
	TotalVolume+
	Program+
	ColorNo+
	ProductCode+
	YarnLotNo+
	ColorShade+
	BulkedRecipe; 
}; 
