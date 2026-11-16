let gridApi = "";
let cachedAllDyelotData = "";

const selectedRowDataHtml = `
    <div id="all-dyelot-details" class="fluid-container mx-3">
        <div class="position-relative">
            <h5 class="mt-3 fw-bold">工卡詳細資訊</h5>
            <div class="close close-btn" id="dyelot-swal-close">&times;</div>
        </div>
        
        <div class="row">
            <div class="col-2 mt-4">
                <div class="list-group" id="redye-type-list-tab" role="tablist"></div>
            </div>
            <div class="col">
                <div class="tab-content" id="nav-tab-content">
                    <div class="card text-center mt-4" id="dyelot-data-card">
                        <div class="card-header">
                            <ul class="nav nav-pills card-header-pills" id="dyelot-navigator-container"></ul>
                        </div>
                        <div class="card-body">
                            <table class="mx-auto text-center table table-bordered mt-3">
                                <tr>
                                    <th class="dyelot-basic-table">工卡號</th>
                                    <td id="sheet-no-data" class="dyelot-basic-table">1</td>
                                    <th colspan="2" class="dyelot-process-table">工卡開始時間</th>
                                    <th colspan="2" class="dyelot-process-table">工卡結束時間</th>
                                </tr>
                                <tr>
                                    <th id="batch-data-text" class="dyelot-basic-table">缸號</th>
                                    <td id="batch-data" class="dyelot-basic-table">1</td>
                                    <td id="start-time-data" colspan="2" rowspan="2" class="dyelot-process-table">1</td>
                                    <td id="end-time-data" colspan="2" rowspan="2" class="dyelot-process-table">1</td>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">染機</th>
                                    <td id="machine-data" class="dyelot-basic-table">1</td>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">客戶</th>
                                    <td id="customer-name-data" class="dyelot-basic-table">1</td>
                                    <th class="dyelot-process-table">工卡總耗時(MIN)</th>
                                    <th class="dyelot-process-table">工卡總用水量(L)</th>
                                    <th class="dyelot-process-table">工卡總耗氣量(KG)</th>
                                    <th class="dyelot-process-table">工卡總用電量(KWH)</th>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">品名</th>
                                    <td id="fabric-name-data" class="dyelot-basic-table">1</td>
                                    <td class="dyelot-process-table" id="run-time-data" rowspan="2">1</td>
                                    <td class="dyelot-process-table" id="water-used-data" rowspan="2">1</td>
                                    <td class="dyelot-process-table" id="steam-used-data" rowspan="2">1</td>
                                    <td class="dyelot-process-table" id="power-used-data" rowspan="2">1</td>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">色號</th>
                                    <td id="color-no-data" class="dyelot-basic-table">1</td>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">色名</th>
                                    <td id="color-name-data" class="dyelot-basic-table">1</td>
                                    <th class="dyelot-track-table">平均耗時(MIN/KG)</th>
                                    <th class="dyelot-track-table">平均用水(L/KG)</th>
                                    <th class="dyelot-track-table">平均耗氣(KG/KG)</th>
                                    <th class="dyelot-track-table">平均用電(KWH/KG)</th>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">訂單</th>
                                    <td id="order-no-data" class="dyelot-basic-table">1</td>
                                    <td class="dyelot-track-table" id="avg-run-time-data" rowspan="2">1</td>
                                    <td class="dyelot-track-table" id="avg-water-used-data" rowspan="2">1</td>
                                    <td class="dyelot-track-table" id="avg-steam-used-data" rowspan="2">1</td>
                                    <td class="dyelot-track-table" id="avg-power-used-data" rowspan="2">1</td>
                                </tr>
                                <tr>
                                    <th class="dyelot-basic-table">投胚量kg</th>
                                    <td id="total-weight-data" class="dyelot-basic-table">1</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <table class="mx-auto text-center table table-bordered mt-3">
                            <tr>
                                <th colspan="2" class="dyelot-process-table">總工卡開始時間</th>
                                <th colspan="2" class="dyelot-process-table">總工卡結束時間</th>
                            </tr>
                            <tr>
                                <td id="all-start-time-data" colspan="2" class="dyelot-process-table">1</td>
                                <td id="all-end-time-data" colspan="2" class="dyelot-process-table">1</td>
                            </tr>
                            <tr>
                                <th class="dyelot-process-table">總工卡耗時(MIN)</th>
                                <th class="dyelot-process-table">總工卡用水量(L)</th>
                                <th class="dyelot-process-table">總工卡耗氣量(KG)</th>
                                <th class="dyelot-process-table">總工卡用電量(KWH)</th>
                            </tr>
                            <tr>
                                <td class="dyelot-process-table" id="all-run-time-data">1</td>
                                <td class="dyelot-process-table" id="all-water-used-data">1</td>
                                <td class="dyelot-process-table" id="all-steam-used-data">1</td>
                                <td class="dyelot-process-table" id="all-power-used-data">1</td>
                            </tr>
                            <tr>
                                <th class="dyelot-track-table">總工卡平均耗時(MIN/KG)</th>
                                <th class="dyelot-track-table">總工卡平均用水(L/KG)</th>
                                <th class="dyelot-track-table">總工卡平均耗氣(KG/KG)</th>
                                <th class="dyelot-track-table">總工卡平均用電(KWH/KG)</th>
                            </tr>
                            <tr>
                                <td class="dyelot-track-table" id="all-avg-run-time-data">1</td>
                                <td class="dyelot-track-table" id="all-avg-water-used-data">1</td>
                                <td class="dyelot-track-table" id="all-avg-steam-used-data">1</td>
                                <td class="dyelot-track-table" id="all-avg-power-used-data">1</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
`;

