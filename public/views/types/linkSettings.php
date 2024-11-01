<?php
namespace ydn;

require_once(YDN_HELPERS_PATH.'AdminHelper.php');
$defaults = AdminHelper::getAllDefaultSettings();
?>
<div class="ydn-bootstrap-wrapper">
	<div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-link-label"><?php _e('Link Label', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <input class="form-control" id="ydn-link-label" name="ydn-link-label" value="<?php echo esc_attr($this->getOptionValue('ydn-link-label')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-text-decoration"><?php _e('Text Decoration', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <?php echo AdminHelper::createSelectBox($defaults['textDecorationTypes'], esc_attr($this->getOptionValue('ydn-text-decoration')), array('name' => 'ydn-text-decoration', 'class' => 'ydn-js-select2'))?>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-file-label"><?php _e('Redirect to new tab:', YDN_TEXT_DOMAIN); ?></label>
        </div>
        <div class="col-md-6">
            <label class="ydn-switch">
                <input type="checkbox" id="ydn-open-downloads-page-new-tab" name="ydn-open-downloads-page-new-tab" class="ydn-open-downloads-page-new-tab" <?php echo $this->getOptionValue('ydn-open-downloads-page-new-tab'); ?>>
                <span class="ydn-slider ydn-round"></span>
            </label>
        </div>
    </div>
	<?php require_once dirname(__FILE__).'/content.php'; ?>
</div>