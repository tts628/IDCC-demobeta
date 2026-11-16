import { updateMainContent } from "./contentUpdater.js";

export function selectInputValidate(selectId, message) {
	if ($(`#${selectId}`).val() == null) {
		showNotification(`請選擇${message}`, "maintenance-form-modal");
		$("#MaintenanceType").focus();
		return false;
	}
	return true;
}

export function setBooleanProperty(element, propName, value) {
	if (typeof value !== "boolean") {
		throw new Error(`Invalid value for ${propName}, expected boolean but got ${typeof value}`);
	}
	element.prop(propName, value);
}

export function ajaxMethod(url) {
	$.ajax({
		url: url,
		success: function (pageContent) {
			$("#content").empty();
			$("#full-page-content").empty().html(pageContent);
		},
		error: function (xhr, status, error) {
			console.error("Request failed:", error);
		},
	});
}

export function generateUniqueId() {
	return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
}

export function showNotification(message, containerId) {
	// Create notification element
	var notification = $("<div></div>", {
		class: "notification",
		text: message,
	});

	// Append notification element to the container
	$(`#${containerId}`).append(notification);

	// Trigger a reflow to apply the initial opacity transition
	notification.width();

	// Fade in the notification
	notification.css("opacity", "1");

	// Fade out and remove the notification after 3 seconds
	setTimeout(function () {
		notification.css("opacity", "0");
		setTimeout(function () {
			notification.remove();
		}, 1000); // Remove notification after fade out animation
	}, 3000); // Display notification for 3 seconds
}

export function querySetterAndGetter(method, queryVar, setValue) {
	const queryString = window.location.search.substring(1); // Remove the '?' character
	const queryParams = new URLSearchParams(queryString);

	// Validate setValue variable before using to prevent error
	if (method === "set" && setValue) {
		// Set or update query parameters
		// searchParams.set("param2", "value2");
		queryParams.set(queryVar, setValue);

		// Construct the new URL with the updated query string
		const newUrl = `${window.location.pathname}?${queryParams.toString()}${window.location.hash}`;

		// Update the URL without reloading the page
		history.pushState({}, "", newUrl);
		// updateMainContent();
		return;
	} else if (method === "get") {
		return queryParams.get(queryVar);
	} else {
		throw new Error("Only method 'set' or 'get' is available in setter/getter function");
	}
}

export function queryBullkSetter(queryObj, exceptionList = []) {
	const cleanedUrlObj = removeQueryParamsExcept(exceptionList);
	const queryParams = new URLSearchParams(cleanedUrlObj.search);

	for (const [queryVar, setValue] of Object.entries(queryObj)) {
		queryParams.set(queryVar, setValue);
	}
    console.log(queryObj)

	cleanedUrlObj.search = queryParams;
	history.pushState({}, document.title, cleanedUrlObj.toString());
	// updateMainContent();
}

export function removeQueryParam(paramName) {
	const urlObj = new URL(window.location.href);
	const params = new URLSearchParams(urlObj.search);
	params.delete(paramName);
	urlObj.search = params.toString();
	history.pushState({}, document.title, urlObj.toString());
	// updateMainContent();
}

export function removeQueryParamsExcept(exceptionList) {
	const urlObj = new URL(window.location.href);
	const params = new URLSearchParams(urlObj.search);

	// Get all parameter names from the URL
	const allParams = Array.from(params.keys());

	// Remove parameters not in the exception list
	allParams.forEach((paramName) => {
		if (!exceptionList.includes(paramName)) {
			params.delete(paramName);
		}
	});

	// Update the URL with the modified parameters
	urlObj.search = params.toString();
	return urlObj;
}

export function switchEnvCheck() {
	const url = new URL(window.location.href);
	if (!url.pathname.includes("DemoBeta")) {
		$(".client-settings-toggler").hide();
	} else {
		console.log("switch env enabled");
	}
}

export async function fetchData(url, data = {}, method = "POST") {
	return fetch(url, {
		method: method,
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(data),
	}).then((response) => {
		if (!response.ok) {
			throw new Error(`Network response was not ok: ${response.statusText}`);
		}
		return response.json();
	});
}

export async function fetchWithFormData(url, formData) {
	return fetch(url, {
		method: "POST",
		body: formData,
	}).then((response) => {
		if (!response.ok) {
			throw new Error(`Network response was not ok: ${response.statusText}`);
		}
		return response.json();
	});
}