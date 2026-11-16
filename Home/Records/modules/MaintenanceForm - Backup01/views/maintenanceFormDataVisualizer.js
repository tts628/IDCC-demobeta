import { showNotification } from "../../../../../bootstrap/views/utilities.js";

let gridApi = "";
const customName = [
	"設備名稱",
	"維修類別",
	"維修項目",
	"原因",
	"維修詳細說明",
	"相片",
	"維修人員",
	"開始時間",
	"結束時間",
];

const columnDefOptions = [
	{
		target: "all",
		options: {
			valueFormatter: function (params) {
				if (params.value) {
					const currentField = params.colDef.field;
					if (currentField === "StartTime" || currentField === "EndTime") {
						const date = new Date(params.value.date);
						const year = date.getFullYear().toString().padStart(4, "0");
						const month = (date.getMonth() + 1).toString().padStart(2, "0");
						const day = date.getDate().toString().padStart(2, "0");
						const hours = date.getHours().toString().padStart(2, "0");
						const minutes = date.getMinutes().toString().padStart(2, "0");

						return `${year}-${month}-${day} ${hours}:${minutes}`;
					} else if (currentField === "MaintenanceType") {
						return mapNumberToWord(params.value) + "級";
					} else {
						return params.value;
					}
				} else {
					return "";
				}
			},
		},
	},
	{
		target: "Photo",
		options: {
			cellRenderer: imageCellRenderer,
			width: 2000,
		},
	},
	{
		target: "Machine",
		options: { suppressMovable: true, pinned: "left" },
	},
];

const pinnedBottomRowData = [
	{
		Machine: "新增",
		insert: true,
	},
];

// class CustomLoadingCellRenderer {
// 	init(params) {
// 		this.eGui = document.createElement("div");
// 		this.eGui.innerHTML = `
//             <div class="ag-custom-loading-cell" style="padding-left: 10px; line-height: 25px;">
//                 <i class="fas fa-spinner fa-pulse"></i>
//                 <span>${params.loadingMessage} </span>
//             </div>
//         `;
// 	}

// 	getGui() {
// 		return this.eGui;
// 	}
// }

const gridOptions = {
	defaultColDef: {
		resizable: true,
		flex: 1,
		filter: true,
		floatingFilter: true,
	},
	onRowClicked: function (event) {
		callMaintenanceFormModal(event);
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
		if (params.node.rowPinned === "bottom") {
			return "add-data-pinned-bottom-row";
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
	gridId: "maintenance-form-grid-container",
	gridOptions: gridOptions,
};

$(function () {
	gridApi = populateGrid(gridArg);
	gridApi.showLoadingOverlay();
	fetchMaintenanceFormData();

	const module = window.location.hash.substring(1); // Get hash value without '#'

	fetch(`./shared/controllers/baseController.php`, {
		method: "POST",
		body: JSON.stringify({ module: module, model: "MachineOption" }),
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
            // process machine option
			console.log(data);
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});

	$("#modalRoot #Photo").change(function (event) {
		const files = event.target.files;

		// Check if more than one image is selected
		if (files.length > 1) {
			Swal.fire({
				title: `請僅選擇一張圖片`,
				icon: "error",
			});
			$(this).val(""); // Clear the input value using jQuery
			return;
		}

		// Check if the selected file is an image
		const file = files[0];
		if (!file.type.startsWith("image/")) {
			Swal.fire({
				title: `請僅選擇圖檔`,
				icon: "error",
			});
			$(this).val("");
			return;
		}

		// File seems valid, proceed with further processing (e.g., compression)
		// console.log("Selected image:", file);
	});

	$("#maintenance-form").on("submit", async function (event) {
		event.preventDefault(); // Prevent default form submission
		const modifyAction = event.originalEvent.submitter.value;

		if (modifyAction === "delete") {
			const confirmationResult = await confirmFormDeletion();
			// Cancel form submission if user cancel delete process
			if (!confirmationResult) {
				return;
			}
		}
		$("#modify-action").val(modifyAction);

		const formData = new FormData(document.getElementById("maintenance-form"));

		if ($("#MaintenanceType").val() == null) {
			showNotification("請選擇維修類別", "maintenance-form-modal");
			$("#MaintenanceType").focus();
			return;
		}

		try {
			Swal.fire({
				title: "載入中...",
				html: '<div class="loading-spinner"></div>', // Custom HTML with loading spinner
				showConfirmButton: false,
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading(); // Show the loading spinner
				},
			});

			const response = await fetch(
				`./Records/modules/MaintenanceForm/controllers/MaintenanceUpdateController.php?machineType=${machineText}`,
				{
					method: "POST",
					body: formData,
				}
			);

			const data = await response.json();
			Swal.hideLoading();
			if (data === '"success"') {
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
					showConfirmButton: true,
				});
			} else {
				Swal.update({
					title: "未知錯誤",
					icon: "error",
					showConfirmButton: true,
				});
			}
			gridApi.showLoadingOverlay();
			fetchMaintenanceFormData(machineType);
		} catch (error) {
			Swal.update({
				title: "未知錯誤",
				icon: "error",
				showConfirmButton: true,
			});
			console.error("Error updating form:", error);
		}

		$("#maintenance-form-modal").modal("hide");
	});

	$("#maintenance-form-modal #image-delete-button, #maintenance-form-modal #Photo").on("click change", function () {
		$("#image-holder").attr("src", "");
		$("#photo-holder").val("");
		$("#image-holder-container").hide();
	});
});

