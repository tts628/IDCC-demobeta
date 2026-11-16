/* jshint esversion: 6 */
const queryString = window.location.search;

$("#machine_name").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajalarm.php" + queryString,
		type: "POST",
		dataType: "json",
		data: { factory_name: factory_name, machine_name: machine_name, day: day },
		success: function (res) {
			console.log(res);

			alarm_day_Data(machine_name_text, res[0].data);
			alarm_week_Data(machine_name_text, res[1].data);
			alarm_month_Data(machine_name_text, res[2].data);
		},
		error: function (err) {
			console.log(err);
		},
	});

	if (machine_name != "") {
		$("#today_alarm").text(machine_name_text + " - " + Daily + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#week_alarm").text(machine_name_text + " - " + Weekly + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#month_alarm").text(machine_name_text + " - " + Monthly + "(" + day + ")");
	}
});
$("#day").change(function () {
	var factory_name = $("#factory_name :selected").val();
	var machine_name = $("#machine_name").val();
	var machine_name_text = $("#machine_name").find("option:selected").text();
	var day = $("#day").val();
	$.ajax({
		url: "./ajalarm.php" + queryString,
		type: "POST",
		dataType: "json",
		data: { factory_name: factory_name, machine_name: machine_name, day: day },
		success: function (res) {
			console.log(res);

			alarm_day_Data(machine_name_text, res[0].data);
			alarm_week_Data(machine_name_text, res[1].data);
			alarm_month_Data(machine_name_text, res[2].data);
		},
		error: function (err) {
			console.log(err);
		},
	});

	if (machine_name != "") {
		$("#today_alarm").text(machine_name_text + " - " + Daily + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#week_alarm").text(machine_name_text + " - " + Weekly + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#month_alarm").text(machine_name_text + " - " + Monthly + "(" + day + ")");
		// -------------------------------------------------------------------------------------------
	}
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
		url: "./ajalarm.php" + queryString,
		type: "POST",
		dataType: "json",
		data: { factory_name: factory_name, machine_name: machine_name, day: day },
		success: function (res) {
			console.log(res);

			alarm_day_Data(machine_name_text, res[0].data);
			alarm_week_Data(machine_name_text, res[1].data);
			alarm_month_Data(machine_name_text, res[2].data);
		},
		error: function (err) {
			console.log(err);
		},
	});

	if (machine_name != "") {
		$("#today_alarm").text(machine_name_text + " - " + Daily + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#week_alarm").text(machine_name_text + " - " + Weekly + "(" + day + ")");
		day = day.substring(0, day.length - 3);
		$("#month_alarm").text(machine_name_text + " - " + Monthly + "(" + day + ")");
		// -------------------------------------------------------------------------------------------
	}
});
// function random(min, max) {
//     return Math.floor(Math.random() * (max - min + 1)) + min;
// }
//
// var a, b, c, d, e, f;  // a=呼叫操作員延遲 b=取樣延遲 c=等待染料 d=等待助劑 e=備藥延遲 f=加藥延遲
// // for (var i = 1 ; i <= 18; i++) {
// //     if (i>9) {
// //         machine_name='A'+i;
// //     }
// //     else{
// //         machine_name='A0'+i;
// //     }
// a = random(0, 5);
// b = random(2, 3);
// c = random(10, 50);
// d = random(1, 25);
// e = random(6, 20);
// f = random(3, 10);
// function alarm_hour_Data(machine_name,data1) {
//     // console.log(data1);
//     Highcharts.chart('alarm_p_h', {
//         chart: {
//             type: 'variablepie',
//             backgroundColor: 'rgba(0,0,0,0)',
//             height: 300,
//             weight: 400,
//             marginTop: 30,
//         },
//         exporting:{
//             enabled:false //用来设 置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
//         },
//         title: {
//             text: null,
//             colors: '#FFFFFF',
//             fill: '#FFFFFF',
//             enabled: false,
//         },
//         // tooltip: {
//         //     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//         // },
//         // plotOptions: {
//         //     pie: {
//         //         allowPointSelect: true,
//         //         cursor: 'pointer',
//         //         dataLabels: {
//         //             enabled: true,
//         //             format: '<b>{series.name}</b>: {point.percentage:.0f} %'
//         //         }
//         //     }
//         // },
//         legend: {colors: '#FFFFFF'},
//         credits: false,
//         series: [{
//             minPointSize: 75,
//             innerSize: '0%',
//             colorByPoint: true,
//             zMin: 0,
//             name: machine_name,
//             dataLabels: {
//                 style: {
//                     textOutline: 0,
//                 },
//                 enabled: true,
//                 format: '<div style="font-size:12px;color:white;">{point.name}: {point.percentage:.0f} %</div>',
//             },
//             data: data1,
//         }],
//     }),
//         // [{
//         //             name: '呼叫操作員延遲',
//         //             y: a,
//         //             z: 1
//         //
//         //         }, {
//         //             name: '取樣延遲',
//         //             y: b,
//         //             z: 1
//         //         }, {
//         //             name: '等待染料',
//         //             y: c,
//         //             z: 1
//         //         }, {
//         //             name: '等待助劑',
//         //             y: d,
//         //             z: 1
//         //         }, {
//         //             name: '備藥延遲',
//         //             y: e,
//         //             z: 1
//         //         }, {
//         //             name: '加藥延遲',
//         //             y: f,
//         //             z: 1
//         //         }]
//         //     }],
//         // });
//         Highcharts.chart('alarm_b_h', {
//
//             chart: {
//                 type: 'bar',
//                 backgroundColor: 'rgba(0,0,0,0)',
//                 height: 350,
//                 weight: 400,
//                 marginTop: 50,
//             },
//             title: {
//                 text: null,
//                 enabled: false,
//             },
//             // subtitle: {
//             //     text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
//             // },
//             xAxis: {
//                 labels: {
//
//                     style: {
//                         color: '#FFF',
//                     }
//                 },
//                 // type: "category",
//                 categories: (function () {
//                     var abc = [];
//                     for(i=0; i<data1.length;i++){
//                         abc.push(
//                             data1[i].name
//                         );
//                     }
//                     return abc;
//                 })(),
//                 title: null
//             },
//             yAxis: {
//                 type: "categories",
//                 min: 0,
//                 title: {
//                     text: '分鐘',
//                     align: 'high',
//                     style: {fill: '#FFFFFF',color:'#FFFFFF'
//                     },
//                 },
//                 labels: {
//                     style: {
//                         color: '#FFF',
//                     },
//                     overflow: 'justify',
//                 }
//             },
//             // tooltip: {
//             //     valueSuffix: '',
//             // },
//             plotOptions: {
//                 bar: {
//                     dataLabels: {
//                         style: {
//                             color: '#FFFFFF',
//                             textOutline: 0,
//                         },
//                         enabled: true,
//                     },
//                 }
//             },
//             legend: {
//                 layout: 'vertical',
//                 align: 'right',
//                 verticalAlign: 'top',
//                 x: -40,
//                 y: 80,
//                 floating: true,
//                 borderWidth: 0,
//                 backgroundColor:
//                     Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
//                 shadow: true,
//                 enabled: false,
//             },
//             credits: {
//                 enabled: false
//             },
//             exporting: {enabled: false},
//
//             series:
//                 [{
//                     name:  (function () {
//                         let abc = [];
//                         for(let i=0; i<data1.length;i++){
//                             abc.push(
//                                 data1[i].name
//                             );
//                         }
//                         return abc;
//                     })(),
//                     data: (function () {
//                         var data = [];
//                         for(i=0; i<data1.length;i++){
//                             data.push({
//                                 y :data1[i].y,
//                                 color:Highcharts.getOptions().colors[i]
//                             });
//                         }
//                         return data;
//                     })(),
//                 }]
//             //     [{
//             //     name: [data1[0].name],
//             //     data: [data1[0].y]
//             // }]
//
//             // [{
//             //     name: '時間',
//             //     data: [a,'','','','','']
//             // }, {
//             //     name: '時間',
//             //     data: ['',b,'','','','']
//             // }, {
//             //     name: '時間',
//             //     data: ['','',b,'','','']
//             // }, {
//             //     name: '時間',
//             //     data: ['','','',d,'','']
//             // }, {
//             //     name: '時間',
//             //     data: ['','','','',e,'']
//             // }, {
//             //     name: '時間',
//             //     data: ['','','','','',f]
//             // }]
//         });
//
// }
function alarm_day_Data(machine_name, data1) {
	// console.log(data1);
	Highcharts.chart("alarm_p_d", {
		chart: {
			type: "variablepie",
			backgroundColor: "rgba(0,0,0,0)",
			height: 300,
			weight: 400,
			marginTop: 30,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
			colors: "#FFFFFF",
			fill: "#FFFFFF",
			enabled: false,
		},
		// tooltip: {
		//     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		// },
		// plotOptions: {
		//     pie: {
		//         allowPointSelect: true,
		//         cursor: 'pointer',
		//         dataLabels: {
		//             enabled: true,
		//             format: '<b>{series.name}</b>: {point.percentage:.0f} %'
		//         }
		//     }
		// },
		legend: { colors: "#FFFFFF" },
		credits: false,
		series: [
			{
				minPointSize: 75,
				innerSize: "0%",
				colorByPoint: true,
				zMin: 0,
				name: machine_name,
				dataLabels: {
					style: {
						textOutline: 0,
					},
					enabled: true,
					format: '<div style="font-size:12px;color:white;">{point.name}: {point.percentage:.0f} %</div>',
				},
				data: data1,
			},
		],
	}),
		Highcharts.chart("alarm_b_d", {
			chart: {
				type: "bar",
				backgroundColor: "rgba(0,0,0,0)",
				height: 350,
				weight: 400,
				marginTop: 50,
			},
			exporting: {
				enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
			},
			title: {
				text: null,
				enabled: false,
			},
			// subtitle: {
			//     text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
			// },
			xAxis: {
				labels: {
					style: {
						color: "#FFF",
					},
				},
				// type: "category",
				categories: (function () {
					let abc = [];
					for (let i = 0; i < data1.length; i++) {
						abc.push(data1[i].name);
					}
					return abc;
				})(),

				title: null,
			},
			yAxis: {
				type: "categories",
				min: 0,
				title: {
					text: "分鐘",
					align: "high",
					style: { fill: "#FFFFFF", color: "#FFFFFF" },
				},
				labels: {
					style: {
						color: "#FFF",
					},
					overflow: "justify",
				},
			},
			// tooltip: {
			//     valueSuffix: '',
			// },
			plotOptions: {
				bar: {
					dataLabels: {
						style: {
							color: "#FFFFFF",
							textOutline: 0,
						},
						enabled: true,
					},
				},
			},
			legend: {
				layout: "vertical",
				align: "right",
				verticalAlign: "top",
				x: -40,
				y: 80,
				floating: true,
				borderWidth: 0,
				backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#FFFFFF",
				shadow: true,
				enabled: false,
			},
			credits: {
				enabled: false,
			},
			exporting: { enabled: false },

			series: [
				{
					name: (function () {
						let abc = [];
						for (let i = 0; i < data1.length; i++) {
							abc.push(data1[i].name);
						}
						return abc;
					})(),
					data: (function () {
						var data = [];
						for (i = 0; i < data1.length; i++) {
							data.push({
								y: data1[i].y,
								color: Highcharts.getOptions().colors[i],
							});
						}
						return data;
					})(),
				},
			],
		});
}
function alarm_week_Data(machine_name, data1) {
	// console.log(data1);
	Highcharts.chart("alarm_p_w", {
		chart: {
			type: "variablepie",
			backgroundColor: "rgba(0,0,0,0)",
			// height: 300,
			// weight: 100,
			marginTop: 30,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
			colors: "#FFFFFF",
			fill: "#FFFFFF",
			enabled: false,
		},
		// tooltip: {
		//     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		// },
		// plotOptions: {
		//     pie: {
		//         allowPointSelect: true,
		//         cursor: 'pointer',
		//         dataLabels: {
		//             enabled: true,
		//             format: '<b>{series.name}</b>: {point.percentage:.0f} %'
		//         }
		//     }
		// },
		legend: { colors: "#FFFFFF" },
		credits: false,
		series: [
			{
				minPointSize: 75,
				innerSize: "0%",
				colorByPoint: true,
				zMin: 0,
				name: machine_name,
				dataLabels: {
					style: {
						textOutline: 0,
					},
					enabled: true,
					format: '<div style="font-size:12px;color:white;">{point.name}: {point.percentage:.0f} %</div>',
				},
				data: data1,
			},
		],
	}),
		Highcharts.chart("alarm_b_w", {
			chart: {
				type: "bar",
				backgroundColor: "rgba(0,0,0,0)",
				// height: 350,
				weight: 400,
				marginTop: 50,
			},
			exporting: {
				enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
			},
			title: {
				text: null,
				enabled: false,
			},
			// subtitle: {
			//     text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
			// },
			xAxis: {
				labels: {
					style: {
						color: "#FFF",
					},
				},
				// type: "category",
				categories: (function () {
					let abc = [];
					for (let i = 0; i < data1.length; i++) {
						abc.push(data1[i].name);
					}
					return abc;
				})(),

				title: null,
			},
			yAxis: {
				type: "categories",
				min: 0,
				title: {
					text: "分鐘",
					align: "high",
					style: { fill: "#FFFFFF", color: "#FFFFFF" },
				},
				labels: {
					style: {
						color: "#FFF",
					},
					overflow: "justify",
				},
			},
			// tooltip: {
			//     valueSuffix: '',
			// },
			plotOptions: {
				bar: {
					dataLabels: {
						style: {
							color: "#FFFFFF",
							textOutline: 0,
						},
						enabled: true,
					},
				},
			},
			legend: {
				layout: "vertical",
				align: "right",
				verticalAlign: "top",
				x: -40,
				y: 80,
				floating: true,
				borderWidth: 0,
				backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#FFFFFF",
				shadow: true,
				enabled: false,
			},
			credits: {
				enabled: false,
			},
			exporting: { enabled: false },

			series: [
				{
					name: (function () {
						let abc = [];
						for (let i = 0; i < data1.length; i++) {
							abc.push(data1[i].name);
						}
						return abc;
					})(),
					data: (function () {
						var data = [];
						for (i = 0; i < data1.length; i++) {
							data.push({
								y: data1[i].y,
								color: Highcharts.getOptions().colors[i],
							});
						}
						return data;
					})(),
				},
			],
		});
}
function alarm_month_Data(machine_name, data1) {
	// console.log(data1);
	Highcharts.chart("alarm_p_m", {
		chart: {
			type: "variablepie",
			backgroundColor: "rgba(0,0,0,0)",
			height: 300,
			weight: 400,
			marginTop: 30,
		},
		exporting: {
			enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
		},
		title: {
			text: null,
			colors: "#FFFFFF",
			fill: "#FFFFFF",
			enabled: false,
		},
		// tooltip: {
		//     pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		// },
		// plotOptions: {
		//     pie: {
		//         allowPointSelect: true,
		//         cursor: 'pointer',
		//         dataLabels: {
		//             enabled: true,
		//             format: '<b>{series.name}</b>: {point.percentage:.0f} %'
		//         }
		//     }
		// },
		legend: { colors: "#FFFFFF" },
		credits: false,
		series: [
			{
				minPointSize: 75,
				innerSize: "0%",
				colorByPoint: true,
				zMin: 0,
				name: machine_name,
				dataLabels: {
					style: {
						textOutline: 0,
					},
					enabled: true,
					format: '<div style="font-size:12px;color:white;">{point.name}: {point.percentage:.0f} %</div>',
				},
				data: data1,
			},
		],
	}),
		Highcharts.chart("alarm_b_m", {
			chart: {
				type: "bar",
				backgroundColor: "rgba(0,0,0,0)",
				height: 350,
				weight: 400,
				marginTop: 50,
			},
			exporting: {
				enabled: false, //用来设置是否显示‘打印’,'导出'等功能按钮，不设置时默认为显示
			},
			title: {
				text: null,
				enabled: false,
			},
			// subtitle: {
			//     text: 'Source: <a href="https://en.wikipedia.org/wiki/World_population">Wikipedia.org</a>'
			// },
			xAxis: {
				labels: {
					style: {
						color: "#FFF",
					},
				},
				// type: "category",
				categories: (function () {
					let abc = [];
					for (let i = 0; i < data1.length; i++) {
						abc.push(data1[i].name);
					}
					return abc;
				})(),

				title: null,
			},
			yAxis: {
				type: "categories",
				min: 0,
				title: {
					text: "分鐘",
					align: "high",
					style: { fill: "#FFFFFF", color: "#FFFFFF" },
				},
				labels: {
					style: {
						color: "#FFF",
					},
					overflow: "justify",
				},
			},
			// tooltip: {
			//     valueSuffix: '',
			// },
			plotOptions: {
				bar: {
					dataLabels: {
						style: {
							color: "#FFFFFF",
							textOutline: 0,
						},
						enabled: true,
					},
				},
			},
			legend: {
				layout: "vertical",
				align: "right",
				verticalAlign: "top",
				x: -40,
				y: 80,
				floating: true,
				borderWidth: 0,
				backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#FFFFFF",
				shadow: true,
				enabled: false,
			},
			credits: {
				enabled: false,
			},
			exporting: { enabled: false },

			series: [
				{
					name: (function () {
						let abc = [];
						for (let i = 0; i < data1.length; i++) {
							abc.push(data1[i].name);
						}
						return abc;
					})(),
					data: (function () {
						var data = [];
						for (i = 0; i < data1.length; i++) {
							data.push({
								y: data1[i].y,
								color: Highcharts.getOptions().colors[i],
							});
						}
						return data;
					})(),
				},
			],
		});
}
