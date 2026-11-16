gantt.config.min_column_width = 50;
gantt.config.scale_height = 54;
gantt.config.duration_unit = "hour";
gantt.config.autosize = "y";
gantt.config.readonly = true;
gantt.config.date_format = "%Y-%m-%d %H:%i";
gantt.config.date_grid = "%Y-%m-%d %H:%i";
gantt.config.touch_tooltip = true

gantt.config.scales = [
    {unit: "year", step: 1, format: "%Y"},
    {unit: "day", format: "%m/%d"},
    {unit: "hour", step: 8, format: "%H:%i"}
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
gantt.templates.tooltip_date_format=function (date){
var formatFunc = gantt.date.date_to_str("%Y-%m-%d %H:%i");
return formatFunc(date);
};
gantt.templates.tooltip_text = function(start,end,task){
    //缸號
    if(task.Batch!=''){
        var Batch="<b>"+order_Batch+":</b>"+task.Batch+"<br>";
    }
    else{
        var Batch='';
    }
    //品名
    if(task.FabricName!=''){
        var FabricName="<b>"+order_FabricName+":</b>"+task.FabricName+"<br>";
    }
    else{
        var FabricName='';
    }
    //色號
    if(task.ColorCode!=''){
        var ColorCode="<b>"+order_ColorCode+":</b>"+task.ColorCode+"<br>";
    }
    else{
        var ColorCode='';
    } 

    //色名
    if(task.ColorName!=''){
        var ColorName="<b>"+order_ColorName+":</b>"+task.ColorName+"<br>";
    }
    else{
        var ColorName='';
    } 
    //客戶
    if(task.Customer!=''){
        var Customer="<b>"+order_Customer+":</b>"+task.Customer+"<br>";
    }
    else{
        var Customer='';
    } 
    //訂單
    if(task.Order!=''){
        var Order="<b>"+order_Order+":</b>"+task.Order+"<br>";
    }
    else{
        var Order='';
    } 
    //布重
    if(task.TotalWeight!=''){
        var TotalWeight="<b>"+order_TotalWeight+":</b>"+task.TotalWeight+"<br>";
    }
    else{
        var TotalWeight='';
    }
    //浴比
    if(task.LiquorRatio!=''){
        var LiquorRatio="<b>"+order_LiquorRatio+":</b>"+task.LiquorRatio+"<br>";
    }
    else{
        var LiquorRatio='';
    } 
    //總浴量
    if(task.TotalVolume!=''){
        var TotalVolume="<b>"+order_TotalVolume+":</b>"+task.TotalVolume+"<br>";
    }
    else{
        var TotalVolume='';
    } 
    //配方
    if(task.BulkedRecipe!=''){
        var BulkedRecipe="<b>"+order_BulkedRecpie+":</b><br>"+task.BulkedRecipe+"<br>";
    }
    else{
        var BulkedRecipe='';
    } 
    //染程
    if(task.Program!=''){
        var Program="<b>"+order_Program+":</b>"+task.Program+"<br>";
    }
    else{
        var Program='';
    } 
    return "<b>"+order_Number+":</b> "+task.Dyelot+"<br/><b>"+order_Startday+":</b> " +
    gantt.templates.tooltip_date_format(start)+ 
    "<br/><b>"+order_Endday+":</b> "+gantt.templates.tooltip_date_format(end)+ "</b><br/><b>"+order_Progress+":</b>"+
    Math.round(task.progress*100)+'%'+"<br>"+Batch+FabricName+ColorCode+ColorName+Customer+Order+TotalWeight+LiquorRatio+TotalVolume+Program+BulkedRecipe; 
}; 