function mapNumberToWord(number) {
	switch (number) {
		case 1:
			return "一";
		case 2:
			return "二";
		case 3:
			return "三";
		default:
			return "Number out of range";
	}
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

function imageCellRenderer(params) {
	if (params.value) {
		const imgElement = `
        <div class="restricted-image-container grid-image-container">
            <img src="data:image/png;base64,${params.value}" alt="image">
        </div>`;
		return imgElement;
	} else {
		return "";
	}
}

function fetchMaintenanceFormData() {
	const module = window.location.hash.substring(1); // Get hash value without '#'

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
				gridId: "maintenance-form-grid-container",
				gridOptions: gridOptions,
			};

			populateGrid(gridArg);
			gridApi.hideOverlay();
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

function callMaintenanceFormModal(event) {
	// Title text
	const isAddNewRow = event.data.insert;
	if (isAddNewRow) {
		$("#maintenance-modal-header").text("新增維修保養紀錄");
		$("#delete-maintenance-record").hide();
		$("#edit-submit-maintenance-modal").hide();
		$("#add-submit-maintenance-modal").show();
		updateForm();
	} else {
		$("#maintenance-modal-header").text("修改維修保養紀錄");
		$("#delete-maintenance-record").show();
		$("#edit-submit-maintenance-modal").show();
		$("#add-submit-maintenance-modal").hide();
		updateForm(event.data);
	}

	$("#maintenance-form-modal").modal("show");
}

function updateForm(data) {
	const $formInputs = $("#maintenance-form input");
	if (data) {
		const dateStartTime = new Date(data.StartTime.date);
		// Format the date according to the datetime-local specification
		const formattedStartTime = dateStartTime.toISOString().slice(0, 16);

		const dateEndTime = new Date(data.EndTime.date);
		// Format the date according to the datetime-local specification
		const formattedEndTime = dateEndTime.toISOString().slice(0, 16);

		let imageBase;
		if (data.Photo !== "") {
			imageBase = data.Photo;
			$("#image-holder-container").show();
		} else {
			imageBase = "";
			$("#image-holder-container").hide();
		}

		$("#Machine").val(data.Machine);
		$("#MaintenanceType").val(data.MaintenanceType);
		$("#Repair").val(data.Repair);
		$("#Reason").val(data.Reason);
		$("#Description").val(data.Description);
		$("#image-holder").attr("src", `data:image/png;base64,${imageBase}`);
		$("#photo-holder").val(data.Photo);
		$("#TechnicianName").val(data.TechnicianName);
		$("#StartTime").val(formattedStartTime);
		$("#EndTime").val(formattedEndTime);
		$("#data-id").val(data.ID);
	} else {
		// Reset form inputs
		$formInputs.each(function () {
			$(this).val("");
		});
		$("#MaintenanceType").prop("selectedIndex", 0);
		$("#image-holder").attr("src", ``);
		$("#photo-holder").val("");
		$("#image-holder-container").hide();
	}
}
