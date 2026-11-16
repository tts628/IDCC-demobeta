
<?php
//  ini_set('display_errors', '0');
//  error_reporting(0);
//  ini_set('log_errors', '1');
// var_dump($_GET['web_name']);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");
?>
    
    <script>
var Flash_section_name_time;
var Flash_section_alarm_time;
var Flash_section_section_time;

</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>染機警報</title>
    <style>


        .text-box {
            background-color: #485464;
    /* background-color: black; */

            box-shadow:  5px 5px 0px #4788fb;
            border: 1px solid #4788fb;
            padding: 5px;
            margin: 7px;
            width: auto;
            height:auto ;
        }


        .marquee {
    width: 100%;
    overflow: hidden;
    background: #333;
    color: white;
    white-space: nowrap;
  }

  .marquee-content {
    /* width: 200px; */
    /* height:10px ; */
    display: inline-block;
    /* padding-left: 100%; */
    animation: marquee 1s linear infinite;
    /* overflow: hidden; */
    white-space: nowrap;

    /* white-space: nowrap; */

  }

  @keyframes marquee {
    0% { transform: translateX(50%); }
    100% { transform: translateX(-100%); }
  }
  .static {
    display: inline-block;
  }
    </style>
    <style>
         .outer_div_danny {
      position: relative; /* 使用相对定位 */
      display: none;
      /* width: 200px;
      height: 200px; */
      /* background-color: #ccc; */
    }
    .outer_div_dongbao {
      position: relative; /* 使用相对定位 */
      display: none;
      /* width: 200px;
      height: 200px; */
      /* background-color: #ccc; */
    }
        .table_danny {
            zoom: 0.8;
            position: absolute; /* 使用绝对定位 */
            left: 0px; /* 距离左侧为 0 */
            top: 37px; /* 距离顶部为 0 */
            width:50px;
            font-size:16px;
            /* width: 80%; */
            /* width:400px; */
            border-collapse: collapse;
            /* margin: 20px auto; */
            /* background: linear-gradient(180deg, #FF3D67, white); */
            /* border-radius: 8px; */
            overflow: hidden;
            /* box-shadow:  5px 5px 10px #4788fb; */
        }
        .table_dongbao {
            zoom: 0.8;

            position: absolute; /* 使用绝对定位 */
            right: -10px; /* 距离左侧为 0 */
            top: 65px; /* 距离顶部为 0 */
            width:50px;
            font-size:16px;
            /* width: 80%; */
            /* width:400px; */
            border-collapse: collapse;
            /* margin: 20px auto; */
            /* background: linear-gradient(180deg, #FF3D67, white); */
            /* border-radius: 8px; */
            overflow: hidden;
            /* box-shadow:  5px 5px 10px #4788fb; */
        }

        th, td {
            border: 1px solid black;
            /* padding:10px; */
            text-align: center;
        }

        th {
            /* background-color: #4788fb; */
            /* color: white; */
        }

        tr:nth-child(even) {
            /* background: linear-gradient(45deg, rgba(52,152,219,1), rgba(52,152,250,0)); */
            /* color: white;  */
        }
        
        tr:nth-child(odd) {
            /* background: linear-gradient(90deg, rgba(255,0,0,1), rgba(255,255,255,1)); */
            /* color: white;  */
        }
        

        tr:hover {
            /* background-color: black */
        }
        .img_danny {
    margin-top: 30px;
    max-width:730px ;
    max-height: 480px;
        }
        .img_dongbao {
    margin-left: -10px;
    margin-top: 50px;
    width:750px ;
        }
        .screenshoot{
    margin-left: -10px;
    margin-top: 50px;
    width:750px ;
    z-index:999;
        }
    </style>
    <style>
        
        .text-with-shadow {
            font-size: 24px;
            color: white;
            text-align: center;
            text-shadow: 5px 5px 10px #4788fb;
        }
        .machine-text-with-shadow{
            margin-top: 20px;
            font-size: 60px;
            color: white;
            text-align: center;
            text-shadow: 5px 5px 10px #4788fb;
        }
        .alarm-text-with-shadow{
            font-size: 30px;
            color: white;
            text-align: center;
            /* text-shadow: 5px 5px 10px #4788fb; */
            
        }
        @keyframes blink {
        0% { stroke: red; }
        50% { stroke: rgba(255,0,0,0.3); }
        100% { stroke: red; }

        
    }

    
    /* animation: blink 1.5s infinite;  */
    </style>
    <style>
/* 设置页面样式 */
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: Arial, sans-serif;
    /* background-color: #2C58A6; */
    background-color: #485464;
    /* background-color: black; */
}

/* 设置容器样式 */
.container {
    /* background: #2C58A6; */
    overflow: hidden;
    width: 1920px;
    height: 1080px;
    display: grid;
    grid-template-rows: 1fr 1fr;
    grid-template-columns: 1fr 1fr;
}

/* 设置区块样式 */
.section {
    /* background-color: #4788fb; */
    
    box-shadow: 0px 0px 10px #4788fb;
    display: flex;
    justify-content: center;
    align-items: center;
    /* background-color: #f0f0f0; */
    border: 1px solid #ccc;
    font-size: 40px;
    /* padding: 20px; */
}

/* 指定各区块的位置 */
#section1 {
    grid-area: 1 / 1 / 2 / 3; /* 第一行第一列到第二行第二列 */
    
}

#section2 {
    grid-area: 2 / 1 / 3 / 3; /* 第一行第二列到第二行第三列 */
    
}

#section3 {
    /* grid-area: 2 / 1 / 3 / 2; 第二行第一列到第三行第二列 */
}

#section4 {
    /* grid-area: 2 / 2 / 3 / 3; 第二行第二列到第三行第三列 */
}

.grid-container {
    width: 1890px;
    height: 510px;
    display: grid;
    grid-template-columns: repeat(13, 1fr); 
    grid-template-rows: repeat(6, 1fr);    
    gap: 20px; /* 可以調整列與行之間的間距 */
    /* border: 1px solid #ccc; */
    padding: 0px;
    
}


.machine_name {
    grid-row: 1 / 2; /* 從第一行到第二行 */
    grid-column: 1 / 5; /* 從第一列到第二列 */
    /* border: 1px solid #ccc; */

}

