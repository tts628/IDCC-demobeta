<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");


// if ($_GET["lang"] == 'en-US') {

//     $name["Alarm_Machines"] = 'Top 10 Alarm Machines of the Month';
//     $name["Alarm_Reasons"] = 'Top 10 Alarm Reasons of the Month';
//     $name["Delayed_Machines"] = 'Top 10 Delayed Machines of the Month';
//     $name["Delay_Reasons"] = 'Top 10 Delay Reasons of the Month';
// } else {
//     $name["Alarm_Machines"] = '本月 Top 10 警報機台';
//     $name["Alarm_Reasons"] = '本月 Top 10 警報原因';
//     $name["Delayed_Machines"] = '本月 Top 10 延遲機台';
//     $name["Delay_Reasons"] = '本月 Top 10 延遲原因';
// }

    $name["Alarm_Machines"] = $lang->line("general.Alarm_Machines");
    $name["Alarm_Reasons"] = $lang->line("general.Alarm_Reasons");
    $name["Delayed_Machines"] =$lang->line("general.Delayed_Machines");
    $name["Delay_Reasons"] = $lang->line("general.Delay_Reasons");


?>
<!DOCTYPE html>
<html>

<head>
    <title>流亞科技</title>
    <style>
    </style>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/data.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/no-data-to-display.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body style="background-color:#272727;">
    <div class="fluid-container">
        <div class="row m-5">
            <div class="col-6" id="alarm_machines"></div>
            <div class="col-6" id="alarm_reasons"></div>
        </div>
        <div class="row m-5">
            <div class="col-6" id="delay_machines"></div>
            <div class="col-6" id="delay_reasons"></div>
        </div>
    </div>

    <script>
        const queryString = window.location.search;

        $.get('./MonthlyInterruptions/alarm_machines.php' + queryString, function (data) {
            var dataArray = JSON.parse(data);

            Highcharts.chart('alarm_machines', {
                chart: {
                    backgroundColor: null,
                    type: 'column',
                },
                colors: ['#00d45a'],
                title: {
                    // text: '本月 Top 10 警報機台',
                    text: '<?php echo $name["Alarm_Machines"]; ?>',
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: '分鐘',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                legend: {
                    enabled: false,
                    itemStyle: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    },
                    title: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                series: [{
                    name: '分鐘',
                    data: dataArray
                }],
                credits: {
                    enabled: false
                },
                tooltip: {
                    valueDecimals: 0
                }
            });
        });

        $.get('./MonthlyInterruptions/alarm_reasons.php' + queryString, function (data) {
            var dataArray = JSON.parse(data);

            Highcharts.chart('alarm_reasons', {
                chart: {
                    backgroundColor: null,
                    type: 'column',
                },
                colors: ['#ffc107'],
                title: {
                    // text: '本月 Top 10 警報原因',
                    text: '<?php echo $name["Alarm_Reasons"]; ?>',
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: '分鐘',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                legend: {
                    enabled: false,
                    itemStyle: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    },
                    title: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                series: [{
                    name: '分鐘',
                    data: dataArray
                }],
                credits: {
                    enabled: false
                },
                tooltip: {
                    valueDecimals: 0
                }
            });
        });

        $.get('./MonthlyInterruptions/delay_machines.php' + queryString, function (data) {
            var dataArray = JSON.parse(data);

            Highcharts.chart('delay_machines', {
                chart: {
                    backgroundColor: null,
                    type: 'column',
                },
                colors: ['#00bcd4'],
                title: {
                    // text: '本月 Top 10 延遲機台',
                    text: '<?php echo $name["Delayed_Machines"]; ?>',
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: '分鐘',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                legend: {
                    enabled: false,
                    itemStyle: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    },
                    title: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                series: [{
                    name: '分鐘',
                    data: dataArray
                }],
                credits: {
                    enabled: false
                },
                tooltip: {
                    valueDecimals: 0
                }
            });
        });


        $.get('./MonthlyInterruptions/delay_reasons.php' + queryString, function (data) {
            var dataArray = JSON.parse(data);

            Highcharts.chart('delay_reasons', {
                chart: {
                    backgroundColor: null,
                    type: 'column',
                },
                colors: ['#ff5252'],
                title: {
                    // text: '本月 Top 10 延遲原因',
                    text: '<?php echo $name["Delay_Reasons"]; ?>',
                    style: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    }
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: '分鐘',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    labels: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                legend: {
                    enabled: false,
                    itemStyle: {
                        color: '#ffffff',
                        fontWeight: 'bold'
                    },
                    title: {
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    }
                },
                series: [{
                    name: '分鐘',
                    data: dataArray
                }],
                credits: {
                    enabled: false
                },
                tooltip: {
                    valueDecimals: 0
                }
            });
        });
    </script>
</body>

</html>