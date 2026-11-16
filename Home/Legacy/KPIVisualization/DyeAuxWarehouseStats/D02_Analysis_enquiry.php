<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
    <meta charset="UTF-8">
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Include the JS for AG Grid -->
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
    <!-- <script src="https://code.highcharts.com/highcharts-3d.js"></script> -->
    <!-- 引入 Highcharts 主题 -->
    <!-- <script src="https://code.highcharts.com/themes/high-contrast-light.js"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

    <title></title>

    <style>
        html,
        body {
            background-image: url(/common/images/bg1.png);
            background-size: 100% 100%;
            /* 保持图像完全填充整个页面 */
            background-repeat: no-repeat;
            background-attachment: fixed;


        }

        a,
        .tog,
        body {
            font-size: 24px;

        }

        .loading {
            display: none;
            /* 預設隱藏 */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            font-size: 16px;
            border-radius: 5px;
            z-index: 9999;
        }

        .checkbox_css {
            color: white;
            /* font-size: 1vw; */
        }

        #timeRange {
            margin-top: 2vh;
        }

        /* 允许自动换行 */
        /* .container {
      width: 100%;
      display: flex;
      flex-wrap: wrap;

    } */
        /* 确保 padding 不会增加元素的宽度 */

        /* .column {
      flex: 1;
      padding: 20px;
      border: 1px solid #ddd;
      text-align: center;
      box-sizing: border-box;
    } */

        Button {
            background-color: #4372c3;
            /* 绿色背景 */
            color: white;
            /* 白色文字 */
            border: none;
            /* 无边框 */
            border-radius: 5px;
            /* 圆角 */
            cursor: pointer;
            /* 鼠标指针样式 */

            transition: background-color 0.3s;
            /* 添加背景颜色变化的过渡效果 */
        }

        Button:hover {
            background-color: #224197;
            /* 鼠标悬停时的背景颜色 */
        }

        .tog {
            display: none;
            color: white;
            /* 初始显示 */
        }
    </style>

    <!-- 選單li的樣式 -->
    <style>
        /* 样式可以根据需要自定义 */


        #FabricName_UL,
        #work_no_UL {
            list-style-type: none;
            padding: 0;
            position: absolute;
            margin-left: 3vw;
            max-height: 300px;
            /* 设置最大高度 */
            min-width: 200px;
            max-width: 500px;
            overflow-y: auto;
            /* 如果内容超过最大高度，则显示滚动条 */
            color: black;
            z-index: 99;
        }

        #FabricName_UL li,
        #work_no_UL li {
            /* padding: 10px; */
            /* background-color: #f9f9f9; */
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            cursor: pointer;

        }

        #FabricName_UL li:hover,
        #work_no_UL li:hover {
            background-color: #ddd;
        }


        .ag-theme-alpine {
            --ag-foreground-color: white;
            /*字*/
            --ag-background-color: rgba(26, 46, 57, 0.5);
            --ag-header-foreground-color: white;
            /*上面的字*/
            --ag-header-background-color: rgba(14, 86, 114, 0.8);
            --ag-odd-row-background-color: rgba(14, 86, 114, 0.5);
            --ag-header-column-resize-handle-color: rgba(0, 238, 238, 0.3);

            --ag-font-size: 20px;
            --ag-font-family: monospace;
        }

        .ag-cell {
            font-size: 24px;
            /* 调整数值以增加或减小字体大小 */
        }

        .ag-header-cell-label {
            font-size: 24px;
        }

        .cell-span {
            background-color: rgba(14, 86, 114, 1);



        }
    </style>
</head>

