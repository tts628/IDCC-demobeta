function drawbarchart(jsonData,title,nodata){
        

        Highcharts.chart('barchart', {

          credits: {
            enabled: false
          },
  chart: {
    events: {
      load: function() {
          $('.highcharts-button').hover(function() {
              $(this).find('.highcharts-button-box').show();
          }, function() {
              $(this).find('.highcharts-button-box').hide();
          });
      }
  },
    
    backgroundColor: null,
    type: 'column',
    animation: true // 啟用動畫效果
    // events: {
      
    //   mouseOver: function () {
    //                   console.log("function executed");
    //     this.yAxis[0].update({
    //       gridLineWidth: 0,
    //       lineWidth: 0
    //     })

        
    //   },
    //   mouseOut: function () {
    //     this.yAxis[0].update({
    //       gridLineWidth: 110,
    //       lineWidth: 100
    //     })
    //   },




    // }
    
  },


  plotOptions: {
    column: {
      pointWidth: 30, // 設置每一條柱形的寬度為 10 像素
      borderWidth: 0.5
    },
    
},


  title: {
    text: title,
    align: 'left', // 設置標題左對齊
    x: 10, // 設置標題水平偏移量為 10 像素
    style: {
      color: '#FFFFFF', 
    
      fontSize: '3.5vh',
      fontWeight: 'bold',
      textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'
    }
  
  },
  subtitle: {
    
    text: ' '
  },
  xAxis: {
    
    type: 'category',
    labels: {
      rotation: -30,
      style: {
        color: 'white',
        fontSize: '1.9vh',
        
        fontFamily: 'Verdana, sans-serif'
        
      },
      x:10
    }
  },
  yAxis: {
  
    // min: 0,
    
    // gridLineWidth:0,
    title: {
      text: '(kWh)',
      style: {
        color: 'white', // 設置標題顏色為橙色
        fontSize: '1.5vh'
        
        }
    },
    labels: {
      style: {
      color: "white", // 設置Y軸標籤顏色
      fontSize: '1.5vh'
      }
    }
  },
  legend: {
    
    enabled: false
  },
  tooltip: {
    style: {
            
      fontSize: '1.5vh'
      },
    pointFormat: 'Total: <b>{point.y:.0f}(kWh) </b>'
  },
  series: [{
    animation: true, //禁用動畫
    name: '(kWh)',
    data: jsonData,
    color: '#00BCD4',
    // dataLabels: {
    //   enabled: true,
    //   rotation: 0,
    //   color: '#FFFFFF',
    //   align: 'right',
    //   format: '{point.y:.f}', // one decimal
    //   x:0,
    //   y: 10, // 10 pixels down from the top
    //   style: {
    //     fontSize: '6.5px',
    //     fontFamily: 'Verdana, sans-serif'
    //   }
    // }
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



