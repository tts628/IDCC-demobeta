function productionMonthChartRenderer(chartMachine, chartProductions) {
	const Machine = chartMachine.slice(1);
	const Productions = chartProductions.slice(1);
	$("#production-month-chart-container").empty();
	// Create a new DOM element
	const newElement = $("<div></div>");

	// Assign an ID to the new element
	newElement.attr("id", "chart-container");

	// Append the new element to an existing element with ID "container"
	newElement.appendTo("#production-month-chart-container");

	const options = {
		series: [
			{
				name: "總產量",
				data: Productions,
			},
		],
		chart: {
			type: "bar",
			height: 350,
		},
		title: {
			text: "總產量統計-依月份",
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
				text: "總產量(公斤)",
			},
		},
		fill: {
			opacity: 1,
		},
		tooltip: {
			y: {
				formatter: function (val) {
					return val + " 公斤";
				},
			},
		},
		theme: {
			mode: "light",
			palette: "palette5",
		},
	};

	const chart = new ApexCharts(
		document.querySelector("#chart-container"),
		options
	);
	chart.render();
}

function fetchproductionMonthStatisticsData() {
	const dateRange = {
		dateStart: $("#start-time-selector").val(),
		dateEnd: $("#end-time-selector").val(),
	};
	const module = window.location.hash.substring(1); // Get hash value without '#'

	fetch("./shared/controllers/dateSelectorController.php", {
		method: "POST",
		body: JSON.stringify({ dateRange: dateRange, module: module }),
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
			const productionMonthData = JSON.parse(data);

			const Month = [];
			const Production = [];

			$.each(productionMonthData, function (_index, obj) {
				Month.push(obj.Month);
				Production.push(obj.總產量);
			});
			productionMonthChartRenderer(Month, Production);

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
				field: Month,
				data: [Production],
				gridId: "production-month-grid-container",
				gridOptions: gridOptions,
			};

			populateGrid(gridArg);
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

$(function () {
	// initialization
	const today = new Date();
	const formattedToday = today.toISOString().slice(0, 10);
	$("#end-time-selector").val(formattedToday);
	fetchproductionMonthStatisticsData();

	// bind event listener
	$("#start-time-selector, #end-time-selector").on("change", function () {
		fetchproductionMonthStatisticsData();
	});
});
