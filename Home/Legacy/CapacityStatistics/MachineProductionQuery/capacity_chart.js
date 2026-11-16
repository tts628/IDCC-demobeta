const queryString = window.location.search;

let day1;
let day2;
$("#machine_name").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajcapacity.php" + queryString,
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
			// console.log(res[0].data);
			day1 = res[0].data[0]["name"];
			day2 = res[0].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			capacity_day_Data(machine_name_text, res[0].data);
			capacity_week_Data(machine_name_text, res[1].data);
			capacity_month_Data(machine_name_text, res[2].data);

			if (machine_name != "") {
				$("#today_capacity").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_capacity").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_capacity").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});
$("#day").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajcapacity.php" + queryString,
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
			// console.log(res[0].data);
			day1 = res[0].data[0]["name"];
			day2 = res[0].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			capacity_day_Data(machine_name_text, res[0].data);
			capacity_week_Data(machine_name_text, res[1].data);
			capacity_month_Data(machine_name_text, res[2].data);
			if (machine_name != "") {
				$("#today_capacity").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_capacity").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_capacity").text(machine_name_text + " - " + Monthly + "(" + day + ")");
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
	// var machine_name_text = '全部機台';

	var day = $("#day").val();
	$.ajax({
		url: "./ajcapacity.php" + queryString,
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
			// console.log(res[0].data);
			day1 = res[0].data[0]["name"];
			day2 = res[0].data[6]["name"];
			day1 = day1.substring(0, day1.length - 5);
			day2 = day2.substring(0, day2.length - 5);
			capacity_day_Data(machine_name_text, res[0].data);
			capacity_week_Data(machine_name_text, res[1].data);
			capacity_month_Data(machine_name_text, res[2].data);
			// if(machine_name!=''){
			//     $('#today_capacity').text(machine_name_text+' - '+Daily);
			//     $('#week_capacity').text(machine_name_text+' - '+Weekly);
			//     $('#month_capacity').text(machine_name_text+' - '+Monthly);
			// }
			if (machine_name != "") {
				$("#today_capacity").text(machine_name_text + " - " + Daily + "(" + day1 + ":" + day2 + ")");
				day = day.substring(0, day.length - 3);
				$("#week_capacity").text(machine_name_text + " - " + Weekly + "(" + day + ")");
				day = day.substring(0, day.length - 3);
				$("#month_capacity").text(machine_name_text + " - " + Monthly + "(" + day + ")");
			}
		},
		error: function (err) {
			console.log(err);
		},
	});
});
// var Mon_capacity = [88, 44, 0, 60, 0, 433, 497, 107, 105, 102, 40, 1276, 1786, 323, 1742, 914, 330, 984,];
// var Tue_capacity = [70, 0, 70, 30, 0, 440, 239, 40, 10, 15, 0, 571, 490, 0, 1718, 510, 340, 972,];
// var Wed_capacity = [178, 159, 130, 138, 456, 636, 882, 37, 40, 15, 0, 0, 1151, 0, 1276, 0, 855, 872,];
// var Thu_capacity = [75, 135, 40, 132, 110, 832, 510, 82, 54, 47, 61, 0, 1613, 0, 0, 908, 585, 405,];
// var Fri_capacity = [30, 59, 35, 15, 0, 155, 92, 32, 18, 54, 12, 0, 0, 240, 732, 0, 0, 290,];
// var Sat_capacity = [118, 206, 80, 0, 161, 470, 322, 52, 84, 55, 42, 290, 2070, 0, 376, 530, 692,];
// var Sun_capacity = [176, 163, 197, 230, 0, 297, 378, 70, 94, 55, 32, 657, 1881, 0, 484, 865, 0, 734,];
// var Month_capacity = [3518, 2342, 2390, 1524, 7186, 7971, 8408, 827, 722, 644, 480, 15410, 17927, 18662, 18225, 20251, 14879, 17535];
// var Year_capacity = [126616, 112331, 205655, 179513, 190480, 184919, 183612, 203457, 158901, 0, 0, 0];

// function random(min, max) {
//     return Math.floor(Math.random() * (max - min + 1)) + min;
// }

