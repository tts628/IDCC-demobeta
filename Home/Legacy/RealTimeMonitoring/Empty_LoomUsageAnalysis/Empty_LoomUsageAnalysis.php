<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <title>空布車追蹤</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>


  <script src="https://code.highcharts.com/highcharts-more.js"></script>
  <!-- 下載圖片用 -->
  <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");
?>

  <style>
    body {

      background-image: url("/common/images/bg1.png");
      background-size: cover;
      background-repeat: repeat;
      height: 100%;
      width: 100%;
      /* overflow-y: hidden; */

    }

    img {
      width: 100px;
      height: 100px;

    }

    .highcharts-button-box {
      display: none;
    }

    .my-div {
      color: white;
      font-size: 45px;


      /* font-Weight: bold; */
      /* textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'; */
    }

    .my-unit-div {
      font-size: 30px;
    }

    .my-title-div {
      font-size: 40px;
    }



    .container-fluid {
      margin-top: 10px;
    }

    .card {
      display: flex;
      background-color: transparent;
      border: 3px double white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      /* height:900px; */
    }







    #container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .box {
      /* 其他樣式 */
      opacity: 0;
    }

    .box {
      /* display: flex; */
      /* justify-content: center;
  align-items: center; */

      text-align: left;
      /* 設置文字左右致中 */
      width: 320px;
      height: 282px;
      background-color: white;
      margin: 10px;
      padding-left: 20px;
      position: relative;
      box-sizing: border-box;
      border-radius: 20px;
      /* 設置圓角半徑為 10px */
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      /* 添加陰影效果 */
      line-height: 0.85;
      white-space: nowrap;
    }

    .machine {
      color: black;
      /* font-size: 30px; */
      font-size: 1.75vw;
      font-weight: bold;
      line-height: 1.2;
      margin-left: 1.2vw;
    }

    .label1 {
      width: 100px;
      height: 100px;
      font-size: 25px;
      /* 設置文字大小為 24 像素 */
      font-family: Arial, sans-serif;
      /* 設置字體為 Arial 或系統默認 sans-serif */
      font-weight: bold;
      /* 設置文字粗細為粗體 */
      /* color: white; 設置文字顏色為深灰色 */
      background-color: black;
      /* 設置背景顏色為淺灰色 */
      /* -webkit-text-stroke: 1px black; */
      /* padding: 5px; */
      /* border-radius: 5px; */
      /* padding: 5px 5px; */
      /* border-radius: 5px; 設置圓角為 5 像素 */
      /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);  */
      /* line-height: 0.5; */
    }

    .label2 {
      font-size: 1vw;
      /* 設置文字大小為 24 像素 */
      font-family: Arial, sans-serif;
      /* 設置字體為 Arial 或系統默認 sans-serif */

      color: black;
      /* 設置文字顏色為深灰色 */
      line-height: 1.4;
      /* text-align:left; */
      position: relative;
      /* 設置元素為相對定位 */
      top: -17px;


    }

    .label3 {
      font-size: 1vw;
      font-weight: 600;
    }

    .icon {
      width: 30px;
      height: 30px;
      position: absolute;
      top: 10px;
      left: 10px;
    }

    #pagination {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .page {
      display: inline-block;
      padding: 5px 10px;
      background-color: #eee;
      margin: 0 5px;
      cursor: pointer;
    }

    .active {
      background-color: #ccc;
    }


    @media screen and (max-width: 1090px) {


      .box {
        width: 100px;
        height: 120px;
      }

      .label1 {
        width: 100px;
        height: 100px;
        font-size: 10px;
        position: relative;
        top: -10px;


      }

      .label2 {
        font-size: 2vw;
        /* font-family: Arial, sans-serif;
        color: black;
        line-height: 1.2;
        position: relative;
        top: -30px; */


      }

      .label3 {
        font-size: 2vw;
        /* font-family: Arial, sans-serif;
        color: black;
        line-height: 1.2;
        position: relative;
        top: -30px; */


      }

      .icon {
        width: 15px;
        height: 15px;
        position: absolute;
        top: 10px;
        left: 10px;
      }

      .machine {
        color: black;
        font-size: 15px;
        font-weight: bold;

      }



      .my-div {
        color: white;
        font-size: 40px;
        font-weight: bold;
      }

      .my-unit-div {
        font-size: 20px;
      }

      .my-title-div {
        font-size: 10px;
      }

      .mdl-card__title text,
      input {
        font-size: 10px;

      }


    }

    .mdl-card__title {
      opacity: 0;
      max-height: 0px;
      overflow: hidden;
      transition: opacity 0.5s ease, max-height 0.5s ease;
      background-color: lightblue;
      padding: 2px !important;
    }

    .mdl-card__title:hover {
      opacity: 1;
      max-height: 100px;
      /* 調整你想要的最大高度 */

    }

    #depart {
      height: 300px;
    position: absolute; 
    z-index: 9999; /* 確保下拉選單顯示在其他元素之上 */
}
  </style>

  <!-- inject:css -->
  <link rel="stylesheet" href="css/lib/getmdl-select.min.css">
  <link rel="stylesheet" href="css/lib/nv.d3.min.css">
  <link rel="stylesheet" href="css/application.min.css">


