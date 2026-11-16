import { carouselIds, changeContent, themeInitializer } from "./contentUpdater.js";
import { querySetterAndGetter, removeQueryParam } from "./utilities.js";

let carouselIntervalId;

export function handleThemeToggler() {
	const currentTheme = querySetterAndGetter("get", "theme");

	if (currentTheme === "dark") {
		querySetterAndGetter("set", "theme", "light");
	} else {
		querySetterAndGetter("set", "theme", "dark");
	}
	themeInitializer();
}

export function moduleTogglerHandler(event) {
	let icon;
	const clickedElement = $(event.target);
	if (clickedElement.hasClass("module-toggler-icon")) {
		icon = clickedElement;
	} else {
		icon = clickedElement.find(".module-toggler-icon");
	}
	clickedElement.closest(".screen-settings-block").find(".hidden").toggle("fast");

	icon.toggleClass("icon-rotated");
}

export function handleCarouselToggler() {
    // Clear all intervals except the one with ID 4, static interval ID possibly from loading element
	clearAllIntervalsExcept([4]);
	const togglerState = $("#bs-carousel-toggler").prop("checked");
	console.log(togglerState);
	if (togglerState) {
		// Stop Carousel
		// console.log("Carousel Stopped");

		// clearInterval(carouselIntervalId);
	} else {
		// Start Carousel
		// console.log("Carousel Started");

		const timerValue = $("#carousel-timer-input").val() * 1000;
		const screen = querySetterAndGetter("get", "screen");
		const carouselSettingsId = carouselIds[screen - 1];
		const selectedModule = [];
		const checkedCheckbox = $(`#${carouselSettingsId}`)
			.closest(".screen-settings-block")
			.find('input[type="checkbox"]:checked');

		checkedCheckbox.each(function () {
			const moduleMethod = $(this).data("method");
			const moduleUrl = $(this).data("url");
			const moduleArr = {
				method: moduleMethod,
				url: moduleUrl,
			};
			selectedModule.push(moduleArr);
		});
		console.log($("#carousel-timer-input").length);
		// return;

		let moduleCounter = 0;
		carouselIntervalId = setInterval(function () {
			const currentModule = selectedModule[moduleCounter];
			const method = currentModule.method;
			const url = currentModule.url;
			changeContent(method, url);
			if (moduleCounter !== selectedModule.length - 1) {
				moduleCounter++;
			} else {
				moduleCounter = 0;
			}
		}, timerValue);
	}
	// console.log(window.activeIntervals); // [interval1, interval2]
	// console.log(carouselIntervalId);
}

export function clearScreenSettings() {
	$("#remove-screen-btn").click(() => {
		$('#carousel-settings-body input[type="radio"]').prop("checked", false);
		removeQueryParam("screen");
	});
}
