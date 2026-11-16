import { selectInputValidate } from "../../../../../bootstrap/views/utilities.js";

const controllerDir = "./Records/modules/AllMachineList/controllers";
const picturesDir = "./Records/modules/AllMachineList/uploads/images";

const $imageContainer = $("#image-thumbnails-container");
const $uploadedPreview = $("#uploaded-preview");
const $existingPreview = $("#existing-preview");

const module = window.location.hash.substring(1); // Get hash value without '#'

let gridApi = "";
const customName = [
	"設備名稱",
	"類別",
	"序號",
	"時間",
	"保管單位",
	"照片",
	"保管人",
	"生效期限",
	"截止期限",
	"建立日期",
];

const columnDefOptions = [
	{
		target: "all",
		options: {
			valueFormatter: function (params) {
				if (params.value) {
					const currentField = params.colDef.field;
					if (currentField === "ReferenceTable") {
						return machineTypeMapper(params.value);
					}
					return params.value;
				} else {
					return "";
				}
			},
		},
	},
	{
		target: "Picture",
		options: {
			cellRenderer: function (params) {
				if (params.value) {
					const pictures = JSON.parse(params.value);
					const gridThumbnail = `
                        <picture class="restricted-image-container">
                            <img src="${picturesDir}/${pictures[0]}" alt="${pictures[0]}">
                        </picture>
                    `;
					return gridThumbnail;
				} else {
					return "";
				}
			},
		},
	},
];

let pinnedBottomRowData = [
	{
		MachineActualName: "",
		isTotalRow: true,
	},
	{
		MachineActualName: "新增",
		insert: true,
	},
];

const gridOptions = {
	defaultColDef: {
		resizable: true,
		filter: true,
		floatingFilter: true,
	},
	onRowClicked: (params) => {
		callMachineListFormModal(params);
	},
	getRowHeight: (params) => {
		// Check if the row is pinned to the bottom
		if (params.node.rowPinned === "bottom") {
			return 50; /* Custom height for pinned bottom rows */
		}
		// Default row height for other rows
		return 100;
	},
	getRowClass: (params) => {
		const isTotalRow = params.data.isTotalRow;
		if (isTotalRow) {
			return "red-bg";
		}

		const isInsertRow = params.data.insert;
		if (isInsertRow) {
			return "insert-ag-row";
		}
	},
};

let gridArg = {
	gridApi: gridApi,
	field: [],
	customName: customName,
	data: [],
	columnDefOptions: columnDefOptions,
	pinnedBottomRowData: pinnedBottomRowData,
	gridId: "machine-list-grid-container",
	gridOptions: gridOptions,
};

function formatDateTimeForInput(dateTimeStr) {
	// Data validation to prevent null value processing
	if (!dateTimeStr || isNaN(Date.parse(dateTimeStr))) {
		return null; // or return a default date-time string if preferred
	}

	// Create a Date object from the input string
	var date = new Date(dateTimeStr);

	// Create a formatter for the date part (yyyy-MM-dd)
	var formattedDate =
		date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);

	// Create a formatter for the time part (HH:mm)
	var formattedTime = ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);

	// Combine the formatted date and time with 'T' as separator
	return formattedDate + "T" + formattedTime;
}

function callMachineListFormModal(event) {
	// Title text
	const isAddNewRow = event.data.insert;
	if (isAddNewRow) {
		$("#machine-list-modal-header").text("新增機台");
		$("#delete-machine-list-record").hide();
		$("#edit-submit-machine-list-modal").hide();
		$("#add-submit-machine-list-modal").show();
		updateForm();
	} else {
		$("#machine-list-modal-header").text("編輯機台");
		$("#delete-machine-list-record").show();
		$("#edit-submit-machine-list-modal").show();
		$("#add-submit-machine-list-modal").hide();
		updateForm(event.data);
	}

	$("#machine-list-modal").modal("show");
}

