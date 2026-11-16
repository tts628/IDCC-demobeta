<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <title>M1F1D1</title>
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



  <style>
    body {

      background-image: url("./images/bg1.png");
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

    / .card {
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
      text-align: center;
      /* 設置文字左右致中 */
      width: 220px;
      height: 200px;
      background-color: white;
      margin: 10px;
      position: relative;
      box-sizing: border-box;
      border-radius: 20px;
      /* 設置圓角半徑為 10px */
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      /* 添加陰影效果 */
      line-height: 0.85;
    }

    .machine {
      color: black;
      font-size: 30px;
      font-weight: bold;
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
      font-size: 18px;
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
        font-size: 8px;
        font-family: Arial, sans-serif;
        color: black;
        line-height: 1.2;
        position: relative;
        top: -30px;


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
        font-size: 35px;
      }



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
        <div class="mdl-card__title">
          <h2 class="mdl-card__title-text"> <span class="my-title-div">DyeMachineSummary:</span></h2>
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
      url: "./get_data_ajax.php" + queryString,
      data: {

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
        var step1 = [];
        var step2 = [];
        var step3 = [];
        var step4 = [];
        var step5 = [];
        var step6 = [];


        for (var i = 0; i < data.machine_num; i++) {
          var str = JSON.stringify(data.Step[i]);
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
        const boxesPerPage = 28; // 每頁顯示 28 個 box 元素


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
          // box.innerHTML ="<br><span class='machine'>"+ box.id +"</span>";
          box.innerHTML = "<br><span class='machine'>" + data.Name[id] + "</span>";
          if (data.Dyelot[id] == "") {
            box.innerHTML += "<br><br><span class='label1' style='background-color: " + data.Color[id] + ";'>" + "" + "</span>"
          }
          else {
            if (data.Color[id] != '#ffffff') {
              box.innerHTML += "<br><br><span class='label1'  style='color: " + data.textcolor[id] + ";  background-color: " + data.Color[id] + ";'>&nbsp;" + data.Dyelot[id] + "&nbsp;</span>"
            }
            else {
              box.innerHTML += "<br><br><span class='label1'  style='color: white;  background-color: " + data.Color[id] + ";'>&nbsp;" + data.Dyelot[id] + "&nbsp;</span>"
            }
          }




          if (data.Dyelot[id] == "" && data.Step[id] == "") {
            box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/gray-light.png'><br><br><br>"
          }
          else if (data.ActiveAlarms[id] != "") {
            box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/red-light.png'><br><br><br>"
          }
          else {
            box.innerHTML += "<img id='icon-" + id + "' class='icon' src='./images/green-light.png'><br><br><br>"
          }

          // box.innerHTML +="<div class='label2'>溫度:0°C<br>B水位:0%<br>步驟:溫度調節</div>";
          box.innerHTML += "<div class='label2'>" + step1[id] + "</div>";
          box.innerHTML += "<div class='label2'>" + step2[id] + "</div>";
          box.innerHTML += "<div class='label2'>" + step3[id] + "</div>";
          box.innerHTML += "<div class='label2'>" + step4[id] + "</div>";
          box.innerHTML += "<div class='label2'>" + step5[id] + "</div>";
          box.innerHTML += "<div class='label2'>" + step6[id] + "</div>";


          container.appendChild(box);


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


    }, 7000);
  }
  autoRefresh();

</script>