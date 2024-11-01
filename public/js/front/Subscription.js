function YdnSubscription() {
	this.initForm();
}

YdnSubscription.prototype.initForm = function () {
	var forms = jQuery('.ydn-subscription-form');

	if (!forms.length) {
		return false;
	}

	forms.each(function () {
		var form = jQuery('form', this);
		var postId = form.data('id');
		var validateObj = eval('ydnSubsObj'+postId);
		validateObj.submitHandler = function (form) {

			var savedArgs = jQuery(form).data('saved-args');
			var formData = jQuery(form).serialize();
			var data = {
				action: 'ydn_subscribe',
				nonce: YDN_ARGS.nonce,
				beforeSend: function () {
					var submitButton = jQuery('.js-subs-submit-btn', form);
					submitButton.prop('disabled', true);
					submitButton.val(submitButton.data('progress-title'))
				},
				postId: postId,
				formData: formData
			};

			jQuery.post(YDN_ARGS.ajaxurl, data, function (e) {
				var response = jQuery.parseJSON(e);
				if (response['status'] == true) {
					var submitButton = jQuery('.js-subs-submit-btn', form);
					jQuery('.js-subs-submit-btn', form).prop('disabled', false);
					submitButton.val(submitButton.data('title'));

					var behavior = savedArgs['ydn-action-behavior'];

					if (behavior == 'download') {
						window.location = YDN_ARGS.downloadPostURL;
					}
				}

			})
		};
		form.validate(validateObj);
	})
};

jQuery(document).ready(function () {
	new YdnSubscription();
});