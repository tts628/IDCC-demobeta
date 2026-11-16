export function setCircularProgressText(progress, suppressIndicator, prefix = "") {
	const circleProgress = Math.min(Math.max(progress, 0), 100);
	$("#" + prefix + "progress-circle").css("--progress", circleProgress * 3.6 + "deg");
	$("#" + prefix + "progress-text").text(circleProgress + "%");
	if (suppressIndicator) {
		$("#" + prefix + "circle-loading-text").hide();
	} else {
		if (circleProgress >= 100) {
			$("#" + prefix + "circle-loading-text").hide();
		} else {
			$("#" + prefix + "circle-loading-text").show();
		}
	}
}

export function setLinearProgressText(progress, suppressIndicator, prefix = "") {
	const linearProgress = Math.min(Math.max(progress, 0), 100);
	$("#" + prefix + "linear-progress-bar")
		.css("width", linearProgress + "%")
		.attr("aria-valuenow", linearProgress);
	$("#" + prefix + "linear-progress-text").text(linearProgress + "%");
	if (suppressIndicator) {
		$("#" + prefix + "linear-loading-text").hide();
	} else {
		if (linearProgress >= 100) {
			$("#" + prefix + "linear-loading-text").hide();
		} else {
			$("#" + prefix + "linear-loading-text").show();
		}
	}
}

export function animateLoadingDots(elementId) {
	const element = $("#" + elementId);
	let dots = 0;
	setInterval(function () {
		dots = (dots + 1) % 4; // Cycle through 0, 1, 2, 3
		let dotText = ".".repeat(dots);
		element.text(dotText);
	}, 300);
}

export function duplicateLoadingElement(originalElementId, targetContainerId, clonedId) {
	const $originalElement = $("#" + originalElementId);
	const $clonedElement = $originalElement.clone(true); // Clone with event handlers
	$clonedElement.attr("id", clonedId);
	$clonedElement.find("[id]").each(function () {
		this.id = clonedId + "-" + this.id;
	});
	initializeLoadingElement(clonedId, targetContainerId, 1, true, $clonedElement, clonedId + "-");
}

export function initializeLoadingElement(
	elementId,
	targetContainerId,
	scale,
	suppressIndicator = false,
	$clonedElement,
	prefixId = ""
) {
	if (elementId.includes("circular") && elementId.includes("linear")) {
		console.error("DO NOT INCLUDE 'circular' AND 'linear' IN THE ELEMENT ID AT THE SAME TIME");
		return;
	} else if (elementId.includes("circular")) {
		setCircularProgressText(0, suppressIndicator, prefixId);
		animateLoadingDots(prefixId + "circle-loading-dots");
	} else if (elementId.includes("linear")) {
		setLinearProgressText(0, suppressIndicator, prefixId);
		animateLoadingDots(prefixId + "linear-loading-dots");
	} else if (elementId.includes("spinner")) {
		animateLoadingDots(prefixId + "spinner-loading-dots");
	} else {
		console.error('Error: The element id should include "spinner", "circular", or "linear".');
		return;
	}

	const $loadingObj = $clonedElement ? $clonedElement : $("#" + elementId);

	$("#" + targetContainerId).append($loadingObj);
	if (scale) {
		$loadingObj.css("transform", "scale(" + scale + ")");
	}
	$loadingObj.show();
}

// Demo function
// function incrementalProgressUpdate() {
//     let progress = 0;
//     const interval = setInterval(function () {
//         progress += 5;
//         setCircularProgressText(progress, true);
//         setLinearProgressText(progress, true);
//         if (progress >= 100) {
//             clearInterval(interval);
//         }
//     }, 300);
// }

// Example usage
// $(() => {
// // Example usage of initializeLoadingElement function
// initializeLoadingElement("loading-circular", "target-container", 1, true);

// let i = 0;
// $("#duplicate-element").click(function () {
//     const newId = "new-circular" + i;
//     duplicateLoadingElement("loading-circular", "target-container2", newId);
//     i++;
//     $("#loading-circular .progress-circle").css("background", "conic-gradient(red var(--progress), #f3f3f3 0)");
//     $("#" + newId + " .progress-circle").css("background", "conic-gradient(green var(--progress), #f3f3f3 0)");
// });

// // Start the incremental progress update for testing
// incrementalProgressUpdate();
// });