<body>
    <div class=" mdl-js-layout  mdl-layout--fixed-header is-small-screen">

        <div class="loading">載入中...</div>

        <button id='toggleButton'>設定搜尋條件</button>

        <div id="timeRange" class='tog'>


            <table>

                <tr>
                    <td>
                        <label for="startDateTime">查詢日期：</label>
                    </td>
                    <td>
                        <input type="date" id="startDateTime" style="display:none">
                        <!-- <label for="endDateTime">-</label> -->
                        <input type="date" id="endDateTime">&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
                <tr style="display:none">
                    <td>時間單位：</td>
                    <td><select id="DDDD_1"></td>
                </tr>
            </table>

            <!-- 選擇機台<br> -->
            <div id="checkboxContainer1" style="display:none"></div>
            <!-- 選擇缸號<br> -->
            <div id="checkboxContainer2" style="display:none"></div>

            <br>
            &nbsp;&nbsp;&nbsp;<button onclick="saveTimeRange()">尋找</button>
            <br>
            <br><a href="#" id="downloadLink1" style="color: #00bcd4;">D02扣帳染助劑統計下載</a>
            <!-- <br><a href="#" id="downloadLink2" style="color: #00bcd4;">供料輸送時間下載</a> -->
            <!-- <br><a href="#" id="downloadLink3" style="color: #00bcd4;">化驗室色號打杯數統計下載</a>
      <br><a href="#" id="downloadLink4" style="color: #00bcd4;">化驗室各品名用量表下載</a>
      <br><a href="#" id="downloadLink5" style="color: #00bcd4;">LA600 打色業績下載</a> -->
            <!-- <br><a href="#" id="downloadLink6" style="color: #00bcd4;">打色成功率下載</a> -->

        </div>


        &nbsp;



        <div id="Daluen-result_1"></div>
        <div id="Daluen-result_2"></div>
        <!-- <div id="Daluen-result_3"></div> -->
        <!-- <div id="Daluen-result_4"></div> -->
        <!-- <div id="Daluen-result_5"></div> -->
        <!-- <div id="Daluen-result_6"></div> -->



    </div>
    <!-- </div> -->
    <br></br>

</body>

</html>



<!-- 設定隱藏 -->
<script>
    const queryString = window.location.search;
    $("#toggleButton").click(function () {
        // $(".tog").toggle();
        $(".tog").slideToggle("slow");

    });
</script>

<!-- 選時間 -->
<script>
    // 获取当前日期
    document.getElementById("startDateTime").valueAsDate = new Date(new Date().setDate(new Date().getDate() - 10));
    document.getElementById("endDateTime").valueAsDate = new Date(new Date().setDate(new Date().getDate()));
</script>

<!-- 選工單 -->
<!-- <script>
    function work_no_filterList() {



      $.ajax({
        beforeSend: function () {
          // $('.loading').fadeIn(300);
        },
        url: "Overtime_Analysis_find_work_no_ajax.php",
        type: "POST",
        dataType: "json",
        data: {

          startDateTime: document.getElementById("startDateTime").value,
          endDateTime: document.getElementById("endDateTime").value,
          work_no_Input: document.getElementById("work_no_Input").value,
          FabricName_Input: document.getElementById("FabricName_Input").value,

        },
        success: function (response) {
          // $("#Daluen-result_1").html(response);
          $("#work_no_UL").empty();

          response.forEach(function (item) {
            $("#work_no_UL").append("<li>" + item + "</li>");
          });
        },
        error: function (err) {
          // $("#Daluen-result_1").html(err);
        },
        complete: function () {
          // $('.loading').fadeOut(300);

          var input, filter, ul, li, txtValue, noMatches;
          input = document.getElementById('work_no_Input');
          filter = input.value.toUpperCase();
          ul = document.getElementById("work_no_UL");
          li = ul.getElementsByTagName('li');
          noMatches = true; // 默认没有匹配项

          // 循环列表，显示匹配项，隐藏非匹配项
          for (var i = 0; i < li.length; i++) {
            txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
              noMatches = false; // 存在匹配项
            } else {
              li[i].style.display = "none";
            }
          }

          // 如果没有匹配项，保持列表显示
          if (noMatches) {
            ul.style.display = "none";
          } else {
            ul.style.display = ""; // 显示列表
          }


          // 添加事件监听器来监听列表项的点击事件
          var listItems = document.querySelectorAll("#work_no_UL li");
          listItems.forEach(function (item) {
            item.addEventListener("click", function () {
              var input = document.getElementById("work_no_Input");
              input.value = this.textContent; // 将列表项的文本内容设置为输入框的值
              document.getElementById("work_no_UL").style.display = "none"; // 点击列表项后隐藏列表

              updateDownloadLink();


            });
          });
          var firstInput = true;
          // 输入框获得焦点时显示全部列表项
          document.getElementById('work_no_Input').addEventListener('focus', function () {
            if (firstInput) {
              document.getElementById('work_no_Input').value = "";
              firstInput = false;
            }
            document.getElementById("work_no_UL").style.display = "";


          });


        }
      });




    }



  </script> -->

<!-- 選FabricName -->

