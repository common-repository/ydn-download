<div class="row form-group">
	<div class="col-md-6">
		<label class="ycd-label-of-input" for="ydn-html-before-content"><?php _e('HTML Before The Content', YDN_TEXT_DOMAIN); ?></label>
	</div>
	<div class="col-md-12">
		<?php
		$editorId = 'ydn-html-before-content';
		$beforeContent = $this->getOptionValue($editorId);
		$settings = array(
			'wpautop' => false,
			'tinymce' => array(
				'width' => '100%'
			),
			'textarea_rows' => '6',
			'media_buttons' => true
		);
		wp_editor($beforeContent, $editorId, $settings);
		?>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-6">
		<label class="ycd-label-of-input" for="ydn-html-after-content"><?php _e('HTML After The Content', YDN_TEXT_DOMAIN); ?></label>
	</div>
	<div class="col-md-12">
		<?php
		$editorId = 'ydn-html-after-content';
		$beforeContent = $this->getOptionValue($editorId);
		$settings = array(
			'wpautop' => false,
			'tinymce' => array(
				'width' => '100%'
			),
			'textarea_rows' => '6',
			'media_buttons' => true
		);
		wp_editor($beforeContent, $editorId, $settings);
		?>
	</div>
</div>