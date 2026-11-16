<style>
    .marquee-container {
        white-space: nowrap;
        overflow: hidden;
        width: 16vw;
    }
</style>




<?php
function Readcsv($filename)
{
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if (!isset($readData)) {
        $readData = [[0]];
    }
    return $readData;
}
function Block($data)
{
    ?>
    <?php
    if ($data[6] == "") {
        ?>
        <!-- 下方原本有font-size:1.4vw -->
        <div class="row"
            style="border-bottom-style:solid; border-color:white; border-width:1px; font-weight:bold;background-color:#7F7F7F">
            <div class="col" style="position:absolute;left:-8px"><?php echo $data[0]; ?></div>
            <div class="col text-right" style="position:relative;right:-10px"><?php echo $data[1]; ?></div>
        </div>
        <div class="text-center align-middle" style="position:absolute;top:50%;left:35%;font-weight:bold">無工單執行中</div>
        <?php
    } else {
        if ($data[4] != "") {
            ?>
            <div class="row"
                style="background-color:#C00000; border-bottom-style:solid; border-color:white;border-width:1px;font-weight:bold">
                <?php
        } else {
            ?>
                <!-- 下方原本有font-size:1.4vw -->
                <div class="row"
                    style="background-color:#538234;border-bottom-style:solid;border-color:white; border-width:1px;font-weight:bold">
                    <?php
        }
        ?>
                <div class="col" style="position:absolute;left:-8px"><?php echo $data[0]; ?></div>
                <div class="col text-right" style="position:relative;right:-10px"><?php echo $data[1]; ?></div>
            </div>
            <div class="row">
                <div class="col " style="position:relative;left:-12px">程式:</div>
                <div class="col marquee-container" style="position:absolute;left:50px"><?php echo $data[2]; ?></div>
            </div>
            <div class="row">
                <div class="col" style="position:relative;left:-12px">步驟:</div>
                <div class="col marquee-container" style="position:absolute;left:50px;white-space:nowrap"><?php $data[3] = str_replace("<", "< ", $data[3]);
                echo $data[3]; ?></div>
            </div>
            <div class="row">
                <div class="col" style="position:relative;left:-12px">警報:</div>
                <div class="col marquee-container" style="position:absolute;left:50px"><?php echo $data[4]; ?></div>
            </div>

            <div class="row">
                <div class="col" style="position:relative;left:-12px">剩餘時間:</div>
                <div class="col" style="position:absolute;left:50px">
                    <?php
                    $currentDateTime = new DateTime();

                    // 设置时区为越南时区
                    $currentDateTime->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

                    // 输出设置后的时间
                    // echo $currentDateTime->format("Y-m-d H:i:s");
                    // echo $currentDateTime->getTimestamp();
                    // echo strtotime($data[5])-strtotime($currentDateTime->format("Y-m-d H:i:s"));
            
                    // echo strtotime($data[5]) - strtotime($currentDateTime->format("Y-m-d H:i:s"));
            
                    if ($data[5] > date("Y-m-d H:i:s")) {
                        echo gmdate("H:i:s", strtotime($data[5]) - strtotime($currentDateTime->format("Y-m-d H:i:s")));
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col" style="position:relative;left:-12px">Now:</div>
            </div>
            <div class="row"
                style=" border-style:solid;border-color:white;border-width:1px; border-radius:6px;background-color:<?php echo $data[7] ?>; color:<?php echo $data[16] ?>">
                <div class="col" style="position:relative;left:-12px">><?php echo $data[6] ?></div>
                <div class="col" style="position:relative;left:-35px"><?php echo $data[8] ?></div>
            </div>
            <div class="row" style="border-bottom-style:solid;border-color:white;border-width:1px;">
                <div class="col" style="position:absolute;left:-8px"><?php echo $data[9] ?></div>
                <div class="col text-right" style="position:relative;right:-10px"><?php echo $data[10] ?></div>
            </div>
            <div class="row" style="background-color:#7F7F7F;height:27%">

                <div class="col" style="position:relative;left:-12px">下一步:
                    <!-- 刪除 -->
                    <div class="row" style="position:relative;left:12px;border-style:solid;border-color:white; border-width:<?php
                    if ($data[11] != null || $data[11] != "")
                        echo "1px";
                    else
                        echo "0px";
                    ?>;
                                border-radius:6px;
                                background-color:<?php echo $data[12] ?>;
                                color:<?php echo $data[17] ?>">
                        >><?php
                        if ($data[11] != null || $data[11] != "")
                            echo $data[11];
                        else
                            echo "無工單";
                        ?>
                        <div class="col" style="position:relative;left:-65px"><?php echo $data[13] ?></div>
                    </div>
                    <div class="row">
                        <div class="col" style="position:absolute;left:5px">
                            <?php if ($data[11] != null || $data[11] != "")
                                echo $data[14];
                            else
                                echo ""; ?>
                        </div>
                        <div class="col text-right" style="position:relative;right:-23px">
                            <?php if ($data[11] != null || $data[11] != "")
                                echo $data[15];
                            else
                                echo ""; ?>
                        </div>
                    </div>
                </div>
                <!-- 刪除 -->
            </div>
            <?php
    }
}

$data = Readcsv("./DyelotOverview_data.csv");
if (isset($_REQUEST["counter"])) {
    $counter = $_REQUEST["counter"];
} else {
    $counter = 0;
}

$currentGroup = floor(($counter - 1) % 3);
$offsetCount = $currentGroup * 15;

for ($i = 0; $i < sizeof($data); $i += 15) {
    $startMachine = $data[$i][0];
    $endMachine = $data[$i + 14][0] ?? $data[sizeof($data) - 1][0];
    $machineGroup[] = $startMachine . "-" . $endMachine;
}

$slicedata = array();
foreach ($data as $key => $row) {
    if ($key >= $offsetCount && $key < $offsetCount + 15) {
        $slicedata[] = $row;
    }
    if ($key >= $offsetCount + 15) {
        break;
    }
}
?>
    <div style="background-color:#272727">
        <div class="container-fluid" style="width:99vw">
            <div style="height:5vh">
                <div class="row">
                    <?php
                    foreach ($machineGroup as $key => $value) {
                        ?>
                        <div class="col-2">
                            <div
                                style="<?php echo $key == $currentGroup ? "font-size:120%;font-weight:bold" : "color:#999999"; ?>">
                                <?php echo $value; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row" style="height:30vh">
                <?php
                foreach ($slicedata as $key => $row) {
                    if ($key % 5 == 0 && $key != 0) {
                        ?>
                    </div>
                    <div style="height:2vh"></div>
                    <div class="row" style="height:30vh">
                        <?php
                    }
                    if ($counter == 0 || $counter == 5 || $counter == 10) {
                        ?>
                        <div class="col border border-light mx-1 w3-animate-left"
                            style="font-size:0.9vw;overflow-x:hidden;background-color:#595959"><?php Block($slicedata[$key]); ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col border border-light mx-1"
                            style="font-size:0.9vw;overflow-x:hidden;background-color:#595959"><?php Block($slicedata[$key]); ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function marquee(container) {
            var text = container.innerText;

            // 将文本复制一份拼接到后面
            container.innerHTML = text + ' ' + text;

            // 每100毫秒滚动一次
            setInterval(function () {
                container.scrollLeft += 1;
                if (container.scrollLeft % container.clientWidth === 0) {
                    // 滚动到文本的最后一份时，回到起始位置
                    container.scrollLeft = 0;
                }
            }, 30);
        }

        // 获取所有 class 为 'marquee-container' 的元素
        var marqueeContainers = document.querySelectorAll('.marquee-container');

        // 为每个元素调用 marquee 函数
        marqueeContainers.forEach(container => {
            marquee(container);
        });
    </script>