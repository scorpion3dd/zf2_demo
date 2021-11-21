/**
 * This file is part of the Simple demo web-project with REST Full API for Mobile.
 *
 * This project is no longer maintained.
 * The project is written in Zend Framework 2 Release.
 *
 * @link https://github.com/scorpion3dd
 * @copyright Copyright (c) 2016-2021 Denis Puzik <scorpion3dd@gmail.com>
 */

/**
 * Sends GET /customers to API gateway.
 *
 * This method is automatically generated. Don't modify it. Application logic
 * for handling the AJAX response should be placed in
 * <code>onGetCustomers</code>.
 *
 * @param gatewayURL
 */
function getCustomers(gatewayURL) {
	Zf2Console.consoleColorWeightLog('function getCustomers');
	// https://api.jquerymobile.com/1.3/loader/
	$.mobile.loading("show");
	$.ajax({
		url : gatewayURL + '/customers',
		cache : false,
		type : 'GET',
		success : function(data, status, xhr) {
			Zf2Console.consoleColorWeightLog('ajax success');
			// console.log(data);
			$.mobile.loading("hide");
			onGetCustomers(data);
		}
	});
}

/**
 * Handle response from GET /customers
 *
 * @param response
 * @returns
 */
function onGetCustomers(response) {
	Zf2Console.consoleColorWeightLog('function onGetCustomers');
	customers = response;

	let newCustomers = '';
	$.each(customers, function(index, item) {
		newCustomers += '<li data-theme="">' +
			'<a href="#page2?empId=' + index + '" data-transition="none">' + item.name + '</a>' +
			'</li>';
	});
	$('#customers li[role!=heading]').remove();
	$('#customers').append(newCustomers).listview('refresh');
}

/**
 * ajaxErrorHandler
 * @param xhr
 * @param ajaxOptions
 * @param thrownError
 */
function ajaxErrorHandler(xhr, ajaxOptions, thrownError) {
	Zf2Console.consoleColorWeightLog('function ajaxErrorHandler');
	$.mobile.loading("hide");
	let _this = this;
	let msg = 'Ajax error. ';
	if (ajaxOptions.statusText != null && ajaxOptions.statusText != '') {
		msg = msg + '<br/>' + ajaxOptions.statusText + '<br/>';
	}
	msg = msg + 'Trying static data!';
	$(this).html(msg).show('slow', function() {
		Zf2Console.consoleColorWeightLog('show');
		onGetCustomers(customers);
		setTimeout(function() {
			$(_this).hide('slow');
		}, 1000);
	});
}


/**
 * Sends GET /search/:query to API gateway.
 *
 * This method is automatically generated. Don't modify it.
 * Application logic for handling the AJAX response should
 * be placed in <code>onGetSearchquery</code>.
 *
 * @param gatewayURL
 */
function getSearchquery(gatewayURL) {
	Zf2Console.consoleColorWeightLog('function getSearchquery');
	// https://api.jquerymobile.com/1.3/loader/
	$.mobile.loading("show");
	let searchinput = $('#custsearchinput').val();
	$.ajax({
		url : gatewayURL + '/search',
		cache : false,
		type: 'POST',
		dataType: "json",
		data: {
			searchinput: searchinput
		},
		success : function(data, status, xhr) {
			Zf2Console.consoleColorWeightLog('ajax success');
			$.mobile.loading("hide");
			onGetSearchquery(data);
		}
	});
}

/**
 * Handle AJAX response for GET /search/:query
 *
 * @param response JSON object with response
 */
function onGetSearchquery(response) {
	Zf2Console.consoleColorWeightLog('function onGetSearchquery');
	// console.log(response);
	// TODO Custom logic to handle server response
	customers = response;

	let newCustomers = '';
	$.each(customers, function(index, item) {
		Zf2Console.consoleColorWeightLog('each');
		// console.log(item);
		newCustomers += '<li data-theme="">' +
			'<a href="#page2?empId=' + index + '" data-transition="none">#' + item.id + ' - ' + item.name + '</a>' +
			'</li>';
	});
	$('#custlistview li[role!=heading]').remove();
	$('#custlistview').append(newCustomers).listview('refresh');
}

/**
 * pageBeforeChange
 * @param data
 */
