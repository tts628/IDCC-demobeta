import { fetchData, updateMainContent } from "../../../../../bootstrap/views/contentUpdater.js";

let debounceTimeout;
const $showToggler = $(".sidebar-show-toggler");

function changeTogglerVisibility(event) {
	const $menuNavigator = $(".menu-navigator");
	const thresholdX = window.innerWidth * 0.15;
	const thresholdY = window.innerHeight * 0.15;

	// Check if mouse coordinates are within specified ranges and change opacity of navigators accordingly
	if (
		event.clientX < thresholdX ||
		event.clientY < thresholdY ||
		event.clientX > window.innerWidth - thresholdX ||
		event.clientY > window.innerHeight - thresholdY
	) {
		// console.log("pointer in range")
		$menuNavigator.css("opacity", 1);
		$menuNavigator.css("pointer-events", "auto");

		// Clear the previous timeout if any movement is detected
		if (debounceTimeout) {
			clearTimeout(debounceTimeout);
		}

		// Set a timeout to revert opacity back to 0 after 1.5 seconds
		debounceTimeout = setTimeout(() => {
			$menuNavigator.css("opacity", 0);
			$showToggler.css("opacity", 0.1);
			$menuNavigator.css("pointer-events", "none");
		}, 1500);
	} else {
		$menuNavigator.css("opacity", 0);
		$showToggler.css("opacity", 0.1);
		$menuNavigator.css("pointer-events", "none");
	}
}

$(document).ready(() => {
	$showToggler.css("opacity", 0.1);
	$(document).on("mousemove", changeTogglerVisibility);
	$(document).on("click", function (event) {
		if ($(".menu-navigator").not(".sidebar-show-toggler").css("opacity") > 0) {
			changeTogglerVisibility(event);
		}
	});
	$(document).on("mouseleave", function () {
		$(".menu-navigator").css("opacity", 0);
	});

	const sidebarToggler = $(".sidebar-toggler");
	const sidebarContent = $(".sidebar-content");

	sidebarToggler.click(function (event) {
		event.stopPropagation();
		sidebarContent.toggle("fast");
	});

	$(document).on("click", function (event) {
		// event.stopPropagation();
		if (!$(event.target).closest("#sidebar").length) {
			if (sidebarContent.is(":visible")) {
				sidebarContent.toggle("fast");
			}
		}
	});

	$("#legacy-content").on("load", function () {
		const iframeDocument = this.contentDocument || this.contentWindow.document;

		// Ensure the iframe document is fully loaded before adding the event listener
		$(iframeDocument).ready(() => {
			$(iframeDocument).on("click", () => {
				if (sidebarContent.is(":visible")) {
					sidebarContent.toggle("fast");
				}
			});

			$(iframeDocument).on("mousemove", changeTogglerVisibility);

			$(iframeDocument).on("click", function (event) {
				if ($(".menu-navigator").css("opacity") > 0) {
					changeTogglerVisibility(event);
				}
			});

			$(iframeDocument).on("mouseleave", function () {
				$(".menu-navigator").css("opacity", 0);
			});
		});
	});

	$(".language-settings-toggler .btn").on("click", async (event) => {
		// Capture the `this` context of the parent scope
		const self = event.currentTarget;

		// console.log
		$(".language-settings-toggler .btn").removeClass("active");
		$(self).addClass("active");

		// Prevent default action (label would trigger radio and fire another trigger if not attended)
		event.preventDefault();

		// Find the input child and get its ID
		const inputId = $(self).find("input").attr("id");

		const setLanguageRes = await fetchData("./Menu/modules/sidebar/controllers/sessionSetController.php", {
			inputId: inputId,
		});

		const setLanguage = JSON.parse(setLanguageRes);

		if (setLanguage.status === "changed") {
			updateMainContent();
		} else if (setLanguage.status !== "unchanged") {
			console.error(setLanguage.error);
		}
	});

	languageHandler();
});

async function languageHandler() {
	const languageRes = await fetchData("./Menu/modules/sidebar/controllers/sessionCheckController.php");
	const language = JSON.parse(languageRes);
	switch (language) {
		case "zh-tw":
			$("#zh-language-btn").prop("selected", true);
			$("#zh-language-btn").closest("label").addClass("active");
			break;
		case "en-us":
			$("#en-language-btn").prop("selected", true);
			$("#en-language-btn").closest("label").addClass("active");
			break;
		default:
			console.error("Invalid language settings: " + language);
	}
}
