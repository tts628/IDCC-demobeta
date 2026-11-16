//機台選單改變 顯示長條圖

  //每小時長條圖
  function Dye_energy_24hour (machine_name,Power,Steam,Water,hour,Water_word,Steam_word,Power_word) {  
    Highcharts.chart('Dye_energy', {
      chart: {
        // type: 'line',
        zoomType: 'xy',
        backgroundColor: "rgba(0,0,0,0)",
      },
      title: {
        text: machine_name,
        align: 'center',
        style: {
            color: '#FFFFFF'
        }
      },
      xAxis: [{
        type: 'category',
        crosshair: true,
        labels: {
            style: {
                color: '#FFFFFF',
                fontSize:'15px'
            }
        }
      }],
      yAxis: [{ // Primary yAxis
        labels: {
          format: '{value}'+hour[0].SteamUsage_hour_unit,
          style: {
            color: Highcharts.getOptions().colors[3],
            // fontSize:'15px'
            fontSize:'20px'

          }
        },
        title: {
            text: null
        },
        opposite: true
      },{gridLineWidth: 0,
      title: {
          text: null
      },
      labels: {
        format: '{value}'+hour[0].WaterUsage_hour_unit,
        style: {
          color: Highcharts.getOptions().colors[0],
          // fontSize:'15px'
          fontSize:'20px'

        }
      }
  
    }, { // Tertiary yAxis
        gridLineWidth: 0,
        title: {
            text: null
        },
        labels: {
          format: '{value}'+hour[0].PowerUsage_hour_unit,
          style: {
            color: '#33FF33',
            // fontSize:'15px'
            fontSize:'20px'

          }
        },
        opposite: true
      }],
      legend: {
        itemStyle: {
            color: '#FFFFFF',
          fontSize: '20px'

        }
      },
      tooltip: {
        shared: true
      },
      series: [{
        name: Water_word,
        type: 'column',
        yAxis: 1,
        data: Water,     
        tooltip: {
          valueSuffix: hour[0].WaterUsage_hour_unit
        },
        dataLabels: {
          enabled: true,
          color: Highcharts.getOptions().colors[0], // 数据标签的颜色
          style: {
              fontSize: '16px', // 数据标签的字体大小
          }
      }
      },{  
        name: Steam_word,  
        type: 'column',
        color: Highcharts.getOptions().colors[3],
        data: Steam,
        tooltip: {
          valueSuffix:hour[0].SteamUsage_hour_unit
        },
        dataLabels: {
          enabled: true,
          color: Highcharts.getOptions().colors[3], // 数据标签的颜色
          style: {
              fontSize: '16px', // 数据标签的字体大小
          }
      }
  
      }, { 
        name: Power_word,
        type: 'column',
        color:'#33FF33',
        yAxis: 2,
        data:Power,
        tooltip: {
          valueSuffix:hour[0].PowerUsage_hour_unit
        },
        dataLabels: {
          enabled: true,
          // color: Highcharts.getOptions().colors[0], // 数据标签的颜色
          color:'#33FF33', // 数据标签的颜色
          style: {
              fontSize: '16px', // 数据标签的字体大小
          }
      }
      }],
      responsive: {
        rules: [{
          condition: {
            maxWidth: 500
          },
          chartOptions: {
            legend: {
              floating: false,
              layout: 'horizontal',
              align: 'center',
              verticalAlign: 'bottom',
              x: 0,
              y: 0
            },
            yAxis: [{
              labels: {
                align: 'right',
                x: 0,
                y: -6
              },
              showLastLabel: false
            }, {
              labels: {
                align: 'left',
                x: 0,
                y: -6
              },
              showLastLabel: false
            }, {
              visible: false
            }]
          }
        }]  
      },
      credits: { enabled: false },
      exporting:{
        enabled:true//用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
    }
    });
  }  
  //每小時長條圖
  //每日長條圖
  function Finish_energy_24hour (machine_name,Power,Steam,Water,hour,Water_word,Steam_word,Power_word) {  
  Highcharts.chart('Finish_energy', {
        chart: {
          zoomType: 'xy',
          backgroundColor:"rgba(0,0,0,0)",
        },
        title: {
          text: machine_name,
          align: 'center',
          style: {
              color: '#FFFFFF'
          }
        },
        credits: {
            enabled: false
        },
        xAxis: [{
          type: 'category',
          crosshair: true,
          labels: {
              style: {
                  color: '#FFFFFF',
                  // fontSize:'15px'
              fontSize:'18px'

              }
          }
        }],
        yAxis: [{ // Primary yAxis
          labels: {
            format: '{value}'+hour[0].SteamUsage_hour_unit,
            style: {
              color: Highcharts.getOptions().colors[3],
              // fontSize:'15px'
          fontSize:'20px'

            }
          },
          title: {
              text: null
          },
          opposite: true
        }, { // Secondary yAxis
          gridLineWidth: 0,
          title: {
              text: null
          },
          labels: {
            format: '{value}'+hour[0].WaterUsage_hour_unit,
            style: {
              color: Highcharts.getOptions().colors[0],
              // fontSize:'15px'
        fontSize:'20px'

            }
          }
  
        }, { // Tertiary yAxis
          gridLineWidth: 0,
          title: {
              text: null
          },
          labels: {
            format: '{value}'+hour[0].PowerUsage_hour_unit,
            style: {
              color: '#33FF33',
              // fontSize:'15px'
          fontSize:'20px'

            }
          },
          opposite: true
        }],
        legend: {
          itemStyle: {
              color: '#FFFFFF',
          fontSize: '20px'

          }
        },
        tooltip: {
          shared: true
        },
        series: [{
          name: Water_word,
          type: 'column',
          yAxis: 1,
          data: Water,     
          tooltip: {
            valueSuffix: hour[0].WaterUsage_hour_unit
          },
          dataLabels: {
            enabled: true,
            color: Highcharts.getOptions().colors[0], // 数据标签的颜色
            style: {
                fontSize: '16px', // 数据标签的字体大小
            }
        }
        }, {  
          name: Steam_word,  
          type: 'column',
          color: Highcharts.getOptions().colors[3],
          data: Steam, 
          tooltip: {
            valueSuffix: hour[0].SteamUsage_hour_unit
          },
          dataLabels: {
            enabled: true,
            color: Highcharts.getOptions().colors[3], // 数据标签的颜色
            style: {
                fontSize: '16px', // 数据标签的字体大小
            }
        }
  
        }, { 
          name: Power_word,
          type: 'column',
          color:'#33FF33',
          yAxis: 2,
          data: Power,
          tooltip: {
            valueSuffix: hour[0].PowerUsage_hour_unit
          },
          dataLabels: {
                  enabled: true,
                  color:  '#33FF33', // 数据标签的颜色
                  style: {
                      fontSize: '16px', // 数据标签的字体大小
                  }
              }
        }],
        responsive: {
          rules: [{
            condition: {
              maxWidth: 500
            },
            chartOptions: {
              legend: {
                floating: false,
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0
              },
              yAxis: [{
                labels: {
                  align: 'right',
                  x: 0,
                  y: -6
                },
                showLastLabel: false
              }, {
                labels: {
                  align: 'left',
                  x: 0,
                  y: -6
                },
                showLastLabel: false
              }, {
                visible: false
              }]
            }
          }]
        },
        credits: { enabled: false }
      });
  }
 