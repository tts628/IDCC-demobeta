const queryString = window.location.search;

var today = new Date();
var selectedDayVal = formatDateToYYYYMMDD(today);
var selectedMonthVal = selectedDayVal.substring(0, selectedDayVal.length - 3);
var monthYear = formatStringToMonthYear(selectedMonthVal);

var monthsData;
var xhr;

function getMonthsData(date, successCallback) {
	const isDataTooBig = false; // if query time gets too long, set to true to divide querying to 3 months only
	if (isDataTooBig) {
		if (xhr && xhr.readyState !== 4) {
			xhr.abort();
		}
		var isSameMonth = false;

		// Check if monthsData is defined and not empty
		if (monthsData) {
			const selectedDate = new Date(date);
			const selectedMonth = selectedDate.getMonth();
			const selectedYear = selectedDate.getFullYear();

			const currentDate = new Date(Object.keys(monthsData)[1]);
			const currentMonth = currentDate.getMonth();
			const currentYear = currentDate.getFullYear();
			isSameMonth = selectedMonth === currentMonth && selectedYear === currentYear;
		}
		if (!isSameMonth) {
			xhr = $.ajax({
				url: "./Model/getEnergyConsumptionData.php" + queryString,
				type: "POST",
				data: { date: date },
				dataType: "json",
				success: function (response) {
					monthsData = response;
					successCallback();
				},
				error: function (error) {
					$("#status").append(`<div style="color: white; text-align: center; border: 1px solid #ffffff; padding: 10px;">
                                            <h1>No Data / Error</h1>
                                        </div>`);
					console.error("Error:", error);
				},
			});
		} else {
			successCallback();
		}
	} else {
		if (!monthsData) {
			xhr = $.ajax({
				url: "./Model/getEnergyConsumptionData.php" + queryString,
				type: "POST",
				data: { date: date },
				dataType: "json",
				success: function (response) {
					monthsData = response;
					successCallback();
				},
				error: function (error) {
					$("#status").append(`<div style="color: white; text-align: center; border: 1px solid #ffffff; padding: 10px;">
                                            <h1>No Data / Error</h1>
                                        </div>`);
					console.error("Error:", error);
				},
			});
		} else {
			successCallback();
		}
	}
}

function monthCellRenderer(params) {
	const cellDate = new Date(params.value);
	const cellDateDay = cellDate.getDate();

	const currentDate = new Date(selectedDayVal);
	const otherMonthClass = cellDate.getMonth() !== currentDate.getMonth() ? "cell-other-month-date" : "";

	const formattedCellMonthDate = formatDateToYYYYMM(cellDate);
	const formattedCellDayDate = formatDateToYYYYMMDD(cellDate);
	let Power;
	let Steam;
	let Water;

	try {
		if (
			typeof monthsData[formattedCellMonthDate][formattedCellDayDate] !== "undefined" ||
			typeof monthsData[formattedCellMonthDate] !== "undefined"
		) {
			const selectedDateData = monthsData[formattedCellMonthDate][formattedCellDayDate];
			Power = selectedDateData.Power + " KHW";
			Steam = selectedDateData.Steam + " Kg";
			Water = selectedDateData.Water + " L";
		} else {
			Power = "no data";
			Steam = "no data";
			Water = "no data";
		}
	} catch (error) {
		Power = "no data";
		Steam = "no data";
		Water = "no data";
	}

	const cellElement = `
        <div class="d-flex">
            <div class="ms-auto ${otherMonthClass}">${cellDateDay}</div>
        </div>
        <div class="fw-bold y-up-1">
            <div>Power: ${Power}</div>
            <div class="y-up-1">Steam: ${Steam}</div>
            <div class="y-up-2">Water: ${Water}</div>
        </div>
    `;
	return cellElement;
}