<!-- <script>
    function FabricName_filterList() {



      $.ajax({
        beforeSend: function () {
          // $('.loading').fadeIn(300);
        },
        url: "Overtime_Analysis_find_FabricName_ajax.php",
        type: "POST",
        dataType: "json",
        data: {

          startDateTime: document.getElementById("startDateTime").value,
          endDateTime: document.getElementById("endDateTime").value,
          work_no_Input: document.getElementById("work_no_Input").value,
          FabricName_Input: document.getElementById("FabricName_Input").value,
        },
        success: function (response) {
          // $("#Daluen-result_1").html(response);
          $("#FabricName_UL").empty();

          response.forEach(function (item) {
            $("#FabricName_UL").append("<li>" + item + "</li>");
          });
        },
        error: function (err) {
          // $("#Daluen-result_1").html(err);
        },
        complete: function () {
          // $('.loading').fadeOut(300);

          var input, filter, ul, li, txtValue, noMatches;
          input = document.getElementById('FabricName_Input');
          filter = input.value.toUpperCase();
          ul = document.getElementById("FabricName_UL");
          li = ul.getElementsByTagName('li');
          noMatches = true; // 默认没有匹配项

          // 循环列表，显示匹配项，隐藏非匹配项
          for (var i = 0; i < li.length; i++) {
            txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
              noMatches = false; // 存在匹配项
            } else {
              li[i].style.display = "none";
            }
          }

          // 如果没有匹配项，保持列表显示
          if (noMatches) {
            ul.style.display = "none";
          } else {
            ul.style.display = ""; // 显示列表
          }


          // 添加事件监听器来监听列表项的点击事件
          var listItems = document.querySelectorAll("#FabricName_UL li");
          listItems.forEach(function (item) {
            item.addEventListener("click", function () {
              var input = document.getElementById("FabricName_Input");
              input.value = this.textContent; // 将列表项的文本内容设置为输入框的值
              document.getElementById("FabricName_UL").style.display = "none"; // 点击列表项后隐藏列表

              updateDownloadLink();
            });
          });
          var firstInput = true;
          // 输入框获得焦点时显示全部列表项
          document.getElementById('FabricName_Input').addEventListener('focus', function () {
            if (firstInput) {
              document.getElementById('FabricName_Input').value = "";
              firstInput = false;
            }
            document.getElementById("FabricName_UL").style.display = "";
          });


        }
      });



      updateDownloadLink();

    }



  </script> -->

<!-- 建立選單 -->
<script>
    function Create_menu_DDDD(options, options_value, optionsID) {
        // 創建 select 元素
        $(optionsID).empty();
        var selectElement = $(optionsID);
        //   var selectElement = $("<select>").attr("id", "selectedOptionw_machine_number");
        // 定義選項
        //   var options = ["1", "2", "3"];
        // 透過迴圈創建選項並添加到 select 元素中


        <?php
        // require("connect.php");
        
        // $connectionInfo = array("Database" => $Database, "UID" => $UID, "PWD" => $PWD, "CharacterSet" => "UTF-8", "ReturnDatesAsStrings" => true);
// $conn = sqlsrv_connect($serverName, $connectionInfo);
// $sql = " SELECT distinct(Warehouse) as 'Warehouse'
//  FROM [Daluen_IDCC].[dbo].[Daluen_cqcr900]
//  where Warehouse like 'D%'
//  order by Warehouse
// ";
// $stmt = sqlsrv_query($conn, $sql);
// if ($stmt === false) {
//   die(print_r(sqlsrv_errors(), true));
// }
// while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//   $options[] = $row['Warehouse'];
// }
        
        //   sqlsrv_close($conn);
        ?>

        // options=<?//php echo json_encode($options);           ?>;
        // options.unshift("D1202+D1204");

        // console.log(optionsID);
        // if(optionsID=='#DDDD_1')
        //   moveToFront(options,'D1202');
        // if(optionsID=='#DDDD_2')
        //   moveToFront(options,'D1204');

        options_value = options


        for (var i = 0; i < options.length; i++) {
            var optionElement = $("<option>").val(options_value[i]).text(options[i]);
            selectElement.append(optionElement);
        }
        // 創建 label 元素
        //   var labelElement = $("<label>").attr("for", "selected_Calculation_Number").text("機台：");
        // 將 label 和 select 元素添加到現有的 div 中
        $(optionsID).append(selectElement);

    }
    Create_menu_DDDD(["日", "周", "月"], ["日", "周", "月"], "#DDDD_1");
