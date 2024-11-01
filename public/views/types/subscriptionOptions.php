<?php
use \ydn\MultipleChoiceButton;
$defaultData = \ydn\AdminHelper::getAllDefaultSettings();
?>
<div class="ydn-bootstrap-wrapper">
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-subs-email-placeholder"><?php _e('Email Placeholder', YDN_TEXT_DOMAIN)?></label>
		</div>
		<div class="col-md-6">
			<input type="text" name="ydn-subs-email-placeholder" id="ydn-subs-email-placeholder" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-email-placeholder')); ?>">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-subs-first-name"><?php _e('First Name', YDN_TEXT_DOMAIN)?></label>
		</div>
		<div class="col-md-6">
			<label class="ydn-switch">
				<input type="checkbox" id="ydn-subs-first-name" name="ydn-subs-first-name" class="ydn-accordion-checkbox" <?php echo $this->getOptionValue('ydn-subs-first-name'); ?>>
				<span class="ydn-slider ydn-round"></span>
			</label>
		</div>
	</div>
	<div class="ydn-accordion-content ydn-hide-content">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="ydn-subs-first-name-placeholder"><?php _e('Placeholder', YDN_TEXT_DOMAIN)?></label>
			</div>
			<div class="col-md-6">
				<input type="text" name="ydn-subs-first-name-placeholder" id="ydn-subs-first-name-placeholder" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-first-name-placeholder')); ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="ydn-subs-first-name-required"><?php _e('Required field', YDN_TEXT_DOMAIN)?></label>
			</div>
			<div class="col-md-6">
				<label class="ydn-switch">
					<input type="checkbox" id="ydn-subs-first-name-required" name="ydn-subs-first-name-required" <?php echo $this->getOptionValue('ydn-subs-first-name-required'); ?>>
					<span class="ydn-slider ydn-round"></span>
				</label>
			</div>
		</div>
	</div>
	
	<!-- Last Name -->
	<div class="row form-group">
		<div class="col-md-4">
			<label for="ydn-subs-last-name"><?php _e('Last Name', YDN_TEXT_DOMAIN)?></label>
		</div>
		<div class="col-md-6">
			<label class="ydn-switch">
				<input type="checkbox" id="ydn-subs-last-name" name="ydn-subs-last-name" class="ydn-accordion-checkbox" <?php echo $this->getOptionValue('ydn-subs-last-name'); ?>>
				<span class="ydn-slider ydn-round"></span>
			</label>
		</div>
	</div>
	<div class="ydn-accordion-content ydn-hide-content">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="ydn-subs-last-name-placeholder"><?php _e('Placeholder', YDN_TEXT_DOMAIN)?></label>
			</div>
			<div class="col-md-6">
				<input type="text" name="ydn-subs-last-name-placeholder" id="ydn-subs-last-name-placeholder" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-last-name-placeholder')); ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label for="ydn-subs-last-name-required"><?php _e('Required field', YDN_TEXT_DOMAIN)?></label>
			</div>
			<div class="col-md-6">
				<label class="ydn-switch">
					<input type="checkbox" id="ydn-subs-last-name-required" name="ydn-subs-last-name-required" <?php echo $this->getOptionValue('ydn-subs-last-name-required'); ?>>
					<span class="ydn-slider ydn-round"></span>
				</label>
			</div>
		</div>
	</div>
	<!-- Last Name -->
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-button-height"><?php _e('Submit button', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-subs-btn-title"><?php _e('Title', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
            <input type="text" name="ydn-subs-btn-title" id="ydn-subs-btn-title" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-btn-title')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-subs-btn-title-progress"><?php _e('Title (in progress):', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
            <input type="text" name="ydn-subs-btn-title-progress" placeholder="<?php _e('Title (in progress):', YDN_TEXT_DOMAIN)?>" id="ydn-subs-btn-title-progress" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-btn-title-progress')); ?>">
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-subs-btn-title"><?php _e('Message:', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="sub-options-wrapper">
        <div class="row form-group">
            <div class="col-md-4">
                <label for="ydn-subs-invalid-email"><?php _e('Invalid email', YDN_TEXT_DOMAIN)?></label>
            </div>
            <div class="col-md-6">
                <input type="text" name="ydn-subs-invalid-email" placeholder="<?php _e('Invalid email messages', YDN_TEXT_DOMAIN)?>" id="ydn-subs-invalid-email" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-invalid-email')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label for="ydn-subs-error-message"><?php _e('Error', YDN_TEXT_DOMAIN)?></label>
            </div>
            <div class="col-md-6">
                <input type="text" name="ydn-subs-error-message" placeholder="<?php _e('Error message', YDN_TEXT_DOMAIN)?>" id="ydn-subs-error-message" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-error-message')); ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label for="ydn-subs-required-field"><?php _e('Required field', YDN_TEXT_DOMAIN)?></label>
            </div>
            <div class="col-md-6">
                <input type="text" name="ydn-subs-required-field" placeholder="<?php _e('Required field', YDN_TEXT_DOMAIN)?>" id="ydn-subs-required-field" class="form-control" value="<?php echo esc_attr($this->getOptionValue('ydn-subs-required-field')); ?>">
            </div>
        </div>
    </div>
    
    <div class="row form-group">
        <div class="col-md-4">
            <label for="ydn-subs-btn-title"><?php _e('After successful subscription:', YDN_TEXT_DOMAIN)?></label>
        </div>
        <div class="col-md-6">
        </div>
    </div>

    <div class="ydn-multichoice-wrapper">
		<?php
		$multipleChoiceButton = new MultipleChoiceButton($defaultData['subscription-behavior'], esc_attr($this->getOptionValue('ydn-action-behavior')));
		echo $multipleChoiceButton;
		?>
    </div>
    <div id="ydn-subscription-download" class="ydn-subscription-download ydn-hide">
    </div>
	<?php require_once dirname(__FILE__).'/content.php'; ?>
</div>