// for (var i = 1 ; i <= 18; i++) {
//     if (i > 9) {
//         machine_name = "A" + i;
//     } else {
//         machine_name = "A0" + i;
//     }
function capacity_day_Data(machine_name, data1) {
	Highcharts.chart("capacity_D", {
		chart: {
			backgroundColor: "rgba(0,0,0,0)",
			type: "column",
			spacingTop: 20,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
		},
		/* subtitle: {
          text: "Source: WorldClimate.com" */
		/* }, */
		credits: {
			enabled: false,
		},
		xAxis: {
			// categories: ["一", "二", "三", "四", "五", "六","日"],
			type: "category",
			labels: {
				style: {
					color: "#FFFFFF",
					fontSize: "18px",
				},
			},
		},
		yAxis: {
			title: {
				text: null,
			},
			labels: {
				style: {
					color: "#FFFFFF",
					fontSize: "18px",
				},
			},
		},

		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "18px",
			},
		},
		plotOptions: {
			column: {
				dataLabels: {
					enabled: true,
					style: {
                        fontSize: '14px', // 調整字體大小
                        color: '#FFFFFF', // 設置字體顏色
                    }
				},
				enableMouseTracking: true,
			},
		},

		color: "#FFC107",
		series: [
			{
				lineWidth: 6,
				name: machine_name,
				color: "#FFC107",
				data: data1,
				// (function () {
				//     var data = [];
				//     var a;
				//     for (var k = 0; k < 7; k += 1) {//月
				//         if (k === 0) {
				//             a = Sun_capacity[i - 1];
				//         } else if (k === 1) {
				//             a = Mon_capacity[i - 1];
				//         } else if (k === 2) {
				//             a = Tue_capacity[i - 1];
				//         } else if (k === 3) {
				//             a = Wed_capacity[i - 1];
				//         } else if (k === 4) {
				//             a = Thu_capacity[i - 1];
				//         } else if (k === 5) {
				//             a = Fri_capacity[i - 1];
				//         } else {
				//             a = Sat_capacity[i - 1];
				//         }
				//         data.push({
				//             y: a
				//
				//         });
				//     }
				//     return data;
				// })(),
			},
		],
	});
}