function weekCellRenderer(params) {
	const cellDate = new Date(params.value);
	const cellDateDay = formatDateToDay(cellDate);
	const dayOfDateNumber = cellDate.getDay();

	const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	const dayName = dayNames[dayOfDateNumber];

	const formattedCellMonthDate = formatDateToYYYYMM(cellDate);
	const formattedCellDayDate = formatDateToYYYYMMDD(cellDate);

	let Power;
	let Steam;
	let Water;

	try {
		if (
			typeof monthsData[formattedCellMonthDate][formattedCellDayDate] !== "undefined" ||
			typeof monthsData[formattedCellMonthDate] !== "undefined"
		) {
			const selectedDateData = monthsData[formattedCellMonthDate][formattedCellDayDate];
			Power = selectedDateData.Power + " KHW";
			Steam = selectedDateData.Steam + " Kg";
			Water = selectedDateData.Water + " L";
		} else {
			Power = "no data";
			Steam = "no data";
			Water = "no data";
		}
	} catch (error) {
		Power = "no data";
		Steam = "no data";
		Water = "no data";
	}

	const cellElement = `
        <div class="row py-1 bg-lightgrey rounded-small">
            <div class="col-6">
                ${dayName}
            </div>
            <div class="col-6">
                <div class="d-flex">
                    <div class="ms-auto">${cellDateDay}</div>
                </div>
            </div>
        </div>
        <div class="fw-bold">
            <div>Power: ${Power}</div>
            <div class="y-up-1">Steam: ${Steam}</div>
            <div class="y-up-2">Water: ${Water}</div>
        </div>
    `;
	return cellElement;
}

function populateCalendarDay(date) {
	$("#dayHiddenOption").prop("checked", true);
	$("#dateSelector").data("datetype", "day");

	const currentDate = new Date(date);

	const today = new Date();
	// disable today button select to prevent confusion
	const isSameDate = currentDate.getDate() === today.getDate();
	if (isSameDate) {
		$("#setToday").prop("disabled", true);
	} else {
		$("#setToday").prop("disabled", false);
	}

	const dayOfWeekNumber = currentDate.getDay();
	const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	const dayName = dayNames[dayOfWeekNumber];
	$("#calendarDayDateText").text(dayName);

	const $selectedDate = $("#selectedDate");
	$selectedDate.text(formatDateToDay(currentDate));

	const formattedCellMonthDate = formatDateToYYYYMM(currentDate);
	const formattedCellDayDate = formatDateToYYYYMMDD(currentDate);

	let Power;
	let Steam;
	let Water;

	try {
		if (
			typeof monthsData[formattedCellMonthDate][formattedCellDayDate] !== "undefined" ||
			typeof monthsData[formattedCellMonthDate] !== "undefined"
		) {
			const selectedDateData = monthsData[formattedCellMonthDate][formattedCellDayDate];
			Power = selectedDateData.Power + " KHW";
			Steam = selectedDateData.Steam + " Kg";
			Water = selectedDateData.Water + " L";
		} else {
			Power = "no data";
			Steam = "no data";
			Water = "no data";
		}
	} catch (error) {
		Power = "no data";
		Steam = "no data";
		Water = "no data";
	}

	$("#calendarDayPower").text(Power);
	$("#calendarDaySteam").text(Steam);
	$("#calendarDayWater").text(Water);
}

// Function to format a date as "yyyy-mm-dd"
function formatDateToYYYYMMDD(date) {
	const year = date.getFullYear();
	const month = (date.getMonth() + 1).toString().padStart(2, "0");
	const day = date.getDate().toString().padStart(2, "0");
	return `${year}-${month}-${day}`;
}

function formatDateToYYYYMM(date) {
	const year = date.getFullYear();
	const month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-based
	return `${year}-${month}`;
}

function formatDateToMonthYear(date) {
	const options = { year: "numeric", month: "long" };
	return date.toLocaleDateString(undefined, options);
}

