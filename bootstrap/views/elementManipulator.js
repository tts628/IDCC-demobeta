import { setBooleanProperty } from "./utilities.js";
import { changeContent, updateMainContent } from "./contentUpdater.js";

const moduleTitles = []; // Set by class '.module-title'
const ajaxHashes = []; // Set by navMethod = "ajax", ajaxHash
const ajaxUrls = []; // Set by navMethod = "ajax", url

export { moduleTitles, ajaxHashes, ajaxUrls };

const modalBase = [
	{
		element: "div",
		class: "modal fade",
		tabindex: "-1",
		role: "dialog",
		child: {
			element: "div",
			class: "modal-dialog modal-xl",
			role: "document",
			child: {
				element: "div",
				class: "modal-content",
				child: [
					{
						element: "div",
						class: "modal-header",
						child: [
							{
								element: "div",
								class: "modal-title",
							},
							{
								element: "button",
								type: "button",
								class: "close btn btn-white",
								ariaLabel: "Close",
								data: {
									"bs-dismiss": "modal",
								},
								child: {
									element: "span",
									ariaHidden: "true",
									html: "&times",
								},
							},
						],
					},
					{
						element: "div",
						class: "modal-body",
					},
					{
						element: "div",
						class: "modal-footer",
					},
				],
			},
		},
	},
];

export function createHTMLElement(structure, root, previousElement = null, modalId = null) {
	if (!Array.isArray(structure)) {
		console.error("Invalid structure: ", structure);
		throw new Error("Structure must be an array.");
	}

	structure.forEach((item) => {
		const {
			element,
			class: className = "",
			id,
			type,
			text,
			child,
			data = {},
			forAttr,
			rows,
			src,
			parentId,
			url,
			navMethod,
			tabindex,
			role,
			ariaLabel,
			ariaHidden,
			html,
			modal,
			ajaxHash,
			remark,
			placeholder,
			required,
			selected,
			hidden,
			disabled,
			value,
			accept,
			name,
			form,
			query,
			action,
			href,
			checked,
			step,
			min,
			max,
			maxlength,
			multiple,
			media,
			srcset,
		} = item;

		// Create the new element
		const newElement = $("<" + element + ">");

		const attributes = {
			id: id,
			type: type,
			for: forAttr,
			rows: rows,
			src: src,
			tabindex: tabindex,
			role: role,
			placeholder: placeholder,
			accept: accept,
			name: name,
			form: form,
			action: action,
			href: href,
			step: step,
			min: min,
			max: max,
			maxlength: maxlength,
			media: media,
			srcset: srcset,
			"aria-label": ariaLabel,
			"aria-hidden": ariaHidden,
		};

		// Loop through the attributes object and set attributes if the value is truthy
		$.each(attributes, function (attributeName, attributeValue) {
			if (attributeValue) {
				newElement.attr(attributeName, attributeValue);
			}
		});

		if (modalId) {
			newElement.attr("id", modalId);
		}

		if (text) {
			newElement.text(text);
		}

		// Utility function to safely escape HTML content
		// function escapeHTML(htmlContent) {
		// 	return $("<div>").text(htmlContent).html();
		// }

		// if (html) {
		// 	newElement.html(escapeHTML(html)); // To prevent XSS
		// }

		if (html) {
			newElement.html(html);
		}

		if (multiple !== undefined) {
			setBooleanProperty(newElement, "multiple", multiple);
		}

		if (required !== undefined) {
			setBooleanProperty(newElement, "required", required);
		}

		if (selected !== undefined) {
			setBooleanProperty(newElement, "selected", selected);
		}

		if (disabled !== undefined) {
			setBooleanProperty(newElement, "disabled", disabled);
		}

		if (hidden !== undefined) {
			setBooleanProperty(newElement, "hidden", hidden);
		}

		if (checked !== undefined) {
			setBooleanProperty(newElement, "checked", checked);
		}

		if (value) {
			newElement.val(value);
		}

		// Set class
		if (className) {
			newElement.addClass(className);
			if (className.includes("drill-toggle")) {
				newElement.click(function () {
					const childrenIsVisible = $(this).parent().children("ul").is(":visible");
					newElement.parent().children("ul").slideToggle("fast");
					if (childrenIsVisible) {
						$(this).children(".fa-angle-down").removeClass("icon-rotated");
					} else {
						$(this).children(".fa-angle-down").addClass("icon-rotated");
					}
				});
			}
		}

		// Set data attributes
		if (typeof data === "object") {
			Object.entries(data).forEach(([key, value]) => {
				newElement.attr("data-" + key, value);
			});
		}

		if (navMethod) {
			const moduleObj = {
				navMethod: navMethod,
				text: remark,
				url: ajaxHash ? ajaxHash : url,
			};
			moduleTitles.push(moduleObj);
			if (navMethod === "ajax") {
				ajaxHashes.push(ajaxHash);
				ajaxUrls.push(url);
			}
			// on click, default change hash, set/remove ajax method from url query using removeQueryParam
			newElement.click(function () {
				changeContent(navMethod, url, ajaxHash, query);
			});
		}

		// Append the new element based on previousElement

		if (modal && id) {
			if (modal.sidebar) {
				createHTMLElement(modalBase, "sidebarModalRoot", null, id);
			} else {
				createHTMLElement(modalBase, "modalRoot", null, id);
			}
			if (modal.closePrevention) {
				$(`#${id}`).attr("data-bs-backdrop", "static");
				$(`#${id}`).attr("data-bs-keyboard", "false");
			}
			const { headerHtml, bodyHtml, footerHtml } = modal;
			if (headerHtml) {
				const header = $(`#${id}`).find(".modal-title").html(modal.header);
				createHTMLElement(headerHtml, root, header);
			}
			if (bodyHtml) {
				const body = $(`#${id}`).find(".modal-body").html(modal.body);
				createHTMLElement(bodyHtml, root, body);
			}
			if (footerHtml) {
				const footer = $(`#${id}`).find(".modal-footer").html(modal.footer);
				createHTMLElement(footerHtml, root, footer);
			}
		} else if (parentId) {
			$(`#${parentId}`).append(newElement);
		} else if (previousElement) {
			previousElement.append(newElement);
		} else {
			$(`#${root}`).append(newElement); // If no previousElement, append to element with root
		}

		// Recursively create child elements
		if (child) {
			if (Array.isArray(child)) {
				createHTMLElement(child, root, newElement);
			} else {
				createHTMLElement([child], root, newElement);
			}
		}
	});
}

