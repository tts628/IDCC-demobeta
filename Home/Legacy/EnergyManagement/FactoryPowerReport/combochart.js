function drawcombochart(jsonData_mon,jsonData_dead,jsonData_hurt,jsonData_add,title,label,nodata,coe,coetitle){
    
    // console.log(jsonData_dead);
    var coe_jsondata = [];
    if(coe==''){
        coe=1;
    }
    else{
     
        if(!isNaN(coe)){
               parseFloat(coe);
        }
        else{
            coe=1;
        }
        // console.log(coe);
    }
        

    for(var i=0;i<jsonData_dead.length;i++){
        coe_jsondata[i] = [jsonData_dead[i][0] * coe];
  
        
    }
    // console.log(coe_jsondata);
    // console.log(jsonData_dead);

    Highcharts.chart('combochart', {

        
    lang: {
        noData: nodata
    },
    noData: {
        style: {
            fontWeight: 'bold',
            fontSize: '15px',
            color: '#00BCD4'
        }
    },
        credits: {
            enabled: false
          },
    chart: {
    backgroundColor: null,
    events: {
        load: function() {
            $('.highcharts-button').hover(function() {
                $(this).find('.highcharts-button-box').show();
            }, function() {
                $(this).find('.highcharts-button-box').hide();
            });
        }
    },
    },
    width: "100px",
    title: {
        text: title,
        type: 'column',

        style: {
            color: '#FFFFFF',
            fontSize: '3.5vh',
            fontWeight: 'bold',
            textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'
            },
            
    },
    plotOptions: {
        column: {
        pointWidth: 12, // 設置每一條柱形的寬度為 10 像素\
        borderWidth: 0.5,
        
        
        }
    },
    legend: {
        itemStyle: {
            color: 'white',
            fontSize: '1.5vh'
        
            
        }
    },
    
    xAxis: {
        // lineColor: 'white',
        // color: 'white',
        categories: jsonData_mon,
        labels: {
            style: {
            color: "white", // 設置Y軸標籤顏色
            fontSize: '1.5vh'
            }
        }
    },
    yAxis: [{
        
        title: {
            // 
            // color: 'white',
            text: '(kWh)',
            style: {
                color: 'white' ,// 設置標題顏色為橙色
                fontSize: '1.5vh'
                }
        },
        
        
        labels: {
            style: {
            color: "#0294A5", // 設置Y軸標籤顏色
            fontSize: '1.5vh'
            }
        }
    
    },
    {
        opposite: true,
        title: {
            // 
            // color: 'white',
            text: '(Carbon Footprint)',
            style: {
                color: 'white' ,// 設置標題顏色為橙色
                fontSize: '1.5vh'
                }
        },
        
        
        labels: {
            style: {
            color: "#ff961d", // 設置Y軸標籤顏色
            fontSize: '1.5vh'
            }
        }
    
    }
],
    tooltip: {
        style: {
            
            fontSize: '1.5vh'
            },
        valueSuffix: ' (kWh)'
    },
    series: [{
        //長條顏色
        animation: true, //禁用動畫
        color: '#0294A5',
        type: 'column',
        name: label,
        
        data: jsonData_dead
    }, 
    {
        yAxis: 1,
        animation: true, //禁用動畫
        color: '#ff961d',
        type: 'column',
        name: coetitle,
        data: coe_jsondata
    }, 

    // {
    //     type: 'column',
    //     name: '2022',
    //     data: [58, 88, 75, 250, 176]
    // }, 

    {
        type: 'spline',
        name: label,
        animation: true, //禁用動畫
        data: jsonData_add,
        //線的顏色
        color: '#fadb71',
        marker: {
            lineWidth: 3,
            lineColor: '#fadb71',
            // fillColor: 'white'
            
        },
        
    }]
});


}