</script>
<!-- 呼叫ajax -->
<script>
    function ajaxCall_1() {
        // console.log(Array.from(document.querySelectorAll('#checkboxContainer1  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value))
        // console.log(Array.from(document.querySelectorAll('#checkboxContainer2  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value))


        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "D02_Analysis_enquiry_1_ajax.php" + queryString,
            type: "POST",
            data: {

                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,
                time_unit: document.getElementById('DDDD_1').value,
                machine_checkbox: Array.from(document.querySelectorAll('#checkboxContainer1  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value),
                batch_checkbox: Array.from(document.querySelectorAll('#checkboxContainer2  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value),
                // work_no_Input: document.getElementById("work_no_Input").value,
                // FabricName_Input: document.getElementById("FabricName_Input").value,


            },
            success: function (response) {
                $("#Daluen-result_1").html(response);
            },
            error: function (err) {
                $("#Daluen-result_1").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }


    function ajaxCall_2() {
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "NNW_Analysis_enquiry_2_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,


            },
            success: function (response) {
                $("#Daluen-result_2").html(response);
            },
            error: function (err) {
                $("#Daluen-result_2").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }


    function ajaxCall_3() {
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "NNW_Analysis_enquiry_3_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,
                time_unit: document.getElementById('DDDD_1').value,


            },
            success: function (response) {
                $("#Daluen-result_3").html(response);
            },
            error: function (err) {
                $("#Daluen-result_3").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }

    function ajaxCall_4() {
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "NNW_Analysis_enquiry_4_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,


            },
            success: function (response) {
                $("#Daluen-result_4").html(response);
            },
            error: function (err) {
                $("#Daluen-result_4").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }

    function ajaxCall_5() {
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "NNW_Analysis_enquiry_5_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,


            },
            success: function (response) {
                $("#Daluen-result_5").html(response);
            },
            error: function (err) {
                $("#Daluen-result_5").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }

    function ajaxCall_6() {
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: "NNW_Analysis_enquiry_6_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,


            },
            success: function (response) {
                $("#Daluen-result_6").html(response);
            },
            error: function (err) {
                $("#Daluen-result_6").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);


            }
        });

    }
</script>
<!-- checkbox1 -->


<script>
    function addCheckboxes(id) {

        $.ajax({

            url: "D02_Analysis_find_machine_option_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,

            },
            success: function (response) {

                // options=<?php echo json_encode($options); ?>;
                // options.unshift("D1202+D1204");

                options = JSON.parse(response);
                // console.log(response);
                // break;
                // if(optionsID=='#DDDD_1')
                //   moveToFront(options,'D1202');
                // if(optionsID=='#DDDD_2')
                //   moveToFront(options,'D1204');
                var container1 = document.getElementById(id);
                container1.innerHTML = '';
                // 遍历数组中的每个选项，并为每个选项创建一个复选框和相应的标签
                options.forEach(function (option, index) {
                    // 创建复选框元素
                    var checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = option; // 设置复选框的名称
                    checkbox.value = option; // 设置复选框的值
                    if (index <= 10) {
                        checkbox.checked = true;
                    }
                    // 创建标签元素用于显示文本
                    var label = document.createElement('label');
                    label.appendChild(document.createTextNode(option));
                    // label.appendChild(document.createTextNode(" "));
                    label.style.marginRight = '10px';
                    label.style.width = '100px';

                    // 将复选框和标签添加到容器中
                    container1.appendChild(checkbox);
                    container1.appendChild(label);

                    // 添加换行符
                    if (index % 10 == 9)
                        container1.appendChild(document.createElement('br'));
                    // container.appendChild(document.createElement('<>'));
                });



            },
            error: function (err) {
            },
            complete: function () {


            }
        });



    }


    function addCheckboxes2(id) {

        $.ajax({

            url: "D02_Analysis_find_batch_option_ajax.php" + queryString,
            type: "POST",
            data: {
                startDateTime: document.getElementById("startDateTime").value,
                endDateTime: document.getElementById("endDateTime").value,

            },
            success: function (response) {

                // options=<?php echo json_encode($options); ?>;
                // options.unshift("D1202+D1204");

                options = JSON.parse(response);
                // console.log(response);
                // break;
                // if(optionsID=='#DDDD_1')
                //   moveToFront(options,'D1202');
                // if(optionsID=='#DDDD_2')
                //   moveToFront(options,'D1204');
                var container1 = document.getElementById(id);
                container1.innerHTML = '';
                // 遍历数组中的每个选项，并为每个选项创建一个复选框和相应的标签
                options.forEach(function (option, index) {
                    // 创建复选框元素
                    var checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = option; // 设置复选框的名称
                    checkbox.value = option; // 设置复选框的值
                    if (index < 5) {
                        checkbox.checked = true;
                    }
                    // 创建标签元素用于显示文本
                    var label = document.createElement('label');
                    label.appendChild(document.createTextNode(option));
                    // label.appendChild(document.createTextNode(" "));
                    label.style.marginRight = '10px';
                    label.style.width = '100px';
                    // 将复选框和标签添加到容器中
                    container1.appendChild(checkbox);
                    container1.appendChild(label);

                    // 添加换行符
                    if (index % 10 == 9)
                        container1.appendChild(document.createElement('br'));
                    // container.appendChild(document.createElement('<>'));
                });



            },
            error: function (err) {
            },
            complete: function () {


            }
        });



    }
    document.getElementById('startDateTime').addEventListener('input', function () {
        // addCheckboxes('checkboxContainer1');
        // addCheckboxes2('checkboxContainer2');
    });

    document.getElementById('endDateTime').addEventListener('input', function () {
        // addCheckboxes('checkboxContainer1');
        // addCheckboxes2('checkboxContainer2');

    });



    // addCheckboxes('checkboxContainer1')
    // addCheckboxes2('checkboxContainer2')


