let chart;
$(function () {
	fetchAlarmTimeStatisticsData();
});

function alarmTimeChartRenderer(chartMachine, chartHours) {
	const Machine = chartMachine.slice(1);
	const Hours = chartHours.slice(1);
	$("#alarm-time-chart-container").empty();
	// Create a new DOM element
	const newElement = $("<div></div>");

	// Assign an ID to the new element
	newElement.attr("id", "chart-container");

	// Append the new element to an existing element with ID "container"
	newElement.appendTo("#alarm-time-chart-container");

	const options = {
		series: [
			{
				name: "Hours",
				data: Hours,
			},
		],
		chart: {
			type: "bar",
			height: 350,
		},
		title: {
			text: "警報時間統計-依染機",
			align: "center",
			style: {
				fontSize: "15px",
				fontWeight: 700,
			},
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: "55%",
				dataLabels: {
					position: "top", // top, center, bottom
				},
			},
		},
		dataLabels: {
			enabled: true,
			offsetY: -20,
			style: {
				colors: ["#304758"],
			},
		},
		stroke: {
			show: true,
			width: 2,
			colors: ["transparent"],
		},
		xaxis: {
			categories: Machine,
		},
		yaxis: {
			title: {
				text: "Hours",
			},
		},
		fill: {
			opacity: 1,
		},
		tooltip: {
			y: {
				formatter: function (val) {
					return val + " 小時";
				},
			},
		},
		theme: {
			mode: "light",
			palette: "palette5",
		},
	};

	const chart = new ApexCharts(document.querySelector("#chart-container"), options);
	chart.render();

	return chart;
}

function fetchAlarmTimeStatisticsData() {
	const module = window.location.hash.substring(1); // Get hash value without '#'

	fetch("./shared/controllers/baseController.php", {
		method: "POST",
		body: JSON.stringify({ module: module }),
		headers: {
			"Content-Type": "application/json", // Add content type header
		},
	})
		.then((response) => {
			// Check if the request was successful
			if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			// Parse the JSON response
			return response.json();
		})
		.then((data) => {
			const dyelotData = JSON.parse(data);

			const Machine = [];
			const Hours = [];

			$.each(dyelotData, function (_index, obj) {
				Machine.push(obj.Machine);
				Hours.push(obj.Hours);
			});
			chart = alarmTimeChartRenderer(Machine, Hours);

			let gridApi = "";
			const gridOptions = {
				defaultColDef: {
					resizable: true,
					width: 100,
					headerClass: "header-text-center",
					cellClass: "text-center",
				},
			};
			const gridArg = {
				gridApi: gridApi,
				field: Machine,
				data: [Hours],
				gridId: "alarm-time-grid-container",
				gridOptions: gridOptions,
			};

			populateGrid(gridArg);
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}
