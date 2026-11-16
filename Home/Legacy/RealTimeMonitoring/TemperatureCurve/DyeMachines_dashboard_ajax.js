var machines = "DyeMachines";
const queryString = window.location.search;
$.ajax({
	url: "./machine_data_dashboard.php" + queryString,
	type: "POST",
	data: { machine_num: machine_num, machines: machines }, //也可以把ViewModel的資料送到後端
	dataType: "json",
	success: function (show_data) {
		//$.each(show_data, function (k, v) {
		//console.log(show_data);
		for (var i = 0; i <= show_data.length; i++) {
			if (show_data[i]) {
				content = "";
				content += '<div class="row">';
				content += '<div class="col-md-3">';
				content += '<div class="mdl-card mdl-shadow--2dp line-chart">';
				content += '<div class="mdl-card__title">';
				if (show_data[i].state == 1) {
					//運行中
					content +=
						'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/1.png" width="30px" height="30px">' +
						show_data[i].name +
						"</h2>";
				} else if (show_data[i].state == 2) {
					//暫停中
					content +=
						'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/2.png" width="30px" height="30px">' +
						show_data[i].name +
						"</h2>";
				} else if (show_data[i].state == 3) {
					//停止中
					content +=
						'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/5.png" width="30px" height="30px">' +
						show_data[i].name +
						"</h2>";
				} else if (show_data[i].state == 0) {
					//待機
					content +=
						'<h2 class="mdl-card__title-text" style="margin:0px auto;font-size: 26px"><img src="./img/3.png" width="30px" height="30px">' +
						show_data[i].name +
						"</h2>";
				}
				content += "</div>";
				content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
				content += "<center>";
				content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';

				content +=
					'<li class="mdl-list__item" style="font-size: 22px"><div style="background-color:#' +
					show_data[i].color +
					';width: 20%;height: 20px;border-radius:10px"></div>&nbsp;' +
					show_data[i].dyelot +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
					show_data[i].color1 +
					';width: 20%;height: 20px;border-radius:10px"></div>&nbsp;' +
					show_data[i].dyelot1 +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px"><div style="background-color:#' +
					show_data[i].color2 +
					';width: 20%;height: 20px;border-radius:10px"></div>&nbsp;' +
					show_data[i].dyelot2 +
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
					show_data[i].t +
					"</h2>";
				content += "</div>";
				content += '<div class="mdl-card__supporting-text" style="margin:0px auto;">';
				content += "<center>";
				content += '<ul class="mdl-list" style="display:table;margin:0px auto;width: 100%">';
				content +=
					'<li class="mdl-list__item" style="font-size: 22px">' +
					program +
					"：" +
					show_data[i].program +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
					FabricName +
					"：" +
					show_data[i].faname +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
					TotalWeight +
					"：" +
					show_data[i].totalw +
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
					show_data[i].newtime +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
					DyelotEndTime +
					"：" +
					show_data[i].endtime +
					"</li>";
				content +=
					'<li class="mdl-list__item list__item--border-top" style="font-size: 22px">' +
					time_remaining +
					"：" +
					show_data[i].lesstime +
					"</li>";
				content += "</ul>";
				content += "</div>";
				content += "</div>";
				content += "</div>";
				content += "</div><br>"; //row
				content += '<div class="row">';
				content += '<div  class="col-md-12" id="machine_data' + (i + 1) + '"></div>';
				content += "</div>"; //row
				content += '<div class="row">';
				content += '<div class="col-md-3" id="speed' + (i * 4 + 1) + '"></div>';
				content += '<div class="col-md-3 " id="speed' + (i * 4 + 2) + '"></div>';
				content += '<div class="col-md-3 " id="speed' + (i * 4 + 3) + '"></div>';
				content += '<div class="col-md-3 " id="speed' + (i * 4 + 4) + '"></div>';
				content += "</div>"; //row//end
				$("#content" + i).html(content);

				//==================================圖表===========================
				var chart1 = "speed" + (1 + 4 * i);
				var chart2 = "speed" + (2 + 4 * i);
				var chart3 = "speed" + (3 + 4 * i);
				var chart4 = "speed" + (4 + 4 * i);
				var chart5 = "machine_data" + (i + 1);

				function draw1(seriesDict1) {
					var gaugeOptions1 = {
						chart: {
							type: "solidgauge",
							backgroundColor: "rgba(0,0,0,0)",
							height: 140,
						},
						title: null,
						pane: {
							center: ["50%", "85%"],
							size: "100%",
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
						chart1,
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
							height: 140,
						},

						title: null,

						pane: {
							center: ["50%", "85%"],
							size: "100%",
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
						chart2,
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
							height: 140,
						},

						title: null,

						pane: {
							center: ["50%", "85%"],
							size: "100%",
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
						chart3,
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
							height: 140,
						},

						title: null,

						pane: {
							center: ["50%", "85%"],
							size: "100%",
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
						chart4,
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
				function draw5(seriesDict10, note) {
					//var data = seriesDict10
					//console.log(seriesDict10)
					var data = seriesDict10[i];

					//seriesDict10.reverse();
					//console.log(note);
					Highcharts.setOptions({ global: { useUTC: false } });
					Highcharts.chart(chart5, {
						chart: {
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
						// tooltip: {
						//     pointFormat: '工單:<b>{point.dye}</b><br>{series.name}<b>{point.y:,.1f}</b><br>備註:{point.z}'
						// },
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
						series: [data],
					});
				}
				draw1(show_data[i].speed1);
				draw2(show_data[i].speed2);
				draw3(show_data[i].speed3);
				draw4(show_data[i].speed4);
				draw5(show_data[i].ok_data);
				//==================================圖表
			} //)
		}
	},
});
