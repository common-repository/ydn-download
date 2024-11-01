<div class="ydn-bootstrap-wrapper">
    <div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-file-label"><?php _e('Label', YDN_TEXT_DOMAIN); ?></label>
		</div>
		<div class="col-md-6">
			<input class="form-control" id="ydn-file-label" name="ydn-file-label" value="<?php echo esc_attr($this->getOptionValue('ydn-file-label')); ?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
			<input id="js-ydn-target-link" class="btn btn-primary" type="button" value="Select File">
		</div>
		<div class="col-md-6 ydn-select-wrapper">
			<input type="url" id="ydn-target-link" name="ydn-target-link" class="form-control" readonly value="<?php echo $this->getOptionValue('ydn-target-link'); ?>">
		</div>
	</div>
    <!-- Version start -->
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-file-version"><?php _e('Version', YDN_TEXT_DOMAIN); ?></label>
		</div>
		<div class="col-md-6">
		</div>
	</div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-file-version-label"><?php _e('label', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <input class="form-control" id="ydn-file-version-label" name="ydn-file-version-label" value="<?php echo esc_attr($this->getOptionValue('ydn-file-version-label')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-file-version"><?php _e('value', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <input class="form-control" id="ydn-file-version" name="ydn-file-version" value="<?php echo esc_attr($this->getOptionValue('ydn-file-version')); ?>">
        </div>
    </div>
    <!-- Version end -->
    <!-- Short description start -->
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-file-short-description"><?php _e('Short Description', YDN_TEXT_DOMAIN); ?></label>
		</div>
		<div class="col-md-6">
		</div>
	</div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-file-short-description-label"><?php _e('label', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <input class="form-control" id="ydn-file-short-description-label" name="ydn-file-short-description-label" value="<?php echo esc_attr($this->getOptionValue('ydn-file-short-description-label')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-file-short-description"><?php _e('text', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <textarea class="form-control" placeholder="<?php _e('Description');?>" name="ydn-file-short-description"><?php echo $this->getOptionValue('ydn-file-short-description'); ?></textarea>
        </div>
    </div>
    <!-- Short description end -->
</div>