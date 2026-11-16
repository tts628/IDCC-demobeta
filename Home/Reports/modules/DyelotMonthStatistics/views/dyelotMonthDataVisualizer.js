function convertChineseMonthToEnglish(chineseMonth) {
    const monthMap = {
        '一月': 'Jan',
        '二月': 'Feb',
        '三月': 'Mar',
        '四月': 'Apr',
        '五月': 'May',
        '六月': 'Jun',
        '七月': 'Jul',
        '八月': 'Aug',
        '九月': 'Sep',
        '十月': 'Oct',
        '十一月': 'Nov',
        '十二月': 'Dec'
    };

    // Split the input string into month and value
    const [month, value] = chineseMonth.split('_');
    
    // Get the English abbreviation for the month
    const englishMonth = monthMap[month] || month;

    // Return the English abbreviation followed by the value
    return englishMonth + ' ' + value;
}

function dyelotMonthChartRenderer(chartField, rowData) {
	const months = chartField.slice(1);
	const field = months.map(convertChineseMonthToEnglish);

	const series = rowData.map((obj) => ({
		name: obj.machine,
		data: Object.values(obj).slice(1), // Get values starting from the second element (skip "machine")
	}));

	$("#dyelot-month-chart-container").empty();
	// Create a new DOM element
	const newElement = $("<div></div>");

	// Assign an ID to the new element
	newElement.attr("id", "chart-container");

	// Append the new element to an existing element with ID "container"
	newElement.appendTo("#dyelot-month-chart-container");

	const options = {
		series: series,
		chart: {
			type: "bar",
			height: 350,
            stacked: true,
			toolbar: {
				show: true,
			},
			zoom: {
				enabled: true,
			},
		},
		title: {
			text: "每月工單統計",
			align: "center",
			style: {
				fontSize: "15px",
				fontWeight: 700,
			},
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: "55%"
			},
		},
		dataLabels: {
			enabled: false
		},
		xaxis: {
			type: "datetime",
			categories: field,
		},
		yaxis: {
			title: {
				text: "Dyelots",
			},
		},
		responsive: [
			{
				breakpoint: 480,
				options: {
					legend: {
						position: "bottom",
						offsetX: -10,
						offsetY: 0,
					},
				},
			},
		],
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

function fetchDyelotMonthStatisticsData() {
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
			const dyelotMonthData = JSON.parse(data);

			const field = dyelotMonthData.columnDefs.field;
			const customName = dyelotMonthData.columnDefs.customName;
			const rowData = dyelotMonthData.rowData;

			dyelotMonthChartRenderer(field, rowData);

			let gridApi = "";
			const gridOptions = {
				defaultColDef: {
					resizable: true,
					width: 100,
					headerClass: "header-text-center",
					cellClass: "text-center",
				},
			};

			const columnDefOptions = [
				{
					target: "machine",
					options: {
						sort: "asc",
					},
				},
			];

			const gridArg = {
				gridApi: gridApi,
				field: field,
				customName: customName,
				columnDefOptions: columnDefOptions,
				data: rowData,
				gridId: "dyelot-month-grid-container",
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
	fetchDyelotMonthStatisticsData();

	// bind event listener
	$("#start-time-selector, #end-time-selector").on("change", function () {
		fetchDyelotMonthStatisticsData();
	});
});
