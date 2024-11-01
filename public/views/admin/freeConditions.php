<?php
use ydn\AdminHelper;
$conditiosnKeys = AdminHelper::conditionsKeys();
$operators = array('is' => __('Is', YDN_TEXT_DOMAIN));
$keys = array_keys($conditiosnKeys);
$fieldAttributes = array(
	'class' => 'ydn-condition-select js-ydn-select',
	'value' => ''
);
?>
<div class="ydn-bootstrap-wrapper ydn-conditions-free-section-wrapper">
	<div class="row">
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($conditiosnKeys, $keys[1], $fieldAttributes)?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($operators, 'is', $fieldAttributes)?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select user devices', YDN_TEXT_DOMAIN);?></label></div>
			<input type="text" class="form-control" value="<?php _e('Select needed devices', YDN_TEXT_DOMAIN); ?>">
		</div>
		<div class="col-md-3">
			<a href="<?php echo YDN_PRO_URL; ?>" target="_blank" class="btn btn-warning btn-xs ydn-conditions-pro-button" style="margin-top: 22px;">
				<?php _e('Permimum', YDN_TEXT_DOMAIN); ?>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($conditiosnKeys, $keys[3], $fieldAttributes)?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($operators, 'is', $fieldAttributes); ?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select countries', YDN_TEXT_DOMAIN);?></label></div>
			<input type="text" class="form-control" value="<?php _e('Select needed countries', YDN_TEXT_DOMAIN); ?>">
		</div>
		<div class="col-md-3">
			<a href="<?php echo YDN_PRO_URL; ?>" target="_blank" class="btn btn-warning btn-xs ydn-conditions-pro-button" style="margin-top: 22px;">
				<?php _e('Permimum', YDN_TEXT_DOMAIN); ?>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($conditiosnKeys, $keys[2], $fieldAttributes)?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select Conditions', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox($operators, 'is', $fieldAttributes)?>
		</div>
		<div class="col-md-3">
			<div class="ydn-condition-header"><label><?php _e('Select user status', YDN_TEXT_DOMAIN);?></label></div>
			<?php echo AdminHelper::selectBox(array('logged_in' => __('logged In', YDN_TEXT_DOMAIN)), '', $fieldAttributes); ?>
		</div>
		<div class="col-md-3">
			<a href="<?php echo YDN_PRO_URL; ?>" target="_blank" class="btn btn-warning btn-xs ydn-conditions-pro-button" style="margin-top: 22px;">
				<?php _e('Permimum', YDN_TEXT_DOMAIN); ?>
			</a>
		</div>
	</div>
	<div class="ydn-pro-options-restric">
		<div class="row">
			<div class="col-md-12">
			</div>
		</div>
	</div>
</div>
<style>
	.ydn-condition-select {
		width: 100% !important;
	}
</style>