// const options = {
// 	year: "numeric",
// 	month: "2-digit",
// 	day: "2-digit",
// 	hour: "numeric",
// 	minute: "2-digit",
// 	hour12: true, // Use 12-hour time format
// 	hourCycle: "h12",
// };

// const formatter = new Intl.DateTimeFormat("zh-CN", options);
// const formattedStartTime = formatter.format(new Date(params.data.StartTime.date));
// const formattedEndTime = formatter.format(new Date(params.data.EndTime.date));

const gridOptions = {
	defaultColDef: {
		resizable: true,
		// width: 100,
		filter: true,
		floatingFilter: true,
	},
	onRowClicked: function (params) {
		Swal.fire({
			html: selectedRowDataHtml,
			showConfirmButton: false,
			didRender: () => {
				const dyelotRows = getRowsWithSpecifiedValue("SheetNo", params.data.SheetNo);
				const selectedRowIndex = dyelotRows.findIndex((item) => item.Dyelot === params.data.Dyelot);
				let earliestStartTime;
				let latestEndTime;
				dyelotRows.forEach(function (value, index) {
					let normalTextIndicator = "正常";
					const redyeType = value.RedyeType;
					if (redyeType.includes("正常") && redyeType !== "正常") {
						normalTextIndicator = redyeType;
					}
					appendRedyeTypeNavigator(index, selectedRowIndex, redyeType);
					if (value.rawGroupedData) {
						value.rawGroupedData.forEach(function (groupValue, groupIndex) {
							if (index === 0 && groupIndex === 0) {
								if (groupValue.ProgramsUsed) {
									earliestStartTime = groupValue.StartTime;
									latestEndTime = groupValue.EndTime;
								} else {
									earliestStartTime = "";
									latestEndTime = "";
								}
							}
							if (groupValue.ProgramsUsed) {
								if (earliestStartTime > groupValue.StartTime || earliestStartTime === "") {
									earliestStartTime = groupValue.StartTime;
								}
								if (latestEndTime < groupValue.EndTime) {
									latestEndTime = groupValue.EndTime;
								}
							}
						});
					}
				});
				const summedData = calculateSums(
					dyelotRows,
					"RunTimeMinutes",
					"WaterUsed",
					"SteamUsed",
					"PowerUsed",
					"AverageRunTime",
					"WaterUsedPerKg",
					"SteamUsedPerKg",
					"PowerUsedPerKg",
					"TotalWeight"
				);
				$("#all-start-time-data").text(earliestStartTime);
				$("#all-end-time-data").text(latestEndTime);
				$("#all-run-time-data").text(summedData[0].toFixed(2));
				$("#all-water-used-data").text(summedData[1].toFixed(2));
				$("#all-steam-used-data").text(summedData[2].toFixed(2));
				$("#all-power-used-data").text(summedData[3].toFixed(2));
				$("#all-avg-run-time-data").text(summedData[4].toFixed(2));
				$("#all-avg-water-used-data").text(summedData[5].toFixed(2));
				$("#all-avg-steam-used-data").text(summedData[6].toFixed(2));
				$("#all-avg-power-used-data").text(summedData[7].toFixed(2));

				$(".list-group-item").on("click", function () {
					$(".list-group-item").removeClass("active");
					$(this).addClass("active");

					$("#dyelot-navigator-container").empty();
					const selectedIndex = $(this).data("index");
					const dyelotDataCard = document.getElementById("dyelot-data-card");
					replaceAllText(dyelotDataCard, "領料單", "工卡");
					updateDyelotDetailsData(dyelotRows[selectedIndex]);
				});
				updateDyelotDetailsData(params.data);
			},
		});
	},

	getRowClass: function (params) {
		if (observingMachine.includes(params.data.Machine)) {
			return "color-red fw-bold";
		}
	},
};

