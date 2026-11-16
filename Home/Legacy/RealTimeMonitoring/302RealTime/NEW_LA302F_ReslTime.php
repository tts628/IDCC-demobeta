<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <title>Adaptive Web Application</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
        /* Background color similar to the original */
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #ffff99;
        /* Yellow background for header */
        padding: 10px;
    }

    .header .top-left button {
        margin-right: 5px;
    }

    .header .top-center {
        text-align: center;
        flex: 1;
        background-color: #ffcc99;
        /* Light orange background for the title */
        padding: 5px;
    }

    .header .top-right {
        text-align: right;
        white-space: nowrap;
        background-color: #ffff99;
        /* Same yellow as header background */
        padding: 5px;
    }

    .status-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f0f0f0;
        padding: 10px;
    }

    .content .status-box {
        background-color: #ff99ff;
        /* Pink background for status boxes */
        padding: 1px;
        margin-right: 10px;
        flex: 1;
        text-align: center;
    }

    .content .status-box2 {
        background-color: #ffff99;
        /* Pink background for status boxes */
        padding: 1px;
        margin-right: 10px;
        flex: 1;
        text-align: center;
    }


    .status-section .exit-button {
        background-color: #66ccff;
        /* Blue button color */
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
    }

    .content {
        display: flex;
        justify-content: space-around;
        padding: 20px;
    }

    .column {
        flex: 1;
        margin: 0 10px;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #ffffff;
        /* White background for content columns */
        position: relative;
        display: flex;
        flex-direction: column;
        height: 300px;
    }

    .column2 {
        flex: 1;
        margin: 0 10px;
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #ffffff;
        /* White background for content columns */
        position: relative;
        display: flex;
        flex-direction: column;
    }

    #Dis1 {
        border-top: 10px solid #ff3333;
        /* Red border for first column */
        border-bottom: 10px solid #009933;
        /* Green border for third column */
        margin: auto;
        height: 30px;
    }

    #Dis2 {
        border-top: 10px solid #ff6600;
        /* Orange border for second column */
        border-bottom: 10px solid #009933;
        /* Green border for third column */
        margin: auto;
        height: 30px;
    }

    #Dis3 {
        border-top: 10px solid #ff6600;
        /* Orange border for second column */
        border-bottom: 10px solid #009933;
        /* Green border for third column */
        margin: auto;
        height: 30px;
    }

    #Dis4 {
        border-top: 10px solid #ff6600;
        /* Orange border for second column */
        border-bottom: 10px solid #009933;
        /* Green border for third column */
        margin: auto;
        height: 30px;
    }

    #Dis1 li {
        margin: auto;

    }

    #Dis2 li {
        margin: auto;
    }

    #Dis3 li {
        margin: auto;
    }

    #Dis4 li {
        margin: auto;
    }

    .column ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        text-align: center;
        /* Center align the list items */
    }


    .column2 p {
        margin: 10px 0;
        font-weight: bold;
        text-align: center;
    }

    .column p {
        margin: 10px 0;
        font-weight: bold;
        text-align: center;
    }

    .column li {
        display: inline-block;
        width: 100%;
        position: relative;
        /* Relative positioning for tooltips */
        margin-top: 0.5em;
        margin-bottom: 1em;
    }

    /* Tooltip container */

    /* Tooltip container */
    .tooltip {
        position: absolute;
        top: 50%;
        /* Position the tooltip at the middle of the element */
        left: 85%;
        /* Position the tooltip to the right of the element */
        transform: translateY(-50%);
        /* Center the tooltip vertically */
        visibility: hidden;
        /* Hidden by default */
        background-color: #333;
        color: #fff;
        text-align: left;
        /* padding: 5px; */
        border-radius: 5px;
        white-space: nowrap;
        font-size: 12px;
        z-index: 1;
        opacity: 0;
        /* Initially hidden */
        transition: opacity 0.3s;
        margin-left: 10px;
        /* Space between the element and the tooltip */
        padding: 0.5em 0;
        height: 100px;
    }

    /* Tooltip arrow */
    .tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        /* Position the arrow at the bottom of the tooltip */
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    /* Show the tooltip when hovering */
    .column li:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }



    /* Tooltip arrow */
    .tooltip::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        /* Position the arrow at the left of the tooltip */
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent transparent #333;
    }

    /* Show the tooltip when hovering */
    .column li:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
</style>

