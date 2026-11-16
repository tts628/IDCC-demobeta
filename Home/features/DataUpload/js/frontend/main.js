import { getRouterPaths } from "../api/api.js";
import { loadCSS } from "../../../../../bootstrap/views/elementManipulator.js";
import { handleFileUpload } from "./dataHandler.js";

/**
 * Initializes the data upload process by loading necessary CSS and setting up event handlers.
 */
export async function initializeDataUpload() {
	const localRouterPath = "./features/DataUpload/config/routes.json";
	const queryString = window.location.search;

	try {
		const routerPaths = await getRouterPaths(localRouterPath);

		// Load required CSS for the page
		const localCSSPath = routerPaths.localCSS;
		loadCSS(localCSSPath);

		// Setup event handlers for file input and form submission
		const validationPath = routerPaths.validation;
		setupEventHandlers(validationPath + queryString);
	} catch (error) {
		console.error("Error initializing data upload:", error);
	}
}

/**
 * Sets up event handlers for the file input button, file input change, and form submission.
 *
 * @param {string} validationPath - The validation API endpoint to be used for file upload.
 */
function setupEventHandlers(validationPath) {
	$(".upload-db-btn")
		.off("click")
		.on("click", () => {
			$("#upload-db-input").trigger("click");
		});

	$("#upload-db-input")
		.off("change")
		.on("change", function () {
			$("#data-upload-form").trigger("submit");
		});

	$("#data-upload-form")
		.off("submit")
		.on("submit", function (event) {
			event.preventDefault();
			handleFileUpload(validationPath);
		});
}
