// Function to prepare row data
function prepareGridRowData(field, data) {
	if (!Array.isArray(field) || !Array.isArray(data)) {
		throw new Error("Both field and data must be arrays.");
	}

	if (!Array.isArray(data[0])) {
		return data;
	}

	const dataLength = getLength(data[0]);
	if (dataLength === 0) {
		return [];
	}
	const fieldLength = field.length;

	if (fieldLength !== dataLength) {
		throw new Error("Length of field and inner arrays of data must match.");
	}

	const rowData = [];
	data.forEach((row) => {
		const rowObject = {};
		for (let i = 0; i < fieldLength; i++) {
			const fieldName = field[i];
			const value = row[i] || null;
			rowObject[fieldName] = value;
		}
		rowData.push(rowObject);
	});

	return rowData;
}

function getLength(data) {
	if (!data) {
		// Handle empty or nullish data gracefully
		return 0;
	}

	if (Array.isArray(data)) {
		return data.length; // Access length directly for arrays
	}

	if (typeof data === "object" && data !== null) {
		return Object.keys(data).length; // Check for non-null objects
	}

	throw new Error("Data must be an array, object, or nullish value"); // Clearer error message
}

// Function to prepare column definitions
function prepareGridColumnDefs(field, customName, columnDefOptions) {
	const fieldLength = field.length;
    if (fieldLength === 0) {
        return [];
    }

	if (customName && fieldLength !== customName.length) {
        console.log("length of field:", fieldLength)
        console.log("length of customName:", customName.length)
		throw new Error("Length of field and customName must match.");
	}

	const columnDefs = [];
	for (let i = 0; i < fieldLength; i++) {
		const fieldName = field[i];
		const headerName = customName ? customName[i] : fieldName;

		// Create a base column definition object
		let columnDef = { field: fieldName, headerName: headerName };

		// Apply custom options based on target and field match
		if (columnDefOptions) {
			columnDefOptions.forEach((option) => {
				if (
					option.target === "all" ||
					option.target.includes(fieldName)
				) {
					columnDef = { ...columnDef, ...option.options }; // Merge options
				}
			});
		}

		columnDefs.push(columnDef);
	}

	return columnDefs;
	// // Example structure of columnDefOptions:
	// const columnDefOptions = [
	// 	{
	// 		target: "all",
	// 		options: {
	// 			headerClass: "text-center",
	// 			cellClass: "text-center",
	// 		},
	// 	},
	// 	{
	// 		target: "field_1",
	// 		options: {
	//          cellRenderer: customCellRenderer
	// 		},
	// 	},
	// ];
}

//         ------- can initialize first before actually populate the grid to get gridApi for showing loading overlay etc.
// Function to create or update the grid
function populateGrid(gridArg) {
	let {
		gridApi,
		field,
		data,
		gridId,
		customName,
		gridOptions,
		columnDefOptions,
		pinnedBottomRowData,
        customColumnDefs,
	} = gridArg;

	try {
        let rowData;
        let columnDefs;

        if (customColumnDefs) {
            columnDefs = customColumnDefs;
            rowData = data;
        } else {
            columnDefs = prepareGridColumnDefs(
                field,
                customName,
                columnDefOptions
            );
            rowData = prepareGridRowData(field, data);
        }
        
		if (!gridApi) {
            const localeText = {
                // Number Filter & Text Filter
                filterOoo: '篩選...',
                equals: '等於 ==',
                notEqual: '不等於 !=',
                blank: '空白',
                notBlank: '非空白',
                empty: 'Choose one',
        
                // Number Filter
                lessThan: '少於 <',
                greaterThan: '大於 >',
                lessThanOrEqual: '小於或等於 ≤',
                greaterThanOrEqual: '大於或等於 ≥',
                inRange: '之間',
                inRangeStart: '從',
                inRangeEnd: '至',
        
                // Text Filter
                contains: '包含',
                notContains: '不包含',
                startsWith: '以...開始',
                endsWith: '以...結束',
        
                // Filter Conditions
                andCondition: '與',
                orCondition: '或',
                advancedFilterAnd: '與',
                advancedFilterOr: '或',
            };

			gridOptions.localeText = localeText;
			gridOptions.rowData = rowData;
			gridOptions.columnDefs = columnDefs;

			gridApi = createGrid(gridOptions, gridId);
		} else {
			updateGrid(rowData, columnDefs, gridApi);
            gridApi.refreshCells();
		}
		if (pinnedBottomRowData) {
			gridApi.setGridOption("pinnedBottomRowData", pinnedBottomRowData);
            gridApi.refreshCells();
		}
	} catch (error) {
		console.error("Error populating grid:", error);
	}

	return gridApi;
}

// Function to create the grid (assuming agGrid is available elsewhere)
function createGrid(gridOptions, gridId) {
	const gridDiv = document.getElementById(gridId);
	gridDiv.innerHTML = "";
	return agGrid.createGrid(gridDiv, gridOptions);
}

// Function to update the grid (assuming agGrid API is available)
function updateGrid(rowData, columnDefs, gridApi) {
	gridApi.setGridOption("columnDefs", columnDefs);
	gridApi.setGridOption("rowData", rowData);
}
