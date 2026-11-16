import { handleDataUpload } from "./dataHandler.js";
import { getRouterPaths } from "../api/api.js";

/**
 * Displays a loading message using SweetAlert2.
 *
 * @param {string} action - The current action being performed (e.g., "辨識" or "上傳").
 */
export function displayLoadingOverlay(action) {
	Swal.fire({
		title: `資料${action}中, 請稍侯...`,
		html: "<div id='swal-info-container'></div>",
		showConfirmButton: false,
		allowOutsideClick: false,
		didOpen: () => {
			Swal.showLoading();
		},
	});
}

/**
 * Adds a file progress indicator to the loading overlay.
 *
 * @param {number} fileIndex - The index of the file being processed.
 * @param {string} fileName - The name of the file being processed.
 * @param {string} action - The current action (e.g., "讀取" or "上傳").
 */
export function addFileProgressIndicator(fileIndex, fileName, action) {
	$("#swal-info-container").append(
		`<div id="file${fileIndex}-progress-info">
            <span id="action-text${fileIndex}">正在${action}: </span>${fileName} <span id="progress-dots${fileIndex}"></span>
        </div>`
	);
}

/**
 * Initiates a dot-based progress animation for a file.
 *
 * @param {number} fileIndex - The index of the file being processed.
 * @returns {number} - The interval ID for the progress indicator.
 */
export function startDotProgress(fileIndex) {
	let progressDots = "";
	return setInterval(() => {
		progressDots += ".";
		if (progressDots.length > 3) {
			progressDots = ""; // Reset after 3 dots
		}
		$(`#progress-dots${fileIndex}`).text(progressDots);
	}, 500); // Update every 500ms
}

/**
 * Marks a file's progress indicator as completed.
 *
 * @param {number} fileIndex - The index of the file being processed.
 */
export function markProgressComplete(fileIndex, action) {
	$(`#action-text${fileIndex}`).text("");
	$(`#progress-dots${fileIndex}`).html(`
        ${action}完畢 
        <div class="checkmark"></div>
    `);
}

// Main function to generate success HTML
function generateSuccessHtml(successData) {
	let successHtml = ``;

	// Check if any index has a non-null validation property
	const hasValidation = successData.some((successMsg) => successMsg.validation != null);

	const language = "zh-TW";

	// Only handle 'validation' related success data
	if (typeof successData === "object" && hasValidation) {
		successHtml = initializeSuccessHtml("將上傳檔案");

		// Add validation-related success data
		successHtml += generateValidationHtml(successData, language);
	} else {
		return successHtml;
	}

	// Finalize successHtml if any content was added
	if (successHtml !== "") {
		successHtml += `</ul></div>`;
	}

	return successHtml;
}

// Function to initialize the success HTML container
function initializeSuccessHtml(title) {
	return `
		<h3 class="text-center">
			<b>${title}</b>
		</h3>
		<div class="success-content container-fluid">
			<ul>`;
}

// Function to generate HTML for validation success messages
function generateValidationHtml(successData) {
	let validationHtml = "";

	successData.forEach((successMsg, index) => {
		if (successMsg.validation) {
			const identities = successMsg.validation.identity ?? []; // Default to empty array if undefined
			if (!identities.includes("unidentified") && identities.length !== 0) {
				validationHtml += `<li>
                                        <b class="success-text">${successMsg.file}</b>
                                        <br>
                                        辨識資料為：
                                        <br>`;
				identities.forEach((identity) => {
					validationHtml += `<div class="row">
                                            <div class="col-6">
                                                <div class="col-form-label">${identity}</div>
                                            </div>
                                            <div class="col">
                                                <select class="form-select" disabled>
                                                    <option value="insert">新增</option>
                                                    <option value="update">覆蓋</option>
                                                </select>
                                            </div>
                                        </div>`;
				});
				validationHtml += `</li>
                                   <br>`;
			}
		}
	});

	return validationHtml;
}