</script>



<!-- 下載 -->
<script>
    // 添加 input1 的值變化事件監聽器
    document.getElementById('startDateTime').addEventListener('input', updateDownloadLink);
    // 添加 input2 的值變化事件監聽器
    document.getElementById('endDateTime').addEventListener('input', updateDownloadLink);
    document.getElementById('DDDD_1').addEventListener('input', updateDownloadLink);
    document.createElement('input').addEventListener('change', updateDownloadLink);
    document.getElementById('checkboxContainer1').addEventListener('change', updateDownloadLink);
    document.getElementById('checkboxContainer2').addEventListener('change', updateDownloadLink);
    document.getElementById('toggleButton').addEventListener('click', updateDownloadLink);

    // 添加 input3 的值變化事件監聽器
    // document.getElementById('work_no_Input').addEventListener('input', updateDownloadLink);
    // document.getElementById('FabricName_Input').addEventListener('input', updateDownloadLink);

    updateDownloadLink();
    // 定義值變化時的動作

    function updateDownloadLink() {
        document.getElementById('downloadLink1').href
            = "D02_Analysis_download_1_ajax.php" + queryString
            // + "startDateTime=" + document.getElementById('startDateTime').value
            + "&endDateTime=" + document.getElementById('endDateTime').value
            // + "&time_unit=" + document.getElementById('DDDD_1').value
            // + "&machine_checkbox=" + Array.from(document.querySelectorAll('#checkboxContainer1  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value)
            // + "&batch_checkbox=" + Array.from(document.querySelectorAll('#checkboxContainer2  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value)
            ;
        // console.log(Array.from(document.querySelectorAll('#checkboxContainer1  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value))
        // document.getElementById('downloadLink2').href
        //   = "NNW_Analysis_download_2_ajax.php?"
        //   + "startDateTime=" + document.getElementById('startDateTime').value
        //   + "&endDateTime=" + document.getElementById('endDateTime').value
        //   + "&time_unit=" + document.getElementById('DDDD_1').value
        //   ;

        // document.getElementById('downloadLink3').href
        // = "NNW_Analysis_download_3_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink4').href
        // = "NNW_Analysis_download_4_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink5').href
        // = "NNW_Analysis_download_5_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink6').href
        // = "NNW_Analysis_download_6_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;
    }
</script>
<script>
    function saveTimeRange() {

        ajaxCall_1();
        // ajaxCall_2();
        // ajaxCall_3();
        // ajaxCall_4();
        // ajaxCall_5();
        // ajaxCall_6();
    }




    ajaxCall_1();
    // ajaxCall_1();
    // ajaxCall_2();
    // ajaxCall_3();
    // ajaxCall_4();
    // ajaxCall_5();
    // ajaxCall_6();


</script>





<link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
<script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>