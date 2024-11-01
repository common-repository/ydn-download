function YdnAdmin() {
	this.init();
}

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

jQuery(document).ready(function() {
	new YdnAdmin();
});