function formatDateToMonth(date) {
	const monthName = date.toLocaleDateString(undefined, { month: "short" });
	return `${monthName}`;
}

function formatDateToDay(date) {
	const year = date.getFullYear();
	const monthName = date.toLocaleDateString(undefined, { month: "long" });
	const day = date.getDate().toString().padStart(2, "0");
	return `${monthName} ${day}, ${year}`;
}

function formatStringToMonthYear(dateString) {
	const dateParts = dateString.split("-");
	if (dateParts.length >= 2) {
		const year = parseInt(dateParts[0], 10);
		const month = parseInt(dateParts[1], 10) - 1; // Months are zero-based
		let day = 1; // Default day to 1

		if (dateParts.length === 3) {
			day = parseInt(dateParts[2], 10);
		}

		if (!isNaN(year) && !isNaN(month) && !isNaN(day)) {
			const formattedDate = new Date(year, month, day);
			return formatDateToMonthYear(formattedDate);
		}
	}
	return "Invalid Date"; // Handle invalid input gracefully
}

function isSameWeek(date1, date2) {
	const weekStart = new Date(date2);
	weekStart.setHours(0, 0, 0, 0);
	weekStart.setDate(date2.getDate() - date2.getDay());

	const weekEnd = new Date(date2);
	weekEnd.setHours(0, 0, 0, 0);
	weekEnd.setDate(date2.getDate() - date2.getDay() + 6);

	return weekStart <= date1 && date1 <= weekEnd;
}

function populateMonthGrid(calendarMonthGrid, sourceDate) {
	const $calendarDay = $("#calendarDay");
	$calendarDay.hide();

	// Function to find the nearest previous Sunday to a given date
	function getNearestPreviousSunday(date) {
		const daysToSunday = (date.getDay() + 7 - 0) % 7;
		const nearestPreviousSunday = new Date(date);
		nearestPreviousSunday.setDate(date.getDate() - daysToSunday);
		return nearestPreviousSunday;
	}

	// Function to find the nearest next Saturday to a given date
	function getNearestNextSaturday(date) {
		const daysUntilSaturday = 6 - date.getDay();
		const nextSaturday = new Date(date);
		nextSaturday.setDate(date.getDate() + daysUntilSaturday);
		return nextSaturday;
	}

	// Get the current date from the sourceDate variable
	const currentDate = new Date(sourceDate);

	const today = new Date();
	// disable today button select to prevent confusion
	const isSameYear = currentDate.getFullYear() === today.getFullYear();
	const isSameMonth = currentDate.getMonth() === today.getMonth();
	const isSameDate = isSameYear && isSameMonth;
	if (isSameDate) {
		$("#setToday").prop("disabled", true);
	} else {
		$("#setToday").prop("disabled", false);
	}

	// Determine the first day of the current month
	const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
	const nearestPreviousSunday = getNearestPreviousSunday(firstDayOfMonth);

	// Calculate the last day of the current month
	const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

	// Create an array of week data
	const nearestSundayDate = new Date(nearestPreviousSunday);
	const saturdayDate = getNearestNextSaturday(lastDayOfMonth);
	const rowData = [];
	let weekData = {};

	// Loop through the days starting from nearestPreviousSunday to nextSaturday
	while (nearestSundayDate <= saturdayDate) {
		const dayOfWeek = nearestSundayDate.getDay();
		const dayName = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"][dayOfWeek];
		const formattedDate = formatDateToYYYYMMDD(nearestSundayDate);

		weekData[dayName] = formattedDate;

		// Move to the next Sunday
		nearestSundayDate.setDate(nearestSundayDate.getDate() + 1);

		if (dayOfWeek === 6 && weekData["sunday"]) {
			rowData.push(weekData);
			weekData = {};
		}
	}

	var gridOptions = {
		rowData: rowData,
		columnDefs: [
			{ field: "sunday" },
			{ field: "monday" },
			{ field: "tuesday" },
			{ field: "wednesday" },
			{ field: "thursday" },
			{ field: "friday" },
			{ field: "saturday" },
		],
		domLayout: "autoHeight",
		defaultColDef: {
			cellRenderer: monthCellRenderer,
			sortable: false,
			suppressMovable: true,
			flex: 1,
		},

		getRowHeight: (params) => {
			return 120;
		},

		onCellClicked: function (params) {
			$("#dayHiddenOption").prop("checked", true);
			$("#dateSelector").data("datetype", "date");
			calendarMonthGrid.innerHTML = "";

			selectedDayVal = params.value;
			getMonthsData(selectedDayVal, function () {
				$calendarDay.html(populateCalendarDay(selectedDayVal));
			});
			$calendarDay.show();
		},
	};

	calendarMonthGrid.innerHTML = "";
	agGrid.createGrid(calendarMonthGrid, gridOptions);

	monthYear = formatStringToMonthYear(selectedMonthVal);
	const $selectedDate = $("#selectedDate");
	$selectedDate.text(monthYear);
}

