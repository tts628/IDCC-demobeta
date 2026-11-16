import { selectInputValidate, querySetterAndGetter } from "../../../../../bootstrap/views/utilities.js";

let cachedMaxId = 0;
let cachedMachineListData;

const module = window.location.hash.substring(1); // Get hash value without '#'

const $machineType = $("#machine-type");
const $machineName = $("#machine-name");
const $imageContainer = $("#image-thumbnails-container");
const $uploadedPreview = $("#uploaded-preview");
const $existingPreview = $("#existing-preview");
const $partsDescriptContainer = $("#parts-description-container");

const controllerDir = "./Records/modules/MaintenanceForm/controllers";
const picturesDir = "./Records/modules/MaintenanceForm/uploads/images";

let gridApi = "";
const customName = [
	"保養序號",
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
	{
		target: "MaintenanceNo",
		options: { suppressMovable: true, pinned: "left" },
	},
];

const pinnedBottomRowData = [
	{
		MaintenanceNo: "新增",
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

function fetchMaintenanceFormData(module) {
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

// Function to generate Swal HTML content
function getSwalHtml(data) {
	return `
        <span class="color-red fw-bold">註: 此覆蓋操作將無法還原歷史紀錄, 請確認是否覆蓋以下資料</span><br>
        <table class="table text-left table-${querySetterAndGetter("get", "theme")}">
            <tr>
                <td>設備類別:</td>
                <td>${machineTypeMapper(data.MachineType)}</td>
            </tr>
            <tr>
                <td>設備名稱:</td>
                <td>${data.MachineActualName}</td>
            </tr>
            <tr>
                <td>維修類別:</td>
                <td>${mapNumberToWord(parseInt(data.MaintenanceType))}級</td>
            </tr>
            <tr>
                <td>維修項目:</td>
                <td>${data.Repair}</td>
            </tr>
            <tr>
                <td>原因:</td>
                <td>${data.Reason}</td>
            </tr>
            <tr>
                <td>維修詳細說明:</td>
                <td>${data.Description}</td>
            </tr>
            <tr>
                <td>維修人員:</td>
                <td>${data.TechnicianName}</td>
            </tr>
            <tr>
                <td>開始時間:</td>
                <td>${data.StartTime}</td>
            </tr>
            <tr>
                <td>結束時間:</td>
                <td>${data.EndTime}</td>
            </tr>
        </table>
    `;
}

// Function to show confirmation Swal with detailed information
async function showConfirmationSwal(integrityData) {
	const swalHtml = getSwalHtml(integrityData); // Function to generate HTML
	Swal.hideLoading();
	return Swal.fire({
		title: "確認資料覆蓋",
		text: "已有相關且不同設備類別/名稱的歷史紀錄, 是否覆蓋",
		html: swalHtml,
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#ff0000",
		confirmButtonText: "確認覆蓋",
		cancelButtonText: "取消",
	});
}

// Function to fetch maintenance update and return data
async function fetchMaintenanceUpdate(formData) {
	const response = await fetch(`${controllerDir}/MaintenanceUpdateController.php`, {
		method: "POST",
		body: formData,
	});
	return response.json();
}

// Function to fetch integrity check and return data
async function fetchIntegrityCheck(formData) {
	const response = await fetch(`${controllerDir}/IntegrityCheckController.php`, {
		method: "POST",
		body: formData,
	});
	return response.json();
}

function formSubmitHandler() {
	$("#maintenance-form").on("submit", async function (event) {
		event.preventDefault(); // Prevent default form submission
		const modifyAction = event.originalEvent.submitter.value;

		if (modifyAction === "delete") {
			const confirmationResult = await confirmFormDeletion();
			// Cancel form submission if user cancel delete process
			if (!confirmationResult) {
				Swal.fire("Deletion Cancelled", "No changes were made.", "info");
				return;
			}
		}
		$("#modify-action").val(modifyAction);

		const formData = new FormData(document.getElementById("maintenance-form"));
		if (!selectInputValidate("machine-type", "設備類別")) return;
		if (!selectInputValidate("machine-name", "設備名稱")) return;
		if (!selectInputValidate("maintenance-type", "維修類別")) return;

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

			if (modifyAction === "edit") {
				const integrityData = await fetchIntegrityCheck(formData);
				const isHistoryDataExist =
					$machineType.val() != integrityData[0].MachineType ||
					$machineName.val() != integrityData[0].ReferenceId;

				if (isHistoryDataExist) {
					const result = await showConfirmationSwal(integrityData[0]);
					if (result.isConfirmed) {
						// Call the function to send form data
						updateData(formData, modifyAction, resultsHtml);
					}
				} else {
					// If condition is false, directly proceed with further process
						updateData(formData, modifyAction, resultsHtml);
				}
			} else {
                updateData(formData, modifyAction, resultsHtml);
			}
		} catch (error) {
			Swal.hideLoading();
			Swal.update({
				title: "未知錯誤",
				icon: "error",
				showConfirmButton: true,
			});
			console.error("Error updating form:", error);
		}
	});
}

async function updateData(formData, modifyAction, resultsHtml) {
	try {
		Swal.showLoading(); // Show the loading spinner
		formData.set("formMethod", "update");

		const response = await fetchMaintenanceUpdate(formData);

		if (response === "success") {
            resultsHtml += `<div>資料庫上傳成功</div>`;
			let feedbackText = getFeedbackText(modifyAction);
			Swal.fire({
				title: `紀錄${feedbackText}成功`,
				icon: "success",
				html: resultsHtml,
				showConfirmButton: true,
			});
			$("#maintenance-form-modal").modal("hide");
		} else {
			throw new Error(data.message || "Error processing your request");
		}
	} catch (error) {
		console.error("Error updating form:", error);
		Swal.fire({
			title: "操作失敗",
			text: error.message,
			icon: "error",
			showConfirmButton: true,
		});
	} finally {
		fetchMaintenanceFormData(module);
	}
}

function getFeedbackText(action) {
    console.log(action)
	switch (action) {
		case "add":
			return "新增";
		case "edit":
			return "更新";
		case "delete":
			return "刪除";
		default:
			return "未知更新方式";
	}
}

function fetchMachineListData(module) {
	fetch(`./shared/controllers/baseController.php`, {
		method: "POST",
		body: JSON.stringify({ module: module, model: "MachineListData" }),
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
			cachedMachineListData = data;

			$machineType.empty();

			$machineType.append(`<option value="" selected disabled hidden>Nothing Selected</option>`);
			$.each(cachedMachineListData, function (index, _value) {
				$machineType.append(`<option value="${index}">${machineTypeMapper(index)}</option>`);
			});
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

function fetchMachineListMaxId(module) {
	fetch(`./shared/controllers/baseController.php`, {
		method: "POST",
		body: JSON.stringify({ module: module, model: "MachineListMaxId" }),
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
			// Update list options if cached data is outdated by comparing max ids
			if (cachedMaxId < data.MaxID) {
				fetchMachineListData(module);
			}
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
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

		$machineType.val(data.MachineType);
		$machineName.empty();
		$.each(cachedMachineListData, function (index, value) {
			if ($("#machine-type").val() === index) {
				$machineName.prop("disabled", false);
				$.each(value, function (_nameIndex, nameValue) {
					const machineActualName = nameValue.MachineActualName;
					const ID = nameValue.ID;
					// Using ReferenceId instead of actual name for better form submission flow
					$machineName.append(`<option value="${ID}">${machineActualName}</option>`);
				});
			}
		});
		const dateStartTime = new Date(data.StartTime.date);
		// Format the date according to the datetime-local specification
		const formattedStartTime = dateStartTime.toISOString().slice(0, 16);

		const dateEndTime = new Date(data.EndTime.date);
		// Format the date according to the datetime-local specification
		const formattedEndTime = dateEndTime.toISOString().slice(0, 16);

		// Using ReferenceId instead of actual name for better form submission flow
		$("#machine-name").val(data.ReferenceId);

		$("#maintenance-type").val(data.MaintenanceType);
		$("#repair").val(data.Repair);
		$("#reason").val(data.Reason);
		$("#description").val(data.Description);
		$("#photo-holder").val(data.Photo);
		$("#technician-name").val(data.TechnicianName);
		$("#start-time").val(formattedStartTime);
		$("#end-time").val(formattedEndTime);
		$("#data-id").val(data.ID);
		$("#maintenance-number").val(data.MaintenanceNo);
	} else {
		// Reset form inputs
		document.getElementById("maintenance-form").reset();
        $imageContainer.hide();
		$machineType.prop("selectedIndex", 0);
		$machineName.empty();
		$machineName.append(`<option value="" selected disabled hidden>請選擇設備類別</option>`);
		$("#maintenance-type").prop("selectedIndex", 0);
		$machineName.prop("disabled", true);
        $partsDescriptContainer.hide();
	}
}

function partsEventHandler() {
    // $("#")
}

function machineTypeChangeEvent() {
	$machineType.on("change", () => {
		$machineName.empty();
		$machineName.append(`<option value="" selected disabled hidden>Nothing Selected</option>`);
		$.each(cachedMachineListData, function (index, value) {
			if ($("#machine-type").val() === index) {
				$machineName.prop("disabled", false);
				$.each(value, function (_nameIndex, nameValue) {
					const machineActualName = nameValue.MachineActualName;
					const ID = nameValue.ID;
					// Using ReferenceId instead of actual name for better form submission flow
					$machineName.append(`<option value="${ID}">${machineActualName}</option>`);
				});
			}
		});
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
	gridApi = populateGrid(gridArg);
	fetchMaintenanceFormData(module);
	fetchMachineListMaxId(module);
	machineTypeChangeEvent();
	formSubmitHandler();
	showImagePreview();
});