function updateForm(data) {
    $uploadedPreview.empty();
    $existingPreview.empty();
	$("#image-input").val("");
	if (data) {
		if (data.Picture) {
			$existingPreview.append(`<div class="mb-3">現有照片:</div>`);
			const existingPictures = JSON.parse(data.Picture);
			$.each(existingPictures, (_index, fileName) => {
				const pictureHtml = `
                    <div class="col-4 mb-3">
                        <div class="position-relative d-flex">
                            <div class="ms-auto">
                                <input class="form-check-input db-delete-check" type="checkbox" value="${fileName}" id="${fileName}">
                                <label for="${fileName}" class="delete-button">×</label>
                            </div>
                        </div>
                        <picture class="restricted-image-container">
                            <img src="${picturesDir}/${fileName}" alt="${fileName}">
                            <div class="break-word">${fileName}</div>
                        </picture>
                    </div>
                `;
				$existingPreview.append($(pictureHtml));
			});
			$imageContainer.show();
		} else {
			$imageContainer.hide();
		}

		const timeInput = formatDateTimeForInput(data.Time);
		const activationDate = formatDateTimeForInput(data.ActivationDate);
		const deactivationDate = formatDateTimeForInput(data.DeactivationDate);
		const createdDate = formatDateTimeForInput(data.CreatedDate);

		$("#time-input").val(timeInput);
		$("#activation-date").val(activationDate);
		$("#deactivation-date").val(deactivationDate);
		$("#created-date").val(createdDate);

		$("#machine-name").val(data.MachineActualName);
		$("#machine-number").val(data.MachineName);
		$("#machine-type").val(data.MachineName);
		$("#serial-number").val(data.SerialNumber);
		$("#custodian-unit").val(data.CustodianUnit);
		$("#custodian-personnel").val(data.CustodianPersonnel);
		$("#machine-type").val(data.ReferenceTable);

		$("#data-id").val(data.ID);
		$("#reference-id").val(data.ReferenceId);
	} else {
		// Reset form inputs
		document.getElementById("machine-list-form").reset();
        $imageContainer.hide();
		// const $formInputs = $("#machine-list-form input");
		// $formInputs.each(function() {
		//     this.reset();  // 'this' refers to each native form element
		// });

		// $formInputs.each(function () {
		// 	$(this).val("");
		// });
	}
}

function machineTypeMapper(machineType) {
	switch (machineType) {
		case "DyeMachines":
			return "染缸";
		case "StenterMachines":
			return "定型";
		case "SingeingMachines":
			return "燒毛";
		case "WashingDryerMachines":
			return "成衣";
		case "PrintingMachines":
			return "印花";
		case "FinishMachines":
			return "後整理";
		case "PackagingMachines":
			return "包裝";
		case "LabMachines":
			return "化驗室";
		case "OthersMachines":
			return "其他";
		default:
			return machineType;
	}
}

