import {
	displayLoadingOverlay,
	displayProcessingSummary,
	addFileProgressIndicator,
	startDotProgress,
	markProgressComplete,
	technicalErrorAlert,
} from "./uiHandler.js";
import { fetchData, fetchWithFormData } from "../../../../../bootstrap/views/utilities.js";

/**
 * Handles the file upload by showing a loading message and processing files in parallel.
 *
 * @param {string} validationPath - The API endpoint for validation.
 */
export function handleFileUpload(validationPath) {
	displayLoadingOverlay("辨識");
	const files = document.getElementById("upload-db-input").files;
	processInParallel(files, validationPath, "辨識");
}

/**
 * Processes the given data in parallel by uploading each item.
 *
 * @param {Array|FileList} dataItems - The array of extracted data or file list to be processed.
 * @param {string} apiEndpoint - The API endpoint for the process.
 * @param {string} action - The action being performed (e.g., "辨識" or "上傳").
 */
function processInParallel(dataItems, apiEndpoint, action) {
	const uploadPromises = [];

	for (let i = 0; i < dataItems.length; i++) {
		const item = dataItems[i];
		addFileProgressIndicator(i, item.name || item.file, action);
		const uploadPromise = uploadSingleItem(item, i, apiEndpoint, action);
		uploadPromises.push(uploadPromise);
	}

	Promise.all(uploadPromises)
		.then((results) => {
			let combinedSuccessData = [];
			let combinedErrorData = [];

			if (action === "辨識") {
				const { combinedSuccessData: successData, combinedErrorData: errorData } = combineResults(
					results,
					action
				);
				combinedSuccessData = successData;
				combinedErrorData = errorData;
			}
            
			displayProcessingSummary(combinedSuccessData, combinedErrorData, action, results);
		})
		.finally(() => {
			// Clear the value of the input to allow the same file to trigger again
			$("#upload-db-input").val(null); // Clear input properly without cloning
		})
		.catch((error) => {
			technicalErrorAlert();
			console.error("Error during uploads:", error);
		});
}

/**
 * Combines the success and error messages from all processed items.
 *
 * @param {Array} results - An array of results from the upload promises.
 * @param {string} action - "辨識" or "上傳", indicating "identify" or "upload".
 * @returns {Object} - An object containing combined success data and error messages.
 */
function combineResults(results) {
	let combinedSuccessData = [];
	let combinedErrorData = [];

	results.forEach((result, i) => {
		const currentFileName = result.file;

		// Ensure the index in combinedSuccessData is initialized
		if (!combinedSuccessData[i]) {
			combinedSuccessData[i] = {};
		}

		// Combine success messages from all items at index i
		if (result.successMsg) {
			Object.keys(result.successMsg).forEach((key) => {
				combinedSuccessData[i][key] = result.successMsg[key];
			});
			combinedSuccessData[i]["file"] = currentFileName;
		}

		// Combine error messages (assumed to be arrays) similarly to success data
		if (Array.isArray(result.errorMsg) && result.errorMsg.length > 0) {
			combinedErrorData[i] = {
				file: currentFileName,
				errors: result.errorMsg, // Storing all error messages for the file
			};
		} else if (typeof result.errorMsg === "object" && result.errorMsg !== null && "upload" in result.errorMsg) {
			// Check if errorMsg is an object and has the key "upload"
			combinedErrorData[i] = {
				file: currentFileName,
				errors: result.errorMsg, // Storing all error messages for the file
			};
		} else {
			console.error("Unexpected error message format:", result.errorMsg);
		}
	});

	return { combinedSuccessData, combinedErrorData };
}

/**
 * Uploads a single file or data item.
 *
 * @param {File|Object} item - The file or data item to be uploaded.
 * @param {number} index - The index of the file being processed.
 * @param {string} apiEndpoint - The API endpoint for uploading the file.
 * @returns {Promise<Object>} - A promise that resolves with the success and error messages.
 */
function uploadSingleItem(item, index, apiEndpoint, action) {
	return new Promise((resolve) => {
		let fetchPromise;
		let progressInterval = startDotProgress(index);

		// Check if item is a File or an Object
		if (item instanceof File) {
			const formData = createFormData(item);
			// Use fetchWithFormData when item is a file
			fetchPromise = fetchWithFormData(apiEndpoint, formData);
		} else if (typeof item === "object") {
			// Use fetchData for JSON objects
			fetchPromise = fetchData(apiEndpoint, item);
		}

		fetchPromise
			.then((data) => {
				let successMsg = data.success ? data.success : {};
				let errorMsg = data.error ? data.error : {};
				let file = data.file ? data.file : "";
				resolve({ successMsg, errorMsg, file });
			})
			.catch((error) => {
				let errorMsg = `Error: ${error.message}<br>`;
				resolve({ successMsg: {}, errorMsg });
			})
			.finally(() => {
				markProgressComplete(index, action);
				clearInterval(progressInterval); // Stop progress indicator
			});
	});
}

/**
 * Creates FormData from a file or data item.
 *
 * @param {File|Object} item - The file or data item to be uploaded.
 * @returns {FormData} - The FormData object containing the file or data.
 */
export function createFormData(item) {
	const formData = new FormData();
	formData.append("uploaded-data", item);
	return formData;
}

/**
 * Handles the data upload by showing a loading message and processing the data in parallel.
 *
 * @param {Array} extractedData - An array of objects of extracted data to be uploaded.
 * @param {string} uploadPath - The API endpoint for uploading the data.
 */
export function handleDataUpload(extractedData, uploadPath) {
	// Filter out objects that do not have the `validation` key
	const filteredData = extractedData.filter((item) => item.hasOwnProperty("validation"));

	// Display the loading overlay
	displayLoadingOverlay("上傳");

	// Process the filtered data in parallel
	processInParallel(filteredData, uploadPath, "上傳");
}
