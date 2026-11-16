async function fetchData(url, postData) {
	try {
		const response = await fetch(url, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(postData),
		});

		if (!response.ok) {
			throw new Error(`Network response was not ok: ${response.statusText}`);
		}

		const data = await response.json();
		return data;
	} catch (error) {
		console.error("Fetch error:", error);
		throw error;
	}
}

function machineChangeEvent(screenshotImages, selectedMachine) {
	if (selectedMachine === "") {
		selectedMachine = "[No Data]";
	} else {
		$("#screenshot-image").attr("src", "data:image/png;base64," + screenshotImages[selectedMachine]);
	}
	$("#machine-name").text(selectedMachine);
}

function selectorHandler(screenshotImages) {
	$("#machine-selector").on("change", (e) => {
		const selectedMachine = e.currentTarget.value;
		machineChangeEvent(screenshotImages, selectedMachine);
	});
}

async function initializePage() {
	const queryString = window.location.search;
	const url = "./screenshotAPI.php" + queryString;

	try {
		const screenshotImages = await fetchData(url, { method: "screenShot" });
		selectorHandler(screenshotImages);

		const machineData = await fetchData(url, { method: "machineName" });
		populateMachineSelector(machineData);

		machineChangeEvent(screenshotImages, $("#machine-selector").val());
	} catch (error) {
		console.error("Error initializing page:", error);
	}
}

function populateMachineSelector(machineData) {
	const machineOptions = Object.values(machineData).map(
		(value) =>
			`<option value="${!value.MachineName ? "" : value.MachineName}">${
				!value.MachineName ? "" : value.MachineName
			}</option>`
	);
	$("#machine-selector").append(machineOptions.join(""));
}

$(initializePage);
