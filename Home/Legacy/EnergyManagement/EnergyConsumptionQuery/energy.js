//機台選單改變 顯示長條圖
$("#machine_name").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajenergy.php" + queryString,
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
			day1 = res[3].data[0]["name"];
			day2 = res[3].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			energy_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[12].hour,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_day_Data(
				machine_name_text,
				res[3].data,
				res[4].data,
				res[5].data,
				res[12].day,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_week_Data(
				machine_name_text,
				res[6].data,
				res[7].data,
				res[8].data,
				res[12].week,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_month_Data(
				machine_name_text,
				res[9].data,
				res[10].data,
				res[11].data,
				res[12].month,
				Water_word,
				Steam_word,
				Power_word
			);
			if (machine_name != "") {
				$("#hour_energy").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_energy").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_energy").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_energy").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});
//機台選單改變 顯示長條圖

//日期選擇改變 顯示長條圖
$("#day").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajenergy.php" + queryString,
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
			day1 = res[3].data[0]["name"];
			day2 = res[3].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			energy_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[12].hour,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_day_Data(
				machine_name_text,
				res[3].data,
				res[4].data,
				res[5].data,
				res[12].day,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_week_Data(
				machine_name_text,
				res[6].data,
				res[7].data,
				res[8].data,
				res[12].week,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_month_Data(
				machine_name_text,
				res[9].data,
				res[10].data,
				res[11].data,
				res[12].month,
				Water_word,
				Steam_word,
				Power_word
			);
			if (machine_name != "") {
				$("#hour_energy").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_energy").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_energy").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_energy").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});
//日期選擇改變 顯示長條圖

//廠區選擇改變 顯示長條圖
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
			// console.log(res);
			//處理回吐的資料
		},
	});

	// var machine_name_text = $('#machine_name').find("option:selected").text();
	// var machine_name = $('#machine_name').val();

	var machine_name = "Allmachine";

	var day = $("#day").val();
	$.ajax({
		url: "./ajenergy.php" + queryString,
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
			day1 = res[3].data[0]["name"];
			day2 = res[3].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			energy_hour_Data(
				machine_name_text,
				res[0].data,
				res[1].data,
				res[2].data,
				res[12].hour,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_day_Data(
				machine_name_text,
				res[3].data,
				res[4].data,
				res[5].data,
				res[12].day,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_week_Data(
				machine_name_text,
				res[6].data,
				res[7].data,
				res[8].data,
				res[12].week,
				Water_word,
				Steam_word,
				Power_word
			);
			energy_month_Data(
				machine_name_text,
				res[9].data,
				res[10].data,
				res[11].data,
				res[12].month,
				Water_word,
				Steam_word,
				Power_word
			);
			if (machine_name != "") {
				$("#hour_energy").text(machine_name_text + " - " + Hourly + "(" + day + ")");
				$("#day_energy").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_energy").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_energy").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});
//廠區選擇改變 顯示長條圖

function random(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

//每小時長條圖
function energy_hour_Data(machine_name, Power, Steam, Water, hour, Water_word, Steam_word, Power_word) {
	Highcharts.chart("energy_hour", {
		chart: {
			// type: 'line',
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
						return this.value + "時"; // this.value 是當前的 x 軸標籤
					},
				},
			},
		],
		yAxis: [
			{
				// Primary yAxis
				labels: {
					format: "{value}" + hour[0].SteamUsage_hour_unit,
					style: {
						color: Highcharts.getOptions().colors[3],
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
					format: "{value}" + hour[0].WaterUsage_hour_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				// Tertiary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + hour[0].PowerUsage_hour_unit,
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
				name: Water_word,
				type: "column",
				yAxis: 1,
				data: Water,
				tooltip: {
					valueSuffix: hour[0].WaterUsage_hour_unit,
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
				name: Steam_word,
				type: "column",
				color: Highcharts.getOptions().colors[3],
				data: Steam,
				tooltip: {
					valueSuffix: hour[0].SteamUsage_hour_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[3], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: Power_word,
				type: "column",
				color: "#33FF33",
				yAxis: 2,
				data: Power,
				tooltip: {
					valueSuffix: hour[0].PowerUsage_hour_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#33FF33", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
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
		exporting: {
			enabled: true, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
	});
}
//每小時長條圖
//每日長條圖
function energy_day_Data(machine_name, Power, Steam, Water, day, Water_word, Steam_word, Power_word) {
	Highcharts.chart("energy_day", {
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
				// Primary yAxis
				labels: {
					format: "{value}" + day[0].SteamUsage_day_unit,
					style: {
						color: Highcharts.getOptions().colors[3],
						fontSize: "20px",
					},
				},
				title: {
					text: null,
				},
				opposite: true,
			},
			{
				// Secondary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + day[0].WaterUsage_day_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				// Tertiary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + day[0].PowerUsage_day_unit,
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
				name: Water_word,
				type: "column",
				yAxis: 1,
				data: Water,
				tooltip: {
					valueSuffix: day[0].WaterUsage_day_unit,
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
				name: Steam_word,
				type: "column",
				color: Highcharts.getOptions().colors[3],
				data: Steam,
				tooltip: {
					valueSuffix: day[0].SteamUsage_day_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[3], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: Power_word,
				type: "column",
				color: "#33FF33",
				yAxis: 2,
				data: Power,
				tooltip: {
					valueSuffix: day[0].PowerUsage_day_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#33FF33", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
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
//每周長條圖
function energy_week_Data(machine_name, Power, Steam, Water, week, Water_word, Steam_word, Power_word) {
	Highcharts.chart("energy_week", {
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
				// Primary yAxis
				labels: {
					format: "{value}" + week[0].SteamUsage_week_unit,
					style: {
						color: Highcharts.getOptions().colors[3],
						fontSize: "20px",
					},
				},
				title: {
					text: null,
				},
				opposite: true,
			},
			{
				// Secondary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + week[0].WaterUsage_week_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				// Tertiary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + week[0].PowerUsage_week_unit,
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
				name: Water_word,
				type: "column",
				yAxis: 1,
				data: Water,
				tooltip: {
					valueSuffix: week[0].WaterUsage_week_unit,
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
				name: Steam_word,
				type: "column",
				color: Highcharts.getOptions().colors[3],
				data: Steam,
				tooltip: {
					valueSuffix: week[0].SteamUsage_week_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[3], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: Power_word,
				type: "column",
				color: "#33FF33",
				yAxis: 2,
				data: Power,
				tooltip: {
					valueSuffix: week[0].PowerUsage_week_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#33FF33", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
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
//每周長條圖

//每月長條圖
function energy_month_Data(machine_name, Power, Steam, Water, month, Water_word, Steam_word, Power_word) {
	Highcharts.chart("energy_month", {
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
				// Primary yAxis
				labels: {
					format: "{value}" + month[0].SteamUsage_month_unit,
					style: {
						color: Highcharts.getOptions().colors[3],
						fontSize: "20px",
					},
				},
				title: {
					text: null,
				},
				opposite: true,
			},
			{
				// Secondary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + month[0].WaterUsage_month_unit,
					style: {
						color: Highcharts.getOptions().colors[0],
						fontSize: "20px",
					},
				},
			},
			{
				// Tertiary yAxis
				gridLineWidth: 0,
				title: {
					text: null,
				},
				labels: {
					format: "{value}" + month[0].PowerUsage_month_unit,
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
				name: Water_word,
				type: "column",
				yAxis: 1,
				data: Water,
				tooltip: {
					valueSuffix: month[0].WaterUsage_month_unit,
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
				name: Steam_word,
				type: "column",
				color: Highcharts.getOptions().colors[3],
				data: Steam,
				tooltip: {
					valueSuffix: month[0].SteamUsage_month_unit,
				},
				dataLabels: {
					enabled: true,
					color: Highcharts.getOptions().colors[3], // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
			{
				name: Power_word,
				type: "column",
				color: "#33FF33",
				yAxis: 2,
				data: Power,
				tooltip: {
					valueSuffix: month[0].PowerUsage_month_unit,
				},
				dataLabels: {
					enabled: true,
					color: "#33FF33", // 数据标签的颜色
					style: {
						fontSize: "16px", // 数据标签的字体大小
					},
				},
			},
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