// Example usage:
// [
// 	{
// 		element: "div",
// 		class: "text-center mt-5",
// 		text: "aaa",
// 		id: "aaa",
// 		data: { "data-a": 1, dataB: 2 },
// 		child: [
// 			{
// 				element: "h4",
// 				class: "text-center mt-5",
// 				text: "工單統計",
// 			}
// 		]
// 	}
// ]

export async function loadScript(dist, script, module, category, fwversion = 0) {
	let completePath =
		dist === "shared" ? `./${category}/modules/${dist}/${script}` : `./${category}/modules/${module}/${script}`;
	if (fwversion === 0.5) {
		completePath =
			dist === "shared" ? `./${category}/modules/${dist}/${script}` : `./pages/${category}/${module}/${script}`;
	}
	// ? `../../Home/${category}/modules/${dist}/${script}`
	// : `../../Home/${category}/modules/${module}/${script}`;
	// paths to use with import() loading method

	try {
		// Load script using jQuery's getScript method
		// await $.getScript(completePath);
		// // Load script using module import method, only available for static use (dynamic module import would raise type module error)
		// import(completePath)
		// 	.then((module) => {
		// 		// console.log("Module loaded successfully:", module);
		// 	})
		// 	.catch((error) => {
		// 		console.error("Error loading module:", error);
		// 	});

		// // Load modules by specifying module version to prevent lazy loading
		$(`script[src*="${completePath}"]`).remove();

		const script = document.createElement("script");
		script.type = "module"; // Specify that it's a module script
		script.src = completePath + "?" + Date.now(); // Append a timestamp
		document.head.appendChild(script);
	} catch (error) {
		console.error(`Failed to load script: ${completePath}`);
		console.error(`Error:`, error);
	}
}

export function loadCSS(filename, isDynamic = false) {
	// Remove any previously dynamically loaded stylesheets
	$("link.dynamic-style").remove();

	// Create and append the new stylesheet
	$("<link>")
		.attr({
			rel: "stylesheet",
			type: "text/css",
			href: filename,
			class: isDynamic ? "dynamic-style" : "", // Add class only if dynamic
		})
		.appendTo("head");
}

export async function loadModuleCSS(dist, cssPath, module, category) {
	const completePath =
		dist == "shared"
			? `./${category}/modules/${dist}/views/${cssPath}`
			: `./${category}/modules/${module}/views/${cssPath}`;
	const isDynamic = !(module === "sidebar" || module === "CarouselSettings");

	loadCSS(completePath, isDynamic);
}