</head>

<body>


  <div class="mdl-grid charts">

    <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
      <div class="mdl-card mdl-shadow--2dp">
        <div class="mdl-card__title ">
          <h2 class="mdl-card__title-text "> <span class="my-title-div ">Theo dõi xe phân phối theo từng khu vực
            </span>
            <span style="display:none;">&nbsp; <label for="yearMonth"></label><input type="month" id="yearMonth_2"
                name="yearMonth"></span>
            &nbsp;<label for="daysInput"></label>
            &nbsp;<span class="my-title-div">滯留標準天數:</span><input style='width:80px' type="text" id="daysInput"
              value="14" oninput="validateInput(this)">
          </h2>
          <h2 class="mdl-card__title-text" style=" overflow: visible;"> <span class="my-title-div">部門:</span><div style="width:200px">
            <select id="depart" multiple  size="5">
              <!-- <option value="all">全部</option>
              <option value="檢布/前處理">檢布/前處理</option>
              <option value="剖布">剖布</option>
              <option value="染房">染房</option>
              <option value="脫水">脫水</option>
              <option value="剖布/展布">剖布/展布</option>
              <option value="精煉">精煉</option>
              <option value="定型">定型</option>
              <option value="烘乾">烘乾</option>
              <option value="起毛/磨毛">起毛/磨毛</option>
              <option value="包裝">包裝</option>
              <option value="化驗室">化驗室</option> -->

              <option value="染房" selected>染房</option>
              <option value="檢布" selected>檢布</option>
              <option value="化驗室" selected>化驗室</option>
              <option value="染色前剖布">染色前剖布</option>
              <option value="脫水">脫水</option>
              <option value="剖布">剖布</option>
              <option value="展布">展布</option>
              <option value="精煉">精煉</option>
              <option value="前定">前定</option>
              <option value="烘乾">烘乾</option>
              <option value="機漂">機漂</option>
              <option value="定型">定型</option>
              <option value="起毛">起毛</option>
              <option value="磨毛">磨毛</option>
              <option value="中檢">中檢</option>
              <option value="成檢">成檢</option>
              <option value="包裝">包裝</option>
            </select>
            </div>
          </h2>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("depart");

    select.addEventListener("mousedown", function (event) {
        event.preventDefault(); // 防止預設行為避免清除其他選項
        
        const option = event.target;
        if (option.tagName === "OPTION") {
            option.selected = !option.selected; // 切換選中狀態
        }

        // **手動觸發 change 事件，讓 select 正確感應選取變更**
        select.dispatchEvent(new Event("change"));
    });

    // 測試：監聽 change 事件來確認選取的值
    // select.addEventListener("change", function () {
    //     console.log("目前選取的值：", Array.from(select.selectedOptions).map(o => o.value));
    // });
});
</script>
          <h2 class="mdl-card__title-text"> <span class="my-title-div">狀態:</span>
            <select id="status">
              <!-- <option value="all">全部</option>
              <option value="檢布/前處理">檢布/前處理</option>
              <option value="剖布">剖布</option>
              <option value="染房">染房</option>
              <option value="脫水">脫水</option>
              <option value="剖布/展布">剖布/展布</option>
              <option value="精煉">精煉</option>
              <option value="定型">定型</option>
              <option value="烘乾">烘乾</option>
              <option value="起毛/磨毛">起毛/磨毛</option>
              <option value="包裝">包裝</option>
              <option value="化驗室">化驗室</option> -->

              <option value="空車">空車</option>
              <option value="使用中">使用中</option>
             
            </select>
          </h2>
        </div>

        <div id='carttitle' style="text-align: center; color: white; font-size: 40px; margin: 20px;line-height: 1.2">
          Searching...
        </div>
        <div id="container"></div>
        <div id="pagination"></div>
      </div>
    </div>
  </div>



  </div>






