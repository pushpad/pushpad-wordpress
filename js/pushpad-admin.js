jQuery(function () {
  var toggleFields = function (value) {
    if (value == 'custom') {
      jQuery('.custom-api-only').show();
      jQuery('.simple-api-only').hide();
    } else {
      jQuery('.custom-api-only').hide();
      jQuery('.simple-api-only').show();
    }
  };
  toggleFields(jQuery('#pushpad-settings-form input[type=radio][name=api]:checked').val());
  jQuery('#pushpad-settings-form input[type=radio][name=api]').change(function () {
    toggleFields(jQuery(this).val());
  });
});