// Main function to generate error HTML
function generateErrorHtml(errorData) {
	let errorHtml = "";

	// Check if errorData is a non-empty array
	if (!Array.isArray(errorData) || errorData.length === 0) {
		console.error("errorData is not an array of objects with 'errors' key or is empty.");
		return errorHtml;
	}

	// Loop through errorData and build errorHtml
	errorData.forEach((obj) => {
		// Check if obj is a valid object
		if (typeof obj !== "object" || obj === null) {
			console.error("Object is not valid:", obj);
			return;
		}

		// Generate the error list HTML from array of error messages
		if (Array.isArray(obj.errors)) {
			errorHtml += generateErrorListFromArray(obj, errorHtml);
		} else {
			console.error("Object doesn't contain a valid 'errors' array:", obj);
		}
	});

	// Finalize errorHtml if any content was added
	if (errorHtml !== "") {
		errorHtml += `</ul></div></div>`;
	}

	return errorHtml;
}

// Function to initialize the HTML container for error messages
function initializeErrorHtml() {
	return `
		<div class="toggle-container">
			<h3 class="text-center pt-3"><b>錯誤資訊</b></h3>
			<div class="toggle-icon">
				<i class="fas fa-chevron-up"></i>
			</div>
			<div class="toggle-content">
				<ul>`;
}

// Function to generate HTML from an array of error strings
function generateErrorListFromArray(obj, errorHtml) {
	let errorListHtml = "";

	obj.errors
		.filter((error) => typeof error === "string" && error !== "")
		.forEach((error) => {
			// Initialize errorHtml structure if not already initialized
			if (errorHtml === "") {
				errorListHtml = initializeErrorHtml();
			}
			errorListHtml += `<li><b class="text-danger">${obj.file}</b><br>${error}</li><br>`;
		});

	return errorListHtml;
}

/**
 * Builds the SweetAlert2 content and configuration for displaying the processing summary.
 *
 * @param {string} successHtml - The HTML representing success messages.
 * @param {string} errorHtml - The HTML representing error messages, if any.
 * @param {string} action - "辨識" or "上傳", indicating "identify" or "upload"
 * @returns {object} - The SweetAlert2 configuration object.
 */
function buildSwalOptions(successHtml, errorHtml, action = "") {
	let swalIcon;
	let swalTitle;
	let swalHtml = "<div class='swal-content'>";

	// Handle cases with success and error messages
	if (errorHtml && successHtml) {
		swalIcon = "warning";
		swalTitle = `部分${action}異常`;
		swalHtml +=
			action === "辨識"
				? `
            ${errorHtml}
            <hr>
            ${successHtml}
        `
				: successHtml;
	} else if (errorHtml) {
		swalIcon = "error";
		swalTitle = `${action}錯誤`;
		swalHtml += `${errorHtml}`;
	} else if (successHtml) {
		swalIcon = action === "上傳" ? "success" : "question";
		swalTitle = `檔案${action}完成`;
		swalHtml += `${successHtml}`;
	} else {
		// Throwing an exception when there are no messages
		technicalErrorAlert();
		throw new Error("No success or error HTML provided");
	}

	swalHtml += "</div>";

	const swalOptions = {
		icon: swalIcon,
		title: swalTitle,
		html: swalHtml,
		allowOutsideClick: false,
		didOpen: () => {
			initializeToggleBehavior();
		},
	};

	// Configure buttons based on the action
	if (action === "上傳") {
		Object.assign(swalOptions, {
			showCancelButton: false,
			confirmButtonText: "確認",
			customClass: {
				confirmButton: "swal2-confirm-btn",
			},
		});
	} else if (action === "辨識" && successHtml) {
		Object.assign(swalOptions, {
			confirmButtonText: "確認上傳",
			showCancelButton: true,
			cancelButtonText: "取消",
			customClass: {
				confirmButton: "swal2-confirm-btn",
				cancelButton: "swal2-cancel-btn",
			},
		});
	}

	return swalOptions;
}

export function technicalErrorAlert() {
	Swal.fire({
		icon: "error",
		title: "Oops...",
		text: "Technical error, please contact the engineer!",
	});
}

