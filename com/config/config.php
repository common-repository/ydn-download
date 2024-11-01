<?php
namespace ydn;

class Config {
	public static function addDefine($name, $value) {
		if(!defined($name)) {
			define($name, $value);
		}
	}

	public static function init() {
		self::addDefine('YDN_URL', plugins_url().'/'.YDN_FOLDER_NAME.'/');
		self::addDefine('YDN_ADMIN_URL', admin_url());
		self::addDefine('YDN_PRO_URL', 'https://edmonsoft.com/download/');
		self::addDefine('YDN_WP_ADMIN_POST_URL', admin_url('admin-post.php'));
		self::addDefine('YDN_PUBLIC_URL', YDN_URL.'public/');
		self::addDefine('YDN_CSS_URL', YDN_PUBLIC_URL.'css/');
		self::addDefine('YDN_ADMIN_CSS_URL', YDN_CSS_URL.'admin/');
		self::addDefine('YDN_FRONT_CSS_URL', YDN_CSS_URL.'front/');
		self::addDefine('YDN_JS_URL', YDN_PUBLIC_URL.'js/');
		self::addDefine('YDN_ADMIN_JS_URL', YDN_JS_URL.'admin/');
		self::addDefine('YDN_FRONT_JS_URL', YDN_JS_URL.'front/');
		self::addDefine('YDN_PATH', WP_PLUGIN_DIR.'/'.YDN_FOLDER_NAME.'/');

		self::addDefine('YDN_COM_PATH', YDN_PATH.'com/');
		self::addDefine('YDN_PUBLIC_PATH', YDN_PATH.'public/');
		self::addDefine('YDN_VIEWS_PATH', YDN_PUBLIC_PATH.'views/');
		self::addDefine('YDN_TYPES_VIEWS_PATH', YDN_VIEWS_PATH.'types/');
		self::addDefine('YDN_ADMIN_VIEWS_PATH', YDN_VIEWS_PATH.'admin/');
		self::addDefine('YDN_CLASS_PATH', YDN_COM_PATH.'classes/');
		self::addDefine('YDN_DATA_TABLE_PATH', YDN_CLASS_PATH.'dataTable/');
		self::addDefine('YDN_FRONT_CLASS_PATH', YDN_CLASS_PATH.'front/');
		self::addDefine('YDN_CLASS_ADMIN_PATH', YDN_CLASS_PATH.'admin/');
		self::addDefine('YDN_TYPES_CLASS_PATH', YDN_CLASS_PATH.'types/');
		self::addDefine('YDN_HELPERS_PATH', YDN_COM_PATH.'helpers/');
		self::addDefine('YDN_DOWNLOADS_NONCE', 'ydnNonce');
		self::addDefine('YDN_POSTS_TABLE_NAME', 'posts');
		self::addDefine('YDN_DOWNLOADS_HISTORY', 'ydn_downloads_history');
		self::addDefine('YDN_SUBSCRIBERS_TABLE_NAME', 'ydn_subscriptions');
		self::addDefine('YDN_POST_TYPE', 'ydndownloader');
		self::addDefine('YDN_TEXT_DOMAIN', 'ydnDownloader');
		self::addDefine('YDN_HISTORY_PAGE', 'ydnHistory');
		self::addDefine('YDN_SUBSCRIPTIONS_PAGE', 'ydnSubscription');
		self::addDefine('YDN_MORE_PLUGINS_PAGE', 'ydn-more-plugins-page');
		self::addDefine('YDN_TYPES', 'ydnTypes');
		self::addDefine('YDN_CATEGORY_TAXONOMY', 'download-category');
		self::addDefine('YDN_DOWNLOAD_REVIEW_URL', 'https://wordpress.org/support/plugin/ydn-download/reviews/?filter=5');
		self::addDefine('YDN_DOWNLOAD_SUPPORT_URL', 'https://wordpress.org/support/plugin/ydn-download/');
		self::addDefine('YDN_VERSION', '1.42');
		self::addDefine('YDN_VERSION_TEXT', '1.4.2');
		self::addDefine('YDN_LAST_UPDATE_DATE', 'Jan 23');
		self::addDefine('YDN_NEXT_UPDATE_DATE', 'Feb 22');
		self::addDefine('YDN_TABLE_LIMIT', 15);
		self::addDefine('YDN_YCD_AJAX_SUCCESS', 1);
		self::addDefine('YDN_SHOW_REVIEW_PERIOD', 30);
		self::addDefine('YDN_MENU_TITLE', 'Downloader');
		self::addDefine('YDN_DEFAULT_TYPE', 'link');
		self::addDefine('YDN_WIDGET', 'ydn_donwloader_widget');
		self::addDefine('YDN_AJAX_STATUS_FALSE', false);
		self::addDefine('YDN_AJAX_STATUS_TRUE', true);

		self::addDefine('YDN_AJAX_TRUE', 1);
		self::addDefine('YDN_AJAX_FALSE', 0);
		self::addDefine('YDN_FREE_VERSION', 1);
		self::addDefine('YDN_SILVER_VERSION', 2);
		self::addDefine('YDN_GOLD_VERSION', 3);
		self::addDefine('YDN_PLATINUM_VERSION', 4);
		require_once(dirname(__FILE__).'/pkg.php');
	}
}

Config::init();