const observingMachine = ["R02", "R03", "R08", "R09", "R11", "R12"];

const customColumnDefs = [
	{
		headerName: "工卡基本資料",
		children: [
			{
				headerName: "工卡號",
				field: "SheetNo",
				width: 120,
				pinned: "left",
				filter: "agTextColumnFilter",
				valueFormatter: (params) => {
					return params.value == null ? "" : params.value.toString();
				},
			},
			{
				headerName: "類別",
				field: "RedyeType",
				width: 100,
				pinned: "left",
			},
			{
				headerName: "缸號",
				field: "Batch",
				valueFormatter: (params) => {
					return params.value == null ? "" : params.value.toString();
				},
			},
			// { headerName: "Dyelot", field: "Dyelot" },
			{ headerName: "布號", field: "FabricNo", cellDataType: "text" },
			{ headerName: "HB布號", field: "HBFabricNo", cellDataType: "text" },
			{ headerName: "客戶", field: "CustomerName" },
			{ headerName: "品名", field: "FabricName" },
			{ headerName: "色號", field: "ColorNo" },
			{ headerName: "色名", field: "ColorName" },
			{ headerName: "訂單", field: "OrderNo" },
			{ headerName: "投胚量kg", field: "TotalWeight" },
		],
	},
	{
		headerName: "工卡行程記錄",
		children: [
			{
				headerName: "染機",
				field: "Machine",
			},
			{
				headerName: "染程",
				field: "ProgramsUsed",
				valueFormatter: (params) => {
					return params.value == null ? "" : params.value.toString();
				},
			},
			{
				headerName: "工卡開始時間",
				field: "StartTime",
				width: 180,
				valueFormatter: (params) => {
					return params.value == null ? "" : params.value.toString();
				},
			},
			{
				headerName: "工卡結束時間",
				field: "EndTime",
				width: 180,
			},
			{ headerName: "工卡總耗時(MIN)", field: "RunTimeMinutes" },
			{ headerName: "工卡總用水量(L)", field: "WaterUsed" },
			{ headerName: "工卡總耗氣量(KG)", field: "SteamUsed" },
			{ headerName: "工卡總用電量(KWH)", field: "PowerUsed" },
		],
	},
	{
		headerName: "能耗追蹤",
		children: [
			{ headerName: "平均耗時(MIN/KG)", field: "AverageRunTime" },
			{ headerName: "平均用水(L/KG)", field: "WaterUsedPerKg" },
			{ headerName: "平均耗氣(KG/KG)", field: "SteamUsedPerKg" },
			{ headerName: "平均用電(KWH/KG)", field: "PowerUsedPerKg" },
		],
	},
];

function calculateSums(data, ...keyNames) {
	const sums = keyNames.map((key) => data.reduce((acc, obj) => acc + obj[key], 0));
	return sums;
}

