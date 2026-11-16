import {
	createHTMLElement,
	loadScript,
	loadModuleCSS,
	moduleTitles,
	ajaxHashes,
	ajaxUrls,
} from "./elementManipulator.js";
import { intervalGarbageCollector } from "./compensations.js";
import { handleCarouselToggler, clearScreenSettings } from "./togglers.js";
import {
	querySetterAndGetter,
	generateUniqueId,
	ajaxMethod,
	removeQueryParam,
	removeQueryParamsExcept,
	queryBullkSetter,
	switchEnvCheck,
} from "./utilities.js";
import {
	saveClientSettings,
	clearClientSettings,
	clearClientInputField,
} from "../../Home/Menu/modules/ClientSettings/views/clientSettingHandler.js";

const carouselIds = []; // Set by carousel settings.json / modified by settings modal

const production = true; // Determine whether to preserve logs or not by default

const clientRoot = window.location.pathname.split("/")[1];

export { carouselIds };

export async function themeInitializer() {
	let currentTheme = querySetterAndGetter("get", "theme");
	const $toggler = $("#theme-toggler-icon");
	const $togglerText = $("#theme-toggler-text");
	if (currentTheme === null) {
		currentTheme = "dark";
		querySetterAndGetter("set", "theme", "dark");
	}

	if (currentTheme === "light") {
		$toggler.removeClass("fa-regular").addClass("fa-solid");
		$togglerText.attr("data-theme", "dark");
		// $togglerText.html("&nbsp; 深色模式");
		$("#light-theme-css").prop("disabled", false);
		// Not working, implementing them changer on legacy content later
		// injectIframeCSS("legacy-content", "clientRoot/bootstrap/views/lightTheme.css");
	} else {
		$toggler.removeClass("fa-solid").addClass("fa-regular");
		$togglerText.attr("data-theme", "light");
		// $togglerText.html("&nbsp; 淺色模式");
		$("#light-theme-css").prop("disabled", true);
		// removeIframeCSS("legacy-content", "clientRoot/bootstrap/views/lightTheme.css");
	}

	await new Promise((resolve) => setTimeout(resolve, 100));

	const easingElements = $(".class1, .class2, html, body, .menu-navigator");
	easingElements.css("transition", "all ease-in-out 0.3s");
}

export function changeContent(navMethod, url, ajaxHash, query = {}) {
	residueCleaner();
	let exceptionList = ["screen", "theme", "client", "autoplay"];
	if (navMethod === "ajax") {
		exceptionList.push("ajax");
		query = { ajax: "true", ...query };
	} else if (navMethod !== "hash") {
		throw new Error(
			`Bad navigation method, only 'ajax' and 'hash' available, ${navMethod} given`
		);
	}
	queryBullkSetter(query, exceptionList);
	window.location.hash = ajaxHash ? ajaxHash : url;
}

