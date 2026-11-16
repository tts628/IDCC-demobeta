import {
	createSidebar,
	updateMainContent,
	carouselSettingsUpdater,
	carouselScreenUpdater,
	carouselIds,
	legacyContentHandler,
	languageLoader,
} from "./contentUpdater.js";
import { handleThemeToggler, handleCarouselToggler, moduleTogglerHandler } from "./togglers.js";
import { initializeLoadingElement } from "./loadingElements/loadingElement.js";

(function () {
	// Create an array to store active interval IDs
	window.activeIntervals = [];

	// Store references to the original functions
	const originalSetInterval = window.setInterval;
	const originalClearInterval = window.clearInterval;

	// Override the global setInterval function
	window.setInterval = function (callback, delay, ...args) {
		const intervalId = originalSetInterval(callback, delay, ...args);
		window.activeIntervals.push(intervalId);
		return intervalId;
	};

	// Override the global clearInterval function
	window.clearInterval = function (intervalId) {
		originalClearInterval(intervalId);
		const index = window.activeIntervals.indexOf(intervalId);
		if (index !== -1) {
			window.activeIntervals.splice(index, 1);
		}
	};

	// Function to clear all intervals except specified IDs
	window.clearAllIntervalsExcept = function (excludedIds) {
		window.activeIntervals.forEach((intervalId) => {
			if (!excludedIds.includes(intervalId)) {
				originalClearInterval(intervalId);
			}
		});
		// Update the activeIntervals array
		window.activeIntervals = window.activeIntervals.filter((id) => excludedIds.includes(id));
	};
})();

$(async () => {
	$("#spinner-loading-text").text("頁面載入中");
	initializeLoadingElement("loading-spinner", "full-overlay-container", 3);

	legacyContentHandler();
	await createSidebar();
	updateMainContent();

	$(window).on("popstate", function () {
		updateMainContent();
	});

	$("#sidebar").on("click", function (event) {
		if (event.target.closest(".web-theme-toggler")) {
			handleThemeToggler();
		}
		if (event.target.closest(".carousel-toggler-container")) {
			handleCarouselToggler();
		}
		if (event.target.closest(".ai-chart")) {
			if ($("#chat-frame").length === 1) {
				$("#chat-frame").toggleClass("visible");
			}
		}
	});

	$("#sidebarModalRoot").on("click", function (event) {
		if (event.target.closest("#save-carousel-settings")) {
			carouselSettingsUpdater();
		}
		if (event.target.closest(".carousel-screen-manipulator")) {
			carouselScreenUpdater(carouselIds, event);
		}
		if (event.target.closest(".carousel-module-toggler")) {
			moduleTogglerHandler(event);
		}
	});
});