function updateDyelotDetailsData(rowData) {
	appendDyelotNavigator(rowData.SheetNo, "SheetNo", "sheet-number", "active");
	const rawGroupedData = rowData.rawGroupedData;

	if (typeof rawGroupedData != "undefined") {
		rawGroupedData.forEach(function (groupedData) {
			let textClass;
			// Bind with css
			if (!groupedData.ProgramsUsed) {
				textClass = "invalid-dyelot";
			} else if (groupedData.Dyelot.length <= 12) {
				textClass = "normal-dyelot";
			} else {
				textClass = "supplement-dyelot";
			}
			appendDyelotNavigator(groupedData.Dyelot, "Dyelot", textClass);
		});
	}
	$("#sheet-no-data").text(rowData.SheetNo);
	$("#batch-data").text(rowData.Batch);
	$("#start-time-data").text(rowData.StartTime);
	$("#end-time-data").text(rowData.EndTime);
	$("#machine-data").text(rowData.Machine);
	$("#customer-name-data").text(rowData.CustomerName);
	$("#fabric-name-data").text(rowData.FabricName);
	$("#run-time-data").text(rowData.RunTimeMinutes);
	$("#water-used-data").text(rowData.WaterUsed);
	$("#steam-used-data").text(rowData.SteamUsed);
	$("#power-used-data").text(rowData.PowerUsed);
	$("#color-no-data").text(rowData.ColorNo);
	$("#color-name-data").text(rowData.ColorName);
	$("#order-no-data").text(rowData.OrderNo);
	$("#avg-run-time-data").text(rowData.AverageRunTime);
	$("#avg-water-used-data").text(rowData.WaterUsedPerKg);
	$("#avg-steam-used-data").text(rowData.SteamUsedPerKg);
	$("#avg-power-used-data").text(rowData.PowerUsedPerKg);
	$("#total-weight-data").text(rowData.TotalWeight);

	$("#dyelot-swal-close").on("click", function () {
		Swal.close();
	});

	$("#dyelot-data-card .nav-link").on("click", function () {
		const $dyelotNavigators = $("#dyelot-data-card").find(".card-header-pills").find(".nav-link");
		$dyelotNavigators.removeClass("active");
		$(this).addClass("active");
		const navigatorDataType = $(this).data("type");
		const navigatorText = $(this).text();
		if (navigatorDataType === "SheetNo") {
			const dyelotDataCard = document.getElementById("dyelot-data-card");
			replaceAllText(dyelotDataCard, "領料單", "工卡");

			$("#batch-data-text").text("缸號");
			$("#sheet-no-data").text(rowData.SheetNo);
			$("#batch-data").text(rowData.Batch);
			$("#start-time-data").text(rowData.StartTime);
			$("#end-time-data").text(rowData.EndTime);
			$("#run-time-data").text(rowData.RunTimeMinutes);
			$("#water-used-data").text(rowData.WaterUsed);
			$("#steam-used-data").text(rowData.SteamUsed);
			$("#power-used-data").text(rowData.PowerUsed);
			$("#avg-run-time-data").text(rowData.AverageRunTime);
			$("#avg-water-used-data").text(rowData.WaterUsedPerKg);
			$("#avg-steam-used-data").text(rowData.SteamUsedPerKg);
			$("#avg-power-used-data").text(rowData.PowerUsedPerKg);
		} else if (navigatorDataType === "Dyelot") {
			const dyelotDataCard = document.getElementById("dyelot-data-card");
			replaceAllText(dyelotDataCard, "工卡", "領料單");
			$("#batch-data-text").text("染程");
			rawGroupedData.forEach(function (groupedData) {
				if (groupedData.Dyelot === navigatorText) {
					$("#sheet-no-data").text(groupedData.Dyelot);
					$("#batch-data").text(groupedData.ProgramsUsed);
					$("#start-time-data").text(groupedData.StartTime);
					$("#end-time-data").text(groupedData.EndTime);
					$("#run-time-data").text(groupedData.RunTimeMinutes);
					$("#water-used-data").text(groupedData.WaterUsed);
					$("#steam-used-data").text(groupedData.SteamUsed);
					$("#power-used-data").text(groupedData.PowerUsed);
					$("#avg-run-time-data").text(groupedData.AverageRunTime);
					$("#avg-water-used-data").text(groupedData.WaterUsedPerKg);
					$("#avg-steam-used-data").text(groupedData.SteamUsedPerKg);
					$("#avg-power-used-data").text(groupedData.PowerUsedPerKg);
				}
			});
		}
	});
}

function getRowsWithSpecifiedValue(field, value) {
	const matchingRows = [];
	gridApi.forEachNode((node) => {
		if (node.data[field] == value) {
			matchingRows.push(node.data);
		}
	});
	return matchingRows;
}