.info {
    grid-row: 2 / 7; /* 從第一行到第二行 */
    grid-column: 1 / 5; /* 從第二列到第三列 */
    zoom:1.5;

    /* border: 1px solid #ccc; */

}
.countdown {
    grid-row: 1 / 4; /* 從第一行到第二行 */
    grid-column: 10 / 14; /* 從第二列到第三列 */
    /* border: 1px solid #ccc; */

}
.simulation {
    grid-row: 1 / 7; /* 從第一行到第二行 */
    grid-column: 5 / 10; /* 從第一列到第二列 */
    /* border: 1px solid #ccc; */
    text-align: center; /* 居中图片 */


}


.alarm {
    grid-row: 4 / 7; /* 從第一行到第二行 */
    grid-column: 10 / 14; /* 從第二列到第三列 */
    /* border: 1px solid #ccc; */

}
.flex-container {
  display: flex;
  flex-wrap: wrap; /* 让元素在需要时换行 */
  width: 100%; /* 可以调整容器宽度 */

}
/* 警報格子 */
.flex-item {
    width: 230px;
    margin:auto; /* 调整格子之间的间距 */
    margin-left:16px; /* 调整格子之间的间距 */
    margin-top:-15px; /* 调整格子之间的间距 */
    margin-bottom:-10px; /* 调整格子之间的间距 */

    background-color: #485464;
    /* background-color: black; */

    height:40px ;
    /* margin-top: 20px; */
    font-size: 30px;
    text-align: center;
    padding: 5px;
    border: 3px dashed orange;
    border-radius: 10px; 
}

    </style>
    
    <style>
/* 倒數計時 */

    .countdown-container {
        margin-left: 30px;
  max-width: 750px; /* 设置最大宽度 */
  display: flex;
  justify-content: space-between; /* 水平分布三个计时器 */
  align-items: center;
  height: 300px;
}

.countdown-ring {
  width: 200px;
  height: 200px;
  text-align: center;
  position: relative;
}

.progress {
  position: absolute;
  width: 100%;
  height: 100%;
}

.circle {
  transition: stroke-dashoffset 1s linear, stroke 0.5s linear; /* 添加 stroke 过渡效果 */
}

.timer {
    
  position: absolute;
  width: 100%;
  height: 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 40px;
  font-weight: bold;
  /* color: #333; */
  color: white;
}

.timer_name {
    
    position: absolute;
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 40px;
    font-weight: bold;
    /* color: #333; */
    color: white;
  }

    </style>
