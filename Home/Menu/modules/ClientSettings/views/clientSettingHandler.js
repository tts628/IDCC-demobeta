import { removeQueryParam, querySetterAndGetter } from "../../../../../bootstrap/views/utilities.js";
import { updateMainContent } from "../../../../../bootstrap/views/contentUpdater.js";

function updateClientToUrl() {
	const clientInput = $("#client-settings-input").val();
	if (clientInput !== "") {
		querySetterAndGetter("set", "client", clientInput);
		updateMainContent();
	} else {
		removeQueryParam("client");
		updateMainContent();
	}
}

export function saveClientSettings() {
	// Keypress event for Enter key
	$("#client-settings-input").on("keypress", function (event) {
		if (event.which === 13) {
			// 13 is the keycode for Enter
			updateClientToUrl();
		}
	});

	$("#save-client-settings-btn").on("click", () => {
		updateClientToUrl();
	});
}

export function clearClientSettings() {
	$("#clear-client-settings-btn").on("click", () => {
		removeQueryParam("client");
		updateMainContent();
	});
}

export function clearClientInputField() {
	$("#client-settings-modal").on("hidden.bs.modal", () => {
		$("#client-settings-input").val("");
	});
}