function replaceAllText(element, oldText, newText) {
	for (const child of element.childNodes) {
		if (child.nodeType === Node.TEXT_NODE) {
			child.nodeValue = child.nodeValue.replace(oldText, newText);
		} else if (child.nodeType === Node.ELEMENT_NODE) {
			replaceAllText(child, oldText, newText); // Recursively call for child elements
		}
	}
}

function appendDyelotNavigator(text, dataType, textColor, state) {
	let activeState;
	if (state) {
		activeState = "active";
	} else {
		activeState = "";
	}
	$("#dyelot-navigator-container").append(
		`<li class="nav-item">
            <button class="nav-link ${textColor} ${activeState}" data-type="${dataType}">${text}</button>
        </li>`
	);
}

function appendRedyeTypeNavigator(counter, selectedRowIndex, redyeType) {
	let activeState;
	if (selectedRowIndex == counter) {
		activeState = "active";
	} else {
		activeState = "";
	}
	if (counter === 0) {
		$("#redye-type-list-tab").append(
			`<a class="list-group-item ${activeState}" data-index="${counter}" role="tab">${redyeType}</a>`
		);
	} else if (counter < 11) {
		$("#redye-type-list-tab").append(
			`<a class="list-group-item ${activeState}" data-index="${counter}" role="tab">重修${counter}</a>`
		);
	} else {
		$("#redye-type-list-tab").append(
			`<a class="list-group-item ${activeState}" data-index="${counter}" role="tab">重修10</a>`
		);
	}
}

function colorCellRenderer(params) {
	const colorBlock = `<div style="background-color:${params.value}">&nbsp;</div>`;
	return colorBlock;
}

