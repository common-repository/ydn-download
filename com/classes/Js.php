<?php
namespace ydn;

class Js {
	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action('admin_enqueue_scripts', array($this, 'adminEenqueScripts'));
	}

	public function allowedPages() {
		$pages = array(
			YDN_POST_TYPE.'_page_'.YDN_HISTORY_PAGE,
			YDN_POST_TYPE.'_page_'.YDN_SUBSCRIPTIONS_PAGE
		);

		return $pages;
	}

	private function gutenbergParams() {
		$settings = array(
			'allDownloads' => Downloader::shapeIdTitleData(),
			'title'   => __('Downloders', YDN_TEXT_DOMAIN),
			'description'   => __('This block will help you to add downloader’s shortcode inside the page content', YDN_TEXT_DOMAIN),
			'logo_classname' => 'ydn-gutenberg-logo',
			'downloader_select' => __('Select downloader', YDN_TEXT_DOMAIN),
			'i18n'=> array(
					'title'            => __( 'Downloader', YDN_TEXT_DOMAIN )
				)
		);

		return $settings;
	}

	public function adminEenqueScripts($hook) {
		$blockSettings = $this->gutenbergParams();
		ScriptsIncluder::registerScript('WpDownloaderBlockMin.js', array('dirUrl' => YDN_ADMIN_JS_URL));
		ScriptsIncluder::localizeScript('WpDownloaderBlockMin.js', 'YDN_GUTENBERG_PARAMS', $blockSettings);
		ScriptsIncluder::enqueueScript('WpDownloaderBlockMin.js');

		$currentPostType = AdminHelper::getCurrentPostType();
		$pages = $this->allowedPages();
		if (empty($currentPostType) && !in_array($hook, $pages)) {
			return '';
		}

		if(!empty($currentPostType) && $currentPostType != YDN_POST_TYPE) {
			return '';
		}

		wp_enqueue_media();
		ScriptsIncluder::loadScript('YdnAdmin.js');
		ScriptsIncluder::registerScript('ConditionBuilder.js', array('dirUrl' => YDN_ADMIN_JS_URL));
		
		$localizedData = array(
			'nonce' => wp_create_nonce('ydn_ajax_nonce'),
			'copied' => __('Copied', YDN_TEXT_DOMAIN),
			'copyToClipboard' => __('Copy to clipboard', YDN_TEXT_DOMAIN),
		);
		
		ScriptsIncluder::registerScript('YdnSelect2.js', array('dirUrl' => YDN_ADMIN_JS_URL));
		ScriptsIncluder::enqueueScript('YdnSelect2.js');
		ScriptsIncluder::registerScript('YdnAdmin.js', array('dirUrl' => YDN_ADMIN_JS_URL));
		ScriptsIncluder::localizeScript('YdnAdmin.js', 'ydn_admin_localized', $localizedData);
		ScriptsIncluder::enqueueScript('YdnAdmin.js');
		ScriptsIncluder::enqueueScript('ConditionBuilder.js');
	}

	public static function frontScripts() {
		$jsFiles = array();
		$localizeData = array();
		$scriptData = array(
			'jsFiles' => apply_filters('ydnJsFiles', $jsFiles),
			'localizeData' => apply_filters('ydnJsLocalizedData', $localizeData)
		);
		$scriptData = apply_filters('ydnJs', $scriptData);

		self::enqueueScripts($scriptData);
	}

	public static function enqueueScripts($scriptData) {
	}
}

new Js();