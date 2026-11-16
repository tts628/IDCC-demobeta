function drawpiechart(jsonData,title,label,nodata,coe,footprint){
    // console.log();
    // console.log(jsonData);
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
        

    for(var i=0;i<jsonData.length;i++){
        jsonData[i][1]  *= coe;
        // console.log(parseFloat(coe));
    }
        
    // console.log(jsonData[i][1]);
    
      
    //   console.log(jsonData);
 
    
      

        Highcharts.chart("piechart", {
            
            credits: {
                enabled: false
            },
            chart: {
                backgroundColor: null,
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: "pie",
                events: {
                    load: function() {
                        $('.highcharts-button').hover(function() {
                          $(this).find('.highcharts-button-box').show();
                        }, function() {
                          $(this).find('.highcharts-button-box').hide();
                        });
                      }
                    },
                    animation: true // 啟用動畫效果
            },
            title: {
                text: footprint,
        align: 'left', // 設置標題左對齊
        x: 10, // 設置標題水平偏移量為 10 像素
        style: {
        color: '#FFFFFF', 
        fontSize: '3.5vh',
        fontWeight: 'bold',
        textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'
    
    }
            },
            tooltip: {
                style: {
            
                    fontSize: '1.5vh'
                    },
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
                
            },
            plotOptions: {
                      pie: {
                allowPointSelect: true,
                cursor: "pointer",
                dataLabels: {
                  enabled: true
                },
                animation: true // 啟用動畫效果
              }
            },
            series: [{
                name: '碳足跡',
                colorByPoint: true,
                animation: true, //禁用動畫
                data:jsonData
                ,
            dataLabels: {
                enabled: true,
                format: "{point.name}: {point.y:.0f} <br><b>({point.percentage:.1f}%)</b> ",
                style: {
                color: "white", // 設置數據標籤顏色為
                fontSize: '2.2vh',
                textOutline: 'none' 
            }
            }
            }],
            lang: {
                noData: nodata
            },
            noData: {
                style: {
                    fontWeight: 'bold',
                    fontSize: '15px',
                    color: '#00BCD4'
                }
            }
            
        });
}



