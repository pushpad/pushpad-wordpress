jQuery(function () {
  var toggleFields = function (value) {
    if (value == 'custom') {
      jQuery('.custom-api-only').show();
    } else {
      jQuery('.custom-api-only').hide();
    }
  };
  toggleFields(jQuery('#pushpad-settings-form input[type=radio][name=api]:checked').val());
  jQuery('#pushpad-settings-form input[type=radio][name=api]').change(function () {
    toggleFields(jQuery(this).val());
  });
});