</head>
<body>
    <div class="container">
        <div class="section" id="section1">
        <div class=" grid-container">
                <div class="machine_name">
                    <div class="machine-text-with-shadow" id="Name_1">機台名稱</div>
                </div>
                <div class="info text-with-shadow" >
                    <div class="text-box" id="MainTemperature_1">
                        <?php echo $lang->line("general.main_cylinder_temperature"); ?>: °C
                    </div>
                    <div class="text-box" id="Dyelot_1">
                        <?php echo $lang->line("general.material_requisition_form"); ?>: 
                    </div>
                    <div class="text-box" id="Program_1">
                        <?php echo $lang->line("general.dyeing_process"); ?>: 
                    </div>
                    <div class="text-box" id="TotalWeight_1">
                        <?php echo $lang->line("general.loading_quantity"); ?>:
                    </div>
                    <div class="text-box" id="FabricName_1">
                        <?php echo $lang->line("general.fabric_type"); ?>: 
                    </div>
                </div>
                <div class="countdown" style="zoom:0.7">
                            
                            <div class="countdown-container">
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_1_1" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_1_1"></div>
                            <div class="timer_name"><?php echo $lang->line("general.unloading_fabric"); ?></div>
                            
                        </div>
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_1_2" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_1_2"></div>
                            <div class="timer_name"><?php echo $lang->line("general.preparing_chemicals"); ?></div>

                        </div>
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_1_3" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_1_3"></div>
                            <div class="timer_name"><?php echo $lang->line("general.dissolving_materials"); ?></div>

                        </div>
                            
                            </div>
                            
                </div>
                <div class="simulation">
                <!-- <img class="screenshoot" src="http://localhost:8080/Users/sam.yang/Desktop/流亞的/共享測試/下載.png" alt="图片描述"> -->
                <!-- C:/LA/LA838LiveScreen/H01.jpg -->  
                
                <div class="outer_div_dongbao" id="outer_div_dongbao_1">
                        <img class="img_dongbao" src="./東寶染機模擬圖.png"   alt="图片描述">
                        

                        <table class="table_dongbao">
                        <tr>
                                
                                <th>Rope1</th>
                                <th>Rope2</th>
                                <th>Rope3</th>
                                <th>Rope4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_dongbao_Rope_1_1">34 sec</td>
                                <td id="outer_div_dongbao_Rope_1_2">23 sec</td>
                                <td id="outer_div_dongbao_Rope_1_3">45 sec</td>
                                <td id="outer_div_dongbao_Rope_1_4">23 sec</td>
                            </tr>
                            <tr>
                                
                                <th>CycleTime1</th>
                                <th>CycleTime2</th>
                                <th>CycleTime3</th>
                                <th>CycleTime4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_dongbao_CycleTime_1_1">34 sec</td>
                                <td id="outer_div_dongbao_CycleTime_1_2">23 sec</td>
                                <td id="outer_div_dongbao_CycleTime_1_3">45 sec</td>
                                <td id="outer_div_dongbao_CycleTime_1_4">23 sec</td>
                            </tr>
                            
                        </table>
                    </div>
                    <div class="outer_div_danny" id="outer_div_danny_1">
                        <img class="img_danny" src="./丹尼染機模擬圖.png"   alt="图片描述">
  
                        <table class="table_danny">
                            <tr>
                                
                                <th>Rope1</th>
                                <th>Rope2</th>
                                <th>Rope3</th>
                                <th>Rope4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_danny_Rope_1_1">34 sec</td>
                                <td id="outer_div_danny_Rope_1_2">23 sec</td>
                                <td id="outer_div_danny_Rope_1_3">45 sec</td>
                                <td id="outer_div_danny_Rope_1_4">23 sec</td>
                            </tr>
                            <tr>
                                
                                <th>CycleTime1</th>
                                <th>CycleTime2</th>
                                <th>CycleTime3</th>
                                <th>CycleTime4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_danny_CycleTime_1_1">34 sec</td>
                                <td id="outer_div_danny_CycleTime_1_2">23 sec</td>
                                <td id="outer_div_danny_CycleTime_1_3">45 sec</td>
                                <td id="outer_div_danny_CycleTime_1_4">23 sec</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="alarm flex-container alarm-text-with-shadow">
                    <div id="alarm_type1_1" class="flex-item"><?php echo $lang->line("general.machine_speed_abnormal"); ?></div>
                    <div id="alarm_type1_2" class="flex-item"><?php echo $lang->line("general.cycle_time_abnormal"); ?></div>
                    <div id="alarm_type1_3" class="flex-item"><?php echo $lang->line("general.nozzle_opening_detection"); ?></div>
                    <div id="alarm_type1_4" class="flex-item"><?php echo $lang->line("general.insufficient_steam"); ?></div>
                    <div id="alarm_type1_5" class="flex-item"><?php echo $lang->line("general.insufficient_cooling"); ?></div>
                    <div id="alarm_type1_6" class="flex-item"><?php echo $lang->line("general.delayed_feeding"); ?></div>
                    <div id="alarm_type1_7" class="flex-item" style="width: 518px;"><?php echo $lang->line("general.other"); ?></div>

                </div>

            </div>


        </div>
        <div class="section" id="section2"> 
            <div class=" grid-container">
                <div class="machine_name">
                    <div class="machine-text-with-shadow" id="Name_2">機台名稱</div>
                </div>
                <div class="info text-with-shadow" >
                    <div class="text-box" id="MainTemperature_2">
                        <?php echo $lang->line("general.main_cylinder_temperature"); ?>: °C
                    </div>
                    <div class="text-box" id="Dyelot_2">
                        <?php echo $lang->line("general.material_requisition_form"); ?>: 
                    </div>
                    <div class="text-box" id="Program_2">
                        <?php echo $lang->line("general.dyeing_process"); ?>: 
                    </div>
                    <div class="text-box" id="TotalWeight_2">
                        <?php echo $lang->line("general.loading_quantity"); ?>:
                    </div>
                    <div class="text-box" id="FabricName_2">
                        <?php echo $lang->line("general.fabric_type"); ?>: 
                    </div>
                </div>
                <div class="countdown" style="zoom:0.7">
                            
                            <div class="countdown-container">
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_2_1" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_2_1"></div>
                            <div class="timer_name"><?php echo $lang->line("general.unloading_fabric"); ?></div>
                        </div>
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_2_2" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_2_2"></div>
                            <div class="timer_name"><?php echo $lang->line("general.preparing_chemicals"); ?></div>
                        </div>
                        <div class="countdown-ring">
                            <div class="progress">
                                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                                    <circle id="c_circle_2_3" class="circle" r="90" cx="100" cy="200" fill="transparent" stroke="#4788fb" stroke-width="15" stroke-dasharray="565.48" stroke-dashoffset="565.48"></circle>
                                </svg>
                            </div>
                            <div class="timer" id="c_timer_2_3"></div>
                            <div class="timer_name"><?php echo $lang->line("general.dissolving_materials"); ?></div>
                        </div>
                            
                            </div>
                </div>
                <div class="simulation">
                <div class="outer_div_dongbao" id="outer_div_dongbao_2">
                        <img class="img_dongbao" src="./東寶染機模擬圖.png"   alt="图片描述">

                        <table class="table_dongbao">
                            <tr>
                                
                                <th>Rope1</th>
                                <th>Rope2</th>
                                <th>Rope3</th>
                                <th>Rope4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_dongbao_Rope_2_1">34 sec</td>
                                <td id="outer_div_dongbao_Rope_2_2">23 sec</td>
                                <td id="outer_div_dongbao_Rope_2_3">45 sec</td>
                                <td id="outer_div_dongbao_Rope_2_4">23 sec</td>
                            </tr>
                            <tr>
                                
                                <th>CycleTime1</th>
                                <th>CycleTime2</th>
                                <th>CycleTime3</th>
                                <th>CycleTime4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_dongbao_CycleTime_2_1">34 sec</td>
                                <td id="outer_div_dongbao_CycleTime_2_2">23 sec</td>
                                <td id="outer_div_dongbao_CycleTime_2_3">45 sec</td>
                                <td id="outer_div_dongbao_CycleTime_2_4">23 sec</td>
                            </tr>
                        </table>
                    </div>
                    <div class="outer_div_danny" id="outer_div_danny_2">
                        <img class="img_danny" src="./丹尼染機模擬圖.png"   alt="图片描述">
  
                        <table class="table_danny">
                            <tr>
                                
                                <th>Rope1</th>
                                <th>Rope2</th>
                                <th>Rope3</th>
                                <th>Rope4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_danny_Rope_2_1">34 sec</td>
                                <td id="outer_div_danny_Rope_2_2">23 sec</td>
                                <td id="outer_div_danny_Rope_2_3">45 sec</td>
                                <td id="outer_div_danny_Rope_2_4">23 sec</td>
                            </tr>
                            <tr>
                                
                                <th>CycleTime1</th>
                                <th>CycleTime2</th>
                                <th>CycleTime3</th>
                                <th>CycleTime4</th>
                            </tr>
                            <tr>
                                
                                <td id="outer_div_danny_CycleTime_2_1">34 sec</td>
                                <td id="outer_div_danny_CycleTime_2_2">23 sec</td>
                                <td id="outer_div_danny_CycleTime_2_3">45 sec</td>
                                <td id="outer_div_danny_CycleTime_2_4">23 sec</td>
                            </tr>
                        </table>
                    </div>
                
                </div>
                <div class="alarm flex-container alarm-text-with-shadow">
                    <div id="alarm_type2_1" class="flex-item"><?php echo $lang->line("general.machine_speed_abnormal"); ?></div>
                    <div id="alarm_type2_2" class="flex-item"><?php echo $lang->line("general.cycle_time_abnormal"); ?></div>
                    <div id="alarm_type2_3" class="flex-item"><?php echo $lang->line("general.nozzle_opening_detection"); ?></div>
                    <div id="alarm_type2_4" class="flex-item"><?php echo $lang->line("general.insufficient_steam"); ?></div>
                    <div id="alarm_type2_5" class="flex-item"><?php echo $lang->line("general.insufficient_cooling"); ?></div>
                    <div id="alarm_type2_6" class="flex-item"><?php echo $lang->line("general.delayed_feeding"); ?></div>
                    <div id="alarm_type2_7" class="flex-item" style="width: 518px;"><?php echo $lang->line("general.other"); ?></div>
                </div>

            </div>

        </div>
        
    </div>