function populateWeekGrid(calendarWeekGrid, sourceDate) {
	const $calendarDay = $("#calendarDay");
	$calendarDay.hide();

	const currentDate = new Date(sourceDate);

	const today = new Date();
	// disable today button select to prevent confusion
	if (isSameWeek(currentDate, today)) {
		$("#setToday").prop("disabled", true);
	} else {
		$("#setToday").prop("disabled", false);
	}

	const currentDay = currentDate.getDay();

	// Calculate the date of the 1st day (Sunday) of this week
	const firstDayOfWeek = new Date(currentDate);
	firstDayOfWeek.setDate(currentDate.getDate() - currentDay);

	// Calculate the date of the last day (Saturday) of this week
	const lastDayOfWeek = new Date(firstDayOfWeek);
	lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6);

	const rowData = [];
	let dayData = {};

	while (firstDayOfWeek <= lastDayOfWeek) {
		const formattedDate = formatDateToYYYYMMDD(firstDayOfWeek);

		dayData["weekField"] = formattedDate;
		rowData.push(dayData);
		dayData = {};

		firstDayOfWeek.setDate(firstDayOfWeek.getDate() + 1);
	}
	firstDayOfWeek.setDate(currentDate.getDate() - currentDay);

	var gridOptions = {
		rowData: rowData,
		columnDefs: [{ field: "weekField" }],
		domLayout: "autoHeight",
		headerHeight: 0,
		defaultColDef: {
			cellRenderer: weekCellRenderer,
			sortable: false,
			suppressMovable: true,
			flex: 1,
		},

		getRowHeight: (params) => {
			return 140;
		},

		onCellClicked: function (params) {
			calendarWeekGrid.innerHTML = "";
			selectedDayVal = params.value;
			getMonthsData(selectedDayVal, function () {
				$calendarDay.html(populateCalendarDay(selectedDayVal));
			});
			$calendarDay.show();
		},
	};

	calendarWeekGrid.innerHTML = "";
	agGrid.createGrid(calendarWeekGrid, gridOptions);

	const isSameMonth = firstDayOfWeek.getMonth() === lastDayOfWeek.getMonth();
	const isSameYear = firstDayOfWeek.getFullYear() === lastDayOfWeek.getFullYear();

	let formattedFirstDayOfWeek, formattedLastDayOfWeek;

	// Scenario 2
	if (isSameYear) {
		// Scenario 1
		if (isSameMonth) {
			formattedFirstDayOfWeek = formatDateToMonth(firstDayOfWeek) + ` ${firstDayOfWeek.getDate()}`;
			formattedLastDayOfWeek = `${lastDayOfWeek.getDate()}, ${lastDayOfWeek.getFullYear()}`;
		} else {
			formattedFirstDayOfWeek = formatDateToMonth(firstDayOfWeek) + ` ${firstDayOfWeek.getDate()}`;
			formattedLastDayOfWeek =
				formatDateToMonth(lastDayOfWeek) + ` ${lastDayOfWeek.getDate()}, ${lastDayOfWeek.getFullYear()}`;
		}
	} else {
		formattedFirstDayOfWeek = formatDateToMonth(firstDayOfWeek) + `, ${firstDayOfWeek.getFullYear()}`;
		formattedLastDayOfWeek = formatDateToMonth(lastDayOfWeek) + `, ${lastDayOfWeek.getFullYear()}`;
	}

	const weekText = formattedFirstDayOfWeek + " - " + formattedLastDayOfWeek;
	const $selectedDate = $("#selectedDate");
	$selectedDate.text(weekText);
}

