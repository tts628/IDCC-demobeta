$("#machine_name").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajwater.php" + queryString,
		type: "POST",
		dataType: "json",
		data: {
			factory_name: factory_name,
			machine_name: machine_name,
			day: day,
			dayname: dayname,
			weekname: weekname,
			monthname: monthname,
			month_name: month_name,
		},
		success: function (res) {
			console.log(res[16].hour);
			console.log(res);

			day1 = res[1].data[0]["name"];
			day2 = res[1].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			water_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[3],
				res[16].hour,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_day_Data(
				machine_name_text,
				res[4].data,
				res[5].data,
				res[6].data,
				res[7],
				res[16].day,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_week_Data(
				machine_name_text,
				res[8].data,
				res[9].data,
				res[10].data,
				res[11],
				res[16].week,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_month_Data(
				machine_name_text,
				res[12].data,
				res[13].data,
				res[14].data,
				res[15],
				res[16].month,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			if (machine_name != "") {
				$("#hour_water").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_water").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_water").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_water").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
	var machine_name = $("#machine_name").val();
});
$("#day").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajwater.php" + queryString,
		type: "POST",
		dataType: "json",
		data: {
			factory_name: factory_name,
			machine_name: machine_name,
			day: day,
			dayname: dayname,
			weekname: weekname,
			monthname: monthname,
			month_name: month_name,
		},
		success: function (res) {
			day1 = res[1].data[0]["name"];
			day2 = res[1].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			water_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[3],
				res[16].hour,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_day_Data(
				machine_name_text,
				res[4].data,
				res[5].data,
				res[6].data,
				res[7],
				res[16].day,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_week_Data(
				machine_name_text,
				res[8].data,
				res[9].data,
				res[10].data,
				res[11],
				res[16].week,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_month_Data(
				machine_name_text,
				res[12].data,
				res[13].data,
				res[14].data,
				res[15],
				res[16].month,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			if (machine_name != "") {
				$("#hour_water").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_water").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_water").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_water").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});

$("#factory_name").change(function () {
	var factory_name = $("#factory_name :selected").val();
	$("#machine_name :selected").remove();
	$.ajax({
		url: "ajfactory.php" + queryString,
		method: "POST",
		data: {
			factory_name: factory_name,
			machine_name_text: machine_name_text,
		},
		success: function (res) {
			$("#machine_name").html(res);
			// console.log(res)
			//處理回吐的資料
		},
	});
	var machine_name = "Allmachine";
	// var machine_name_text = '全部機台';//需要php翻譯和修改ajfactory

	var day = $("#day").val();
	$.ajax({
		url: "./ajwater.php" + queryString,
		type: "POST",
		dataType: "json",
		data: {
			factory_name: factory_name,
			machine_name: machine_name,
			day: day,
			dayname: dayname,
			weekname: weekname,
			monthname: monthname,
			month_name: month_name,
		},
		success: function (res) {
			day1 = res[1].data[0]["name"];
			day2 = res[1].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			water_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[3],
				res[16].hour,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_day_Data(
				machine_name_text,
				res[4].data,
				res[5].data,
				res[6].data,
				res[7],
				res[16].day,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_week_Data(
				machine_name_text,
				res[8].data,
				res[9].data,
				res[10].data,
				res[11],
				res[16].week,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			water_month_Data(
				machine_name_text,
				res[12].data,
				res[13].data,
				res[14].data,
				res[15],
				res[16].month,
				ColdWaterUsage_word,
				HotWaterUsage_word,
				RecycledWaterUsage_word,
				Water4Usage_word
			);
			if (machine_name != "") {
				$("#hour_water").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_water").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_water").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_water").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
	var machine_name = $("#machine_name").val();
});

//長條圖開始
function water_hour_Data(
	machine_name,
	Water1Usage,
	Water2Usage,
	Water3Usage,
	Water4Usage,
	hour,
	ColdWaterUsage_word,
	HotWaterUsage_word,
	RecycledWaterUsage_word,
	Water4Usage_word
) {
	//console.log(hour)
	Highcharts.chart("water_hour", {
		chart: {
			zoomType: "xy",
			backgroundColor: "rgba(0,0,0,0)",
		},
		title: {
			text: machine_name,
			align: "center",
			style: {
				color: "#FFFFFF",
			},
		},
		xAxis: [
			{
				type: "category",
				crosshair: true,
				labels: {
					style: {
						color: "#FFFFFF",
						fontSize: "18px",
					},
					formatter: function () {
						return this.value + hour_word; // this.value 是當前的 x 軸標籤
					},
				},
			},
		],
		yAxis: [
			{
				// Secondary yAxis
				// gridLineWidth: 0,
				//Water1標籤
				labels: {
					format: "{value}" + hour[0].Water1Usage_hour_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
				title: {
					text: null,
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				//Water2標籤
				labels: {
					format: "{value}" + hour[0].Water2Usage_hour_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + hour[0].Water3Usage_hour_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + hour[0].Water4Usage_hour_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
				opposite: true,
			},
		],
		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "20px",
			},
		},
		tooltip: {
			shared: true,
		},
		series: [
			{
				name: ColdWaterUsage_word,
				type: "column", //柱狀圖
				color: Highcharts.getOptions().colors[0],
				//yAxis: 1,
				data: Water1Usage,
				tooltip: {
					valueSuffix: hour[0].Water1Usage_hour_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[0], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: HotWaterUsage_word,
				type: "column", //柱狀圖
				color: "#FF0000",
				//yAxis: 2,
				data: Water2Usage,
				tooltip: {
					valueSuffix: hour[0].Water2Usage_hour_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#FF0000", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: RecycledWaterUsage_word,
				type: "column", //柱狀圖
				color: "#00FF00",
				//yAxis: 3,
				data: Water3Usage,
				tooltip: {
					valueSuffix: hour[0].Water3Usage_hour_unit,
				},
			},
			// 去掉軟水
			// {
			//     name: Water4Usage_word,
			//     type: 'column', //柱狀圖
			//     color: '#FFFF00',
			//     //yAxis: 4,
			//     data: Water4Usage,
			//     tooltip: {
			//         valueSuffix: hour[0].Water4Usage_hour_unit,
			//     },
			//     dataLabels: {
			//         enabled: false
			//     }
			// }
		],
		responsive: {
			rules: [
				{
					condition: {
						maxWidth: 500,
					},
					chartOptions: {
						legend: {
							floating: false,
							layout: "horizontal",
							align: "center",
							verticalAlign: "bottom",
							x: 0,
							y: 0,
						},
						yAxis: [
							{
								labels: {
									align: "right",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								labels: {
									align: "left",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								visible: false,
							},
						],
					},
				},
			],
		},
		credits: { enabled: false },
	});
}
//每日長條圖
function water_day_Data(
	machine_name,
	Water1Usage,
	Water2Usage,
	Water3Usage,
	Water4Usage,
	day,
	ColdWaterUsage_word,
	HotWaterUsage_word,
	RecycledWaterUsage_word,
	Water4Usage_word
) {
	//console.log(day[0].Water1Usage_day_unit);
	Highcharts.chart("water_day", {
		chart: {
			zoomType: "xy",
			backgroundColor: "rgba(0,0,0,0)",
		},
		title: {
			text: machine_name,
			align: "center",
			style: {
				color: "#FFFFFF",
			},
		},
		xAxis: [
			{
				type: "category",
				crosshair: true,
				labels: {
					style: {
						color: "#FFFFFF",
						fontSize: "18px",
					},
				},
			},
		],
		yAxis: [
			{
				// Secondary yAxis
				// gridLineWidth: 0;
				labels: {
					format: "{value} " + day[0].Water1Usage_day_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
				title: {
					text: null,
				},
				opposite: true,
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + day[0].Water2Usage_day_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + day[0].Water3Usage_day_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + day[0].Water4Usage_day_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
				opposite: true,
			},
		],
		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "20px",
			},
		},
		tooltip: {
			shared: true,
		},
		series: [
			{
				name: ColdWaterUsage_word,
				type: "column",
				color: Highcharts.getOptions().colors[0],
				// yAxis: 1,
				data: Water1Usage,
				tooltip: {
					valueSuffix: day[0].Water1Usage_day_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[0], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: HotWaterUsage_word,
				type: "column",
				color: "#FF0000",
				// yAxis: 1,
				data: Water2Usage,
				tooltip: {
					valueSuffix: day[0].Water2Usage_day_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#FF0000", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: RecycledWaterUsage_word,
				type: "column",
				color: "#00FF00",
				//yAxis: 2,
				data: Water3Usage,
				tooltip: {
					valueSuffix: day[0].Water3Usage_day_unit,
				},
			},
			// 去掉軟水
			// {
			//     name: Water4Usage_word,
			//     type: 'column',
			//     color: '#FFFF00',
			//     // yAxis: 1,
			//     data: Water4Usage,
			//     tooltip: {
			//         valueSuffix: day[0].Water4Usage_day_unit,
			//     },
			//     dataLabels: {
			//         enabled: false
			//     }
			// }
		],
		responsive: {
			rules: [
				{
					condition: {
						maxWidth: 500,
					},
					chartOptions: {
						legend: {
							floating: false,
							layout: "horizontal",
							align: "center",
							verticalAlign: "bottom",
							x: 0,
							y: 0,
						},
						yAxis: [
							{
								labels: {
									align: "right",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								labels: {
									align: "left",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								visible: false,
							},
						],
					},
				},
			],
		},
		credits: { enabled: false },
	});
}
//每週長條圖
function water_week_Data(
	machine_name,
	Water1Usage,
	Water2Usage,
	Water3Usage,
	Water4Usage,
	week,
	ColdWaterUsage_word,
	HotWaterUsage_word,
	RecycledWaterUsage_word,
	Water4Usage_word
) {
	Highcharts.chart("water_week", {
		chart: {
			zoomType: "xy",
			backgroundColor: "rgba(0,0,0,0)",
		},
		title: {
			text: machine_name,
			align: "center",
			style: {
				color: "#FFFFFF",
			},
		},
		xAxis: [
			{
				type: "category",
				crosshair: true,
				labels: {
					style: {
						color: "#FFFFFF",
						fontSize: "18px",
					},
				},
			},
		],
		yAxis: [
			{
				// Secondary yAxis
				// gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value} " + week[0].Water1Usage_week_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
				opposite: true,
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + week[0].Water2Usage_week_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + week[0].Water3Usage_week_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + week[0].Water4Usage_week_unit,
					style: {
						color: "#33FF33", //標籤的大小與顏色
						fontSize: "20px",
					},
				},
				opposite: true,
			},
		],
		legend: {
			itemStyle: {
				color: "#FFFFFF", //標籤名字的大小與顏色
				fontSize: "20px",
			},
		},
		tooltip: {
			shared: true,
		},
		series: [
			{
				name: ColdWaterUsage_word,
				type: "column",
				color: Highcharts.getOptions().colors[0], //柱狀的顏色
				// yAxis: 1,
				data: Water1Usage,
				tooltip: {
					valueSuffix: week[0].Water1Usage_week_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[0], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: HotWaterUsage_word,
				type: "column",
				color: "#FF0000",
				// yAxis: 1,
				data: Water2Usage,
				tooltip: {
					valueSuffix: week[0].Water2Usage_week_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#FF0000", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: RecycledWaterUsage_word,
				type: "column",
				color: "#00FF00",
				// yAxis: 1,
				data: Water3Usage,
				tooltip: {
					valueSuffix: week[0].Water3Usage_week_unit,
				},
			},
			// 去掉軟水
			// {
			//     name: Water4Usage_word,
			//     type: 'column',
			//     color: '#FFFF00',
			//     // yAxis: 1,
			//     data: Water4Usage,
			//     tooltip: {
			//         valueSuffix: week[0].Water4Usage_week_unit,
			//     },
			//     dataLabels: {
			//         enabled: false

			//     }

			// }
		],
		responsive: {
			rules: [
				{
					condition: {
						maxWidth: 500,
					},
					chartOptions: {
						legend: {
							floating: false,
							layout: "horizontal",
							align: "center",
							verticalAlign: "bottom",
							x: 0,
							y: 0,
						},
						yAxis: [
							{
								labels: {
									align: "right",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								labels: {
									align: "left",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								visible: false,
							},
						],
					},
				},
			],
		},
		credits: { enabled: false },
	});
}
//每月長條圖
function water_month_Data(
	machine_name,
	Water1Usage,
	Water2Usage,
	Water3Usage,
	Water4Usage,
	month,
	ColdWaterUsage_word,
	HotWaterUsage_word,
	RecycledWaterUsage_word,
	Water4Usage_word
) {
	Highcharts.chart("water_month", {
		chart: {
			zoomType: "xy",
			backgroundColor: "rgba(0,0,0,0)",
		},
		title: {
			text: machine_name,
			align: "center",
			style: {
				color: "#FFFFFF",
			},
		},
		xAxis: [
			{
				type: "category",
				crosshair: true,
				labels: {
					style: {
						color: "#FFFFFF",
						fontSize: "18px",
					},
				},
			},
		],
		yAxis: [
			{
				// Secondary yAxis
				// gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value} " + month[0].Water1Usage_month_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
				opposite: true,
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + month[0].Water2Usage_month_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + month[0].Water3Usage_month_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
			},
			{
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + month[0].Water4Usage_month_unit,
					style: {
						color: "#33FF33",
						fontSize: "20px",
					},
				},
				opposite: true,
			},
		],
		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "20px",
			},
		},
		tooltip: {
			shared: true,
		},
		series: [
			{
				name: ColdWaterUsage_word,
				type: "column",
				// yAxis: 1,
				data: Water1Usage,
				tooltip: {
					valueSuffix: month[0].Water1Usage_month_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[0], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: HotWaterUsage_word,
				type: "column",
				color: "#FF0000",
				// yAxis: 1,
				data: Water2Usage,
				tooltip: {
					valueSuffix: month[0].Water2Usage_month_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#FF0000", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: RecycledWaterUsage_word,
				type: "column",
				color: "#00FF00",
				// yAxis: 1,
				data: Water3Usage,
				tooltip: {
					valueSuffix: month[0].Water3Usage_month_unit,
				},
			},
			// 去掉軟水
			// {
			//     name: Water4Usage_word,
			//     type: 'column',
			//     color: '#FFFF00',
			//     // yAxis: 1,
			//     data: Water4Usage,
			//     tooltip: {
			//         valueSuffix: month[0].Water4Usage_month_unit,
			//     },
			//     dataLabels: {
			//         enabled: false

			//     }
			// }
		],
		responsive: {
			rules: [
				{
					condition: {
						maxWidth: 500,
					},
					chartOptions: {
						legend: {
							floating: false,
							layout: "horizontal",
							align: "center",
							verticalAlign: "bottom",
							x: 0,
							y: 0,
						},
						yAxis: [
							{
								labels: {
									align: "right",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								labels: {
									align: "left",
									x: 0,
									y: -6,
								},
								showLastLabel: false,
							},
							{
								visible: false,
							},
						],
					},
				},
			],
		},
		credits: { enabled: false },
	});
}