function pageBeforeChange(data) {
	Zf2Console.consoleColorWeightLog('function pageBeforeChange');
	if (typeof data.toPage === 'string') {
		let r = data.toPage.match(/page2\?empId=(.*)/);
		if (r) {
			let customer = customers[r[1]];
			// console.log(customer);
			if (customer) {
				Zf2Console.consoleColorWeightLog('customer ' + customer.name);
				$("#customer_id").html(customer.id);
				$("#customer_name").html(customer.name);
				$("#customer_address").html(customer.address);
				$("#customer_birthday").html(customer.birthday);
				$("#customer_email").html(customer.email);
				$("#customer_description").html(customer.description);
				$("#customer_activity").html('Is currently ' + (customer.activity || '') + ' in:');
				if (customer.phone) {
					$("#customercall").attr('href', 'tel:' + customer.phone).parent().show().trigger('enhance');
				} else {
					$("#customercall").hide();
				}
				let location = customer.location;
				$("#locationMap").attr(
					"src",
					"https://maps.googleapis.com/maps/api/staticmap?center="
					+ location
					+ "&zoom=14&size=288x200&markers="
					+ location + "&sensor=false");
			}
		}
	}
}

/**
 * widget
 * @param name
 */
function widget(name) {
	Zf2Console.consoleColorWeightLog('function widget ' + name);
	$.widget(name, $.mobile.navbar, {
		_create : function() {
			Zf2Console.consoleColorWeightLog('_create widget ' + name);
			// Set the theme before we call the prototype, which will
			// ensure buttonMarkup() correctly grabs the inheritied theme.
			// We default to the "a" swatch if none is found
			let theme = this.element.jqmData('theme') || "a";
			this.element.addClass('ui-footer ui-footer-fixed ui-bar-' + theme);

			// Make sure the page has padding added to it to account for the fixed bar
			this.element.closest('[data-role="page"]').addClass('ui-page-footer-fixed');

			// Call the NavBar _create prototype
			$.mobile.navbar.prototype._create.call(this);
		},

		// Set the active URL for the Tab Bar, and highlight that button on the bar
		setActive : function(url) {
			Zf2Console.consoleColorWeightLog('setActive widget ' + name);
			// Sometimes the active state isn't properly cleared, so we reset it ourselves
			this.element.find('a').removeClass('ui-btn-active ui-state-persist');
			this.element.find('a[href="' + url + '"]').addClass('ui-btn-active ui-state-persist');
		}
	});
}

/**
 * Grab the id of the page that's showing, and select it on the Tab Bar
 * @param e
 */
function grabId(e) {
	Zf2Console.consoleColorWeightLog('function grabId');
	let tabBar, id = $(e.target).attr('id');
	tabBar = $.mobile.activePage.find(':jqmData(role="tabbar")');
	if (tabBar.length) {
		tabBar.tabbar('setActive', '#' + id);
	}
}

/**
 * buttonMarkup
 * @param options
 * @param attachEvents
 *
 * @returns {buttonMarkup}
 */