export async function createSidebar() {
	return fetch("../bootstrap/controllers/getModuleSettings.php", {
		method: "POST",
		body: JSON.stringify({ module: "sidebar" }),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => {
			// Check if the request was successful
			if (!response.ok) {
				console.log(response.json());
				throw new Error("Network response was not ok");
			}
			// Parse the JSON response
			return response.json();
		})
		.then((data) => {
			// Expects customized settings structure, refer to www/docs.txt
			const settingsData = JSON.parse(data);
			const { html, category, script, cssLink } = settingsData;
			createHTMLElement(html, "sidebar");

			if (script) {
				script.forEach(function (src) {
					loadScript(
						src.moduleDir,
						src.scriptPath,
						"sidebar",
						category
					);
				});
			}
			if (cssLink) {
				cssLink.forEach(function (link) {
					loadModuleCSS(
						link.moduleDir,
						link.cssPath,
						"sidebar",
						category
					);
				});
			}
		})
		.then(async (_response) => {
			$("#bs-carousel-toggler").bootstrapToggle({
				on: "&nbsp;<i class='fa-solid fa-stop'></i> 停止輪播",
				off: "<i class='fa-solid fa-play'></i> 開始輪播&nbsp;",
			});

			if (!querySetterAndGetter("get", "screen")) {
				$(".carousel-toggler-container").hide();
			}
			await fetchCarouselSettingsContent("create");
			fetchClientSettingsContent();
			themeInitializer();
		})
		.then(() => {
			import("../../Home/features/DataUpload/js/frontend/main.js").then(
				(module) => {
					module.initializeDataUpload();
				}
			);

			const autoPlay = querySetterAndGetter("get", "autoplay");
			if (autoPlay) {
				$("#bs-carousel-toggler").bootstrapToggle("on");
				$("#bs-carousel-toggler").prop("checked", false);
				handleCarouselToggler();
			}
			clearScreenSettings();
			switchEnvCheck();
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

function updateAjaxContent(module) {
	const moduleIndex = ajaxHashes.indexOf(module);

	let queryString = window.location.search;

	if (moduleIndex !== -1) {
		$("#legacy-content").show();

		if (ajaxUrls[moduleIndex].includes("?")) {
			// Replace the first '?' with '&'
			queryString = "&" + queryString.slice(1);
		}

		$("#legacy-content").prop("src", ajaxUrls[moduleIndex] + queryString);
		// console.log(queryString);

		// ajaxMethod(ajaxUrls[moduleIndex] + queryString);
		// $("#full-page-content").css("height", "100%");
	} else if (module === "") {
		$("#legacy-content").show();
		$("#legacy-content").prop("src", "./Legacy/Homepage" + queryString);
	} else {
		$("#full-overlay-container").fadeOut(400);
		// if (production) console.clear();
		Swal.fire(
			`Module <span style="color:#bfdeff;">"${module}"</span> is not available`,
			"",
			"warning"
		);
		console.warn(
			`module ${module} is not available for ajax method, consider an upgrade!`
		);
	}

	// update sidebar language
	languageLoader("sidebar");
}

export async function updateMainContent() {
	const isCarouselScreen = querySetterAndGetter("get", "screen");
	if (!isCarouselScreen) {
		$("#full-overlay-container").fadeIn(400);
	}
	// Reset content and hide elements
	$("#full-page-content").empty();
	$("#content, #full-page-content").css("height", 0);

	intervalGarbageCollector();

	const module = window.location.hash.substring(1); // Get hash value without '#'
	if (module != "") {
		for (const moduleTitle of moduleTitles) {
			for (const key in moduleTitle) {
				if (key === "url" && moduleTitle[key] === module) {
					document.title = moduleTitle.text;
					break;
				}
			}
		}
		if (querySetterAndGetter("get", "ajax") === "true") {
			updateAjaxContent(module);
		} else {
			$("#legacy-content").hide();
			fetch("../bootstrap/controllers/getModuleSettings.php", {
				method: "POST",
				body: JSON.stringify({ module: module }),
				headers: {
					"Content-Type": "application/json", // Add content type header
				},
			})
				.then((response) => {
					// Check if the request was successful
					if (!response.ok) {
						console.log(response.json());
						throw new Error("Network response was not ok");
					}
					$("#content").css("height", "100%");
					// Parse the JSON response
					return response.json();
				})
				.then((data) => {
					// Expects customized settings structure, refer to www/docs.txt
					$("#modalRoot, #content").empty();

					const settingsData = JSON.parse(data);
					if (settingsData === "module not available") {
						if (!querySetterAndGetter("get", "ajax")) {
							querySetterAndGetter("set", "ajax", "true");
							updateAjaxContent(module);
						} else {
							console.warn(
								`module ${module} is not available, consider an upgrade!`
							);
						}
						return;
					}
					const { html, category, script, cssLink, fwversion } =
						settingsData;
					createHTMLElement(html, "content");

					if (script) {
						script.forEach(function (src) {
							loadScript(
								src.moduleDir,
								src.scriptPath,
								module,
								category,
								fwversion
							);
						});
					}

					if (cssLink) {
						cssLink.forEach(function (link) {
							loadModuleCSS(
								link.moduleDir,
								link.cssPath,
								module,
								category
							);
						});
					}
				})
				.then(() => {
					$("#full-overlay-container").fadeOut(400);

					// update sidebar language
					languageLoader("sidebar");
					// if (production) console.clear();
				})
				.catch((error) => {
					// Handle any errors that occurred during the fetch
					console.error("Fetch error:", error);
				});
		}
	} else {
		updateAjaxContent(module);
	}
}

export async function fetchClientSettingsContent() {
	return fetch("../bootstrap/controllers/getModuleSettings.php", {
		method: "POST",
		body: JSON.stringify({ module: "ClientSettings" }),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => {
			// Check if the request was successful
			if (!response.ok) {
				console.log(response.json());
				throw new Error("Network response was not ok");
			}
			// Parse the JSON response
			return response.json();
		})
		.then((data) => {
			const settingsData = JSON.parse(data);
			if (settingsData === "module not available") {
				return;
			}
			const { html, category, cssLink } = settingsData;
			if (cssLink) {
				cssLink.forEach(function (link) {
					loadModuleCSS(
						link.moduleDir,
						link.cssPath,
						"ClientSettings",
						category
					);
				});
			}
			if (!document.getElementById("client-settings-body")) {
				throw new Error("Root element not found");
			}
			createHTMLElement(html, "client-settings-body");
		})
		.then(() => {
			saveClientSettings();
			clearClientSettings();
			clearClientInputField();
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

export async function fetchCarouselSettingsContent(method, screenSettings) {
	return fetch("../bootstrap/controllers/getModuleSettings.php", {
		method: "POST",
		body: JSON.stringify({ module: "CarouselSettings" }),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => {
			// Check if the request was successful
			if (!response.ok) {
				console.log(response.json());
				throw new Error("Network response was not ok");
			}
			// Parse the JSON response
			return response.json();
		})
		.then((data) => {
			const settingsData = JSON.parse(data);
			const { html, category, cssLink, carouselTimer, carouselScreen } =
				settingsData;
			if (method === "create") {
				$("#carousel-settings-body").empty();

				if (cssLink) {
					cssLink.forEach(function (link) {
						loadModuleCSS(
							link.moduleDir,
							link.cssPath,
							"CarouselSettings",
							category
						);
					});
				}
				if (!document.getElementById("carousel-settings-body")) {
					throw new Error("Root element not found");
				}
				createHTMLElement(html, "carousel-settings-body");
				$("#carousel-timer-input").val(carouselTimer);
				$.each(carouselScreen, function (screenIndex, value) {
					const uniqueId = generateUniqueId();
					carouselIds.push(uniqueId);
					carouselScreenUpdater(carouselIds);

					const currentScreen = querySetterAndGetter("get", "screen");
					const screenNumber =
						parseInt(screenIndex.charAt(screenIndex.length - 1)) +
						1;

					if (currentScreen) {
						if (screenNumber == currentScreen) {
							$(`#${uniqueId}`).prop("checked", true);
						}
					}

					$.each(value, function (_key2, moduleText) {
						// Escape the moduleText to safely use in selector
						const escapedModuleText = $.escapeSelector(moduleText);
						$(`#${escapedModuleText}-${uniqueId}`).prop(
							"checked",
							true
						);
					});
				});
			} else if (method === "update") {
				const timerSettings = $("#carousel-timer-input").val();
				const newSettingsData = {
					html,
					cssLink,
					carouselTimer: timerSettings,
					carouselScreen: screenSettings,
				};
				return newSettingsData;
			}
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

export function carouselSettingsUpdater() {
	const checkedRadio = $('input[type="radio"].screen-radio-option:checked');
	const checkedRadioIndex = carouselIds.indexOf(checkedRadio.val());
	if (checkedRadioIndex !== -1) {
		querySetterAndGetter("set", "screen", checkedRadioIndex + 1);
		$(".carousel-toggler-container").show();
	} else {
		removeQueryParam("screen");
	}

	const screenSettings = {};
	$.each(carouselIds, function (index, Id) {
		const selectedModule = [];
		const checkedCheckbox = $(`#${Id}`)
			.closest(".screen-settings-block")
			.find('input[type="checkbox"]:checked');
		checkedCheckbox.each(function () {
			const checkboxVal = $(this).val();
			selectedModule.push(checkboxVal);
		});
		screenSettings[`screen_${index}`] = selectedModule;
	});

	fetchCarouselSettingsContent("update", screenSettings).then(
		(updatedSettings) => {
			fetch(
				"./Menu/modules/CarouselSettings/controllers/carouselSettingsController.php",
				{
					method: "POST",
					body: JSON.stringify(updatedSettings),
					headers: {
						"Content-Type": "application/json", // Add content type header
					},
				}
			)
				.then((response) => {
					// Check if the request was successful
					if (!response.ok) {
						console.log(response.json());
						throw new Error("Network response was not ok");
					}
					// Parse the JSON response
					return response.json();
				})
				.then((data) => {
					if (data === "Success") {
						Swal.fire({
							text: "輪播設定已儲存",
							icon: "success",
						}).then(() => {
							// Dismiss the Bootstrap modal after alert is confirmed
							const modal = document.getElementById(
								"carousel-settings-modal"
							);
							const bootstrapModal =
								bootstrap.Modal.getInstance(modal);
							bootstrapModal.hide();
						});
					} else {
						Swal.fire({
							text: "Something went wrong, check log",
							icon: "error",
						});
					}
				})
				.catch((error) => {
					// Handle any errors that occurred during the fetch
					console.error("Fetch error:", error);
				});
		}
	);
}

export function carouselScreenUpdater(carouselIds, event) {
	let addScreen;
	let deleteScreen;
	const settingsContainer = $("#carousel-detailed-settings-container");
	if (event) {
		addScreen = event.target.closest("#add-carousel-screen");
		deleteScreen = event.target.closest(".screen-label-delete");
		if (addScreen) {
			const uniqueId = generateUniqueId();
			carouselIds.push(uniqueId);
		} else if (deleteScreen) {
			const targetId = $(event.target).data("id");
			const targetIdIndex = carouselIds.indexOf(targetId);
			if (targetIdIndex !== -1) {
				carouselIds.splice(targetIdIndex, 1);
			}
		}
	}
	// settingsContainer.empty();
	$.each(carouselIds, function (index, Id) {
		const isScreenOptionExist = $("#carousel-settings-modal").find(
			`#${Id}`
		).length;
		const indicator = index + 1;
		let deleteFlag = true;
		if (!isScreenOptionExist) {
			const newElement = $(
				`<div class="row screen-settings-block">
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 col-6">
                        <div class="position-relative">
                            <input type="radio" id="${Id}" class="screen-radio-option" name="screen-radio-option" value="${Id}">
                            <div class="position-absolute screen-label-delete carousel-screen-manipulator" data-id="${Id}">×</div>
                            <label for="${Id}" class="carousel-screen-label ps-2 py-2">
                                螢幕 ${indicator}
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-8 col-md-6 col-sm-6 col-6">
                        <div class="text-center">
                            <div class="clickable carousel-module-toggler p-2">
                                <i class="fa-solid fa-caret-down module-toggler-icon"></i>
                            </div>
                        </div>
                        <div class="row my-2" id="carousel-modules-${Id}"></div>
                    </div>
                </div>`
			);
			settingsContainer.append(newElement);
			$.each(moduleTitles, function (_moduleIndex, title) {
				const moduleSelector = `
                <div class="hidden col-3">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="${title.text}-${Id}" 
                        data-url="${title.url}" 
                        data-method="${title.navMethod}" 
                        value="${title.text}"
                    >
                    <label for="${title.text}-${Id}">
                        ${title.text}
                    </label>
                </div>`;
				$(`#carousel-modules-${Id}`).append(moduleSelector);
			});
		} else if (deleteScreen) {
			if (deleteFlag) {
				$("[name='screen-radio-option']").each(function () {
					const id = $(this).attr("id");
					if (carouselIds.indexOf(id) === -1) {
						$(`#${id}`).closest(".screen-settings-block").remove();
					}
				});
				deleteFlag = false;
			}
			$(`#${Id}`).parent().children("label").text(`螢幕 ${indicator}`);
		}
	});
}

export async function fetchData(apiUrl, jsonValue = null, formData = null) {
	let requestData;
	if (jsonValue) {
		requestData = JSON.stringify(jsonValue);
	} else {
		requestData = formData;
	}

	const response = await fetch(apiUrl, {
		method: "POST",
		body: requestData,
	});

	if (!response.ok) {
		console.error("fetch error on endpoint:" + apiUrl);
		throw new Error(`HTTP error! status: ${response.status}`);
	}

	const contentType = response.headers.get("Content-Type");
	let data;
	if (contentType.includes("application/json")) {
		data = await response.json();
	} else {
		// Handle non-JSON response
		data = await response.text(); // Example: assuming plain text response
	}
	return data;
}

export function legacyContentHandler() {
	const pathArray = window.location.pathname
		.split("/")
		.filter((part) => part !== "");
	const clientRoot = pathArray[0];

	const $legacyContent = $("#legacy-content");
	$legacyContent.on("load", function () {
		$("#full-overlay-container").fadeOut(400);
		// if (production) console.clear();
		const iframeContent = $legacyContent.contents();

		if (iframeContent.find(".new-legacy-content").length > 0) {
			// Create a link element for the CSS file
			const link = iframeContent[0].createElement("link");
			link.rel = "stylesheet";
			link.type = "text/css";
			link.href = "/" + clientRoot + "/bootstrap/views/main.css"; // Change this to the path of your CSS file

			// Append the link element to the iframe's head
			iframeContent.find("head").append(link);
		}
	});
}

function getIframeDocument(iframeId) {
	const iframe = document.getElementById(iframeId);
	if (!iframe) {
		console.error("Iframe not found");
		return { iframe: null, iframeDocument: null };
	}
	const iframeDocument =
		iframe.contentDocument || iframe.contentWindow.document;
	return { iframe, iframeDocument };
}

export function injectIframeCSS(iframeId, cssPath) {
	const { iframe, iframeDocument } = getIframeDocument(iframeId);
	if (!iframe || !iframeDocument) return;

	iframe.onload = function () {
		// Create a new <link> element
		const linkElement = iframeDocument.createElement("link");
		linkElement.rel = "stylesheet";
		linkElement.href = cssPath;

		// Append the <link> element to the <head> of the iframe
		iframeDocument.head.appendChild(linkElement);
	};
}

export function removeIframeCSS(iframeId, cssPath) {
	const { iframeDocument } = getIframeDocument(iframeId);
	if (!iframeDocument) return;

	// Find the injected <link> element by its href
	const linkElement = iframeDocument.querySelector(`link[href="${cssPath}"]`);

	// If the link element exists, remove it
	if (linkElement) {
		linkElement.parentNode.removeChild(linkElement);
	}
}

export async function languageLoader(pageName) {
	return fetch("../bootstrap/controllers/LanguagePackController.php", {
		method: "POST",
		body: JSON.stringify({ pageName: pageName }),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => {
			// Check if the request was successful
			if (!response.ok) {
				console.log(response.json());
				throw new Error("Network response was not ok");
			}
			// Parse the JSON response
			return response.json();
		})
		.then((languagePack) => {
			contentLanguageUpdate(languagePack.languagePhrases);
			sidebarEdgeLanguage(languagePack);
		})
		.catch((error) => {
			// Handle any errors that occurred during the fetch
			console.error("Fetch error:", error);
		});
}

function contentLanguageUpdate(languagePhrases) {
	console.log(languagePhrases);
	Object.entries(languagePhrases).forEach(([key, value]) => {
		const $el = $(`#${key}`);
		if ($el.length) {
			// only proceed if an element with this ID exists
			$el.html(value);
		}
	});
}

function sidebarEdgeLanguage(languagePack) {
	// handling edge cases for sidebar
	const currentTheme = $("#theme-toggler-text").data("theme");
	$("#theme-toggler-text").html(
		languagePack.languagePhrases[`theme-${currentTheme}`]
	);

	//handling styling position
	const currentLanguage = languagePack.language;
	if (currentLanguage === "en-us") {
		$(".upload-db-btn").css("transform", "translateX(-20px)");
		$(".upload-db-btn").data("translated", true); // mark it
	} else if (
		currentLanguage === "zh-tw" &&
		$(".upload-db-btn").data("translated")
	) {
		$(".upload-db-btn").css("transform", "translateX(0px)");
		$(".upload-db-btn").data("translated", false); // mark it
	} else {
		console.warn("could not adjust sidebar styling due to unkown language");
	}
}

async function residueCleaner() {
	try {
		// Clear localStorage
		localStorage.clear();

		// Clear sessionStorage
		sessionStorage.clear();

		// Clear cookies (but EXCLUDE the PHP session cookie)
		const SESSION_COOKIE_NAME = "PHPSESSID"; // Check your PHP config if it's different

		document.cookie.split(";").forEach((cookie) => {
			const name = cookie.split("=")[0].trim();

			// **Only clear the cookie if it is NOT the PHP session ID.**
			if (name !== SESSION_COOKIE_NAME) {
				document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/`;
			}
		});

		// Clear Cache Storage (used by service workers / PWAs)
		if ("caches" in window) {
			const cacheNames = await caches.keys();
			await Promise.all(cacheNames.map((name) => caches.delete(name)));
		}

		// console.log("Residue cleanup complete ✅");
	} catch (err) {
		console.error("Residue cleanup failed ❌", err);
	}
}
