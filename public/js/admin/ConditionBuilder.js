function ConditionBuilder() {
}

ConditionBuilder.prototype.init = function() {
	this.conditionsBuilder();
	this.select2();
};

ConditionBuilder.prototype.select2 = function() {
	var select2 = jQuery('.js-ydn-select');

	if(!select2.length) {
		return false;
	}
	select2.each(function() {
		var type = jQuery(this).data('select-type');

		var options = {
			width: '100%'
		};

		if (type == 'ajax') {
			options = jQuery.extend(options, {
				minimumInputLength: 1,
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					delay: 250,
					type: "POST",
					data: function(params) {

						var searchKey = jQuery(this).attr('data-value-param');
						var postType = jQuery(this).attr('data-post-type');

						return {
							action: 'ydn_select2_search_data',
							nonce_ajax: ydn_admin_localized.nonce,
							postType: postType,
							searchTerm: params.term,
							searchKey: searchKey
						};
					},
					processResults: function(data) {
						return {
							results: jQuery.map(data.items, function(item) {

								return {
									text: item.text,
									id: item.id
								}

							})
						};
					}
				}
			});
		}

		jQuery(this).select2(options);
	});
};

ConditionBuilder.prototype.conditionsBuilder = function() {
	this.conditionsBuilderEdit();
	this.conditionsBuilderAdd();
	this.conditionsBuilderDelte();
};

ConditionBuilder.prototype.conditionsBuilderAdd = function() {
	var params = jQuery('.ydn-condition-add');

	if(!params.length) {
		return false;
	}
	var that = this;
	params.bind('click', function() {
		var currentWrapper = jQuery(this).parents('.ydn-condion-wrapper').first();
		var selectedParams = currentWrapper.find('.js-conditions-param').val();

		that.addViaAjax(selectedParams, currentWrapper);
	});
};

ConditionBuilder.prototype.conditionsBuilderDelte = function() {
	var params = jQuery('.ydn-condition-delete');

	if(!params.length) {
		return false;
	}

	params.bind('click', function() {
		var currentWrapper = jQuery(this).parents('.ydn-condion-wrapper').first();

		currentWrapper.remove();
	});
};

ConditionBuilder.prototype.conditionsBuilderEdit = function() {
	var params = jQuery('.js-conditions-param');

	if(!params.length) {
		return false;
	}
	var that = this;
	params.bind('change', function() {
		var selectedParams = jQuery(this).val();
		var currentWrapper = jQuery(this).parents('.ydn-condion-wrapper').first();

		that.changeViaAjax(selectedParams, currentWrapper);
	});
};

ConditionBuilder.prototype.addViaAjax = function(selectedParams, currentWrapper) {
	var conditionId = parseInt(currentWrapper.data('condition-id'))+1;
	var conditionsClassName = currentWrapper.parent().data('child-class');

	var that = this;

	var data = {
		action: 'ydn_add_conditions_row',
		nonce: ydn_admin_localized.nonce,
		conditionId: conditionId,
		conditionsClassName: conditionsClassName,
		selectedParams: selectedParams
	};

	jQuery.post(ajaxurl, data, function(response) {
		currentWrapper.after(response);
		that.init();
	});
};

ConditionBuilder.prototype.changeViaAjax = function(selectedParams, currentWrapper) {
	var conditionId = currentWrapper.data('condition-id');
	var conditionsClassName = currentWrapper.parent().data('child-class');

	var that = this;

	var data = {
		action: 'ydn_edit_conditions_row',
		nonce: ydn_admin_localized.nonce,
		conditionId: conditionId,
		conditionsClassName: conditionsClassName,
		selectedParams: selectedParams
	};

	jQuery.post(ajaxurl, data, function(response) {
		currentWrapper.replaceWith(response);
		that.init();
	});
};

jQuery(document).ready(function() {
	var obj = new ConditionBuilder();
	obj.init();
});