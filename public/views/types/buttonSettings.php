<?php
use ydn\AdminHelper;
use \ydn\MultipleChoiceButton;
$defaults = AdminHelper::getAllDefaultSettings();
$dimensions = $defaults['dimensionsMode'];
?>
<div class="ydn-bootstrap-wrapper">
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-button-label"><?php _e('Button Label', YDN_TEXT_DOMAIN); ?></label>
		</div>
		<div class="col-md-6">
			<input class="form-control" id="ydn-button-label" name="ydn-button-label" value="<?php echo esc_attr($this->getOptionValue('ydn-button-label')); ?>">
		</div>
	</div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-button-custom-dimension"><?php _e('Custom Dimensions', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
            <label class="ydn-switch">
                <input type="checkbox" id="ydn-button-custom-dimension" name="ydn-button-custom-dimension" class="ydn-accordion-checkbox" <?php echo $this->getOptionValue('ydn-button-custom-dimension'); ?>>
                <span class="ydn-slider ydn-round"></span>
            </label>
        </div>
    </div>
    <div class="ydn-accordion-content ydn-hide-content">
        <div class="ydn-multichoice-wrapper">
		    <?php
		    $multipleChoiceButton = new MultipleChoiceButton($dimensions, esc_attr($this->getOptionValue('ydn-dimension-mode')));
		    echo $multipleChoiceButton;
		    ?>
        </div>
        <div id="dimension-mode-classic" class="dimension-mode-classic ydn-hide">
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="ydn-button-width" class="ydn-multiChoice-label"><?php _e('Width', YDN_TEXT_DOMAIN)?></label>
                </div>
                <div class="col-md-6">
                    <input type="text" placeholder="<?php _e('Width', YDN_TEXT_DOMAIN)?>" name="ydn-button-width" id="ydn-button-width" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-button-width')); ?>">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-5">
                    <label for="ydn-button-height" class="ydn-multiChoice-label"><?php _e('Height', YDN_TEXT_DOMAIN)?></label>
                </div>
                <div class="col-md-6">
                    <input type="text" placeholder="<?php _e('Height', YDN_TEXT_DOMAIN)?>" name="ydn-button-height" id="ydn-button-height" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-button-height')); ?>">
                </div>
            </div>
        </div>
    </div>
    <div id="dimension-mode-auto" class="ydn-hide">
        <div class="row row-static-margin-bottom">
            <div class="col-xs-4">
                <label class="control-label"><?php _e('Button padding', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-2">
                <label for="ydn-button-padding-top" class="ydn-label">Top</label>
                <input type="text" id="ydn-button-padding-top" data-direction="top" name="ydn-button-padding-top" class="form-control button-padding" value="<?php echo $this->getOptionValue('ydn-button-padding-top')?>">
            </div>
            <div class="col-xs-2">
                <label for="ydn-button-padding-right" class="ydn-label">Right</label>
                <input type="text" id="ydn-button-padding-right" data-direction="right" name="ydn-button-padding-right" class="form-control button-padding" value="<?php echo $this->getOptionValue('ydn-button-padding-right')?>">
            </div>
            <div class="col-xs-2">
                <label for="ydn-button-padding-bottom" class="ydn-label">Bottom</label>
                <input type="text" id="ydn-button-padding-bottom" data-direction="bottom" name="ydn-button-padding-bottom" class="form-control button-padding" value="<?php echo $this->getOptionValue('ydn-button-padding-bottom')?>">
            </div>
            <div class="col-xs-2">
                <label for="ydn-button-padding-left" class="ydn-label">Left</label>
                <input type="text" id="ydn-button-padding-left" data-direction="left" name="ydn-button-padding-left" class="form-control button-padding" value="<?php echo $this->getOptionValue('ydn-button-padding-left')?>">
            </div>
        </div>
    </div>
    <?php require_once dirname(__FILE__).'/content.php'; ?>
</div>