<body>
    <div class="header">
        <div class="top-center">
            <p>Hệ thống phân phối cân đo tự động hóa chất tạo nhuộm LA-302F (Nơi xử lý dữ liệu)</p>
        </div>
        <div class="top-right">
            <p>2024/08/09 15:22:52</p>
        </div>
    </div>

    <div class="content" style="padding:1px;">
        <div class="column2 status-box">
            <p>Trạng thái hệ thống F1:</p>
        </div>
        <div class="column2 status-box">
            <p>Trạng thái hệ thống F2:</p>
        </div>
    </div>
    <div class="content" style="padding:1px;">
        <div class="column2 status-box2" id="DisF1">

        </div>
        <div class="column2 status-box2" id="DisF2">
            ''
        </div>
    </div>
    <div class="content">
        <div class="column" id="Dis1">
            <ul>
                <li>

                </li>
        </div>
        <div class="column" id="Dis2">
            <ul>
                <li>

                </li>
            </ul>
        </div>
        <div class="column" id="Dis3">
            <ul>
                <li>

                </li>
            </ul>
        </div>
        <div class="column" id="Dis4">
            <ul>
                <li>

                </li>
            </ul>
        </div>
    </div>
    <div class="content">
        <div class="column" id="Sch1">
            <ul>
                <li>


                </li>
                <li>

                    <!-- <span class="tooltip">Additional info for JD17-1</span> -->
                </li>
            </ul>
            <p style="margin-top: auto;">Số lượng đơn nhỏ: 0</p>
        </div>
        <div class="column" id="Sch2">
            <ul>
                <li>

                    <!-- <span class="tooltip">Additional info for JD18-1</span> -->
                </li>
            </ul>
            <p style="margin-top: auto;">Số lượng đơn nhỏ: 3</p>
        </div>
        <div class="column" id="Sch3">
            <ul>
                <li>

                    <!-- <span class="tooltip">Additional info for JD21-1</span> -->
                </li>
                <li>

                    <!-- <span class="tooltip">Additional info for JD25-1</span> -->
                </li>
                <li>

                    <!-- <span class="tooltip">Additional info for JD29-1</span> -->
                </li>
            </ul>
            <p style="margin-top: auto;">Số lượng đơn nhỏ: 3</p>
        </div>
        <div class="column" id="Sch4">
            <ul>
                <li>
                    <!-- <span class="tooltip">Additional info for JD21-1</span> -->
                </li>
                <li>

                    <!-- <span class="tooltip">Additional info for JD25-1</span> -->
                </li>
                <li>

                    <!-- <span class="tooltip">Additional info for JD29-1</span> -->
                </li>
            </ul>
            <p style="margin-top: auto;">Số lượng đơn nhỏ: 3</p>
        </div>
    </div>
    <script>
        const queryString = window.location.search;


        $(document).ready(function () {
            dataAjAX()
            setInterval(dataAjAX, 30000);

        })
        function dataAjAX() {
            $.ajax({

                url: "NEW_LA302F_ReslTime_ajax.php" + queryString,
                method: "post",
                dataType: "json",
                success: function (res) {
                    console.log(res)
                    // // 在頁面上生成所有未安排機台、開始、結束時間的工單
                    const length = Object.keys(res).length;
                    console.log(length)
                    if (length != 0) { //有待排工單
                        creat_show_html(res)

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '無法獲取資料，請匯入資料',
                        })
                    }
                },
                error: function (errOrder) {
                    if (errOrder === "error") {
                        Swal.fire({
                            icon: 'error',
                            title: '無法獲取資料，請聯絡工程師',
                        })
                        return;
                    }
                },
            })
        }
        function creat_show_html(res) {
            $('#Sch1').empty()
            $('#Sch2').empty()
            $('#Sch3').empty()
            $('#Sch4').empty()
            $('#Dis1').empty()
            $('#Dis2').empty()
            $('#Dis3').empty()
            $('#Dis4').empty()
            $('#DisF1').empty()
            $('#DisF2').empty()
            var info2 = `<p style="margin-top: auto;">Số lượng đơn nhỏ: 0</p>`
            $('#Sch1').append(info2)
            $('#Sch2').append(info2)
            $('#Sch3').append(info2)
            $('#Sch4').append(info2)

            for (const key in res) {
                if (res.hasOwnProperty(key)) { // 確保屬性來自於物件本身，而不是從原型鏈繼承的

                    if (key == 'Sch1' || key == 'Sch2' || key == 'Sch3' || key == 'Sch4') {
                        // console.log(value)
                        $('#' + key).empty()
                        var info = ` <ul>`
                        res[key].forEach(function (value) {
                            info += ` 
                <li>${value.Dyelot}@0 [${value.DyeMachine}-${value.Tank}] 
                <span class="tooltip">
                領料單號：${value.Dyelot}<br>
                染機：${value.DyeMachine}<br>
                工單：${value.OrderNo}<br>
                染料/助劑品名號碼：${value.ProductName}<br>
                目標重量${value.TargetGrams}<br>
                </span> </li>`

                        });
                        info += `</ul>`
                        $('#' + key).append(info)
                        info2 = `<p style="margin-top: auto;">Số lượng đơn nhỏ: ${res[key].length}</p>`
                        $('#' + key).append(info2)
                    } else {


                        res[key].forEach(function (value) {
                            // console.log(value)
                            var info = `<p>${value['Dyelot']}@ [${value['DyeMachine']}-${value['Tank']}]</p>`;
                            $('#' + key).append(info)
                        });

                    }

                }
            }
        }
    </script>
</body>

</html>