</body>

</html>




<script>

  let currentPage = 0; // 當前頁數
  function autoRefresh() {
    // console.log("function called");

    const queryString = window.location.search;
    $.ajax({
      type: 'POST',
      url: "./get_Bleaching_data_ajax.php" + queryString,
      data: {
        month: document.getElementById('yearMonth_2').value,
        // DDDD_name:document.getElementById('yearMonth_1').textContent ,
        // DDDD:document.getElementById('DDDD_3').value,
        days: document.getElementById('daysInput').value,
        depart: Array.from(document.getElementById("depart").selectedOptions).map(o => o.value),
        status: document.getElementById('status').value
      },
      success: function (result) {
        // $('#demo').html(result);
        let data = JSON.parse(result);
        // $('#data_MainSpeed').html(data.data_MainSpeed);
        // console.log(data.lastTemperature3);
        // $('#data_Width').html(data.data_Width);
        // $('#data_OverFeed').html(data.data_OverFeed);
        // // $('#demo').html(data.data_Job1);
        // // $('#demo').html(data.data_Job2);
        // // $('#demo').html(data.data_Time);
        // $('#lastTemperature1').html(data.lastTemperature1);
        // $('#lastTemperature2').html(data.lastTemperature2);
        // $('#lastTemperature3').html(data.lastTemperature3);
        // $('#lastTemperature4').html(data.lastTemperature4);
        // $('#lastTemperature5').html(data.lastTemperature5);
        // $('#lastTemperature5').append("add");
        // console.log(data.carttitle);
        document.getElementById('carttitle').innerText = data.carttitle;
        var step1 = [];
        var step2 = [];
        var step3 = [];
        var step4 = [];
        var step5 = [];
        var step6 = [];


        for (var i = 0; i < data.machine_num; i++) {
          var str = JSON.stringify(data.Step[i]);
          // if(str!==null)
          str = str.replace(/[\[\"\]]/g, "");
          var delimiter = " ";
          var paragraphs = str.split(delimiter);
          var group = [];

          group[0] = paragraphs.slice(0, 3).join(" ");
          group[1] = paragraphs.slice(3, 5).join(" ");
          group[2] = paragraphs.slice(5, 7).join(" ");
          group[3] = paragraphs.slice(7, 9).join(" ");
          group[4] = paragraphs.slice(9, 11).join(" ");
          group[5] = paragraphs.slice(11, 13).join(" ");

          // console.log("group  "+group[3])
          // console.log(JSON.stringify(data.Step[i]));
          step1[i] = group[0];
          step2[i] = group[1];
          step3[i] = group[2];
          step4[i] = group[3];
          step5[i] = group[4];
          step6[i] = group[5];
        }


        // console.log(data.Step[1]);


        const container = document.getElementById("container");
        const pagination = document.getElementById("pagination");
        let numBoxes = data.machine_num; // 假設有 50 個 box 元素
        const boxesPerPage = 15; // 每頁顯示 28 個 box 元素


        // 創建 box 元素
        function createBox(id) {
          // console.log(id);
          // Name  
          // Dyelot
          // Color 
          // Step  
          // var color = "800080";
          const box = document.createElement("div");
          box.classList.add("box");
          box.id = "H" + id;
          if(parseInt(data.StandstillTime[id])>document.getElementById('daysInput').value){
            box.style.background = "linear-gradient(to bottom , rgb(249, 179, 87), rgb(255, 255, 255))";
          }
          // box.innerHTML ="<br><span class='machine'>"+ box.id +"</span>";
          box.innerHTML = "<br><span class='machine'>" + data.Name[id] + "</span>";
          // if (data.Dyelot[id] == "") {
          //   box.innerHTML += "<br><br><span class='label1' style='background-color: " + data.Color[id] + ";'>" + "" + "</span>"
          // }
          // else {
          //   if (data.Color[id] != '#ffffff') {
          //     box.innerHTML += "<br><br><span class='label1'  style='color: " + data.textcolor[id] + ";  background-color: " + data.Color[id] + ";'>&nbsp;" + data.Dyelot[id] + "&nbsp;</span>"
          //   }
          //   else {
          //     box.innerHTML += "<br><br><span class='label1'  style='color: white;  background-color: " + data.Color[id] + ";'>&nbsp;" + data.Dyelot[id] + "&nbsp;</span>"
          //   }
          // }




          // if (data.Dyelot[id] == "" && data.Step[id] == "") {
          //   box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/gray-light.png'><br><br><br>"
          // }
          // else if (data.ActiveAlarms[id] != "") {
          //   box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/red-light.png'><br><br><br>"
          // }
          // else {
          //   box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/green-light.png'><br><br><br>"
          // }

          box.innerHTML += `
    <canvas id='clothingCanvas`+ id + `'  'width='150' height='120'></canvas>
    <style>
        #clothingCanvas`+ id + ` {
            // background-color: lightgray;
            transform: scale(1); /* 设置缩放比例，这里是缩小一半 */
            width: 6vw;
            margin-top:-0.7vw;
            margin-left:0.1vw;
        }
    </style>
    <br>
    <br>
    <br>
`;


          // box.innerHTML +="<div class='label2'>溫度:0°C<br>B水位:0%<br>步驟:溫度調節</div>";
          // box.innerHTML += "<div class='label2'>" + step1[id] + "</div>";
          // box.innerHTML += "<div class='label2'>" + step2[id] + "</div>";
          // box.innerHTML += "<div class='label2'>" + step3[id] + "</div>";
          // box.innerHTML += "<div class='label2'>" + step4[id] + "</div>";
          // box.innerHTML += "<div class='label2'>" + step5[id] + "</div>";
          // box.innerHTML += "<div class='label2'>" + step6[id] + "</div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Batch_Status"); ?>'+"：<span class='label3'>" + data.ClothCarStatus[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Work_Order"); ?>'+"：<span class='label3'>" + data.WorkNo[id] + "</span></div>";
          box.innerHTML += "<div class='label2' style='overflow: hidden;'>"+'<?php echo $lang->line("general.Product_Name"); ?>'+"：<span class='label3' >" + data.FabricName[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Last_Recorded_Process"); ?>'+"：<span class='label3'>" + data.LastRecordedSection[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Next_Stage"); ?>'+"：<span class='label3'>" + data.NextSection[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Idle_Time"); ?>'+"：<span class='label3'>" + data.StandstillTime[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Record_Time"); ?>'+"：<span class='label3'>" + data.lasttime[id] + "</span></div>";
          box.innerHTML += "<div class='label2'>"+'<?php echo $lang->line("general.Energy_Consumption"); ?>'+"：<span class='label3'>" + '' + "</span></div>";
          // box.innerHTML += "<div class='label2'>當月漂缸次數<br><span class='label3'>" + data.washtime[id] + "</span></div>";


          container.appendChild(box);
          var canvas = document.getElementById('clothingCanvas' + id);
          var ctx = canvas.getContext("2d");

          // 布车尺寸和位置参数
          var cartWidth = 110;
          var cartHeight = 70;
          var cartDepth = 25; // 深度（用于模拟厚度）
          var handleWidth = 30;
          var handleHeight = 15;
          var x = 50; // 布车左上角的x坐标
          var y = 4; // 布车左上角的y坐标
          var shelf = 10;
          var setcolor = 'rgba(255, 0, 0,1)';
          // var setcolor = data.setcolor[id];
          // console.log(data.setcolor[id]);
          if (data.setcolor[id] != null)
            var setcolor = data.setcolor[id];



          drawWheel(x + cartDepth + cartWidth / 2, y + cartHeight + cartDepth + shelf / 2); // 輪子
          drawWheel(x + cartDepth / 2, y + cartHeight + cartDepth / 2); // 輪子

          // 0.5 右邊把手
          ctx.strokeStyle = "black"; // 框架颜色
          ctx.lineWidth = 5;
          ctx.beginPath();
          ctx.lineJoin = "round";
          ctx.moveTo(x + cartWidth, y + shelf + handleHeight); // 顶部左侧
          ctx.lineTo(x + cartWidth + handleWidth, y + shelf); // 左侧内角
          ctx.lineTo(x + cartWidth + cartDepth + handleWidth, y + cartDepth + shelf); // 左侧内角
          ctx.lineTo(x + cartWidth + cartDepth, y + cartDepth + shelf + handleHeight); // 左侧内角
          ctx.stroke();

          // 1. 绘制布车的顶部边缘
          ctx.fillStyle = setcolor; // 顶部颜色
          // ctx.fillStyle ="#FFFFFF"; // 顶部颜色
          ctx.strokeStyle = '#707070';
          ctx.lineWidth = 1;
          ctx.beginPath();
          ctx.moveTo(x, y); // 左上角
          ctx.lineTo(x + cartWidth, y); // 右上角
          ctx.lineTo(x + cartWidth + cartDepth, y + cartDepth); // 右上侧角`
          ctx.lineTo(x + cartDepth, y + cartDepth); // 左上侧角
          ctx.closePath();
          ctx.fill();
          ctx.stroke();




          // 2. 绘制布车内部（带渐变效果）
          var innerGradient = ctx.createLinearGradient(x, y + cartDepth, x, y + cartHeight + cartDepth);
          innerGradient.addColorStop(0, "#FFFFFF");  // 内部顶部颜色
          innerGradient.addColorStop(0, setcolor);  // 内部底部颜色
          ctx.fillStyle = innerGradient;
          ctx.fillRect(x + cartDepth, y + cartDepth, cartWidth, cartHeight);

          // 4. 绘制布车的正面边框
          ctx.lineWidth = 1;
          ctx.strokeStyle = "#707070"; // 框架颜色
          // ctx.lineWidth = 5;
          ctx.strokeRect(x + cartDepth, y + cartDepth, cartWidth, cartHeight);

          // 4.5正面車架
          ctx.strokeStyle = "black"; // 框架颜色
          ctx.lineWidth = 5;
          ctx.strokeRect(x + cartDepth, y + cartDepth + shelf, cartWidth, cartHeight - shelf);
          ctx.beginPath();
          ctx.moveTo(x + cartDepth + cartWidth / 3, y + cartDepth + shelf); // 顶部左侧
          ctx.lineTo(x + cartDepth + cartWidth / 3, y + cartDepth + cartHeight); // 左侧内角
          ctx.moveTo(x + cartDepth + cartWidth * 2 / 3, y + cartDepth + shelf); // 顶部左侧
          ctx.lineTo(x + cartDepth + cartWidth * 2 / 3, y + cartDepth + cartHeight); // 左侧内角
          ctx.closePath();
          ctx.stroke();

          // 5. 绘制布车的左侧边框
          // ctx.fillStyle = "#B0B0B0";
          // ctx.fillStyle = setcolor;
          var innerGradient = ctx.createLinearGradient(x, y, x, y + cartHeight + cartDepth);
          innerGradient.addColorStop(0, "#FFFFFF");  // 内部顶部颜色
          innerGradient.addColorStop(0, setcolor);  // 内部底部颜色
          ctx.fillStyle = innerGradient;
          ctx.strokeStyle = "#707070"; // 框架颜色
          ctx.lineWidth = 1;
          ctx.beginPath();
          ctx.moveTo(x, y); // 顶部左侧
          ctx.lineTo(x + cartDepth, y + cartDepth); // 左侧内角
          ctx.lineTo(x + cartDepth, y + cartHeight + cartDepth); // 左侧底角
          ctx.lineTo(x, y + cartHeight); // 底部左侧
          ctx.closePath();
          ctx.fill();
          ctx.stroke();


          // 5.5
          // ctx.fillStyle = "#B0B0B0";
          ctx.strokeStyle = "black"; // 框架颜色
          ctx.lineWidth = 5;

          ctx.beginPath();
          ctx.moveTo(x, y + shelf); // 顶部左侧
          ctx.lineTo(x + cartDepth, y + cartDepth + shelf); // 左侧内角
          ctx.lineTo(x + cartDepth, y + cartHeight + cartDepth); // 左侧底角
          ctx.lineTo(x, y + cartHeight); // 底部左侧
          ctx.closePath();
          ctx.stroke();

          // 左邊中線
          ctx.beginPath();
          ctx.moveTo(x + cartDepth / 2, y + cartDepth / 2 + shelf); // 顶部左侧
          ctx.lineTo(x + cartDepth / 2, y + cartHeight + cartDepth / 2); // 左侧内角

          ctx.closePath();
          ctx.stroke();

          // 左邊把手
          ctx.strokeStyle = "black"; // 框架颜色
          ctx.lineWidth = 5;

          ctx.beginPath();
          ctx.lineJoin = "round";
          ctx.moveTo(x, y + shelf + handleHeight); // 顶部左侧
          ctx.lineTo(x - handleWidth, y + shelf); // 左侧内角
          ctx.lineTo(x + cartDepth - handleWidth, y + cartDepth + shelf); // 左侧内角
          ctx.lineTo(x + cartDepth, y + cartDepth + shelf + handleHeight); // 左侧内角
          ctx.stroke();




          // ctx.fill();

          // 6. 绘制布车的轮子（模拟从上方看的效果）
          function drawWheel(cx, cy) {
            ctx.beginPath();
            ctx.arc(cx, cy, 15, 0, Math.PI * 2);
            ctx.fillStyle = "#f5f4f0"; // 轮子颜色
            ctx.fill();
            ctx.strokeStyle = "#b7b7b7"; // 轮子边框颜色
            ctx.lineWidth = 8;
            ctx.stroke();
          }
        }


        // console.log(JSON.stringify(data.Step[0]));


        // function trab(i){
        //   console.log(data.Step[i]);
        //   var str = JSON.stringify(data.Step[i]);
        // var delimiter = " ";
        // var paragraphs = str.split(delimiter);
        // var group = [];

        // group[0] = paragraphs.slice(0, 3);
        // group[1] = paragraphs.slice(3, 5);
        // group[2] = paragraphs.slice(5, 7);
        // group[3] = paragraphs.slice(7, 9);
        // group[4] = paragraphs.slice(9, 11);
        // group[5] = paragraphs.slice(11, 13);
        // console.log(group[0])


        // }




        // 創建分頁按鈕
        function createPagination() {
          container.innerHTML = ""; // 清空容器
          pagination.innerHTML = ""; // 清空分頁按鈕
          const numPages = Math.ceil(numBoxes / boxesPerPage); // 計算需要顯示多少頁
          for (let i = 1; i <= numPages; i++) {
            const page = document.createElement("div");
            page.classList.add("page");
            page.innerText = i;
            if (i === currentPage) {
              page.classList.add("active");
            }
            page.addEventListener("click", function () {
              currentPage = i;

              showPage();

              // light();
              //  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!按下去也要判斷紅燈 這裡最後要加上判斷燈的
              // const icon = document.getElementById("icon-0"); // 假設要修改第一個 box 元素的圖像
              //   console.log(data.machine_num)
              //   // if(data.machine_num==160){
              //     // 紅燈
              //     if(data.machine_num == 160){
              //     icon.src = "./images/red-light.png"; 
              //   }
              //   // 綠燈
              //   else if(data.machine_num == 14){

              //     icon.src = "./images/green-light.png"; 
              //   }
              //    // 灰燈
              //   else{
              //     icon.src = "./images/gray-light.png"; 
              //   }

            });
            pagination.appendChild(page);
          }



        }

        // 顯示當前頁面的 box 元素
        function showPage() {
          container.innerHTML = ""; // 清空容器
          const startIndex = (currentPage - 1) * boxesPerPage; // 計算當前頁面的起始索引
          const endIndex = startIndex + boxesPerPage; // 計算當前頁面的結束索引
          for (let i = startIndex; i < endIndex && i < numBoxes; i++) {
            createBox(i);
            const box = document.getElementById("H" + i);
            box.style.opacity = 0; // 新添加的 box 元素初始為透明
            box.animate([{ opacity: 0 }, { opacity: 1 }], {
              duration: 300, // 動畫持續時間為 300 毫秒
              easing: "ease-out", // 動畫緩動函數為 ease-out
              fill: "forwards", // 動畫結束後保持最終狀態
              delay: i * 30 // 每個 box 元素延遲 50 毫秒開始動畫
            });
          }

          // 更新分頁按鈕的狀態
          const pages = document.querySelectorAll(".page");
          for (let i = 0; i < pages.length; i++) {
            if (i + 1 === currentPage) {
              pages[i].classList.add("active");
            } else {
              pages[i].classList.remove("active");
            }
          }
        }

        //   // 延遲 50 毫秒後，將新添加的 box 元素的 opacity 屬性設置為 1，觸發動畫效果
        //   setTimeout(() => {
        //     for (let i = startIndex; i < endIndex && i < numBoxes; i++) {
        //       const box = document.getElementById("H" + i);
        //       box.style.opacity = 1;
        //     }
        //   }, 50);
        // }



        // 清除所有 box 元素和分頁按鈕
        function clearAll() {
          container.innerHTML = "";
          pagination.innerHTML = "";
          numBoxes = 0;
          currentPage = 1;

        }

        function nextPage() {
          currentPage++;
          if (currentPage > Math.ceil(numBoxes / boxesPerPage)) {
            currentPage = 1;
          }
          showPage();
        }

        // 初始化頁面
        createPagination();
        showPage();
        nextPage();

        // 燈
        // function light() {
        // for (let i = 0; i <= 100; i++) {
        //   const icon = document.getElementById("icon-" + i);
        //   // 在這裡使用 icon 元素對象進行操作
        // console.log(data.Dyelot[i]);
        //   console.log(data.Dyelot[i])
        //         // if(data.machine_num==160){
        //           // 紅燈
        //         if(data.Dyelot[i] == ''){
        //           icon.src = "./images/red-light.png"; 
        //         }
        //         // 綠燈
        //         else if(data.machine_num == 14){

        //           icon.src = "./images/green-light.png"; 
        //         }
        //          // 灰燈
        //         else{
        //           icon.src = "./images/gray-light.png"; 
        //         }



        // }; // 假設要修改第一個 box 元素的圖像

        // }
        // light();







      }
    });
    setTimeout(function () {
      autoRefresh();
      // clearInterval(timerId);

      // clearAll();


    }, 10000);
  }
  $(() => {
    autoRefresh();

  })




  var today = new Date();
  // 获取年月份的字符串表示，例如："2024-01"
  var lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());

  var yearMonthString = today.toISOString().slice(0, 7);
  var lastMonthString = lastMonth.toISOString().slice(0, 7);


  // document.getElementById('yearMonth_1').value = lastMonthString;
  document.getElementById('yearMonth_2').value = yearMonthString;
  // document.getElementById('yearMonth_3').value = lastMonthString;

  function validateInput(input) {
    // 使用正则表达式检查输入是否为数字
    input.value = input.value.replace(/[^0-9.]/g, '');

    // 更新输入框的值
    const inputValue = parseFloat(input.value);
    if (!isNaN(inputValue)) {
      // 不使用toFixed，直接將輸入轉換為浮點數
      input.value = inputValue.toString();
    }
  }

</script>