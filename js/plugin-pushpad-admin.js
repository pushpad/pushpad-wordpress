/**
 * Function to validate send notification form
 */
jQuery(document).ready(function() {

	jQuery(".NotifAPIRadio").click(function() {
		var target = jQuery(this).attr("data-container");
		jQuery(".NotifSendContainer").hide();
		jQuery(".NotifSendContainerTest").hide();
		jQuery("#" + target).show();
		jQuery("#" + target + "Test").show();
	});

	jQuery('.plugin-pushpad-count-up').each(function(index, item) {
		jQuery(item).keyup(function() {
			var countspan = jQuery(item).parent().find('.countUP');
			var caracter_count = jQuery(item).val().length;
			countspan.text(caracter_count);
		});
	});

	jQuery('#submit-notification').click(function(evt) {

		$validated = false;

		if (jQuery('#notification-title').val() == '') {
			$validated = true;
			jQuery('#notification-title').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-title').css('border-color', 'green')
		}

		if (jQuery('#notification-body').val() == '') {
			$validated = true;
			jQuery('#notification-body').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-body').css('border-color', 'green')
		}

		if (!validateURL(jQuery('#notification-url').val())) {
			$validated = true;
			jQuery('#notification-url').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-url').css('border-color', 'green')
		}

		if ($validated) {
			return false;
		}
		;

	});

	jQuery('#push-notification').on('click', '.plugin-pushpad-error', function() {
		jQuery(this).removeClass('plugin-pushpad-error');
	});

	jQuery('#submit-notification-custom').click(function(evt) {

		$validated = false;

		if (jQuery('#notification-title-custom').val() == '') {
			$validated = true;
			jQuery('#notification-title-custom').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-title-custom').css('border-color', 'green')
		}

		if (jQuery('#notification-body-custom').val() == '') {
			$validated = true;
			jQuery('#notification-body-custom').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-body-custom').css('border-color', 'green')
		}

		if (!validateURL(jQuery('#notification-url-custom').val())) {
			$validated = true;
			jQuery('#notification-url-custom').css('border-color', '#c0392b');
		} else {
			jQuery('#notification-url-custom').css('border-color', 'green')
		}

		if ($validated) {
			return false;
		}
		;

	});

	jQuery('#push-notification-custom').on('click', '.plugin-pushpad-error', function() {
		jQuery(this).removeClass('plugin-pushpad-error');
	});

});

function validateURL(textval) {
	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(textval);
}