function fetchAllDyelotStatisticsData(formData = {}) {
	fetch("./Reports/modules/AllDyelot/controllers/AllDyelotController.php", {
		method: "POST",
		body: formData,
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
			let allDyelotData;
			try {
				allDyelotData = JSON.parse(data);
			} catch (error) {
				const cleanedErrorMessage = data.replace(/^Error: |^"|"$/g, "");
				Swal.hideLoading();
				Swal.update({
					title: "資料檢視錯誤",
					text: cleanedErrorMessage,
					icon: "warning",
					showConfirmButton: true,
				});
				return;
			}

			cachedAllDyelotData = allDyelotData;
			let visualizedData;

			if ($("#monitored-machine-checkbox").prop("checked")) {
				visualizedData = cachedAllDyelotData.filter(
					(obj) =>
						obj.Machine === "R02" ||
						obj.Machine === "R03" ||
						obj.Machine === "R08" ||
						obj.Machine === "R09" ||
						obj.Machine === "R11" ||
						obj.Machine === "R12"
				);
			} else {
				visualizedData = cachedAllDyelotData;
			}

			const gridArg = {
				gridApi: gridApi,
				data: visualizedData,
				gridId: "all-dyelot-grid-container",
				gridOptions: gridOptions,
				customColumnDefs: customColumnDefs,
			};

			gridApi = populateGrid(gridArg);
			Swal.close();
		})
		.catch((error) => {
			Swal.hideLoading();
			Swal.update({
				title: "未知錯誤",
				html: error,
				icon: "error",
				showConfirmButton: true,
			});
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

// Function to convert array to CSV string
function objectsToCSV(objects) {
	const headers = Object.keys(objects[0]);
	const rows = objects.map((obj) => headers.map((header) => obj[header]));
	return [headers.join(","), ...rows.map((row) => row.join(","))].join("\n");
}

function arrayToCSV(array) {
	return array
		.map((row) =>
			row
				.map((item) => {
					// Check for null and replace with an empty string
					const safeItem = item === null ? "" : item;
					// Wrap entire item as a string in Excel formula to treat as text
					// This ensures that even items with commas are treated as a single data entry
					return `"=""${safeItem.toString().replace(/"/g, '""')}"""`;
				})
				.join(",")
		)
		.join("\n");
}

function mapValuesToTarget(target, data) {
	let mappedArray = [];

	for (let row of data) {
		let mappedRow = [];
		for (let targetArray of target) {
			if (row.hasOwnProperty(targetArray)) {
				mappedRow.push(row[targetArray]);
			} else {
				throw new Error(`Error: Value for '${targetArray}' not found in data object`);
			}
		}
		mappedArray.push(mappedRow);
	}

	return mappedArray;
}

function formatDateForInput(dateString) {
	const date = new Date(dateString);
	const formattedDate = date.toISOString().split("T")[0];
	return formattedDate;
}

function fetchDateLimitData() {
	const module = window.location.hash.substring(1); // Get hash value without '#'

	fetch(`./shared/controllers/baseController.php`, {
		method: "POST",
		body: JSON.stringify({ module: module, model: "DateLimit" }),
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
			const jsonData = JSON.parse(data);
            if (jsonData.EarliestEndTime && jsonData.LatestEndTime) {
                const earliestEndTime = jsonData.EarliestEndTime.date;
                const latestEndTime = jsonData.LatestEndTime.date;
    
                const minDate = formatDateForInput(earliestEndTime);
                const maxDate = formatDateForInput(latestEndTime);
    
                // Set min and max attributes of HTML input using jQuery
                $(".dyelot-date-input").attr("min", minDate);
                $(".dyelot-date-input").attr("max", maxDate);
            }
		})
		.catch((error) => {
			Swal.hideLoading();
			Swal.update({
				title: "未知錯誤",
				html: error,
				icon: "error",
				showConfirmButton: true,
			});
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

$(function () {
	fetchDateLimitData();
	// bind event listener
	$("#all-dyelot-selector-form").on("submit", async function (event) {
		event.preventDefault(); // Prevent default form submission
		const modifyAction = event.originalEvent.submitter.value;

		if (modifyAction === "csv") {
			if (gridApi == "") {
				Swal.fire({
					title: "請先查詢資料",
					icon: "warning",
					showConfirmButton: true,
					didOpen: () => {
						Swal.hideLoading();
					},
				});
				return;
			}
			const anchorArray = [];
			const headerArray = [];
			const colDefs = gridApi.getColumnDefs();
			for (const colDef of colDefs) {
				if (colDef.children) {
					for (const colDefChild of colDef.children) {
						const fieldValue = colDefChild.field;
						const headerNameValue = colDefChild.headerName;
						anchorArray.push(fieldValue);
						headerArray.push(headerNameValue);
					}
				}
			}

			const rowData = [];
			gridApi.forEachNodeAfterFilterAndSort((node) => {
				rowData.push(node.data);
			});

			const csvArray = [...[headerArray], ...mapValuesToTarget(anchorArray, rowData)];

			const csvContent = "\uFEFF" + arrayToCSV(csvArray);
			// console.log(csvContent)
			const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8" });
			const a = document.createElement("a");
			a.href = URL.createObjectURL(blob);
			a.download = "所有工單.csv";
			a.click();
			URL.revokeObjectURL(a.href);
			return;
		}

		// Client-side validation
		if ($("#dyelot-selector").val() || $("#start-time-selector").val() || $("#end-time-selector").val()) {
			Swal.fire({
				title: "載入中...",
				showConfirmButton: false,
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading(); // Show the loading spinner
				},
			});

			const formData = new FormData(document.getElementById("all-dyelot-selector-form"));

			fetchAllDyelotStatisticsData(formData);
		} else {
			Swal.fire("查詢條件錯誤!", "請輸入工卡號或選擇區間", "warning");
			return;
		}
	});

	$(".machine-type-input").on("change", function () {
		const selectedMachine = $(this).val();
		if (gridApi != "") {
			let visualizedData;
			if (selectedMachine === "monitored") {
				visualizedData = cachedAllDyelotData.filter(
					(obj) =>
						obj.Machine === "R02" ||
						obj.Machine === "R03" ||
						obj.Machine === "R08" ||
						obj.Machine === "R09" ||
						obj.Machine === "R11" ||
						obj.Machine === "R12"
				);
			} else {
				visualizedData = cachedAllDyelotData;
			}
			const gridArg = {
				gridApi: gridApi,
				data: visualizedData,
				gridId: "all-dyelot-grid-container",
				gridOptions: gridOptions,
				customColumnDefs: customColumnDefs,
			};

			gridApi = populateGrid(gridArg);
		}
	});
});
