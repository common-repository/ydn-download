function YdnAdmin() {
	this.init();
	this.toggleCheckedHistoryCheckbox();
	this.deleteHistory();
	this.switchEnable();
	this.copySortCode();
	this.select2();
	this.accordionContent();
	this.multipleChoiceButton();

	this.changeImage();
}


YdnAdmin.prototype.accordionContent = function() {

	var that = this;
	var accordionCheckbox = jQuery('.ydn-accordion-checkbox');

	if (!accordionCheckbox.length) {
		return false;
	}
	accordionCheckbox.each(function () {
		that.doAccordion(jQuery(this), jQuery(this).is(':checked'));
	});
	accordionCheckbox.each(function () {
		jQuery(this).bind('change', function () {
			var attrChecked = jQuery(this).is(':checked');
			var currentCheckbox = jQuery(this);
			that.doAccordion(currentCheckbox, attrChecked);
		});
	});
};

YdnAdmin.prototype.changeImage = function() {
	var supportedImageTypes = ['image/bmp', 'image/png', 'image/jpeg', 'image/jpg', 'image/ico', 'image/gif'];
	var custom_uploader;
	if(jQuery('#js-upload-image').val()) {
		jQuery('.ydn-show-image-container').css({'background-image': 'url("' +jQuery('#js-upload-image').val() + '")'});
	}
	jQuery('#js-upload-image-button').click(function(e) {
		e.preventDefault();

		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function () {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			if (supportedImageTypes.indexOf(attachment.mime) === -1) {
				return;
			}
			jQuery(".ydn-show-image-container").css({'background-image': 'url("' + attachment.url + '")'});
			jQuery(".ydn-show-image-container").html(" ");
			jQuery('#js-upload-image').val(attachment.url);
		});
		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
	});
};

YdnAdmin.prototype.doAccordion = function(checkbox, isChecked) {
	var accordionContent = checkbox.parents('.row').nextAll('.ydn-accordion-content').first();

	if(isChecked) {
		accordionContent.removeClass('ydn-hide-content');
	}
	else {
		accordionContent.addClass('ydn-hide-content');
	}
};

YdnAdmin.prototype.copySortCode = function() {
	jQuery('.download-shortcode').bind('click', function() {
		var currentId = jQuery(this).data('id');
		var copyText = document.getElementById('ydn-shortcode-input-'+currentId);
		copyText.select();
		document.execCommand('copy');

		var tooltip = document.getElementById('ydn-tooltip-'+currentId);
		tooltip.innerHTML = ydn_admin_localized.copied;
	});

	jQuery('.download-shortcode').mouseleave(function() {
		var currentId = jQuery(this).data('id');
		var tooltip = document.getElementById('ydn-tooltip-'+currentId);
		tooltip.innerHTML = ydn_admin_localized.copyToClipboard;
	});
};

YdnAdmin.prototype.switchEnable= function() {
    var switchEnable = jQuery('.ydn-switch-checkbox');

    if(!switchEnable.length) {
        return false;
    }

    switchEnable.each(function() {
        jQuery(this).bind('change', function() {
            var data = {
                action: 'ydn-switch',
                nonce: ydn_admin_localized.nonce,
                id: jQuery(this).data('switch-id'),
                checked: jQuery(this).is(':checked')
            };

            jQuery.post(ajaxurl, data, function(e, responce) {
                console.log(responce);
            });
        })
    });
};

YdnAdmin.prototype.init = function() {
	var addFile = jQuery('#js-ydn-target-link');

	if(!addFile.length) {
		return false;
	}

    addFile.bind('click', function() {
		/* Extend the wp.media object */
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            titleFF: 'Choose File',
            button: {
                text: 'Choose File'
            },
            multiple: false
        });
		/* When a file is selected, grab the URL and set it as the text field's value */
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('#ydn-target-link').val(attachment.url);
        });
		/* If the uploader object has already been created, reopen the dialog */
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
    });
};

YdnAdmin.prototype.deleteHistory = function() {
    var deleteSubs = jQuery('.ydn-datable-delete');

    if (!deleteSubs.length) {
        return false;
    }
    var that = this;

    deleteSubs.bind('click', function(e) {
        e.preventDefault();
        var bulkValue = jQuery(this).prev().val();
        var tableName = jQuery(this).data('table-name');

        if (bulkValue == 'delete') {
            that.deleteHistoryViaAjax(tableName)
        }
    });
};

YdnAdmin.prototype.deleteHistoryViaAjax = function(tableName)
{
    var checkedItemsList = [];

    jQuery('.ydn-delete-checkbox').each(function() {
        if (jQuery(this).is(':checked')) {
            checkedItemsList.push(jQuery(this).val())
        }
    });

    var data = {
        action: 'ydn_datatable_delete',
        nonce:  ydn_admin_localized.nonce,
        itemsId: checkedItemsList,
        tableName: tableName,
        beforeSend: function() {
        }
    };

    jQuery.post(ajaxurl, data, function(response) {
        jQuery('.ydn-delete-checkbox').prop('checked', '');
        window.location.reload();
    });
};

YdnAdmin.prototype.toggleCheckedHistoryCheckbox = function() {
    var subsBulk = jQuery('.subs-bulk');

    if (!subsBulk.length) {
        return false;
    }
    var that = this;

    subsBulk.each(function() {
        jQuery(this).bind('click', function() {
            var bulkStatus = jQuery(this).is(':checked');
            subsBulk.each(function() {
                jQuery(this).prop('checked', bulkStatus);
            });
            that.changeCheckedCheckboxes(bulkStatus);
        });
    })
};

YdnAdmin.prototype.select2 = function () {

	var select2 = jQuery('.ydn-js-select2');

	if(!select2.length) {
		return false;
	}

	select2.select2();
};

YdnAdmin.prototype.changeCheckedCheckboxes = function(bulkStatus) {
    jQuery('.ydn-delete-checkbox').each(function() {
        jQuery(this).prop('checked', bulkStatus);
    });
};

YdnAdmin.prototype.multipleChoiceButton = function() {
	var choiceOptions = jQuery('.ydn-choice-option-wrapper input');
	if(!choiceOptions.length) {
		return false;
	}

	var that = this;

	choiceOptions.each(function() {

		if(jQuery(this).is(':checked')) {
			that.buildChoiceShowOption(jQuery(this));
		}

		jQuery(this).on('change', function() {
			that.hideAllChoiceWrapper(jQuery(this).parents('.ydn-multichoice-wrapper').first());
			that.buildChoiceShowOption(jQuery(this));
		});
	})
};

YdnAdmin.prototype.hideAllChoiceWrapper = function(choiceOptionsWrapper) {
	choiceOptionsWrapper.find('input').each(function() {
		var choiceInputWrapperId = jQuery(this).attr('data-attr-href');
		jQuery('#'+choiceInputWrapperId).addClass('ydn-hide');
	})
};

YdnAdmin.prototype.buildChoiceShowOption = function(currentRadioButton)  {
	var choiceOptions = currentRadioButton.attr('data-attr-href');
	var currentOptionWrapper = currentRadioButton.parents('.ydn-choice-wrapper').first();
	var choiceOptionWrapper = jQuery('#'+choiceOptions).removeClass('ydn-hide');
	currentOptionWrapper.after(choiceOptionWrapper);
};

jQuery(document).ready(function() {
	new YdnAdmin();
});