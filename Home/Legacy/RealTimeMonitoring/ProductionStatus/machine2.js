const queryString = window.location.search;

$("#machine_id").change(function () {
	// $("#show_chart").empty();
	var machine_id = $("#machine_id").val();
	var machines = $("#machines").val();
	// console.log(machines);
	// console.log(machine_id);
	$.ajax({
		url: "./machine_data.php" + queryString,
		type: "POST",
		data: { machine_id: machine_id, machines: machines }, //也可以把ViewModel的資料送到後端
		dataType: "json",
		success: function (show_data) {
			$.each(show_data, function (k, v) {
				// console.log(show_data);
				// alert( "Key: " + k + ", Value: " + v );
				content = "";
				if (machines == "DyeMachines") {
					content += '<div class="row">';
					content += '<div class="col-md-3">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					if (v.state == 1) {
						//運行中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/1.png" width="40px" height="40px">' +
							Machine +
							"： " +
							v.name +
							"</span>";
					} else if (v.state == 2) {
						//暫停中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/2.png" width="40px" height="40px">' +
							Machine +
							"： " +
							v.name +
							"</span>";
					} else if (v.state == 3) {
						//停止中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/5.png" width="40px" height="40px">' +
							Machine +
							"： " +
							v.name +
							"</span>";
					} else if (v.state == 0) {
						//待機
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/3.png" width="40px" height="40px">' +
							Machine +
							"： " +
							v.name +
							"</span>";
					}
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content +=
						'<li class="mdl-list__item" style="font-size: 22px"><div style="background-color:#' +
						v.color +
						';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
						v.dyelot +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
						v.color1 +
						';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
						v.dyelot1 +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
						v.color2 +
						';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
						v.dyelot2 +
						"</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += '<div class="col-md-4">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					content +=
						'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/t.png" width="40px" height="40px">' +
						t +
						"：" +
						v.t +
						"</span>";
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content +=
						'<li class="mdl-list__item" style="font-size: 22px">' + program + "：" + v.program + "</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						FabricName +
						"：" +
						v.faname +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						TotalWeight +
						"：" +
						v.totalw +
						"kg</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += '<div class="col-md-5">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					content +=
						'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/time.png" width="40px" height="40px">' +
						time +
						"</span>";
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content +=
						'<li class="mdl-list__item" style="font-size: 22px">' +
						DyelotStartTime +
						"：" +
						v.newtime +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						DyelotEndTime +
						"：" +
						v.endtime +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						time_remaining +
						"：" +
						v.lesstime +
						"</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += "</div><br>"; //row
					content += '<div class="row">';
					content += '<div  class="col-md-12" id="machine_data"></div>';
					content += "</div>"; //row
					content += '<div class="row">';
					content += '<div class="col-md-3" id="speed1"></div>';
					content += '<div class="col-md-3 " id="speed2"></div>';
					content += '<div class="col-md-3 " id="speed3"></div>';
					content += '<div class="col-md-3 " id="speed4"></div>';
					content += "</div>"; //row//end
					$("#content").html(content);
					draw1(v.speed1);
					draw2(v.speed2);
					draw3(v.speed3);
					draw4(v.speed4);
					draw5(v.ok_data, v.note);
				}
				if (machines == "FinishMachines") {
					content += '<div class="row">';
					content += '<div class="col-md-3">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					if (v.state == 1) {
						//運行中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/1.png" width="40px" height="40px">' +
							Machine +
							"：" +
							v.name +
							"</span>";
					} else if (v.state == 2) {
						//暫停中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/2.png" width="40px" height="40px">' +
							Machine +
							"：" +
							v.name +
							"</span>";
					} else if (v.state == 3) {
						//停止中
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/5.png" width="40px" height="40px">' +
							Machine +
							"：" +
							v.name +
							"</span>";
					} else if (v.state == 0) {
						//待機
						content +=
							'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/3.png" width="40px" height="40px">' +
							Machine +
							"：" +
							v.name +
							"</span>";
					} else if (v.state == null) {
						//待機
						content +=
							'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/1.png" width="40px" height="40px">' +
							Machine +
							"：" +
							v.name +
							"</h2>";
					}
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content += '<li class="mdl-list__item" style="font-size: 22px">' + v.job + "</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' + v.job1 + "</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' + v.job2 + "</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += '<div class="col-md-4">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					content +=
						'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 31px"><img src="./img/txt.png" width="40px" height="40px">' +
						Information +
						"</span>";
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content +=
						'<li class="mdl-list__item" style="font-size: 22px">' + program + "：" + v.program + "</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						FabricName +
						"：" +
						v.faname +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						TotalLength +
						"：" +
						v.totall +
						"" +
						yard +
						"</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += '<div class="col-md-5">';
					content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
					content += '<div class="mdl-card__title">';
					content +=
						'<span class="mdl-card__title-text" style="margin:0px auto;font-size: 32px"><img src="./img/time.png" width="40px" height="40px">' +
						time +
						"</span>";
					content += "</div>";
					content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
					content += "<center>";
					content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
					content +=
						'<li class="mdl-list__item" style="font-size: 22px">' +
						FinishStartTime +
						"：" +
						v.newtime +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						FinishEndTime +
						"：" +
						v.endtime +
						"</li>";
					content +=
						'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
						time_remaining +
						"：" +
						v.lesstime +
						"</li>";
					content += "</ul>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += "</div><br>"; //row
					content += '<div class="row">';
					content += '<div  class="col-md-12" id="machine_data"></div>';
					content += "</div>"; //row
					content += '<div class="row">';
					content += '<div class="col-md-4" id="speed1"></div>';
					content += '<div class="col-md-4" id="speed2"></div>';
					content += '<div class="col-md-4" id="speed3"></div>';
					content += "</div>"; //row//end
					// content+= '<span>444444</span>';

					//ssssssssssssssssssssssssssssssssssssssssssssssss

					content += "<style>";

					content += "@media screen and (max-width: 1222px) {";
					content += "  .move {";
					content += "    transform: scale(0.7);";
					content += "    margin-left: -200px;";
					content += "  }";
					content += "}";

					content += "@media screen and (max-width: 775px) {";
					content += "  .move {";
					content += "    transform: scale(0.45);";
					content += "    margin-left: -230px;";
					content += "  }";
					content += "}";

					content += "@media screen and (max-width: 550px) {";
					content += "  .move {";
					content += "    transform: scale(0.35);";
					content += "    margin-left: -230px;";
					content += "  }";
					content += "}";

					content += "@media screen and (max-width: 413px) {";
					content += "  .move {";
					content += "    transform: scale(0.3);";
					content += "    margin-left: -220px;";
					content += "  }";
					content += "}";

					content += "";
					content += ".move{";
					content += "         position: relative; ";
					content += "         top: 50px; ";
					content += "         /*position: absolute; */";
					content += "         /*top: 590px;*/ ";
					content += "        ";
					content += "    }";
					content += ".image_LA570_1 {";
					content += "position: absolute;";
					content += "top: 20px;";
					content += "left: 70px;";
					content += "width: 1120px;";
					content += "}";
					content += "";
					content += ".slider img {";
					content += "width: 45px;";
					content += "}";
					content += "";
					content += ".alarm img {";
					content += "width: 55px;";
					content += "height: 50px;";
					content += "position: absolute;";
					content += "top: -246px;";
					content += "left: -804px;";
					content += "}";
					content += "</style>";

					content += '                  <div class="move">';
					content += "";
					content += "    <!-- 平面570 -->";
					content += "    <div>";
					content += '      <img class="image_LA570_1" src="images/LA570A.png" alt="LA570A Background">';
					content += "";
					content += "      <span";
					content +=
						'        style="position: absolute; top: 300px; left: 95px; font-size: 38px;font-weight: bold;color: #fff;text-shadow: 2px 2px 0 #000;background-color: #000;padding: 5px;">&nbsp;&nbsp;###.#</span>';
					content += '      <span style="position: absolute; top: 158px; left: 165px;">#</span>';
					content += "      <!-- 編號 -->";
					content += '      <span style="position: absolute; top: 60px; left: 290px;">&nbsp;&nbsp;1</span>';
					content += '      <span style="position: absolute; top: 60px; left: 370px;">&nbsp;&nbsp;2</span>';
					content += '      <span style="position: absolute; top: 60px; left: 450px;">&nbsp;&nbsp;3</span>';
					content += '      <span style="position: absolute; top: 60px; left: 530px;">&nbsp;&nbsp;4</span>';
					content += '      <span style="position: absolute; top: 60px; left: 610px;">&nbsp;&nbsp;5</span>';
					content += '      <span style="position: absolute; top: 60px; left: 690px;">&nbsp;&nbsp;6</span>';
					content += '      <span style="position: absolute; top: 60px; left: 770px;">&nbsp;&nbsp;7</span>';
					content += '      <span style="position: absolute; top: 60px; left: 850px;">&nbsp;&nbsp;8</span>';
					content += '      <span style="position: absolute; top: 60px; left: 930px;">&nbsp;&nbsp;9</span>';
					content += '      <span style="position: absolute; top: 60px; left: 1010px;">10</span>';
					content += "";
					content += "      <!-- 上PV -->";
					content += "";
					content +=
						'      <span style="position: absolute; top: 150px; left: 290px;"><span id="lastTemperature1"></span></span>';
					content +=
						'      <span style="position: absolute; top: 150px; left: 370px;"><span id="lastTemperature2"></span></span>';
					content +=
						'      <span style="position: absolute; top: 150px; left: 450px;"><span id="lastTemperature3"></span></span>';
					content +=
						'      <span style="position: absolute; top: 150px; left: 530px;"><span id="lastTemperature4"></span></span>';
					content +=
						'      <span style="position: absolute; top: 150px; left: 610px;"><span id="lastTemperature5"></span></span>';
					content += '      <span style="position: absolute; top: 150px; left: 690px;">###.#</span>';
					content += '      <span style="position: absolute; top: 150px; left: 770px;">###.#</span>';
					content += '      <span style="position: absolute; top: 150px; left: 850px;">###.#</span>';
					content += '      <span style="position: absolute; top: 150px; left: 930px;">###.#</span>';
					content += '      <span style="position: absolute; top: 150px; left: 1010px;">###.#</span>';
					content += "      <!-- 下PV -->";
					content += "";
					content += '      <span style="position: absolute; top: 310px; left: 290px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 370px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 450px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 530px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 610px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 690px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 770px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 850px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 930px;">###.#</span>';
					content += '      <span style="position: absolute; top: 310px; left: 1010px;">###.#</span>';
					content += "";
					content += "";
					content +=
						'      <div class="slider1 slider" style="position: absolute; top: 248px; left: 282px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content +=
						'      <div class="slider2 slider" style="position: absolute; top: 248px; left: 362px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider3 slider" style="position: absolute; top: 248px; left: 442px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider4 slider" style="position: absolute; top: 248px; left: 522px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider5 slider" style="position: absolute; top: 248px; left: 602px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider6 slider" style="position: absolute; top: 248px; left: 680px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider7 slider" style="position: absolute; top: 248px; left: 760px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content += "";
					content +=
						'      <div class="slider8 slider" style="position: absolute; top: 248px; left: 837px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content +=
						'      <div class="slider9 slider" style="position: absolute; top: 248px; left: 917px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += "";
					content +=
						'      <div class="slider10 slider" style="position: absolute; top: 248px; left: 997px;">';
					content += '        <img src="images/Fan1 36x36.png" alt="圖片1">';
					content += '        <img src="images/Fan2 36x36.png" alt="圖片2">';
					content += '        <img src="images/Fan3 36x36.png" alt="圖片3">';
					content += '        <img src="images/Fan4 36x36.png" alt="圖片4">';
					content += "      </div>";
					content += '      <div id="statement"></div>';
					content += "";
					content += "";
					content += "      </div>";
					content += "  </div>";

					// sssssssssssssssssssssssssssssssssssssssssssssssssss

					$("#content").html(content);
					draw1(v.speed1);
					draw2(v.speed2);
					draw3(v.speed3);
					// console.log(v.lobt1[0].data);
					draw5(
						v.lobt1[0].data,
						v.lobt2[0].data,
						v.lobt3[0].data,
						v.lobt4[0].data,
						v.lobt5[0].data,
						v.lobt6[0].data
					);
				}
				if (machines == "DispenserMachines") {
					content += '<div style="display:block:padding: 10px">';
					content += '<div class="row">';
					content += '<div class="col-md-2" style="height: 200px">';
					if (v.state == 1) {
						//運行中
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff"><img src="./img/1.png" width="30px">' +
							v.name +
							"</h1>";
						content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 60%">';
					} else if (v.state == 2) {
						//暫停中
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff;"><img src="./img/2.png" width="30px">' +
							v.name +
							"</h1>";
						content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 60%">';
					} else if (v.state == 3) {
						//停止中
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff"><img src="./img/5.png" width="30px">' +
							v.name +
							"</h1>";
						content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 60%">';
					} else if (v.state == 0) {
						//待機
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff"><img src="./img/3.png" width="30px">' +
							v.name +
							"</h1>";
						content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 60%">';
					} else if (v.state == null) {
						//待機
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff">' +
							v.name +
							"</h1>";
						content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 75%">';
					}
					content += "</div>";
					content += '<div class="col-md-4">';
					content +=
						'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 30px;border-style:inset;border-color:#ffffff">' +
						Measuring +
						"：" +
						v.dyelot +
						" " +
						Step +
						" " +
						v.step +
						"</h1>";
					content +=
						'<table border="2" style="width: 100%;text-align: center;color: #ffffff;margin-top:5%;font-size: 22px">';
					content += "<tr>";
					content += "<th>" + Step + "</th>";
					content += "<th>" + ProductCode + "</th>";
					content += "<th>" + TargetGrams + "</th>";
					content += "<th>" + DispenseGrams + "</th>";
					content += "</tr>";
					if (v.product == null) {
						var product = 0;
					} else {
						var product = v.product.length;
					}
					for (i = 0; i < product; i++) {
						content += "<tr><td>" + v.type[i] + "</td>";
						content += "<td>" + v.product[i] + "</td>";
						content += "<td>" + v.tgrams[i] + "</td>";
						content += "<td>" + v.dgrams[i] + "</td></tr>";
					}
					content += "</table>";
					content += "</div>";
					content += '<div class="col-md-6" id="machine_data"><div id="machine_data"></div>';
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += "</div>";
					content += '<div class="row">';
					content += '<div class="col-md-12" style="overflow:auto;display:block">';
					content +=
						'<table id="example" class="display" style="width:100%;text-align: center;color: #ffffff">';
					content += '<thead class="table-striped table-dark">';
					content += "<tr>";
					content += '<th style="white-space: nowrap;">' + ProductCode + "</th>";
					content += '<th style="white-space: nowrap;">' + ProductName + "</th>";
					content += '<th style="white-space: nowrap;">' + Machine + "</th>";
					content += '<th style="white-space: nowrap;">' + TargetGrams + "</th>";
					content += '<th style="white-space: nowrap;">' + DispenseGrams + "</th>";
					content += '<th style="white-space: nowrap;">' + Measurement_error + "</th>";
					content += '<th style="white-space: nowrap;">' + FinishStartTime + "</th>";
					content += '<th style="white-space: nowrap;">' + FinishEndTime + "</th>";
					content += '<th style="white-space: nowrap;">' + DispenseSeconds + "</th>";
					content += "</tr>";
					content += "</thead>";
					content += "<tbody>";
					for (i = 0; i < v.product1.length; i++) {
						content += "<tr><td>" + v.product1[i] + "</td>";
						content += "<td>" + v.pname[i] + "</td>";
						content += "<td>" + v.dest[i] + "</td>";
						content += "<td>" + v.dgrams1[i] + "</td>";
						content += "<td>" + v.tgrams1[i] + "</td>";
						content += "<td>" + v.error[i] + "</td>";
						content += "<td>" + v.starttime[i] + "</td>";
						content += "<td>" + v.endtime2[i] + "</td>";
						content += "<td>" + v.dsec[i] + "</td></tr>";
					}
					content += "</tbody>";
					content += "</table>";
					content += "</div>";

					$("#content").html(content);
					draw5(v.ds);
					$(document).ready(function () {
						$("#example").DataTable({
							searching: false, //去掉搜索框
							paging: false, //禁止分页
							info: false, //去掉底部文字
							order: [[6, "desc"]], //時間最大排序
						});
					});
				}
			});
		},
		// error: function(XMLHttpRequest, textStatus, errorThrown) {
		// console.log(XMLHttpRequest.status);
		// console.log(XMLHttpRequest.readyState);
		// console.log(textStatus);
		// }
	});

	var myVar = setInterval(function () {
		$.ajax({
			url: "./machine_data.php" + queryString,
			type: "POST",
			data: { machine_id: machine_id, machines: machines }, //也可以把ViewModel的資料送到後端
			dataType: "json",
			success: function (show_data) {
				$.each(show_data, function (k, v) {
					// console.log(v.state);
					// alert( "Key: " + k + ", Value: " + v );
					content = "";
					if (machines == "DyeMachines") {
						content += '<div class="row">';
						content += '<div class="col-md-3">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						if (v.state == 1) {
							//運行中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/1.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 2) {
							//暫停中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/2.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 3) {
							//停止中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/5.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 0) {
							//待機
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/3.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						}
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';

						content +=
							'<li class="mdl-list__item" style="font-size: 22px"><div style="background-color:#' +
							v.color +
							';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
							v.dyelot +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
							v.color1 +
							';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
							v.dyelot1 +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
							v.color2 +
							';width: 30%;height: 20px;border-radius:10px"></div>&nbsp;&nbsp;' +
							v.dyelot2 +
							"</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += '<div class="col-md-4">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						content +=
							'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/t.png" width="30px" height="30px">' +
							v.t +
							"</h2>";
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
						content +=
							'<li class="mdl-list__item" style="font-size: 22px">' +
							program +
							"：" +
							v.program +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							FabricName +
							"：" +
							v.faname +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							TotalWeight +
							"：" +
							v.totalw +
							"kg</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += '<div class="col-md-5">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						content +=
							'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/time.png" width="30px" height="30px">Time</h2>';
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
						content +=
							'<li class="mdl-list__item" style="font-size: 22px">' +
							DyelotStartTime +
							"：" +
							v.newtime +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							DyelotEndTime +
							"：" +
							v.endtime +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							time_remaining +
							"：" +
							v.lesstime +
							"</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += "</div><br>"; //row
						content += '<div class="row">';
						content += '<div  class="col-md-12" id="machine_data"></div>';
						content += "</div>"; //row
						content += '<div class="row">';
						content += '<div class="col-md-3" id="speed1"></div>';
						content += '<div class="col-md-3 " id="speed2"></div>';
						content += '<div class="col-md-3 " id="speed3"></div>';
						content += '<div class="col-md-3 " id="speed4"></div>';
						content += "</div>"; //row//end
						$("#content").html(content);
						draw1(v.speed1);
						draw2(v.speed2);
						draw3(v.speed3);
						draw4(v.speed4);
						draw5(v.ok_data, v.note);
					}
					if (machines == "FinishMachines") {
						content += '<div class="row">';
						content += '<div class="col-md-3">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						if (v.state == 1) {
							//運行中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/1.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 2) {
							//暫停中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/2.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 3) {
							//停止中
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/5.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == 0) {
							//待機
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/3.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						} else if (v.state == null) {
							//待機
							content +=
								'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/1.png" width="30px" height="30px">' +
								v.name +
								"</h2>";
						}
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
						content += '<li class="mdl-list__item" style="font-size: 22px">' + v.job + "</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							v.job1 +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							v.job2 +
							"</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += '<div class="col-md-4">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						content +=
							'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/txt.png" width="30px" height="30px">' +
							Information +
							"</h2>";
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
						content +=
							'<li class="mdl-list__item" style="font-size: 22px">' +
							program +
							"：" +
							v.program +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							FabricName +
							"：" +
							v.faname +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							TotalLength +
							"：" +
							v.totall +
							"" +
							yard +
							"</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += '<div class="col-md-5">';
						content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
						content += '<div class="mdl-card__title">';
						content +=
							'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/time.png" width="30px" height="30px">Time</h2>';
						content += "</div>";
						content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
						content += "<center>";
						content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
						content +=
							'<li class="mdl-list__item" style="font-size: 22px">' +
							FinishStartTime +
							"：" +
							v.newtime +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							FinishEndTime +
							"：" +
							v.endtime +
							"</li>";
						content +=
							'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
							time_remaining +
							"：" +
							v.lesstime +
							"</li>";
						content += "</ul>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += "</div><br>"; //row
						content += '<div class="row">';
						content += '<div  class="col-md-12" id="machine_data"></div>';
						content += "</div>"; //row
						content += '<div class="row">';
						content += '<div class="col-md-4" id="speed1"></div>';
						content += '<div class="col-md-4" id="speed2"></div>';
						content += '<div class="col-md-4" id="speed3"></div>';
						content += "</div>"; //row//end
						$("#content").html(content);
						draw1(v.speed1);
						draw2(v.speed2);
						draw3(v.speed3);
						// console.log(v.lobt1[0].data);
						draw5(
							v.lobt1[0].data,
							v.lobt2[0].data,
							v.lobt3[0].data,
							v.lobt4[0].data,
							v.lobt5[0].data,
							v.lobt6[0].data
						);
					}
					if (machines == "DispenserMachines") {
						content += '<div style="display:block:padding: 10px">';
						content += '<div class="row">';
						content += '<div class="col-md-2" >';
						if (v.state == 1) {
							//運行中
							content +=
								'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff"><img src="./img/1.png" width="30px">' +
								v.name +
								"</h1>";
							content += '<img src="' + v.img + '" width="30px">';
						} else if (v.state == 2) {
							//暫停中
							content +=
								'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff;"><img src="./img/2.png" width="30px">' +
								v.name +
								"</h1>";
							content += '<img src="' + v.img + '" style="width:100%;text-align: center">';
						} else if (v.state == 3) {
							//停止中
							content +=
								'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff"><img src="./img/5.png" width="30px">' +
								v.name +
								"</h1>";
							content += '<img src="' + v.img + '" style="width:100%;text-align: center">';
						} else if (v.state == 0) {
							//待機
							content +=
								'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff"><img src="./img/3.png" width="30px">' +
								v.name +
								"</h1>";
							content += '<img src="' + v.img + '" style="width:100%;text-align: center">';
						} else if (v.state == null) {
							//待機
							content +=
								'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff">' +
								v.name +
								"</h1>";
							content += '<img src="' + v.img + '" style="width:100%;text-align: center;height: 60%">';
						}
						content += "</div>";
						content += '<div class="col-md-4">';
						content +=
							'<h1 style="color: #ffffff;border-radius:10px;background-color:#383838;text-align: center;font-size: 24px;border-style:inset;border-color:#ffffff">' +
							Measuring +
							"：" +
							v.dyelot +
							" " +
							Step +
							" " +
							v.step +
							"</h1>";
						content +=
							'<table border="2" style="width: 100%;text-align: center;color: #ffffff;margin-top:5%;">';
						content += "<tr>";
						content += "<th>" + Step + "</th>";
						content += "<th>" + ProductCode + "</th>";
						content += "<th>" + TargetGrams + "</th>";
						content += "<th>" + DispenseGrams + "</th>";
						content += "</tr>";
						if (v.product == null) {
							var product = 0;
						} else {
							var product = v.product.length;
						}
						for (i = 0; i < product; i++) {
							content += "<tr><td>" + v.type[i] + "</td>";
							content += "<td>" + v.product[i] + "</td>";
							content += "<td>" + v.tgrams[i] + "</td>";
							content += "<td>" + v.dgrams[i] + "</td></tr>";
						}
						content += "</table>";
						content += "</div>";
						content += '<div class="col-md-6" id="machine_data"><div id="machine_data"></div>';
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += "</div>";
						content += '<div class="row">';
						content += '<div class="col-md-12" style="overflow:auto;display:block">';
						content +=
							'<table id="example" class="display" style="width:100%;text-align: center;color: #ffffff">';
						content += '<thead class="table-striped table-dark">';
						content += "<tr>";
						content += '<th style="white-space: nowrap;">' + ProductCode + "</th>";
						content += '<th style="white-space: nowrap;">' + ProductName + "</th>";
						content += '<th style="white-space: nowrap;">' + Machine + "</th>";
						content += '<th style="white-space: nowrap;">' + TargetGrams + "</th>";
						content += '<th style="white-space: nowrap;">' + DispenseGrams + "</th>";
						content += '<th style="white-space: nowrap;">' + Measurement_error + "</th>";
						content += '<th style="white-space: nowrap;">' + FinishStartTime + "</th>";
						content += '<th style="white-space: nowrap;">' + FinishEndTime + "</th>";
						content += '<th style="white-space: nowrap;">' + DispenseSeconds + "</th>";
						content += "</tr>";
						content += "</thead>";
						content += "<tbody>";
						for (i = 0; i < v.product1.length; i++) {
							content += "<tr><td>" + v.product1[i] + "</td>";
							content += "<td>" + v.pname[i] + "</td>";
							content += "<td>" + v.dest[i] + "</td>";
							content += "<td>" + v.dgrams1[i] + "</td>";
							content += "<td>" + v.tgrams1[i] + "</td>";
							content += "<td>" + v.error[i] + "</td>";
							content += "<td>" + v.starttime[i] + "</td>";
							content += "<td>" + v.endtime2[i] + "</td>";
							content += "<td>" + v.dsec[i] + "</td></tr>";
						}
						content += "</tbody>";
						content += "</table>";
						content += "</div>";

						$("#content").html(content);
						draw5(v.ds);
						$(document).ready(function () {
							$("#example").DataTable({
								searching: false, //去掉搜索框
								paging: false, //禁止分页
								info: false, //去掉底部文字
								order: [[6, "desc"]],
							});
						});
					}
				});
			},
		});
	}, 300000);
	$("#machine_id").change(function () {
		clearInterval(myVar);
	});
	if (machines == "DyeMachines") {
		function draw5(seriesDict10, note) {
			seriesDict10.reverse();
			//console.log(note);
			Highcharts.setOptions({ global: { useUTC: false } });
			Highcharts.chart("machine_data", {
				chart: {
					backgroundColor: "rgba(0,0,0,0)",
					height: 400,
				},
				title: {
					text: "",
					style: {
						color: "white",
						fontSize: "24px",
					},
				},
				legend: {
					enabled: true,
					itemStyle: {
						color: "#ffffff",
						fontWeight: "bold",
					},
				},
				xAxis: {
					startOnTick: true,
					type: "datetime",
					dateTimeLabelFormats: {
						hour: "%H:%M",
					},
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
					},
				},
				yAxis: {
					title: {
						text: "",
					},
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
					},
				},
				tooltip: {
					// formatter: function() {
					//     console.log(this.z);
					//     return 'X: '+this.x+'<br/>Y: '+this.y+'<br/>Z: '+this.z+'';
					// }
					pointFormat: "工單:<b>{point.dye}</b><br>{series.name}<b>{point.y:,.1f}</b><br>備註:{point.z}",
				},
				plotOptions: {
					series: {
						turboThreshold: 2000, // 或者更多，必須大於陣列長度（1440）
					},
					area: {
						pointStart: 0,
						marker: {
							enabled: false,
							symbol: "circle",
							radius: 2,
							states: {
								hover: {
									enabled: false,
								},
							},
						},
					},
				},
				credits: false,
				series: seriesDict10,
			});
		}
		//--------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------
		function draw1(seriesDict1) {
			// console.log(seriesDict1);
			var gaugeOptions1 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed1",
				Highcharts.merge(gaugeOptions1, {
					yAxis: {
						min: 0,
						max: 1600,
						title: {
							text: MainPumpSpeed,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict1],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:10px;color: #ffffff">RPM</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " RPM",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw2(seriesDict2) {
			var gaugeOptions2 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed2",
				Highcharts.merge(gaugeOptions2, {
					yAxis: {
						min: 0,
						max: 1600,
						title: {
							text: ReelSpeed,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict2],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:10px;color: #ffffff">RPM</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " RPM",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}

		function draw3(seriesDict3) {
			var gaugeOptions3 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed3",
				Highcharts.merge(gaugeOptions3, {
					yAxis: {
						min: 0,
						max: 200,
						title: {
							text: CycleTime,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict3],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:10px;color: #ffffff">RPM</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " s",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw4(seriesDict4) {
			var gaugeOptions4 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#DF5353"], // green
						[0.5, "#da64df"], // yellow
						[0.9, "#383abf"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed4",
				Highcharts.merge(gaugeOptions4, {
					yAxis: {
						min: 0,
						max: 100,
						title: {
							text: Progress,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict4],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:10px;color: #ffffff">%</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " %",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
	}
	if (machines == "FinishMachines") {
		function draw5(Temperature1, Temperature2, Temperature3, Temperature4, Temperature5, Temperature6) {
			// console.log(Temperature2);
			Highcharts.setOptions({ global: { useUTC: false } });
			Highcharts.chart("machine_data", {
				chart: {
					backgroundColor: "rgba(0,0,0,0)",
					height: 400,
				},
				title: {
					text: "",
					style: {
						color: "white",
						fontSize: "24px",
					},
				},
				legend: {
					enabled: true,
					itemStyle: {
						color: "#ffffff",
						fontWeight: "bold",
					},
				},
				xAxis: {
					startOnTick: true,
					type: "datetime",
					dateTimeLabelFormats: {
						hour: "%H:%M",
					},
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
					},
				},
				yAxis: {
					title: {
						text: "",
					},
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
					},
				},
				tooltip: {
					pointFormat:
						"工單:<b>{point.job}</b><br>{series.name}:<b>{point.y:,.1f}</b><br>備註:<b>{point.note1}",
				},
				plotOptions: {
					area: {
						pointStart: 0,
						marker: {
							enabled: false,
							symbol: "circle",
							radius: 2,
							states: {
								hover: {
									enabled: false,
								},
							},
						},
					},
				},
				credits: false,
				series: [
					{
						name: "風箱1溫度",
						type: "line",
						data: Temperature1,
						color: Highcharts.getOptions().colors[0],
					},
					{
						name: "風箱2溫度",
						type: "line",
						data: Temperature2,
						color: Highcharts.getOptions().colors[7],
					},
					{
						name: "風箱3溫度",
						type: "line",
						data: Temperature3,
						color: Highcharts.getOptions().colors[2],
					},
					{
						name: "風箱4溫度",
						type: "line",
						data: Temperature4,
						color: Highcharts.getOptions().colors[3],
					},
					{
						name: "風箱5溫度",
						type: "line",
						data: Temperature5,
						color: Highcharts.getOptions().colors[4],
					},
					{
						name: "風箱6溫度",
						type: "line",
						data: Temperature6,
						color: Highcharts.getOptions().colors[6],
					},
				],
			});
		}
		//--------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------
		function draw1(seriesDict1) {
			// console.log(seriesDict1);
			var gaugeOptions1 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed1",
				Highcharts.merge(gaugeOptions1, {
					yAxis: {
						min: 0,
						max: 100,
						title: {
							text: MainSpeed,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict1],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:18px;color: #ffffff">m/min</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " m/min",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw2(seriesDict2) {
			var gaugeOptions2 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed2",
				Highcharts.merge(gaugeOptions2, {
					yAxis: {
						min: 0,
						max: 100,
						title: {
							text: Width,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict2],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:18px;color: #ffffff">inch</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " inch",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw3(seriesDict3) {
			var gaugeOptions3 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed3",
				Highcharts.merge(gaugeOptions3, {
					yAxis: {
						min: -200,
						max: 200,
						title: {
							text: OverFeed,
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict3],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}%</span>  ' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " s",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
	}
	if (machines == "DispenserMachines") {
		function draw5(ds) {
			// console.log(ds);
			Highcharts.setOptions({ global: { useUTC: false } });
			Highcharts.chart("machine_data", {
				chart: {
					// type: 'column',
					backgroundColor: "rgba(0,0,0,0)",
					height: 200,
				},
				title: {
					text: "",
					style: {
						color: "white",
						fontSize: "24px",
					},
				},
				legend: {
					enabled: false,
					itemStyle: {
						color: "#ffffff",
						fontWeight: "bold",
					},
				},
				xAxis: {
					type: "category",
					allowDecimals: false,
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
						// enabled:false
					},
				},
				yAxis: {
					title: {
						text: "",
					},
					labels: {
						style: {
							fontSize: "12px",
							fontFamily: "Verdana, sans-serif",
							color: "#FFFFFF",
						},
					},
				},
				tooltip: {
					pointFormat: "{series.name}<b>{point.y}</b>",
				},
				plotOptions: {
					series: {
						borderWidth: 0,
						dataLabels: {
							enabled: true,
							format: "{point.y}",
							style: {
								fontSize: "12px",
								fontFamily: "Verdana, sans-serif",
								color: "#FFFFFF",
							},
						},
					},
				},
				credits: false,
				series: [
					{
						name: "計量次數",
						type: "column",
						data: ds,
						color: Highcharts.getOptions().colors[2],
					},
				],
			});
		}
		//--------------------------------------------------------------------
		// -------------------------------------------------------------------------------------------
		function draw1(seriesDict1) {
			// console.log(seriesDict1);
			var gaugeOptions1 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed1",
				Highcharts.merge(gaugeOptions1, {
					yAxis: {
						min: 0,
						max: 1600,
						title: {
							text: "主速度",
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict1],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									'<span style="font-size:10px;color: #ffffff">RPM</span>' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " RPM",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw2(seriesDict2) {
			var gaugeOptions2 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed2",
				Highcharts.merge(gaugeOptions2, {
					yAxis: {
						min: 0,
						max: 1600,
						title: {
							text: "幅寬",
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict2],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " RPM",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
		function draw3(seriesDict3) {
			var gaugeOptions3 = {
				chart: {
					type: "solidgauge",
					backgroundColor: "rgba(0,0,0,0)",
					height: 160,
				},

				title: null,

				pane: {
					center: ["50%", "85%"],
					size: "140%",
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#EEE",
						innerRadius: "60%",
						outerRadius: "100%",
						shape: "arc",
					},
				},

				exporting: {
					enabled: false,
				},

				tooltip: {
					enabled: false,
				},

				// the value axis
				yAxis: {
					stops: [
						[0.1, "#55BF3B"], // green
						[0.5, "#DDDF0D"], // yellow
						[0.9, "#DF5353"], // red
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -60,
					},
					labels: {
						enabled: false,
					},
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true,
						},
					},
				},
			};
			Highcharts.chart(
				"speed3",
				Highcharts.merge(gaugeOptions3, {
					yAxis: {
						min: 0,
						max: 200,
						title: {
							text: "條數",
							style: {
								fontSize: "18px",
								fontFamily: "",
								color: "#ffffff",
							},
						},
					},

					credits: {
						enabled: false,
					},

					series: [
						{
							name: "Speed",
							data: [seriesDict3],
							dataLabels: {
								format:
									'<div style="text-align:center">' +
									'<span style="font-size:18px;color: #ffffff">{y}</span>  ' +
									"</div>",
							},
							tooltip: {
								valueSuffix: " s",
								style: {
									fontSize: "24px",
									color: "#ffffff",
								},
							},
						},
					],
				})
			);
		}
	}
});