function fetchAllMachineFormData() {
	gridApi.showLoadingOverlay();

	fetch(`./shared/controllers/baseController.php`, {
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
			const maintenanceFormData = JSON.parse(data);

			const columnDefs = maintenanceFormData.columnDefs;
			const rowData = maintenanceFormData.rowData;
			gridArg = {
				gridApi: gridApi,
				field: columnDefs,
				customName: customName,
				data: rowData,
				columnDefOptions: columnDefOptions,
				pinnedBottomRowData: pinnedBottomRowData,
				gridOptions: gridOptions,
			};

			populateGrid(gridArg);
			gridApi.hideOverlay();

			const totalRows = gridApi.getModel().getRowCount();
			pinnedBottomRowData[0].MachineActualName = "Total: " + totalRows + " data";
			gridApi.setGridOption("pinnedBottomRowData", pinnedBottomRowData);
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

function fetchMachineTypeData() {
	fetch(`./shared/controllers/baseController.php`, {
		method: "POST",
		body: JSON.stringify({ module: module, model: "MachineType" }),
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
			const machineTypeData = JSON.parse(data);
			const $machineType = $("#machine-type");
			machineTypeData.forEach(function (value, _index) {
				const typeOption = `<option value="${value}">${machineTypeMapper(value)}</option>`;
				$machineType.append(typeOption);
			});
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

async function confirmFormDeletion() {
	// Show SweetAlert confirmation
	const result = await Swal.fire({
		title: "確認刪除",
		html: '<span class="info-text-important">刪除後僅系統管理員可復原</span>',
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#808080",
		confirmButtonText: "確認",
		cancelButtonText: "取消",
	});

	return result.isConfirmed;
}

async function updateMachineList(formData, modifyAction) {
	try {
        let filenames = [];
        let resultsHtml = "";
		const files = formData.getAll("image-input[]");
		if (files[0].size > 0)
			for (let file of files) {
				const individualFormData = new FormData();
				individualFormData.append("image-input", file);
                individualFormData.append("module", module);

				try {
					const imageResponse = await fetch(`./shared/controllers/FileUploadController.php`, {
						method: "POST",
						body: individualFormData,
					});
					const result = await imageResponse.json();
					// Update resultsHtml with each result
					if (result.success) {
						resultsHtml += `<div>${result.success}</div>`;
					} else if (result.error) {
						resultsHtml += `<div>${result.error}</div>`;
					} else if (result.warning) {
						resultsHtml += `<div>${result.warning}</div>`;
					}
					// Update the Swal dialog
					Swal.update({
						html: resultsHtml,
					});
					Swal.showLoading(); // Dunno why the loading sometimes evaporates into void after update

					if (result.fileName) {
						filenames.push(result.fileName);
					}
				} catch (error) {
					console.error("Upload failed:", error);
					resultsHtml += `<div>Error: Image upload failed with an exception - ${error}</div>`;
					Swal.update({
						html: resultsHtml,
					});
				}
			}

        const dbFileNames = [];
        $('.db-delete-check').each(function() {
            if (!$(this).is(':checked')) {
                dbFileNames.push($(this).val());
            }
        });

		formData.delete("image-input");
		formData.append("file-names", JSON.stringify(filenames));
		formData.append("db-file-names", JSON.stringify(dbFileNames));

		// Update data
		const dbResponse = await fetch(`${controllerDir}/MachineListUpdateController.php`, {
			method: "POST",
			body: formData,
		});

		const dbData = await dbResponse.json();
		Swal.hideLoading();

		// Process response
		if (dbData === '"success"') {
			resultsHtml += `<div>資料庫上傳成功</div>`;

			let feedbackText;
			switch (modifyAction) {
				case "add":
					feedbackText = "新增";
					break;
				case "edit":
					feedbackText = "更新";
					break;
				case "delete":
					feedbackText = "刪除";
					break;
				default:
					feedbackText = "未知更新方式";
			}

			Swal.update({
				title: `紀錄${feedbackText}成功`,
				icon: "success",
				html: resultsHtml,
				showConfirmButton: true,
			});

			$("#machine-list-modal").modal("hide");
		} else {
			Swal.update({
				title: "未知錯誤",
				icon: "error",
				showConfirmButton: true,
			});
		}
		// Refresh page content
		fetchAllMachineFormData();
	} catch (error) {
		const cleanedErrorMessage = String(error).replace(/^Error: |^"|"$/g, "");
		Swal.hideLoading();
		Swal.update({
			title: "資料傳輸錯誤",
			html: cleanedErrorMessage,
			icon: "warning",
			showConfirmButton: true,
		});
		console.error(error);
	}
}

function formSubmitHandler() {
	$("#machine-list-form").on("submit", async function (event) {
		event.preventDefault(); // Prevent default form submission

		// Custon form select input validating
		if (!selectInputValidate("machine-type", "設備類別")) return;

		const modifyAction = event.originalEvent.submitter.value;

		if (modifyAction === "delete") {
			const confirmationResult = await confirmFormDeletion();
			// Cancel form submission if user cancel delete process
			if (!confirmationResult) {
				return;
			}
		}
		// Pass value to controller to process further
		$("#modify-action").val(modifyAction);

		const formData = new FormData(document.getElementById("machine-list-form"));

		// Loading visual
		Swal.fire({
			title: "載入中...",
			html: '<div class="loading-spinner"></div>', // Custom HTML with loading spinner
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading(); // Show the loading spinner
			},
		});

		updateMachineList(formData, modifyAction);
	});
}

function showImagePreview() {
	$("#image-input").on("change", (event) => {
		$uploadedPreview.empty();

		const files = event.target.files;
		if (files.length > 0) {
			$uploadedPreview.append(`<div class="mb-3">即將上傳照片:</div>`);
			$imageContainer.show();
		}

		for (let i = 0; i < files.length; i++) {
			const file = files[i];
			const reader = new FileReader();

			reader.onload = (function (f) {
				return function (e) {
					const pictureHtml = `
                        <div class="col-4 mb-3">
                            <picture class="restricted-image-container">
                                <img src="${e.target.result}" alt="${f.name}">
                                <div class="break-word">${f.name}</div>
                            </picture>
                        </div>
                    `;
					const img = $(pictureHtml);
					$uploadedPreview.append(img);
				};
			})(file);

			reader.readAsDataURL(file);
		}
	});
}

$(function () {
	fetchMachineTypeData();

	gridApi = populateGrid(gridArg);
	gridApi.showLoadingOverlay();
	fetchAllMachineFormData();

	formSubmitHandler();
	showImagePreview();
});
