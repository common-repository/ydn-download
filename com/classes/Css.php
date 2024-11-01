<?php
namespace ydn;

class Css {
	
	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action('admin_enqueue_scripts', array($this, 'adminEenqueScripts'));
	}

	public function allowedPages() {
	    $pages = array(
            YDN_POST_TYPE.'_page_'.YDN_HISTORY_PAGE,
            YDN_POST_TYPE.'_page_'.YDN_SUBSCRIPTIONS_PAGE,
            YDN_POST_TYPE.'_page_'.YDN_TYPES,
            YDN_POST_TYPE.'_page_'.YDN_MORE_PLUGINS_PAGE
        );

	    return $pages;
    }

	public function adminEenqueScripts($hook) {

		$currentPostType = AdminHelper::getCurrentPostType();

        $pages = $this->allowedPages();
        if (empty($currentPostType) && !in_array($hook, $pages)) {
            return '';
        }

		if(!empty($currentPostType) && $currentPostType != YDN_POST_TYPE) {
			return '';
		}

		ScriptsIncluder::loadStyle('select2.css');
		ScriptsIncluder::loadStyle('ydnAdmin.css');
		ScriptsIncluder::loadStyle('ydnBootstrap.css');
	}
}

new Css();