export function displayProcessingSummary(successData, errorData, action, rawResults) {
	let swalOptions;

	if (action === "辨識") {
		const successHtml = generateSuccessHtml(successData);
		const errorHtml = generateErrorHtml(errorData);
		swalOptions = buildSwalOptions(successHtml, errorHtml, action);

		// Handle the "辨識" action with additional logic for upload confirmation
		if (successHtml) {
			Swal.fire(swalOptions).then(async (result) => {
				if (result.isConfirmed) {
					const localRouterPath = "./features/DataUpload/config/routes.json";
					const queryString = window.location.search;

					const routerPaths = await getRouterPaths(localRouterPath);
					const uploadPath = routerPaths.upload;

					// Handle data upload
					handleDataUpload(successData, uploadPath + queryString);
				}
			});
		} else {
			Swal.fire(swalOptions);
		}
	} else if (action === "上傳") {
		const { successHtml, errorHtml } = generateUploadHtml(rawResults);
		swalOptions = buildSwalOptions(successHtml, errorHtml, action);

		// No extra logic for "上傳", just show the modal
		Swal.fire(swalOptions);
	} else {
		console.error("Unknown action:", action);
	}
}

function generateUploadHtml(rawResults) {
	let successHtml = "";
	let errorHtml = "";

	rawResults.forEach((fileResult) => {
		if (!isValidFileResult(fileResult)) {
			console.error("raw result of upload process is not of expected format");
			return;
		}

		let fileFlag = false;

		if (hasMixedResults(fileResult)) {
			successHtml = handleSuccessHtml(fileResult, successHtml, fileFlag, "warning");
			fileFlag = true;
		}

		if (hasOnlySuccess(fileResult)) {
			successHtml = handleSuccessHtml(fileResult, successHtml, fileFlag, "success");
			fileFlag = true;
		}

		if (hasOnlyErrors(fileResult)) {
			errorHtml = handleErrorHtml(fileResult, errorHtml, fileFlag, "danger");
		}
	});

	return { successHtml: successHtml, errorHtml: errorHtml };
}

function isValidFileResult(fileResult) {
	return fileResult.file && fileResult.successMsg && fileResult.errorMsg;
}

function hasMixedResults(fileResult) {
	return fileResult.successMsg.length > 0 && fileResult.errorMsg.length > 0;
}

function hasOnlySuccess(fileResult) {
	return fileResult.successMsg.length > 0 && fileResult.errorMsg.length === 0;
}

function hasOnlyErrors(fileResult) {
	return fileResult.successMsg.length === 0 && fileResult.errorMsg.length > 0;
}

function addFileHeader(fileResult, fileFlag, indicator) {
	if (!fileFlag) {
		return `<li><b class="${indicator}-text">${fileResult.file}</b><br>`;
	}
	return "";
}

function wrapList(content) {
	return `<ul>${content}</ul>`;
}

function addSuccessMessages(successMsg) {
	return successMsg.map((successSheet) => `<li><div class="checkmark"></div> ${successSheet}</li>`).join("");
}

function addErrorMessages(errorMsg) {
	return errorMsg
		.map((errorInfo) => `<li><div class="cross"></div> ${errorInfo.sheet}: ${errorInfo.message}</li>`)
		.join("");
}

function handleSuccessHtml(fileResult, successHtml, fileFlag, indicator) {
	if (!successHtml) {
		successHtml = `<div class="upload-content"><ul class="file-list">`;
	}

	successHtml += addFileHeader(fileResult, fileFlag, indicator);
	successHtml += wrapList(addSuccessMessages(fileResult.successMsg) + addErrorMessages(fileResult.errorMsg));
	successHtml += `</li><br>`;
	return successHtml;
}

function handleErrorHtml(fileResult, errorHtml, fileFlag, indicator) {
	if (!errorHtml) {
		errorHtml = `<div class="upload-content"><ul class="file-list">`;
	}

	errorHtml += addFileHeader(fileResult, fileFlag, indicator);
	errorHtml += wrapList(addErrorMessages(fileResult.errorMsg));
	errorHtml += `</li><br>`;
	return errorHtml;
}

/**
 * Binds the toggle event to elements with the class `toggle-container`.
 * Allows the content to slide up/down on click and rotates the icon.
 */
export function initializeToggleBehavior() {
	$(".toggle-container")
		.off("click")
		.on("click", function () {
			const $content = $(this).find(".toggle-content");
			$content.slideToggle(300);

			// Rotate the icon
			$(this).find("i").toggleClass("fa-chevron-down fa-chevron-up");
		});
}