function buttonMarkup(options, attachEvents) {
	Zf2Console.consoleColorWeightLog('function buttonMarkup');
	let $workingSet = this;

	// Enforce options to be of type string
	options = (options && ($.type(options) == "object")) ? options : {};
	for ( let i = 0; i < $workingSet.length; i++) {
		let el = $workingSet.eq(i), e = el[0],
			o = $.extend({}, $.fn.buttonMarkup.defaults,
				{
					icon : options.icon !== undefined ? options.icon : el.jqmData("icon"),
					iconpos : options.iconpos !== undefined ? options.iconpos : el.jqmData("iconpos"),
					theme : options.theme !== undefined ? options.theme : el.jqmData("theme")
						|| $.mobile.getInheritedTheme(el, "c"),
					inline : options.inline !== undefined ? options.inline : el.jqmData("inline"),
					shadow : options.shadow !== undefined ? options.shadow : el.jqmData("shadow"),
					corners : options.corners !== undefined ? options.corners : el.jqmData("corners"),
					iconshadow : options.iconshadow !== undefined ? options.iconshadow : el.jqmData("iconshadow"),
					iconsize : options.iconsize !== undefined ? options.iconsize : el.jqmData("iconsize"),
					mini : options.mini !== undefined ? options.mini : el.jqmData("mini")
				}, options),
			// Classes Defined
			innerClass = "ui-btn-inner",
			textClass = "ui-btn-text",
			buttonClass, iconClass,
			// Button inner markup
			buttonInner, buttonText, buttonIcon, buttonElements;

		$.each(o, function(key, value) {
			Zf2Console.consoleColorWeightLog('each function');
			e.setAttribute("data-" + $.mobile.ns + key, value);
			el.jqmData(key, value);
		});

		// Check if this element is already enhanced
		buttonElements = $.data(((e.tagName === "INPUT" || e.tagName === "BUTTON") ? e.parentNode : e), "buttonElements");
		if (buttonElements) {
			e = buttonElements.outer;
			el = $(e);
			buttonInner = buttonElements.inner;
			buttonText = buttonElements.text;
			// We will recreate this icon below
			$(buttonElements.icon).remove();
			buttonElements.icon = null;
		} else {
			buttonInner = document.createElement(o.wrapperEls);
			buttonText = document.createElement(o.wrapperEls);
		}
		buttonIcon = o.icon ? document.createElement("span") : null;

		if (attachEvents && !buttonElements) {
			attachEvents();
		}

		// if not, try to find closest theme container
		if (!o.theme) {
			o.theme = $.mobile.getInheritedTheme(el, "c");
		}

		buttonClass = "ui-btn ui-btn-up-" + o.theme;
		buttonClass += o.inline ? " ui-btn-inline" : "";
		buttonClass += o.shadow ? " ui-shadow" : "";
		buttonClass += o.corners ? " ui-btn-corner-all" : "";

		if (o.mini !== undefined) {
			// Used to control styling in headers/footers, where buttons default to `mini` style.
			buttonClass += o.mini ? " ui-mini" : " ui-fullsize";
		}

		if (o.inline !== undefined) {
			// Used to control styling in headers/footers, where buttons default to `mini` style.
			buttonClass += o.inline === false ? " ui-btn-block" : " ui-btn-inline";
		}

		if (o.icon) {
			o.icon = "ui-icon-" + o.icon;
			o.iconpos = o.iconpos || "left";
			iconClass = "ui-icon " + o.icon;
			if (o.iconshadow) {
				iconClass += " ui-icon-shadow";
			}
			if (o.iconsize) {
				iconClass += " ui-iconsize-" + o.iconsize;
			}
		}

		if (o.iconpos) {
			buttonClass += " ui-btn-icon-" + o.iconpos;
			if (o.iconpos == "notext" && !el.attr("title")) {
				el.attr("title", el.getEncodedText());
			}
		}

		innerClass += o.corners ? " ui-btn-corner-all" : "";

		if (o.iconpos && o.iconpos === "notext" && !el.attr("title")) {
			el.attr("title", el.getEncodedText());
		}

		if (buttonElements) {
			el.removeClass(buttonElements.bcls || "");
		}
		el.removeClass("ui-link").addClass(buttonClass);

		buttonInner.className = innerClass;
		buttonText.className = textClass;
		if (!buttonElements) {
			buttonInner.appendChild(buttonText);
		}
		if (buttonIcon) {
			buttonIcon.className = iconClass;
			if (!(buttonElements && buttonElements.icon)) {
				buttonIcon.appendChild(document.createTextNode("\u00a0"));
				buttonInner.appendChild(buttonIcon);
			}
		}

		while (e.firstChild && !buttonElements) {
			buttonText.appendChild(e.firstChild);
		}

		if (!buttonElements) {
			e.appendChild(buttonInner);
		}

		// Assign a structure containing the elements of this button to the elements of this button. This
		// will allow us to recognize this as an already-enhanced button in future calls to buttonMarkup().
		buttonElements = {
			bcls : buttonClass,
			outer : e,
			inner : buttonInner,
			text : buttonText,
			icon : buttonIcon
		};

		$.data(e, 'buttonElements', buttonElements);
		$.data(buttonInner, 'buttonElements', buttonElements);
		$.data(buttonText, 'buttonElements', buttonElements);
		if (buttonIcon) {
			$.data(buttonIcon, 'buttonElements', buttonElements);
		}
	}

	return this;
}


/**
 * closestEnabledButton
 *
 * @param element
 * @returns {*}
 */
function closestEnabledButton(element) {
	Zf2Console.consoleColorWeightLog('function closestEnabledButton');
	let cname;
	while (element) {
		// Note that we check for typeof className below because the element
		// we handed could be in an SVG DOM where className on SVG elements is
		// defined to be of a different type (SVGAnimatedString). We only operate on
		// HTML DOM elements, so we look for plain "string".
		cname = (typeof element.className === 'string') && (element.className + ' ');
		if (cname && cname.indexOf("ui-btn ") > -1 && cname.indexOf("ui-disabled ") < 0) {
			break;
		}
		element = element.parentNode;
	}

	return element;
}