</body>
</html>

<!-- 日期換算 -->
<script>
    function formatDateTime(dateTimeString) {
  const date = new Date(dateTimeString);
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${month}/${day} ${hours}:${minutes}`;
}
// console.log(new Date());
</script>

<!-- 換算時間差 -->

<script>
  function calculate_time_left(EndDatetimeStr) {
  // 将给定日期时间字符串转换为 Date 对象
  const givenDatetime = new Date(EndDatetimeStr);

  // 获取当前时间的 Date 对象
  const currentDatetime = new Date();

  // 计算秒数差异
  const timeDifferenceInSeconds = Math.floor((givenDatetime - currentDatetime) / 1000);

  return timeDifferenceInSeconds;
}

function calculate_total_time(StartDatetimeStr,EndDatetimeStr) {

  const givenDatetime1 = new Date(StartDatetimeStr);
  const givenDatetime2 = new Date(EndDatetimeStr);


  // 计算秒数差异
  const timeDifferenceInSeconds_total = Math.floor((givenDatetime2 - givenDatetime1) / 1000);

  return timeDifferenceInSeconds_total;
}
// 使用函数计算时间差


</script>


<script>

function startCountdown(starttime, endtime, circleElement, timerElement, Estimated) {

  const circumference = parseFloat(getComputedStyle(circleElement).getPropertyValue("stroke-dasharray"));

// let remainingTime = durationInSeconds;
// const interval = 1000; // 更新间隔，以毫秒为单位
// 耗時多少
durationInSeconds = calculate_total_time(starttime,endtime);
// 剩下幾秒
remainingTime= calculate_time_left(endtime);

// 距離開始時間

const updateProgress = () => {
  

  if(remainingTime>durationInSeconds){
        // timerElement.textContent =  "Next:"+nextDyelot+nextDyelot_time;
        timerElement.textContent =  "預計時間"+formatDateTime(starttime);
    
        circleElement.style.stroke = "rgba(0,0,0,0)"; 
        circleElement.style.animation = "none";

    }
else if (remainingTime < 0 || endtime=="" || starttime=="") {
    if(endtime==""){
        console.log(Estimated+"沒endtime值");
    }
    else if(starttime==""){
        console.log(Estimated+"沒starttime值");
    }

    // clearInterval(timerInterval);
    if(Estimated=='出布')
    timerElement.textContent = "<?php echo $lang->line("general.unloading_completed"); ?>";
    else if(Estimated=='備藥')
    timerElement.textContent = "<?php echo $lang->line("general.preparation_completed"); ?>";
    else if(Estimated=='化料')
    timerElement.textContent = "<?php echo $lang->line("general.dissolving_completed"); ?>";


    circleElement.style.stroke = "rgba(0,0,0,0)"; 
    circleElement.style.animation = "none";

  }
else{


        const percentage = (durationInSeconds - remainingTime) / durationInSeconds;
        const dashOffset = circumference * (1 - percentage);
        
        // 20秒內紅色
        if(remainingTime<=600){
            circleElement.style.stroke = "yellow"; 
            circleElement.style.animation = "none";

            if(remainingTime<=60)
            {
            circleElement.style.stroke = "red"; 
            circleElement.style.animation = "blink 0.5s infinite";
            if(remainingTime<=0){
            // circleElement.style.stroke = "green"; 
            circleElement.style.stroke = "rgba(0,0,0,0)"; 
            circleElement.style.animation = "none";

            }
        }
        }
        else{
            circleElement.style.stroke = "#4788fb"; 
            circleElement.style.animation = "none";

        }
        
        circleElement.style.strokeDashoffset = dashOffset;
        // timerElement.textContent = remainingTime;
        if(Math.floor(remainingTime / 60)>0)
            // timerElement.textContent = Math.floor(remainingTime / 60) + "分 " + (remainingTime % 60) + "秒";
            timerElement.textContent = Math.floor(remainingTime / 60) + ":" +((remainingTime % 60)>=10 ? "" : "0")+ (remainingTime % 60) + "";
        else
            timerElement.textContent = (remainingTime % 60) + "秒";
    }


  // if (percentage >= 0.8 && !circleElement.classList.contains("red")) {
  //   circleElement.style.stroke = "green"; // 当进度达到20%时，改变边框颜色为红色
  //   circleElement.classList.add("green"); // 添加类以防止多次更改颜色
  // }



//   remainingTime--;
};


    updateProgress();


// const timerInterval = setInterval(updateProgress, interval);

    

}

// 调用 startCountdown 函数并传递倒计时的持续时间、圆圈元素和计时器元素
// const circleElements = document.querySelectorAll(".circle");
// const timerElements = document.querySelectorAll(".timer");
var circleElements1_1 = document.getElementById("c_circle_1_1");
var timerElements1_1 = document.getElementById("c_timer_1_1");
var circleElements1_2 = document.getElementById("c_circle_1_2");
var timerElements1_2 = document.getElementById("c_timer_1_2");
var circleElements1_3 = document.getElementById("c_circle_1_3");
var timerElements1_3 = document.getElementById("c_timer_1_3");

var circleElements2_1 = document.getElementById("c_circle_2_1");
var timerElements2_1 = document.getElementById("c_timer_2_1");
var circleElements2_2 = document.getElementById("c_circle_2_2");
var timerElements2_2 = document.getElementById("c_timer_2_2");
var circleElements2_3 = document.getElementById("c_circle_2_3");
var timerElements2_3 = document.getElementById("c_timer_2_3");

// var circleElements3_1 = document.getElementById("c_circle_3_1");
// var timerElements3_1 = document.getElementById("c_timer_3_1");
// var circleElements3_2 = document.getElementById("c_circle_3_2");
// var timerElements3_2 = document.getElementById("c_timer_3_2");
// var circleElements3_3 = document.getElementById("c_circle_3_3");
// var timerElements3_3 = document.getElementById("c_timer_3_3");

// var circleElements4_1 = document.getElementById("c_circle_4_1");
// var timerElements4_1 = document.getElementById("c_timer_4_1");
// var circleElements4_2 = document.getElementById("c_circle_4_2");
// var timerElements4_2 = document.getElementById("c_timer_4_2");
// var circleElements4_3 = document.getElementById("c_circle_4_3");
// var timerElements4_3 = document.getElementById("c_timer_4_3");
// for (let i = 0; i < circleElements.length; i++) {
var timer1_1=0;
var timer1_2=0;
var timer1_3=0;

var timer2_1=0;
var timer2_2=0;
var timer2_3=0;

// var timer3_1=0;
// var timer3_2=0;
// var timer3_3=0;

// var timer4_1=0;
// var timer4_2=0;
// var timer4_3=0;
//   startCountdown(5, circleElements[i], timerElements[i]);
// }
    // startCountdown(15, circleElements1_1, timerElements1_1,'出布');
    // startCountdown(15, circleElements1_2, timerElements1_2,'備藥');
    // startCountdown(15, circleElements1_3, timerElements1_3,'化料');
  
    // startCountdown(15, circleElements2_1, timerElements2_1,'出布',"","");
    // startCountdown(15, circleElements2_2, timerElements2_2,'備藥',"","");
    // startCountdown(15, circleElements2_3, timerElements2_3,'化料');

    // startCountdown(15, circleElements3_1, timerElements3_1,'出布');
    // startCountdown(15, circleElements3_2, timerElements3_2,'備藥');
    // startCountdown(15, circleElements3_3, timerElements3_3,'化料');

    // startCountdown(15, circleElements4_1, timerElements4_1,'出布');
    // startCountdown(15, circleElements4_2, timerElements4_2,'備藥');
    // startCountdown(15, circleElements4_3, timerElements4_3,'化料');

    </script>



<!-- 參考用 -->
<script>
function Flash_section(section,command) {
    // console.log(section);
  const targetElement = document.getElementById(section); // 替换为你的目标元素的 ID
//   const targetElement_name = document.getElementById("Name_1"); // 替换为你的目标元素的 ID
  
//   if (!targetElement) {
//     console.error('目标元素未找到'); // 如果未找到目标元素，输出错误信息
//     return;
//   }
//   const content = document.querySelector('.content');
  let isFlashing = false;
  function flashBackground() {
    isFlashing = !isFlashing;
    if (isFlashing) {
      // 渐变为红色
    //   targetElement.style.transition = 'background-color 1s ease-in-out';
    //   targetElement.style.backgroundColor = 'red';
    targetElement_name.style.transition = 'color  0.4s ease-in-out';
      targetElement_name.style.color  = 'red';
    //   content.style.zIndex = '1'; // 设置内容z-index，使其显示在闪烁下方
    } else {
      // 渐变回原始颜色
    //   targetElement.style.transition = 'background-color 1s ease-in-out';
    //   targetElement.style.backgroundColor = ' #485464';
      targetElement_name.style.transition = 'color  0.4s ease-in-out';
      targetElement_name.style.color  = 'white';
    //   content.style.zIndex = '0'; // 恢复内容z-index
    }
    if(isFlashing==true)
        setTimeout(flashBackground, 400);
    // console.log(isFlashing);
  }
  if(command=="flash"){
    // var intervalId = setInterval(flashBackground, 1000); // 1秒切换一次颜色
    flashBackground();
    
  }
  else if(command=="click"){
    // clearInterval(intervalId);
    // targetElement.style.backgroundColor = 'red';
    targetElement_name.style.color  = 'red';


  }
  else if(command=="stop"){
    // clearInterval(intervalId);
    // targetElement.style.backgroundColor = ' #485464';
    
    targetElement_name.style.color  = 'white';

  }

  // 在需要的时候停止定时器，例如：
//   clearInterval(intervalId);
}



</script>

<!-- flash name -->
<script>
function Flash_section_name(section,command) {
  const targetElement_name = document.getElementById(section); // 替换为你的目标元素的 ID
  

  let isFlashing = false;
  function flashBackground() {
    isFlashing = !isFlashing;
    if (isFlashing) {
      // 渐变为红色

      targetElement_name.style.transition = 'background-color   0.4s ease-in-out';
      targetElement_name.style.backgroundColor   = 'red';
    } else {

      targetElement_name.style.transition = 'background-color   0.4s ease-in-out';
      targetElement_name.style.backgroundColor   = '#485464';
    //   targetElement_name.style.backgroundColor   = 'black';

    }
    if(isFlashing==true)
    Flash_section_name_time=setTimeout(flashBackground, 400);
  }
  if(command=="flash"){
    flashBackground();
    
  }
  else if(command=="click"){
    targetElement_name.style.color  = 'red';
    targetElement_name.style.backgroundColor  = 'red';
  }
  else if(command=="stop"){
    targetElement_name.style.color  = 'white';
    targetElement_name.style.backgroundColor   = '#485464';
    // targetElement_name.style.backgroundColor   = 'black';


  }
}

    </script>


<!-- flash alarm -->
<script>
function Flash_section_alarm(section,command) {
  const targetElement_alarm = document.getElementById(section); // 替换为你的目标元素的 ID

  let isFlashing = false;
  function flashBackground() {
    isFlashing = !isFlashing;
    if (isFlashing) {
      // 渐变为红色
      targetElement_alarm.style.border  = '3px solid red';
      targetElement_alarm.style.transition = 'background-color  0.4s ease-in-out';
    //   targetElement_alarm.style.backgroundColor   = 'rgba(255, 0, 0, 0.3)';

    } else {
        targetElement_alarm.style.border  = '3px dashed orange';
        targetElement_alarm.style.transition = 'background-color  0.4s ease-in-out';
        // targetElement_alarm.style.backgroundColor   = '#485464';

    }
    if(isFlashing==true)
    Flash_section_alarm_time=setTimeout(flashBackground, 400);
  }
  if(command=="flash"){
    flashBackground();
    
  }
  else if(command=="click"){
    targetElement_alarm.style.border  = '3px solid red';
    targetElement_alarm.style.backgroundColor   = 'rgba(255, 0, 0, 0.3)';


  }
  else if(command=="stop"){

    targetElement_alarm.style.border  = '3px dashed orange';
    targetElement_alarm.style.backgroundColor   = '#485464';
    // targetElement_alarm.style.backgroundColor   = 'black';



  }
}

    </script>

    
<!-- flash section -->
<script>
function Flash_section_section(section,command) {
  const targetElement_section = document.getElementById(section); // 替换为你的目标元素的 ID

  let isFlashing = false;
  function flashBackground() {
    isFlashing = !isFlashing;
    if (isFlashing) {
      // 渐变为红色

    //   targetElement_section.style.transition = 'border 0.4s ease-in-out';
      targetElement_section.style.border  = '15px solid red';

    } else {
        // targetElement_section.style.transition = 'border 0.4s ease-in-out';
        targetElement_section.style.border  = '1px solid #ccc';

    }
    if(isFlashing==true)
    Flash_section_section_time=setTimeout(flashBackground, 400);
  }
  if(command=="flash"){
    flashBackground();
    
  }
  else if(command=="click"){
    targetElement_section.style.border  = '15px solid red';
  }
  else if(command=="stop"){

    targetElement_section.style.border  = '1px solid #ccc';

  }
}

    </script>
<!-- 2background -->


<!-- 用網址GET機台 -->

<?php
$machine1 = $_GET['machine1'];
$machine2 = $_GET['machine2'];
// $machine3 = $_GET['machine3'];
// $machine4 = $_GET['machine4'];
// var_dump($machine1); // 输出 "value1"
// var_dump($machine2); // 输出 "value1"
?>


<!-- 1 -->

<script>


    var queryString = window.location.search;
  function ajaxCall_1(machine) {
        $.ajax({

            url: "Dyeing_machine_visualization_ajax_v4.php" + queryString, 
            type: "POST",
            dataType: "json", // 预期的响应数据类型
            data: {
               machine:machine
            },
            success: function(response) {
                // $("#Daluen-result").html(response); 
                // console.log(response);
                // console.log(response['Name1']);


                var Name_1 = document.getElementById("Name_1");
                var MainTemperature_1 = document.getElementById("MainTemperature_1");
                var Dyelot_1 = document.getElementById("Dyelot_1");
                var Program_1 = document.getElementById("Program_1");
                var TotalWeight_1 = document.getElementById("TotalWeight_1");
                var FabricName_1 = document.getElementById("FabricName_1");
                // var alarm_type1_1 = document.getElementById("alarm_type1_1");
                var alarm_type1_7 = document.getElementById("alarm_type1_7");


                Name_1.innerHTML = response['Name'];
                MainTemperature_1.innerHTML = "<?php echo $lang->line("general.main_cylinder_temperature"); ?>: "+response['MainTemperature']+"°C";
                Dyelot_1.innerHTML = "<?php echo $lang->line("general.material_requisition_form"); ?>: "+response['Dyelot'];
                Program_1.innerHTML = "<?php echo $lang->line("general.dyeing_process"); ?>: "+response['Program'];
                TotalWeight_1.innerHTML = "<?php echo $lang->line("general.loading_quantity"); ?>:"+response['TotalWeight'];
                FabricName_1.innerHTML = "<?php echo $lang->line("general.fabric_type"); ?>: "+response['FabricName'];

                // console.log((response['ActiveAlarms'].length ));
                // alarm_type1_1.style.backgroundColor = 'orange';
                
                if(response['Description']=='dongbao'){
                    document.getElementById("outer_div_dongbao_1").style.display='block';
                    document.getElementById("outer_div_danny_1").style.display='none';
                    document.getElementById("outer_div_dongbao_CycleTime_1_1").innerHTML=response['CycleTime1'];
                    document.getElementById("outer_div_dongbao_CycleTime_1_2").innerHTML=response['CycleTime2'];
                    document.getElementById("outer_div_dongbao_CycleTime_1_3").innerHTML=response['CycleTime3'];
                    document.getElementById("outer_div_dongbao_CycleTime_1_4").innerHTML=response['CycleTime4'];
                    document.getElementById("outer_div_dongbao_Rope_1_1").innerHTML=response['RopeSpeed1'];
                    document.getElementById("outer_div_dongbao_Rope_1_2").innerHTML=response['RopeSpeed2'];
                    document.getElementById("outer_div_dongbao_Rope_1_3").innerHTML=response['RopeSpeed3'];
                    document.getElementById("outer_div_dongbao_Rope_1_4").innerHTML=response['RopeSpeed4'];
                    document.getElementById("alarm_type1_3").innerHTML="烘機檢測";
                    
                }
                else if(response['Description']=='danny'){
                    document.getElementById("outer_div_dongbao_1").style.display='none';
                    document.getElementById("outer_div_danny_1").style.display='block';
                    document.getElementById("outer_div_danny_CycleTime_1_1").innerHTML=response['CycleTime1'];
                    document.getElementById("outer_div_danny_CycleTime_1_2").innerHTML=response['CycleTime2'];
                    document.getElementById("outer_div_danny_CycleTime_1_3").innerHTML=response['CycleTime3'];
                    document.getElementById("outer_div_danny_CycleTime_1_4").innerHTML=response['CycleTime4'];
                    document.getElementById("outer_div_danny_Rope_1_1").innerHTML=response['RopeSpeed1'];
                    document.getElementById("outer_div_danny_Rope_1_2").innerHTML=response['RopeSpeed2'];
                    document.getElementById("outer_div_danny_Rope_1_3").innerHTML=response['RopeSpeed3'];
                    document.getElementById("outer_div_danny_Rope_1_4").innerHTML=response['RopeSpeed4'];
                    document.getElementById("alarm_type1_3").innerHTML="<?php echo $lang->line("general.nozzle_opening_detection"); ?>";
                }
                // 出布時間還沒到
                // starttime1_1='2023-10-25 12:20:14.000';
                // endtime1_1='2023-10-25 12:30:14.000';
                
                    startCountdown(response['出布開始'], response['出布結束'], circleElements1_1, timerElements1_1,'出布');
                    // timer1_1=timer1_1_ajax;

                    
                
                // starttime1_2='2023-10-25 11:00:14.000';
                // endtime1_2='2023-10-25 12:00:14.000';
                
                    startCountdown(response['備藥開始'], response['備藥結束'], circleElements1_2, timerElements1_2,'備藥');
                    // timer1_2=timer1_2_ajax;
                

                // starttime1_3='2023-10-24 17:10:14.000';
                // endtime1_3='2023-10-24 18:00:14.000';
                
                    startCountdown(response['化料開始'], response['化料結束'], circleElements1_3, timerElements1_3,'化料');
                    // timer1_3=timer1_3_ajax;
                

                if(response['ActiveAlarms'] !='' &&response['ActiveAlarms'] !='          '){
                    // Flash_section('section1',"flash");  
                    Flash_section_name("Name_1","flash");
                    var options = response['ActiveAlarms'].split(',');
                    alarm_type1_7.innerHTML ="<?php echo $lang->line("general.other"); ?>: ";

                    // var options = ['烘機檢測'];
                        options.forEach(function(option) {
                            switch (option) {
                                case '機台轉數異常':
                                    Flash_section_alarm("alarm_type1_1","flash");
                                    break;
                                case '圈時異常':
                                    Flash_section_alarm("alarm_type1_2","flash");
                                    break;
                                case '噴嘴開度檢測':
                                    Flash_section_alarm("alarm_type1_3","flash");
                                    break;
                                case '蒸氣不足':
                                    Flash_section_alarm("alarm_type1_4","flash");
                                    break;
                                case '降溫不足':
                                    Flash_section_alarm("alarm_type1_5","flash");
                                    break;
                                case '延遲加料':
                                    Flash_section_alarm("alarm_type1_6","flash");
                                    break;
                                case '烘機檢測':
                                    Flash_section_alarm("alarm_type1_3","flash");
                                    break;
                                default:
                                    Flash_section_alarm("alarm_type1_7","flash");
                                    alarm_type1_7.innerHTML += option+"&nbsp;";

                            }
                        });
                    Flash_section_section("section1","flash");

                    // alarm_type1_7.innerHTML ="異常";

                }
                else
                {
                    // Flash_section('section1',"stop");
                    Flash_section_name("Name_1","stop");
                    Flash_section_alarm("alarm_type1_1","stop");
                    Flash_section_alarm("alarm_type1_2","stop");
                    Flash_section_alarm("alarm_type1_3","stop");
                    Flash_section_alarm("alarm_type1_4","stop");
                    Flash_section_alarm("alarm_type1_5","stop");
                    Flash_section_alarm("alarm_type1_6","stop");
                    Flash_section_alarm("alarm_type1_7","stop");
                    Flash_section_section("section1","stop");


                    alarm_type1_7.innerHTML ="無異常";
                }
            },
            error: function(err) {
                // $("#Daluen-result").html(err);
            },
 
        });
//         setTimeout(function () {
//     ajaxCall_1('<?php echo $machine1; ?>')
//   }, 1000);
}
    ajaxCall_1('<?php echo $machine1; ?>');
    var ajaxCall_1_time=setInterval(function () {
        ajaxCall_1('<?php echo $machine1; ?>');
        // console.log("ajaxCall_2_time");
}, 1000);
    
  

</script>




<!-- 2 -->

<script>


  function ajaxCall_2(machine) {
        $.ajax({

            url: "Dyeing_machine_visualization_ajax_v4.php" + queryString, 
            type: "POST",
            dataType: "json", // 预期的响应数据类型
            data: {
               machine:machine
            },
            success: function(response) {
                // $("#Daluen-result").html(response); 
                // console.log(response);
                // console.log(response['Name1']);
                

                var Name_2 = document.getElementById("Name_2");
                var MainTemperature_2 = document.getElementById("MainTemperature_2");
                var Dyelot_2 = document.getElementById("Dyelot_2");
                var Program_2 = document.getElementById("Program_2");
                var TotalWeight_2 = document.getElementById("TotalWeight_2");
                var FabricName_2 = document.getElementById("FabricName_2");
                // var alarm_type1_1 = document.getElementById("alarm_type1_1");
                var alarm_type2_7 = document.getElementById("alarm_type2_7");

                Name_2.innerHTML = response['Name'];
                MainTemperature_2.innerHTML = "<?php echo $lang->line("general.main_cylinder_temperature"); ?>: "+response['MainTemperature']+"°C";
                Dyelot_2.innerHTML = "<?php echo $lang->line("general.material_requisition_form"); ?>: "+response['Dyelot'];
                Program_2.innerHTML = "<?php echo $lang->line("general.dyeing_process"); ?>: "+response['Program'];
                TotalWeight_2.innerHTML = "<?php echo $lang->line("general.loading_quantity"); ?>:"+response['TotalWeight'];
                FabricName_2.innerHTML = "<?php echo $lang->line("general.fabric_type"); ?>: "+response['FabricName'];

                if(response['Description']=='dongbao'){
                    document.getElementById("outer_div_dongbao_2").style.display='block';
                    document.getElementById("outer_div_danny_2").style.display='none';
                    document.getElementById("outer_div_dongbao_Rope_2_1").innerHTML=response['RopeSpeed1'];
                    document.getElementById("outer_div_dongbao_Rope_2_2").innerHTML=response['RopeSpeed2'];
                    document.getElementById("outer_div_dongbao_Rope_2_3").innerHTML=response['RopeSpeed3'];
                    document.getElementById("outer_div_dongbao_Rope_2_4").innerHTML=response['RopeSpeed4'];
                    document.getElementById("outer_div_dongbao_CycleTime_2_1").innerHTML=response['CycleTime1'];
                    document.getElementById("outer_div_dongbao_CycleTime_2_2").innerHTML=response['CycleTime2'];
                    document.getElementById("outer_div_dongbao_CycleTime_2_3").innerHTML=response['CycleTime3'];
                    document.getElementById("outer_div_dongbao_CycleTime_2_4").innerHTML=response['CycleTime4'];
                    
                    document.getElementById("alarm_type2_3").innerHTML="烘機檢測";
                    
                }
                else if(response['Description']=='danny'){
                    document.getElementById("outer_div_dongbao_2").style.display='none';
                    document.getElementById("outer_div_danny_2").style.display='block';
                    document.getElementById("outer_div_danny_Rope_2_1").innerHTML=response['RopeSpeed1'];
                    document.getElementById("outer_div_danny_Rope_2_2").innerHTML=response['RopeSpeed2'];
                    document.getElementById("outer_div_danny_Rope_2_3").innerHTML=response['RopeSpeed3'];
                    document.getElementById("outer_div_danny_Rope_2_4").innerHTML=response['RopeSpeed4'];
                    document.getElementById("outer_div_danny_CycleTime_2_1").innerHTML=response['CycleTime1'];
                    document.getElementById("outer_div_danny_CycleTime_2_2").innerHTML=response['CycleTime2'];
                    document.getElementById("outer_div_danny_CycleTime_2_3").innerHTML=response['CycleTime3'];
                    document.getElementById("outer_div_danny_CycleTime_2_4").innerHTML=response['CycleTime4'];
                    document.getElementById("alarm_type2_3").innerHTML="<?php echo $lang->line("general.nozzle_opening_detection"); ?>";
                }


                // timer2_1_ajax = 36584;
                // if(timer2_1_ajax!=timer2_1){
                    startCountdown(response['出布開始'], response['出布結束'], circleElements2_1, timerElements2_1,'出布');
                //     timer2_1=timer2_1_ajax;

                    
                // }
                // timer2_2_ajax = 80;
                // if(timer2_2_ajax!=timer2_2){
                    startCountdown(response['備藥開始'], response['備藥結束'], circleElements2_2, timerElements2_2,'備藥');
                //     timer2_2=timer2_2_ajax;
                // }

                // timer2_3_ajax = 20;
                // if(timer2_3_ajax!=timer2_3){
                    startCountdown(response['化料開始'], response['化料結束'], circleElements2_3, timerElements2_3,'化料');
                //     timer2_3=timer2_3_ajax;
                // }

                // alarm_type1_1.style.backgroundColor = 'orange';
                if(response['ActiveAlarms'] !='' &&response['ActiveAlarms'] !='          '){
                    // Flash_section('section1',"flash");  
                    Flash_section_name("Name_2","flash");
                    var options = response['ActiveAlarms'].split(',');
                    alarm_type2_7.innerHTML ="<?php echo $lang->line("general.other"); ?>: ";

                    // var options = ['烘機檢測'];
                        options.forEach(function(option) {
                            switch (option) {
                                case '機台轉數異常':
                                    Flash_section_alarm("alarm_type2_1","flash");
                                    break;
                                case '圈時異常':
                                    Flash_section_alarm("alarm_type2_2","flash");
                                    break;
                                case '噴嘴開度檢測':
                                    Flash_section_alarm("alarm_type2_3","flash");
                                    break;
                                case '蒸氣不足':
                                    Flash_section_alarm("alarm_type2_4","flash");
                                    break;
                                case '降溫不足':
                                    Flash_section_alarm("alarm_type2_5","flash");
                                    break;
                                case '延遲加料':
                                    Flash_section_alarm("alarm_type2_6","flash");
                                    break;
                                case '烘機檢測':
                                    Flash_section_alarm("alarm_type2_3","flash");
                                    break;
                                default:
                                    Flash_section_alarm("alarm_type2_7","flash");
                                    alarm_type2_7.innerHTML += option+"&nbsp;";

                            }
                        });
                    Flash_section_section("section2","flash");

                    // alarm_type2_7.innerHTML ="異常";

                }
                else
                {
                    // Flash_section("section2","stop");
                    Flash_section_name("Name_2","stop");
                    Flash_section_alarm("alarm_type2_1","stop");
                    Flash_section_alarm("alarm_type2_2","stop");
                    Flash_section_alarm("alarm_type2_3","stop");
                    Flash_section_alarm("alarm_type2_4","stop");
                    Flash_section_alarm("alarm_type2_5","stop");
                    Flash_section_alarm("alarm_type2_6","stop");
                    Flash_section_alarm("alarm_type2_7","stop");
                    Flash_section_section("section2","stop");

                    alarm_type2_7.innerHTML ="無異常";
                }
            },
            error: function(err) {
                // $("#Daluen-result").html(err);
            },
 
        });
//         setTimeout(function () {
//     ajaxCall_2('<?php echo $machine2; ?>')
//   }, 1000);
}
    ajaxCall_2('<?php echo $machine2; ?>');
    var ajaxCall_2_time=setInterval(function () {
        ajaxCall_2('<?php echo $machine2; ?>');
        // console.log("ajaxCall_2_time");

        LA838LiveScreen()
}, 1000);
    


</script>



<script>
    LA838LiveScreen()
    
    // 換LA838Live圖，有資料就用圖片
  function LA838LiveScreen() {
    
<?php

// $machine1 = "下載";
// $machine2 = "下載";
$machine1 = $_GET['machine1'];
$machine2 = $_GET['machine2'];

// C:/LA/LA838LiveScreen/H01.jpg

// 本地文件路径
// $localImagePath_1 = 'C:/Users/sam.yang/Desktop/流亞的/共享測試/'.$machine1.'.png';
// $localImagePath_2 = 'C:/Users/sam.yang/Desktop/流亞的/共享測試/'.$machine2.'.png';
$localImagePath_1 = 'C:/LA/LA838LiveScreen/' . $machine1 . '.jpg';
$localImagePath_2 = 'C:/LA/LA838LiveScreen/' . $machine2 . '.jpg';


// Web访问路径
// $imageUrl_1 = 'http://localhost:8080/Users/sam.yang/Desktop/流亞的/共享測試/'.$machine1.'.png';
// $imageUrl_2 = 'http://localhost:8080/Users/sam.yang/Desktop/流亞的/共享測試/'.$machine2.'.png';
// $imageUrl_1 = 'http://localhost:8080/LA/LA838LiveScreen/'.$machine1.'.jpg';
// $imageUrl_2 = 'http://localhost:8080/LA/LA838LiveScreen/'.$machine2.'.jpg';
$imageUrl_1 = '../../../../LA/LA838LiveScreen/' . $machine1 . '.jpg';
$imageUrl_2 = '../../../../LA/LA838LiveScreen/' . $machine2 . '.jpg';

// 判断文件是否存在
if (file_exists($localImagePath_1)) {
    $imageSrc_1 = $imageUrl_1;
    $table_danny_1_display = "none";
} else {
    // 文件不存在时的占位符图像路径
    $imageSrc_1 = './丹尼染機模擬圖.png'; // 请替换为实际的占位符图像路径
    $table_danny_1_display = "table";

}

if (file_exists($localImagePath_2)) {
    $imageSrc_2 = $imageUrl_2;
    $table_danny_2_display = "none";

} else {
    // 文件不存在时的占位符图像路径
    $imageSrc_2 = './丹尼染機模擬圖.png'; // 请替换为实际的占位符图像路径
    $table_danny_2_display = "table";

}
?>

    
        // 使用JavaScript动态更改图像的src属性
        // document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('outer_div_danny_1').getElementsByClassName('img_danny')[0].src = '<?php echo $imageSrc_1; ?>';
            document.getElementById('outer_div_danny_1').getElementsByClassName('table_danny')[0].style.display  = '<?php echo $table_danny_1_display; ?>';
            document.getElementById('outer_div_danny_2').getElementsByClassName('img_danny')[0].src = '<?php echo $imageSrc_2; ?>';
            document.getElementById('outer_div_danny_2').getElementsByClassName('table_danny')[0].style.display  = '<?php echo $table_danny_2_display; ?>';

        // });

}
    </script>

