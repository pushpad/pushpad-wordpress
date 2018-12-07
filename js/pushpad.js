function pushpadShowMessage(notice_or_alert, text) {
  jQuery('body').append('<div class="pushpad-' + notice_or_alert + '">' + text + ' <a href="#" onclick="javascript:this.parentNode.style.display=\'none\';" class="close">&times;</a></div>');
}

jQuery(function () {
  pushpad('init', pushpadSettings.projectId);

  var updateButton = function (isSubscribed) {
    jQuery('button.pushpad-button').each(function () {
      var btn = jQuery(this);
      if (isSubscribed) {
        btn.html(btn.data('unsubscribe-text'));
        btn.removeClass('unsubscribed').addClass('subscribed');
      } else {
        btn.html(btn.data('subscribe-text'));
        btn.removeClass('subscribed').addClass('unsubscribed');
      }
    });
  };
  pushpad('status', updateButton);

  if (pushpadSettings.subscribeOnLoad) {
    pushpad('subscribe', function () { updateButton(true); });
  }

  jQuery(".pushpad-button").on("click", function(e) {
    e.preventDefault();
    if (jQuery(this).hasClass('subscribed')) {
      pushpad('unsubscribe', function () { updateButton(false); });
    } else {
      pushpad('subscribe', function (isSubscribed) { 
        if (isSubscribed) {
          updateButton(true);
          pushpadShowMessage('notice', pushpadSettings.subscribedNotice);
        } else {
          updateButton(false);
          pushpadShowMessage('alert', pushpadSettings.notSubscribedNotice);
        }
      });
    }
  });

  pushpad('unsupported', function() {
    jQuery('.pushpad-button').on('click', function() {
      pushpadShowMessage('alert', pushpadSettings.unsupportedNotice);
    });
  });
});