function capacity_week_Data(machine_name, data1) {
	// console.log(machine_name, data1);
	Highcharts.chart("capacity_week", {
		chart: {
			backgroundColor: "rgba(0,0,0,0)",
			type: "column",
			height: 400,
			spacingTop: 20,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
		},
		/*     subtitle: {
                text: "Source: WorldClimate.com"
            }, */
		credits: {
			enabled: false,
		},
		xAxis: [
			{
				type: "category",
				labels: {
					style: {
						color: "#FFFFFF",
						fontSize: "18px",
					},
				},
				crosshair: true,
			},
		],
		yAxis: {
			title: {
				text: null,
			},
			labels: {
				style: {
					color: "#FFFFFF",
					fontSize: "18px",
				},
			},
		},
		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "18px",
			},
		},
		plotOptions: {
			column: {
				dataLabels: {
					enabled: true,
					style: {
                        fontSize: '14px', // 調整字體大小
                        color: '#FFFFFF', // 設置字體顏色
                    }
				},
				enableMouseTracking: true,
			},
		},
		series: [
			{
				name: machine_name,
				borderWidth: "0",
				data: data1,
				color: "#FFC107",
				// }, {
				//     name: "A02",
				//     borderWidth:"0",
				//     data: [220, 200, 770, 320, 490, 760]
				//
				// }, {
				//     name: "A03",
				//     borderWidth:"0",
				//     data: [220, 220, 270, 300, 340, 380]
				// }, {
				//     name: "A04",
				//     borderWidth:"0",
				//     data: [111, 520, 577, 402, 249, 386]
				// }, {
				//     name: "A05",
				//     borderWidth:"0",
				//     data: [555, 420, 777, 102, 449, 786]
				//
				// }, {
				//     name: "A06",
				//     borderWidth:"0",
				//     data: [220, 220, 270, 300, 340, 380]
				//
				// }, {
				//     name: "A07",
				//     borderWidth:"0",
				//     data: [111, 520, 577, 402, 249, 386]
				//
				// }, {
				//     name: "A08",
				//     borderWidth:"0",
				//     data: [555, 420, 777, 102, 449, 786]
				//
				// }, {
				//     name: "A09",
				//     borderWidth:"0",
				//     data: [333, 320, 477, 302, 649, 786]
			},
		],
	});
}
// Highcharts.chart("capacity_week_2", {
//             chart: {
//                 backgroundColor: "rgba(0,0,0,0)",
//                 type: "column",
//                 height:400,
//                 spacingTop: 20,
//             },
//             title: {
//                 text: null
//             },
//             /*     subtitle: {
//                     text: "Source: WorldClimate.com"
//                 }, */
//             credits: {
//                 enabled: false
//             },
//             xAxis: {
//                 categories: [
//                     "A10",
//                     "A11",
//                     "A12",
//                     "A13",
//                     "A14",
//                     "A15",
//                     "A16",
//                     "A17",
//                     "A18",
//                 ],
//                 labels: {
//                     style: {
//                             color: "#FFFFFF",
//                             fontSize: "24px",
//                         }
//                 },
//                 crosshair: true
//             },
//             yAxis: {
//             title: {
//                 text: null,
//             },
//             labels: {
//               style: {
//                 color: "#FFFFFF",
//                 fontSize: "24px",
//               }
//             },
//             },
//             legend: {
//                 itemStyle: {
//                     color: "#FFFFFF",
//                     fontSize: "24px",
//                 }
//               },
//             series: [
//             {
//                 name: "產能",
//                 borderWidth:"0",
//                 data: [Month_capacity[9],Month_capacity[10],Month_capacity[11],Month_capacity[12],Month_capacity[13],Month_capacity[14],Month_capacity[15],Month_capacity[16],Month_capacity[17]]
//
//             // }, {
//             //     name: "A02",
//             //     borderWidth:"0",
//             //     data: [830, 780, 780, 830, 106, 840]
//             //
//             // }, {
//             //     name: "A03",
//             //     borderWidth:"0",
//             //     data: [480, 380, 390, 410, 470, 480]
//             //
//             // }, {
//             //     name: "A04",
//             //     borderWidth:"0",
//             //     data: [420, 330, 340, 390, 520, 750]
//             //
//             // }, {
//             //     name: "A05",
//             //      borderWidth:"0",
//             //     data: [220, 200, 770, 320, 490, 760]
//             //
//             // }, {
//             //     name: "A06",
//             //      borderWidth:"0",
//             //     data: [220, 220, 270, 300, 340, 380]
//             //
//             // }, {
//             //     name: "A07",
//             //     borderWidth:"0",
//             //     data: [111, 520, 577, 402, 249, 386]
//             //
//             // }, {
//             //     name: "A08",
//             //     borderWidth:"0",
//             //     data: [555, 420, 777, 102, 449, 786]
//             //
//             // }, {
//             //     name: "A09",
//             //     borderWidth:"0",
//             //     data: [333, 320, 477, 302, 649, 786]
//             }]
// });
function capacity_month_Data(machine_name, data1) {
	// console.log(machine_name, data1);
	Highcharts.chart("capacity_month", {
		chart: {
			type: "column",
			backgroundColor: "rgba(0,0,0,0)",
			height: 400,
			spacingTop: 20,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
		},
		/*     subtitle: {
                        text: "Source: WorldClimate.com"
                    }, */
		credits: {
			enabled: false,
		},
		xAxis: {
			type: "category",
			// categories: [
			//     "一月",
			//     "二月",
			//     "三月",
			//     "四月",
			//     "五月",
			//     "六月",
			//     "七月",
			//     "八月",
			//     "九月",
			//     "十月",
			//     "十一月",
			//     "十二月"
			// ],
			labels: {
				style: {
					color: "#FFFFFF",
					fontSize: "18px",
				},
			},
			crosshair: true,
		},
		yAxis: {
			title: {
				text: null,
			},
			labels: {
				style: {
					color: "#FFFFFF",
					fontSize: "18px",
				},
			},
		},
		legend: {
			itemStyle: {
				color: "#FFFFFF",
				fontSize: "18px",
			},
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0,
				dataLabels: {
					enabled: true,
					style: {
                        fontSize: '14px', // 調整字體大小
                        color: '#FFFFFF', // 設置字體顏色
                    }
				},
				enableMouseTracking: true,
			}
		},
		series: [
			{
				name: machine_name,
				color: "#FFC107",
				data: data1,
			},
		],
	});
}
