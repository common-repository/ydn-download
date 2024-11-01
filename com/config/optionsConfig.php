<?php
namespace ydn;

class YdnOptionsConfig {
	public static function init() {
		global $YDN_TYPES;
		
		$YDN_TYPES['typeName'] = apply_filters('ydnTypes', array(
			'link' => YDN_FREE_VERSION,
			'button' => YDN_FREE_VERSION,
			'image' => YDN_FREE_VERSION,
			'subscription' => YDN_FREE_VERSION
		));
		
		$YDN_TYPES['typePath'] = apply_filters('ydnTypePaths', array(
			'link' => YDN_TYPES_CLASS_PATH,
			'button' => YDN_TYPES_CLASS_PATH,
			'image' => YDN_TYPES_CLASS_PATH,
			'subscription' => YDN_TYPES_CLASS_PATH
		));
		
		$YDN_TYPES['titles'] = apply_filters('ydnTitles', array(
			'link' => __('Link type', YDN_TEXT_DOMAIN),
			'button' => __('Button type', YDN_TEXT_DOMAIN),
			'image' => __('Image type', YDN_TEXT_DOMAIN),
			'subscription' => __('Subscription type', YDN_TEXT_DOMAIN),
		));
	}
	
	public static function optionsValues() {
		global $YDN_OPTIONS;
		$options = array();
		$options[] = array('name' => 'ydn-target-link', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'ydn-type', 'type' => 'text', 'defaultValue' => 'link');
		$options[] = array('name' => 'ydn-link-label', 'type' => 'text', 'defaultValue' => __('Download', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-file-label', 'type' => 'text', 'defaultValue' => __('Download', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-file-version', 'type' => 'text', 'defaultValue' => '1.0');
		$options[] = array('name' => 'ydn-text-decoration', 'type' => 'text', 'defaultValue' => 'underline');
		$options[] = array('name' => 'ydn-button-label', 'type' => 'text', 'defaultValue' => __('Download', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-button-custom-dimension', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ydn-button-dimensions-behavior', 'type' => 'text', 'defaultValue' => 'classicMode');
		$options[] = array('name' => 'ydn-button-padding-top', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ydn-button-padding-right', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ydn-button-padding-bottom', 'type' => 'text', 'defaultValue' => '0px');
		$options[] = array('name' => 'ydn-button-padding-left', 'type' => 'text', 'defaultValue' => '0px');

		$options[] = array('name' => 'ydn-subs-first-name', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ydn-subs-first-name-required', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ydn-subs-first-name-placeholder', 'type' => 'text', 'defaultValue' => __('First name', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-last-name', 'type' => 'checkbox', 'defaultValue' => 'on');
		$options[] = array('name' => 'ydn-subs-last-name-required', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'ydn-subs-last-name-placeholder', 'type' => 'text', 'defaultValue' => __('Last name', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-email-placeholder', 'type' => 'text', 'defaultValue' => __('Email *', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-btn-title', 'type' => 'text', 'defaultValue' => __('Subscribe', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-btn-title-progress', 'type' => 'text', 'defaultValue' => __('Please wait...', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-action-behavior', 'type' => 'text', 'defaultValue' => 'download');
		$options[] = array('name' => 'ydn-open-downloads-page-new-tab', 'type' => 'checkbox', 'defaultValue' => '');
		
		$options[] = array('name' => 'ydn-file-version-label', 'type' => 'text', 'defaultValue' => __('Version', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-file-short-description-label', 'type' => 'text', 'defaultValue' => __('Short Description', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-error-message', 'type' => 'text', 'defaultValue' => __('There was an error while trying to send your request. Please try again.', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-invalid-email', 'type' => 'text', 'defaultValue' => __('Please enter a valid email address.', YDN_TEXT_DOMAIN));
		$options[] = array('name' => 'ydn-subs-required-field', 'type' => 'text', 'defaultValue' => __('This field is required.', YDN_TEXT_DOMAIN));

		$options[] = array('name' => 'ydn-display-conditions', 'type' => 'ydn', 'defaultValue' => array(array('key1' => 'select_settings')));
		
		$YDN_OPTIONS = apply_filters('ydnpDefaultOptions', $options);
	}
}

YdnOptionsConfig::init();
YdnOptionsConfig::optionsValues();