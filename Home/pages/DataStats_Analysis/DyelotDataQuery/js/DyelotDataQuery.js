$(() => {
	// Initialization
	const today = formatDate(new Date());
	$("#start-time-selector").val(today);

	initializeForm();
});

function initializeForm() {
	$("#dyelot-data-form").on("submit", handleFormSubmit);
	submitForm(); // Initial form submission on page load
}

function formatDate(date) {
	return date.toISOString().slice(0, 10);
}

function handleFormSubmit(event) {
	event.preventDefault();
	submitForm();
}

async function submitForm() {
	const form = document.getElementById("dyelot-data-form");
	const formData = new FormData(form);
	await fetchProductionMonthStatisticsData(formData);
}

async function fetchProductionMonthStatisticsData(formData) {
	try {
		const queryString = window.location.search;
		const response = await fetch(`./pages/DataStats_Analysis/DyelotDataQuery/controllers/DyelotDataQueryController.php${queryString}`, {
			method: "POST",
			body: formData,
		});

		if (!response.ok) {
			throw new Error("Network response was not ok");
		}

		const data = await response.json();
		handleDyelotQueryResponse(data);
	} catch (error) {
		console.error("Fetch error:", error);
	}
}

function handleDyelotQueryResponse(data) {
	const dyelotQueryRes = JSON.parse(data);

	const gridOptions = {
		defaultColDef: {
			resizable: true,
			width: 120,
			headerClass: "header-text-center",
			cellClass: "text-center",
			filter: true,
			floatingFilter: true,
		},
	};

	const columnDefOptions = [
		createPinnedColumn("Dyelot"),
		createPinnedColumn("Order"),
		{
			target: "ProductType",
			options: {
				cellRenderer: renderProductType,
			},
		},
	];

	const customName = [
		"領料單", // "工卡",
		"工單號",
		"重染",
		"供料機台",
		"染機號碼", // "目標機台",
		"原料代碼",
		"原料名稱",
		"原料種類",
		"目標重量",
		"實際重量",
		"開始時間",
		"結束時間",
	];

	const gridArg = {
		gridApi: "",
		field: dyelotQueryRes.field,
		data: dyelotQueryRes.data,
		gridId: "dyelot-data-grid-container",
		customName: customName,
		gridOptions: gridOptions,
		columnDefOptions: columnDefOptions,
	};

	populateGrid(gridArg);
}

function createPinnedColumn(field) {
	return {
		target: field,
		options: {
			pinned: "left",
		},
	};
}

function renderProductType(params) {
	const productTypeCode = params.value;
	switch (productTypeCode) {
		case "1":
			return "染料";
		case "2":
			return "助劑";
		default:
			return productTypeCode;
	}
}