$(function () {
	$(".date-input").css("display", "none"); // date input is not hidden properly on mobile initialization when called with menu
	$("#loadingSpinner").hide();

	// Show the loading spinner before making the AJAX request
	$(document).ajaxStart(function () {
		$("#loadingSpinner").show();
		today = new Date();
	});

	// Hide the loading spinner when the AJAX request is complete
	$(document).ajaxComplete(function () {
		$("#loadingSpinner").hide();
	});

	const $dateSelector = $("#dateSelector");
	const $monthInput = $("#monthInput");
	const $dateInput = $("#dateInput");
	const $selectedDate = $("#selectedDate");
	const calendarMonthGrid = document.querySelector("#calendarMonth");
	const calendarWeekGrid = document.querySelector("#calendarWeek");

	getMonthsData(selectedDayVal, function () {
		populateMonthGrid(calendarMonthGrid, selectedDayVal);
	});
	$selectedDate.text(monthYear);

	$("#monthInput").on("input propertychange", function () {
		// Handle date input change event
		selectedMonthVal = $(this).val();
		selectedDayVal = selectedMonthVal + "-01";
		getMonthsData(selectedDayVal, function () {
			populateMonthGrid(calendarMonthGrid, selectedDayVal);
		});
	});

	$("#dateInput").on("input propertychange", function () {
		selectedDayVal = $(this).val();
		selectedMonthVal = selectedDayVal.substring(0, selectedDayVal.length - 3);

		const dateType = $dateSelector.data("datetype");
		switch (dateType) {
			case "week":
				getMonthsData(selectedDayVal, function () {
					populateWeekGrid(calendarMonthGrid, selectedDayVal);
				});
				break;
			case "day":
				getMonthsData(selectedDayVal, function () {
					populateCalendarDay(selectedDayVal);
				});
				break;
		}
	});

	// Add a click event handler to the button
	$("#checkRadioButton").click(function () {
		// Check the radio button with the id "dayHiddenOption"
		$("#dayHiddenOption").prop("checked", true);
	});

	// Add a click event handler to the button
	$dateSelector.on("mouseenter click", function () {
		const dateType = $dateSelector.data("datetype");

		if (dateType === "month") {
			$monthInput.show("slide", { direction: "up" }, 150);
			$monthInput.val(selectedMonthVal);
		} else if (dateType === "day" || dateType === "week") {
			$dateInput.show("slide", { direction: "up" }, 150);
			$dateInput.val(selectedDayVal);
		}
	});

	$(document).on("mousemove click", function (event) {
		if (!$dateSelector.is(event.target) && !$dateSelector.has(event.target).length) {
			$monthInput.hide("slide", { direction: "up" }, 50);
			$dateInput.hide("slide", { direction: "up" }, 50);
		}
	});

	$(".date-iterator").click(function () {
		const dateType = $dateSelector.data("datetype");
		switch (dateType) {
			case "month":
				var direction = $(this).data("direction");

				// Convert selectedMonthVal to Date object for manipulation
				var selectedMonthDate = new Date(selectedMonthVal);

				if (direction === "previous") {
					// Decrement the month
					selectedMonthDate.setMonth(selectedMonthDate.getMonth() - 1);
				} else if (direction === "next") {
					// Increment the month
					selectedMonthDate.setMonth(selectedMonthDate.getMonth() + 1);
				}

				// Convert back to "YYYY-MM" format
				var year = selectedMonthDate.getFullYear();
				var month = (selectedMonthDate.getMonth() + 1).toString().padStart(2, "0");
				selectedMonthVal = `${year}-${month}`;

				selectedDayVal = selectedMonthVal + "-01";
				getMonthsData(selectedDayVal, function () {
					populateMonthGrid(calendarMonthGrid, selectedDayVal);
				});

				break;
			case "week":
				var direction = $(this).data("direction");

				// Convert selectedDayVal to Date object for manipulation
				var selectedDayDate = new Date(selectedDayVal);

				if (direction === "previous") {
					selectedDayDate.setDate(selectedDayDate.getDate() - 7);
				} else if (direction === "next") {
					selectedDayDate.setDate(selectedDayDate.getDate() + 7);
				}

				// Convert back to "YYYY-MM-DD" format
				var year = selectedDayDate.getFullYear();
				var month = (selectedDayDate.getMonth() + 1).toString().padStart(2, "0");
				var day = selectedDayDate.getDate().toString().padStart(2, "0");
				selectedDayVal = `${year}-${month}-${day}`;
				selectedMonthVal = `${year}-${month}`;

				getMonthsData(selectedDayVal, function () {
					populateWeekGrid(calendarWeekGrid, selectedDayVal);
				});

				break;
			case "day":
				var direction = $(this).data("direction");

				// Convert selectedDayVal to Date object for manipulation
				var selectedDayDate = new Date(selectedDayVal);

				if (direction === "previous") {
					selectedDayDate.setDate(selectedDayDate.getDate() - 1);
				} else if (direction === "next") {
					selectedDayDate.setDate(selectedDayDate.getDate() + 1);
				}

				// Convert back to "YYYY-MM-DD" format
				var year = selectedDayDate.getFullYear();
				var month = (selectedDayDate.getMonth() + 1).toString().padStart(2, "0");
				var day = selectedDayDate.getDate().toString().padStart(2, "0");
				selectedDayVal = `${year}-${month}-${day}`;
				selectedMonthVal = `${year}-${month}`;

				getMonthsData(selectedDayVal, function () {
					populateCalendarDay(selectedDayVal);
				});

				break;
		}
	});

	$("#setToday").click(function () {
		$("#setToday").prop("disabled", true);
		selectedDayVal = formatDateToYYYYMMDD(today);
		selectedMonthVal = selectedDayVal.substring(0, selectedDayVal.length - 3);
		const dateType = $dateSelector.data("datetype");
		switch (dateType) {
			case "month":
				calendarWeekGrid.innerHTML = "";
				getMonthsData(selectedDayVal, function () {
					populateMonthGrid(calendarMonthGrid, selectedDayVal);
				});
				break;
			case "week":
				calendarMonthGrid.innerHTML = "";
				getMonthsData(selectedDayVal, function () {
					populateWeekGrid(calendarWeekGrid, selectedDayVal);
				});
				break;
			case "day":
				getMonthsData(selectedDayVal, function () {
					populateCalendarDay(selectedDayVal);
				});
				break;
		}
	});

	$("#weekOption").click(function () {
		calendarMonthGrid.innerHTML = "";
		getMonthsData(selectedDayVal, function () {
			populateWeekGrid(calendarWeekGrid, selectedDayVal);
		});
		$("#dateSelector").data("datetype", "week");
	});

	$("#monthOption").click(function () {
		calendarWeekGrid.innerHTML = "";
		getMonthsData(selectedDayVal, function () {
			populateMonthGrid(calendarMonthGrid, selectedDayVal);
		});
		$("#dateSelector").data("datetype", "month");
	});
});
