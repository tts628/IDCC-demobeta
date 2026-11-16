gantt.config.min_column_width = 50;
gantt.config.scale_height = 60;
gantt.config.duration_unit = "hour";
gantt.config.autosize = "y";
gantt.config.readonly = true;
gantt.config.date_format = "%Y/%m/%d %H:%i";
gantt.config.date_grid = "%Y/%m/%d %H:%i";
gantt.config.touch_tooltip = true
gantt.config.show_tasks_outside_timescale=true
gantt.config.order_branch =true

gantt.config.scales = [
    {unit: "month", step: 1, format: "%F, %Y"},
    {unit: "hour", step: 1, format: "%d %H"}
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
var formatFunc = gantt.date.date_to_str("%Y/%m/%d %H:%i");
return formatFunc(datetime);
};
gantt.templates.tooltip_text = function(start,end,task){
    //品名
    if(task.FabricName!=''){
        var FabricName="<b>"+order_FabricName+": </b>"+task.FabricName+"<br>";
    }
    else{
        var FabricName="<b>"+order_FabricName+": </b>";
    }
    //色名
    if(task.ColorName!=''){
        var ColorName="<b>"+order_ColorName+":</b>"+task.ColorName+"<br>";
    }
    else{
        var ColorName="<b>"+order_ColorName+":</b>";
    } 
    //布重
    if(task.TotalWeight!=''){
        var TotalWeight="<b>"+order_TotalWeight+":</b>"+task.TotalWeight+"<br>";
    }
    else{
        var TotalWeight="<b>"+order_TotalWeight+":</b>";
    }
    //交期
    if(task.DeliveryTime!=''){
        var DeliveryTime="<b>"+order_DeliveryTime+":</b>"+task.DeliveryTime+"<br>";
    }
    else{
        var DeliveryTime="<b>"+order_DeliveryTime+":</b>";
    }
    //給工單日期
    if(task.MakeDyelotTime!=''){
        var MakeDyelotTime="<b>"+order_MakeDyelotTime+":</b>"+task.MakeDyelotTime+"<br>";
    }
    else{
        var MakeDyelotTime="<b>"+order_MakeDyelotTime+":</b>";
    }
    return "<b>"+order_Number+": </b> "+task.Dyelot+"<br/>"+
	"<b>"+order_Startday+": </b> " + gantt.templates.tooltip_datetime_format(start)+ "<br/>"+
	"<b>"+order_Endday+": </b> "+gantt.templates.tooltip_datetime_format(end)+ "</b><br/>"+
	FabricName+ColorName+TotalWeight+DeliveryTime+MakeDyelotTime;
}; 
