<?php
$imageURl = $this->getOptionValue('ydn-image-url');
?>
<div class="ydn-bootstrap-wrapper">
	<div class="yst-images-wrapper">
		<?php
		$imageURl = $this->getOptionValue('ydn-image-url');
		?>
	</div>
	<div class="ydn-picture-h">
		<h3><?php _e('Please choose your picture', YDN_TEXT_DOMAIN);?></h3>
	</div>
	<div class="ydn-image-uploader-wrapper">
		<input class="input-width-static" id="js-upload-image" type="text" size="36" name="ydn-image-url" value="<?php echo esc_attr($imageURl); ?>" required>
		<input id="js-upload-image-button" class="button" type="button" value="<?php _e('Select image', YDN_TEXT_DOMAIN);?>">
	</div>
	<div class="ydn-show-image-container">
        <?php if (empty($imageURl)): ?>
		<span class="ydn-no-image">(<?php _e('No image selected', YDN_TEXT_DOMAIN);?>)</span>
        <?php endif; ?>
	</div>
	<?php require_once dirname(__FILE__).'/content.php'; ?>
</div>
